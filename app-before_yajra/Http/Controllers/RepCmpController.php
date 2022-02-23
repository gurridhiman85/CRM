<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Library\Ajax;
use App\User;
use Validator;
use Auth;
use Crypt;
use DB;
use \Illuminate\Support\Facades\View as View;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App;
use App\Mail\SendReportEmail;
use App\Mail\SendCampaignEmail;
use App\Mail\ShareReportEmail;
use Illuminate\Support\Facades\Mail;
use \LynX39\LaraPdfMerger\PdfManage;
use Session;

class RepCmpController extends Controller
{
    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->schDir = config('constant.schDir');
        $this->phpPath = config('constant.phpPath');
        $this->filePath = config('constant.filePath');
        $this->prefix = config('constant.prefix');
        $this->clientname = config('constant.client_name');
    }

    public function getFieldTypes(Request $request,Ajax $ajax){
        $listLevel = $request->input('list_level');

        if(!empty($listLevel)){
            $checked_fields = $request->input('checked_fields',[]);
            $aData = DB::select("SELECT DISTINCT [Type] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $listLevel . "'");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            $hHtml = "";
            foreach ($aData as $tKey => $tTypeInfo) {
                $aData = DB::select("SELECT DISTINCT [Field_Display] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $listLevel . "' AND Type = '" . $tTypeInfo['Type'] . "' AND Display_For_Select = 1");
                $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $hHtml .= '<div class="col-md-2">
                        <div class="form-group">
                            <select name="'.$tTypeInfo['Type'].'" class="form-control form-control-sm chosen-select" id="s_'.$tKey.'" multiple="multiple" data-placeholder="Select '.$tTypeInfo['Type'].'">';

                    foreach ($aData as $key => $fFieldInfo) {
                        if (in_array($fFieldInfo['Field_Display'], $checked_fields)) {
                            $checked = "selected='selected'";
                        } else {
                            $checked = "";
                        }
                        $hHtml .= '<option '.$checked.' value="'.$fFieldInfo['Field_Display'].'">'.$fFieldInfo['Field_Display'].'</option>';
                    }
                    $hHtml .= "</select>
                        </div>
                    </div>";
                }
            }

            $aDataDF = DB::select("select Field_Display,Filter_Type from UL_RepCmp_Lookup_Fields where report = 1 and List_Level = '" . $listLevel . "' AND Filter_Type IN('lkp','Lkp','Num')");
            $aDataDF = collect($aDataDF)->map(function($x){ return (array) $x; })->toArray();


            $lkpOptions = array();
            $numOptions = array();
            if (!empty($aDataDF)) {
                foreach ($aDataDF as $data) {
                    if (in_array($data['Filter_Type'],['Lkp','lkp'])) {
                        $lkpOptions[] = $data['Field_Display'];
                    } else {
                        $numOptions[] = $data['Field_Display'];
                    }
                }
            }

            return $ajax->success()
                ->appendParam('fieldsHtml',$hHtml)
                ->appendParam('lkpOptions',$lkpOptions)
                ->appendParam('numOptions',$numOptions)
                ->response();

        }
    }

    public function getDistriPopUp(Request $request,Ajax $ajax){
        $listLevel = $request->input('list_level');
        $sql = $request->input('sql');
        if(!empty($listLevel)){
            $aDataDF = DB::select("select Field_Display,Filter_Type from UL_RepCmp_Lookup_Fields where report = 1 and List_Level = '" . $listLevel . "' AND Filter_Type IN('lkp','Lkp','Num')");
            $aDataDF = collect($aDataDF)->map(function($x){ return (array) $x; })->toArray();


            $lkpOptions = array();
            $numOptions = array();
            if (!empty($aDataDF)) {
                foreach ($aDataDF as $data) {
                    if (in_array($data['Filter_Type'],['Lkp','lkp'])) {
                        $lkpOptions[] = $data['Field_Display'];
                    } else {
                        $numOptions[] = $data['Field_Display'];
                    }
                }
            }
            $params = $request->input('params',[
                'row_variable' => '',
                'column_variable' => '',
                'function_variable' => '',
                'sum_variable' => '',
                'show_as' => '',
                'chart_variable' => '',
                'chart_axis_scale' => '',
                'chart_label_value' => ''
            ]);
            $content = View::make('layouts.summary-report-row',[
                'lkpOptions' => $lkpOptions,
                'numOptions' => $numOptions,
                'list_level' => $listLevel,
                'sql' => $sql,
                'params' => $params,                'popup' => true
            ])->render();

            $sdata = [
                'content' => $content
            ];

            $title = 'Summary Report';
            $size = 'modal-xxl';

            if (isset($title)) {
                $sdata['title'] = $title;
            }
            if (isset($size)) {
                $sdata['size'] = $size;
            }

            $view = View::make('layouts.modal-popup-layout', $sdata);
            $html = $view->render();

            return $ajax->success()->appendParam('params', $params)->appendParam('html', $html)->jscallback('loadModalLayout')->response();
        }
    }

    public function reportRun(Request $request, Ajax $ajax){
        try{
            DB::statement("drop table  temp1");
        }catch (\Exception $exception){}

        $sql = $request->input('sql','SELECT * FROM emailable Order By DS_MKC_ContactID DESC');

        $list_level = $request->input('list_level');

        $column_variable = $request->input('column_variable');
        $row_variable = $request->input('row_variable');
        $function_variable = $request->input('function_variable');
        $show_as = $request->input('show_as');
        $sum_variable = $request->input('sum_variable');
        $chart_variable = $request->input('chart_variable');
        $chart_axis_scale = $request->input('chart_axis_scale');
        $chart_label_value = $request->input('chart_label_value');

        $pos = strpos($sql, "Order By");
        if ($pos != false) {
            $sql = substr($sql, 0, $pos - 1);
        }
        $sqlQuery = !empty($sql) ? explode("where", strtolower($sql)) : '';

        $where = "";
        if (is_array($sqlQuery) && count($sqlQuery) > 1) { $where = " WHERE " . $sqlQuery[1]; }

        if (empty($column_variable) && $function_variable == "count") {

            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . " , count(*) as Distribution from " . $list_level . " " . $where . " group by " . $row_variable . ") t");

            $dData = DB::select("select * from temp1 Order By ".$row_variable);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
            $colVar = 'Distribution';
        }
        else if (empty($column_variable) && $function_variable == "sum") {
            if(empty($sum_variable)){
                return $ajax->fail()
                    ->message('Please select sum variable')
                    ->jscallback()
                    ->response();
            }

            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . ", sum(" . $sum_variable . ") as Distribution from " . $list_level . "  " . $where . " group by " . $row_variable . ") t");

            $dData = DB::select("select * from temp1 Order By ".$row_variable);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
            $colVar = 'Distribution';
        }
        else if (empty($column_variable) && in_array($function_variable , ['cs','sc'])) {
            if(empty($sum_variable)){
                return $ajax->fail()
                    ->message('Please select sum variable')
                    ->jscallback()
                    ->response();
            }
            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;select * into temp1 from (select " . $row_variable . ",count(*) as Number, sum(" . $sum_variable . ") as [Total] from " . $list_level . "  " . $where . " group by " . $row_variable . ") t");

            $dData = DB::select("select * from temp1 Order By ".$row_variable);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
            $colVar = 'Count';

            $result = [];
            if(strtoupper($show_as) == 'NP' && $function_variable == 'cs'){
                $result = Helper::print_report_datatable_NPCS($dData, $colVar, $sum_variable);
            } else if(strtoupper($show_as) == 'PN' && $function_variable == 'cs'){
                $result = Helper::print_report_datatable_PNCS($dData, $colVar, $sum_variable);
            }else if(strtoupper($show_as) == 'NP'  && $function_variable == 'sc'){
                $result = Helper::print_report_datatable_NPSC($dData, $colVar, $sum_variable);
            }else if(strtoupper($show_as) == 'PN'  && $function_variable == 'sc'){
                $result = Helper::print_report_datatable_PNSC($dData, $colVar, $sum_variable);
            }
            $inner_call = $request->input('inner_call',0);
            if($inner_call == 1)
                $js_callback = 'ajax_run_report_result_inner';
            elseif ($inner_call == 2)
                $js_callback = 'ajax_run_report_result_outer';
            else
                $js_callback = 'ajax_run_report_result';

            return $ajax->success()
                ->appendParam('result',$result)
                ->appendParam('row_variable',$row_variable)
                ->appendParam('column_variable',$column_variable)
                ->appendParam('function_variable',$function_variable)
                ->appendParam('show_as',$show_as)
                ->appendParam('sum_variable',$sum_variable)
                ->appendParam('chart_variable',$chart_variable)
                ->appendParam('chart_axis_scale',$chart_axis_scale)
                ->appendParam('chart_label_value',$chart_label_value)
                ->appendParam('show_as',$show_as)
                ->jscallback($js_callback)
                ->response();
        }
        else {
            $sSqlInsert = "select * into temp1 from (select " . $row_variable . " , " . $column_variable . ", ";

            if ($function_variable == "sum") {
                $column = $sum_variable;
            } else {
                $column = "*";
            }

            $sSqlInsert .= $function_variable . "(" . $column . ")";
            $sSqlInsert .= " as Distribution from " . $list_level . " " . $where . " group by " . $row_variable . ", " . $column_variable . ") t";

            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;".$sSqlInsert);

            $sSqlSelect = "select distinct " . $column_variable . " from temp1 Order By " . $column_variable;
            $dData = DB::select($sSqlSelect);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
            $sSqlTempSelect = "select * from temp1 pivot (sum(Distribution) for " . $column_variable . " in(";
            $c = 0;
            foreach ($dData as $key => $column) {
                if(!empty($column[$column_variable])){
                    if ($c == 0) {
                        $sSqlTempSelect .= "[" . $column[$column_variable] . "]";
                    } else {
                        $sSqlTempSelect .= ",[" . $column[$column_variable] . "]";
                    }
                    $c++;
                }
            }
            $sSqlTempSelect .= ")) as " . $row_variable;
            $dData = DB::select($sSqlTempSelect);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();
            $colVar = $column_variable;
        }

        if (strtoupper($show_as) == 'PRT') {
            $result = Helper::print_report_datatable_PRT($dData, $colVar);
        } else if (strtoupper($show_as) == 'PCT') {
            $result = Helper::print_report_datatable_PCT($dData, $colVar);
        } else if (strtoupper($show_as) == 'PGT') {
            $result = Helper::print_report_datatable_PGT($dData, $colVar);
        } else if (strtoupper($show_as) == 'NPRT') {
            $result = Helper::print_report_datatable_NPRT($dData, $colVar);
        } else if (strtoupper($show_as) == 'PRTN') {
            $result = Helper::print_report_datatable_PRTN($dData, $colVar);
        } else if (strtoupper($show_as) == 'NPCT') {
            $result = Helper::print_report_datatable_NPCT($dData, $colVar);
        } else if (strtoupper($show_as) == 'PCTN') {
            $result = Helper::print_report_datatable_PCTN($dData, $colVar);
        } else if(strtoupper($show_as) == 'NP'){
            $result = Helper::print_report_datatable_numberNPWC($dData, $colVar);
        } else if(strtoupper($show_as) == 'PN'){
            $result = Helper::print_report_datatable_numberPNWC($dData, $colVar);
        } else if(strtoupper($show_as) == 'SBN'){
            $result = Helper::print_report_datatable_SideByNumber($dData,$row_variable, $column_variable, $sum_variable);
        } else {
            $result = Helper::print_report_datatable_number($dData, $colVar);
        }

        $inner_call = $request->input('inner_call',0);
        if($inner_call == 1)
            $js_callback = 'ajax_run_report_result_inner';
        elseif ($inner_call == 2)
            $js_callback = 'ajax_run_report_result_outer';
        else
            $js_callback = 'ajax_run_report_result';

        return $ajax->success()
            ->appendParam('result',$result)
            ->appendParam('row_variable',$row_variable)
            ->appendParam('column_variable',$column_variable)
            ->appendParam('function_variable',$function_variable)
            ->appendParam('show_as',$show_as)
            ->appendParam('sum_variable',$sum_variable)
            ->appendParam('chart_variable',$chart_variable)
            ->appendParam('chart_axis_scale',$chart_axis_scale)
            ->appendParam('chart_label_value',$chart_label_value)
            ->appendParam('show_as',$show_as)
            ->jscallback($js_callback)
            ->response();

    }

    public function getFieldTypesForFilter(Request $request, Ajax $ajax){
        $listLevel = $request->input('List_Level','');
        $sectiontype = $request->input('sectiontype','F');
        $extraCondition = '';
        if ($sectiontype == 'F')
            $extraCondition = 'AND Display_For_Filter = 1';
        elseif ($sectiontype == 'CE')
            $extraCondition = 'AND Display_For_Filter_Excl = 1';
        elseif ($sectiontype == 'CI')
            $extraCondition = 'AND Display_For_Filter_Incl = 1';
        $tTypeArrop = '<option value="">Select</option>';
        $aData = DB::select("SELECT DISTINCT [Type] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $listLevel . "' $extraCondition");
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        foreach ($aData as $tKey => $tTypeInfo) {
            $tTypeArrop .= '<option value="' . trim($tTypeInfo['Type']) . '">' . trim($tTypeInfo['Type']) . '</option>';
        }
        return $ajax->success()
            ->appendParam('colOptions',$tTypeArrop)->response();
    }

    public function getFields(Request $request, Ajax $ajax){
        $listLevel = $request->input('List_Level');
        $sectiontype = $request->input('sectiontype');
        $field_type = $request->input('field_type');

        $extraCondition = '';
        if ($sectiontype == 'F')
            $extraCondition = 'AND Display_For_Filter = 1';
        elseif ($sectiontype == 'CE')
            $extraCondition = 'AND Display_For_Filter_Excl = 1';
        elseif ($sectiontype == 'CI')
            $extraCondition = 'AND Display_For_Filter_Incl = 1';
        if (!empty($field_type)) {
            $aDataTable = DB::select("SELECT Distinct [Table_Name] from [UL_RepCmp_Lookup_Fields] WHERE Type = '$field_type' AND List_Level = '" . $listLevel . "'  $extraCondition");
            $aDataTable = collect($aDataTable)->map(function($x){ return (array) $x; })->toArray();

            $aData = DB::select("SELECT [Field_Display] from [UL_RepCmp_Lookup_Fields] WHERE Type = '$field_type' AND List_Level = '" . $listLevel . "'  $extraCondition");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        } else {
            $aData = DB::select("SELECT [Field_Display] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $listLevel . "'  $extraCondition");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        }
        $fFieldArr = '<option value="">Select</option>';
        foreach ($aData as $fFieldInfo) {
            $fFieldArr .= '<option value="' . trim($fFieldInfo['Field_Display']) . '">' . trim($fFieldInfo['Field_Display']) . '</option>';
        }

        /*if ($field_type != undefined) {
            echo $aDataTable[0]['Table_Name'] . '::^' . implode(',', $fFieldArr);
        } else {
            echo ' ::^' . implode(',', $fFieldArr);
        }*/

        return $ajax->success()
            ->appendParam('Tb_Name',$aDataTable[0]['Table_Name'])
            ->appendParam('fields',$fFieldArr)
            ->response();

    }

    public function getColByCustom(Request $request, Ajax $ajax){
        $request->input('colId');
        $sectiontype = $request->input('sectiontype');
        $colName = $request->input('colName');
        $secIds = $request->input('secIds');
        $aData1 = DB::select("select Filter_Type,Lookup,Type_Num from UL_RepCmp_Lookup_Fields where Field_Display ='" . $colName . "'"); //die;
        $aData1 = collect($aData1)->map(function($x){ return (array) $x; })->toArray();
        $customClass = '';
        if ($sectiontype == 2) {
            $customClass = 'red-elements';
        }
        $eEmpty = 0;
        if (isset($aData1) && !empty($aData1)) {
            if (ucfirst($aData1[0]['Filter_Type']) == 'Lkp') {
                $operators = '<select rel="' . $aData1[0]['Type_Num'] . '" class="form-control form-control-sm ' . $customClass . '" onchange="changeVal(this.value,' . $secIds . ',' . $sectiontype . ');" style="width:100%;" id="op' . $secIds . '" name="op' . $secIds . '"><option value=" "></option><option selected value="6">Includes</option><option value="7">Excludes</option><option value="8">Contains</option><option value="8.1">Starts with</option><option value="8.2">Ends with</option><option value="9">Doesn\'t Contain</option><option value="9.1">Does not Start with</option><option value="9.2">Does not End with</option></select>';
            } else if ($aData1[0]['Filter_Type'] == 'Inc') {
                $operators = '<select rel="' . $aData1[0]['Type_Num'] . '" class="form-control form-control-sm ' . $customClass . '" onchange="changeVal(this.value,' . $secIds . ',' . $sectiontype . ');" style="width:100%;" id="op' . $secIds . '" name="op' . $secIds . '"><option value=" "></option><option value="4">Is</option><option value="5">Is not</option><option selected value="6">Includes</option><option value="8">Contains</option><option value="8.1">Starts with</option><option value="8.2">Ends with</option><option value="9.1">Does not Start with</option><option value="9.2">Does not End with</option><option value="9">Doesn\'t Contain</option></select>';
            } else if ($aData1[0]['Filter_Type'] == 'Num') {
                $operators = '<select rel="' . $aData1[0]['Type_Num'] . '" class="form-control form-control-sm ' . $customClass . '" onchange="changeVal(this.value,' . $secIds . ',' . $sectiontype . ');" style="width:100%;" id="op' . $secIds . '" name="op' . $secIds . '"><option selected value=" "></option><option value="4">Is</option><option value="5">Is not</option><option  selected value="0">&gt;</option><option value="2">&gt;=</option><option value="1">&lt;</option><option value="3">&lt;=</option></select>';
            } else if ($aData1[0]['Filter_Type'] == 'All') {
                $operators = '<select rel="' . $aData1[0]['Type_Num'] . '" class="form-control form-control-sm ' . $customClass . '" onchange="changeVal(this.value,' . $secIds . ',' . $sectiontype . ');" style="width:100%;" id="op' . $secIds . '" name="op' . $secIds . '"><option value=" "></option><option value="4">is</option><option value="5">is not</option><option selected value="6">includes</option><option value="8">Contains</option><option value="8.1">Starts with</option><option value="8.2">Ends with</option><option value="9">Does not Contain</option></select>';
            } else {
                $operators = '<select rel="' . $aData1[0]['Type_Num'] . '" class="form-control form-control-sm ' . $customClass . '" onchange="changeVal(this.value,' . $secIds . ',' . $sectiontype . ');" style="width:100%;" id="op' . $secIds . '" name="op' . $secIds . '"><option value=" "></option><option value="0">&gt;</option><option value="1">&lt;</option><option value="2">&gt;=</option><option value="3">&lt;=</option><option value="4">=</option><option value="5">!=</option><option selected value="6">in</option><option value="7">not in</option><option value="8">Contains</option><option value="8.1">Starts with</option><option value="8.2">Ends with</option><option value="9">Doesn\'t Contain</option></select>';
            }
            $Str = array();
            if ($aData1[0]['Lookup'] == 1) {
                $aData = DB::select("select code_value from UL_RepCmp_Lookup_Values where code_type ='" . $colName . "'"); //die;
                $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                if (isset($aData) && !empty($aData)) {
                    $options = '<select class="form-control form-control-sm ' . $customClass . '" style="width:100%" id="val' . $secIds . '" style="width:100px" onKeyPress = "GetTextInfo(this,event);" multiple="multiple" >';
                    foreach ($aData as $key => $aDatas) {
                        $op = str_replace(",", "::", $aDatas['code_value']);
                        $options .= '<option value="' . $op . '">' . $aDatas['code_value'] . '</option>';
                    }
                    $options .= '</select>';
                    echo $aData1[0]['Filter_Type'] . ':::^' . $options . ':::^' . $operators . ':::^' . $aData1[0]['Type_Num'];
                } else {
                    echo $aData1[0]['Filter_Type'] . ':::^' . $eEmpty . ':::^' . $operators . ':::^' . $aData1[0]['Type_Num'];
                }
            } else {
                echo $aData1[0]['Filter_Type'] . ':::^' . $eEmpty . ':::^' . $operators . ':::^' . $aData1[0]['Type_Num'];
            }
        } else {
            echo $aData1[0]['Filter_Type'] . ':::^' . $eEmpty . ':::^' . $eEmpty . ':::^' . $aData1[0]['Type_Num'];
        }
    }

    public function getCountSql(Request $request, Ajax $ajax){
        $sSQL = $request->input('sSQL');
        $count = 0;
        if (trim($sSQL) != "") {
            $pos = strpos($sSQL,"Order By");

            if ($pos != false)
            {
                $sSQL = substr($sSQL,0,$pos-1);
            }
            if(stripos($sSQL, "blank") !== false){
                $sSQL = str_replace("blank", " ", $sSQL);
            }
            $sSQL = str_replace("::", ",", $sSQL);

            $aData = DB::select("SELECT  count_big(*) as Count from " . "( " . $sSQL . " ) as t");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            //echo '<pre>'; print_r($aData); die;
            if (!empty($aData)) {
                $count = $aData[0]['Count'];
            }

            $content = '<div class="row">
        <div class="col-md-9 pr-0"><small class="form-control-feedback font-14 ds-l">Number of Records Satisfying Query Criteria:</small></div>
        <div class="col-md-3 pl-0"><small class="form-control-feedback ds-l" style="border:1px solid #888;background:#fff;padding:2px;padding-left:10px;width: 100%;padding-right:10px;margin-right: 31px;font-size: 13px;/* width: 151px; */">'.$count.'</small></div>
    </div>';

            $sdata = [
                'content' => $content
            ];

            $title = 'Check Counts';
            $size = 'modal-dialog-centered modal-md';

            if (isset($title)) {
                $sdata['title'] = $title;
            }
            if (isset($size)) {
                $sdata['size'] = $size;
            }

            $view = View::make('layouts.modal-popup-layout', $sdata);
            $html = $view->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('loadModalLayout')
                ->response();

        }
        return $ajax->success()
            ->appendParam('cnt',$count)
            ->response();
    }

    public function showPreview(Request $request, Ajax $ajax){
        $sSQL = $request->input('sql');
        $reportName = $request->input('reportName');
        $list_format = $request->input('list_format');
        $repdes = $request->input('repdes');
        if (trim($sSQL) != "") {
            if (strpos($sSQL, "*") !== false) {
                $nSQL = str_replace("*", "TOP 1000 * ", $sSQL);
            } else {
                $nSQL = substr($sSQL, 0, 6) . " top 1000 " . substr($sSQL, 7, strlen($sSQL));
            }

            if(stripos($nSQL, "blank") !== false){
                $nSQL = str_replace("blank", "", $nSQL);
            }

            $aData1 = DB::select($nSQL);
            $aData1 = collect($aData1)->map(function($x){ return (array) $x; })->toArray();
            $aData = array_slice($aData1, 0, 1000);

            $sdata = [
                'content' => '<div class="row" style="overflow: scroll;height: 650px;"> '.Helper::print_datatable($aData).'</div><div class="row pull-right mt-2"><div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <button type="button" class="btn-light font-18 s-f sr-btn" onclick="list_report_run(2,\''.$reportName.'\',\''.$list_format.'\',\''.$repdes.'\');" title="Download Pdf"><i class="fas fa-file-pdf" style="color: #e92639;"></i></button>
                <button type="button" class="btn-light font-18 s-f sr-btn" onclick="list_report_run(1,\''.$reportName.'\');" title="Download XLSX"><i class="fas fa-file-excel" style="color: #06b489;"></i></button>
            </div>
        </div></div>'
            ];

            $title = 'List Report';
            $size = 'modal-xxl';

            if (isset($title)) {
                $sdata['title'] = $title;
            }
            if (isset($size)) {
                $sdata['size'] = $size;
            }

            $view = View::make('layouts.modal-popup-layout', $sdata);
            $html = $view->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('loadModalLayout')
                ->response();

        }
        return $ajax->success()
            ->response();
    }

    public function HTMLtoPDF(Request $request, Ajax $ajax){
        $header = ucfirst($request->input('rpheader'));
        $footer = ucfirst($request->input('rpfooter'));
        $tablehtml = $request->input('tablehtml');
        $charthtml = $request->input('charthtml');
        $filename = $request->input('filename');
        $papersize = $request->input('papersize','portrait');
        $filters = !is_null($request->input('filters')) ? $request->input('filters') : [];

        $selections = '';
        /*if(count($filters) > 0){
            foreach ($filters as $filter){
                foreach ($filter as $ftype=>$filt) {
                    if($ftype == 'cont_filters'){
                        $selections .= preg_replace('/\bAND\b/', 'and', $filt);
                    }
                    else if($ftype == 'incl_filters'){
                        $selections .= ' AND '.preg_replace('/\bAND\b/', 'and', $filt);;
                    }
                    else if($ftype == 'excl_filters'){
                        $selections .= ' AND '.preg_replace('/\bAND\b/', 'and', $filt);;
                    }
                }
            }
        }*/
        try {
            /*$html = View::make('layouts.pdf', [
                'header' => $header,
                'footer' => $footer,
                'tablehtml' => $tablehtml,
                'charthtml' => $charthtml,
                'filename' => $this->prefix.$filename,
                'selections' => $selections
            ])->render();
            echo $html; die;*/
            PDF::loadView('layouts.pdf', [
                'header' => $header,
                'footer' => $footer,
                'tablehtml' => $tablehtml,
                'charthtml' => $charthtml,
                'filename' => $this->prefix.$filename,
                'selections' => $selections
            ])->setPaper('letter',$papersize)->setWarnings(false)->save(public_path().'/downloads/'.$this->prefix.$filename);
            return $ajax->success()->jscallback('ajax_download_sr_file')
                ->appendParam('link',url('/').'/downloads/'.$this->prefix.$filename)
                ->appendParam('filename',$this->prefix.$filename)
                ->response();


        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function showPdfUpload(Request $request, Ajax $ajax){
        $rowids = $request->input('ids');
        $t_type = $request->input('type');

        $sdata = [
            'content' => '<form class="ajax-Form" method="post" enctype="multipart/form-data" action="downloadmultiplepdf">'.csrf_field().'<div class="form-group">
                                    <label>Upload More Files</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="padding: .275rem .75rem !important;">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="pdffile[]" multiple>
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div><div class="pull-right mt-1"><div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
            <div class="input-group">
                <input type="hidden" name="ids" value="'.$rowids.'">
                <input type="hidden" name="action" value="wpdf">
                <input type="hidden" name="type" value="'.$t_type.'">
                <button type="submit" class="btn btn-info">Upload</button>
            </div>
        </div></div></form>'
        ];

        $title = 'Upload';
        $size = 'modal-ml';

        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.modal-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()
            ->appendParam('html', $html)
            ->jscallback('loadModalLayout')
            ->response();
    }

    public function downloadMultiplePDF(Request $request, Ajax $ajax){
        $PdfManage = new \PDFMerger;
        $mMultiPageFileName = $this->prefix.'multiple.pdf';
        $data = [];
        if($request->input('action') && $request->input('action') == 'wpdf'){
            $rules = [
                'pdffile' => 'required',
                'pdffile.*' => 'mimes:pdf'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $ajax->fail()
                    ->form_errors($validator->errors())
                    ->jscallback()
                    ->response();
            }

            //Custom upload - start
            $destination = public_path('\\downloads\\');

            $files = $request->file('pdffile');
            foreach($files as $key=>$file)
            {
                $a_url = sha1($key.time().'_'.$file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
                $file->move($destination, $a_url);
                $PdfManage->addPDF($destination.$a_url, 'all');
                array_push($data,$destination.$a_url);
                //echo "<br/>".public_path($destination.$a_url);
            }
            //Custom upload - end
        }

        $rowidsData = $request->input('ids');
        $t_type = $request->input('type');
        if($t_type == 'C'){
            $mainTb = 'UC_Campaign_Templates';
            $prefixSR = $this->prefix.'CAM_';
            $prefixList = $this->prefix.'CAL_';
            $sql = "Select [t_id],(isnull(za.promoexpo_folder+'\\".$prefixSR."'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf','')) as File_Name,(isnull(za.promoexpo_folder+'\\".$prefixList."'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf','')) as listPdfFileName,'".$prefixSR."'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf' as [pdfFile],'".$prefixList."'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf' as [listpdfFile] from [$mainTb] as za Where [row_id] = ";

        }elseif ($t_type == 'A'){
            $mainTb = 'UR_Report_Templates';
            $prefixSR = $this->prefix.'RPS_';
            $prefixList = $this->prefix.'RPL_';
            $sql = "Select [row_id],[t_id],(isnull(za.promoexpo_folder+'\\".$prefixSR."'+za.promoexpo_file+'.pdf','')) as File_Name,(isnull(za.promoexpo_folder+'\\".$prefixList."'+za.promoexpo_file+'.pdf','')) as listPdfFileName,'".$prefixSR."'+za.promoexpo_file+'.pdf' as [pdfFile],'".$prefixList."'+za.promoexpo_file+'.pdf' as [listpdfFile] from [$mainTb] as za Where [row_id] = ";
        }

        $rowidsData = explode(',',$rowidsData);

        foreach ($rowidsData as $key=> $idData){
            $rowid = explode('_',$idData);
            $sSQL = DB::select($sql.$rowid[0]);

            $aData = collect($sSQL)->map(function ($x) {
                return (array)$x;
            })->toArray();
            if($aData){
                $aData = $aData[0];
                if($key == 0){
                    $fileNameArr = explode('_',$aData['pdfFile']);
                    array_pop($fileNameArr);
                    $mMultiPageFileName = implode('_',$fileNameArr).'_Multi.pdf';
                }
                $filename = ($rowid[1] == 'list') ? $aData['listPdfFileName'] : $aData['File_Name'];
                $PdfManage->addPDF(public_path($filename), 'all');
            }
        }
        $PdfManage->merge('file', public_path('\\downloads\\'.$mMultiPageFileName));

        foreach ($data as $datum){
            unlink($datum);
        }

        return $ajax->success()->jscallback('ajax_download_sr_file')
            ->appendParam('link',url('/').'/downloads/'.$mMultiPageFileName)
            ->appendParam('filename',$mMultiPageFileName)
            ->response();
    }

    public function download10K(Request $request, Ajax $ajax){
        ini_set('max_execution_time', 3500);
        ini_set('memory_limit', '1024M');
        ob_clean();

        $ftype = $request->input('ftype');
        $sSQL = $request->input('sSQL');
        $filename = $this->prefix.$request->input('filename');
        $list_format = $request->input('list_format');
        $repdes = $request->input('repdes');

        try{
            if (trim($sSQL) != "") {
                if (strpos($sSQL, "*") === true) {
                    $nSQL = str_replace("*", "TOP 100 * ", $sSQL);
                    //$fFSQL = str_replace("*", "TOP 1 * ", $sSQL);
                } else {
                    $nSQL = substr($sSQL, 0, 6) . " top 100 " . substr($sSQL, 7, strlen($sSQL));
                    //$fFSQL = substr($sSQL, 0, 6) . " top 1 " . substr($sSQL, 7, strlen($sSQL));
                }
                $files = glob(public_path().'/downloads/*'); // get all file names
                foreach($files as $file){ // iterate files
                    if(is_file($file))
                        unlink($file); // delete file
                }


                $records = DB::select($nSQL);
                $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();

                if($ftype == 'xlsx'){
                    $headerCells = config('constant.XlsxHeaderCells');
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    $notAllowed = ['ROWN','ROWNUMBER'];
                    foreach ($aData as $colArr){
                        $i = 0;
                        foreach ($colArr as $cName => $value) {
                            if(!in_array($cName,$notAllowed)){
                                $sheet->setCellValue($headerCells[$i].'1', $cName);
                                $i++;
                            }
                        }
                        break;
                    }

                    $j = 2;
                    foreach ($aData as $ValArr){
                        $i = 0;
                        foreach ($ValArr as $cName => $value) {
                            if(!in_array($cName,$notAllowed)) {
                                $sheet->setCellValue($headerCells[$i] . $j, $value);
                                $i++;
                            }
                        }
                        $j++;
                    }

                    /* For Values */

                    $writer = new Xlsx($spreadsheet);
                    $writer->save(public_path()."\\downloads\\".$filename.".xlsx");
                    header("Content-Type: application/vnd.ms-excel");

                    $sBaseUrl = config('constant.BaseUrl');
                    return $ajax->success()
                        ->jscallback()
                        ->form_reset(false)
                        ->redirectTo(url('/') . "/downloads/" . $filename . '.xlsx')
                        ->response();

                }elseif ($ftype == 'pdf'){

                    $tableHtml = View::make('layouts.table',['records' => $aData])->render();
                    $header = ucfirst($repdes);
                    $footer = ucfirst($filename); //ucfirst('test');
                    /*$html = View::make('layouts.pdf-v2', [
                        'header' => $header,
                        'footer' => $footer,
                        'tablehtml' => $tableHtml,
                        'charthtml' => '',
                        'filename' => $filename.'.pdf',
                        'selections' => ''
                    ])->render();
                    echo $html; die;*/
                    PDF::loadView('layouts.pdf-v2', [
                        'header' => $header,
                        'footer' => $footer,
                        'tablehtml' => $tableHtml,
                        'charthtml' => '',
                        'filename' => $filename.'.pdf',
                        'selections' => ''
                    ])->setPaper('letter',$list_format)->setWarnings(false)->save(public_path().'/downloads/'.$filename.'.pdf');
                    // If you want to store the generated pdf to the server then you can use the store function
                    //$pdf->save(public_path().'/downloads/'.$filename);
                    // Finally, you can download the file using download function
                    //return $pdf->download($filename);
                    return $ajax->success()->jscallback('ajax_download_sr_file')
                        ->appendParam('link',url('/').'/downloads/'.$filename.'.pdf')
                        ->appendParam('filename',$filename.'.pdf')
                        ->response();
                }
            }
        }catch (\Exception $e){
            return $ajax->fail()
                ->appendParam('error_message',$e->getMessage())
                ->message('Downloading failed')
                ->response();

        }
    }

    public function convertToXLSX(Request $request, Ajax $ajax){
        $tablehtml = $request->input('tablehtml');
        $charthtml = $request->input('charthtml');
        $filename = $request->input('file_name');
        define('UPLOAD_DIR', public_path().'\\'.'Chart_Images\\');
        if(!empty($charthtml)){

            $img = $charthtml;
            if (strpos($img, 'data:image/png;base64,') !== false) {
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $cI = UPLOAD_DIR . uniqid() . '.png';
                file_put_contents($cI, $data);
            }else{
                $cI = $charthtml;
            }
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadhseet = $reader->loadFromString($tablehtml);
        $sheet = $spreadhseet->getActiveSheet();
        $sheet->getStyle('B3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
        $sheet->getStyle('C3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
        $sheet->getStyle('D3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
        $sheet->getStyle('E3')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT );
        $spreadhseet->setActiveSheetIndex(0);
        $spreadhseet->getActiveSheet()->setTitle('Table');

        $spreadhseet->createSheet();
        $spreadhseet->setActiveSheetIndex(1);
        $spreadhseet->getActiveSheet()->setTitle('Chart');


        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Paid');
        $drawing->setDescription('Paid');
        $drawing->setPath($cI); // put your path and image here
        $drawing->setCoordinates('A2');
        $spreadhseet->getActiveSheet()->setShowGridlines(False);
        $drawing->setOffsetX(110);
        $drawing->setRotation(360);
        $drawing->getShadow()->setVisible(false);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($spreadhseet->getActiveSheet());

        $spreadhseet->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

        $writer->save(public_path()."\\downloads\\".$this->prefix.$filename);
        $sBaseUrl = config('constant.BaseUrl');
        return $ajax->success()
            ->jscallback()
            ->form_reset(false)
            ->redirectTo($sBaseUrl . "downloads/" . $this->prefix.$filename)
            ->response();
    }

    public function sendViaEmail(Request $request, Ajax $ajax){
        $eRowid = $request->input('eCampid');
        $type = $request->input('t_type');
        //$tTable = $type == 'C' ? 'UC_Campaign_Templates' : 'UR_Report_Templates';

        //$sSql = DB::select("SELECT * FROM $tTable WHERE row_id='".$eRowid."' AND t_type='".$type."'");
        //$result = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        //$result = $result[0];
        if($type == 'C'){
            $result = App\Model\CampaignTemplate::with('rpschedule.ccschstatusmap')->where('row_id',$eRowid)->where('t_type',$type)->first()->toArray();
        }else{
            $result = App\Model\ReportTemplate::with('rpschedule.rpschstatusmap')->where('row_id',$eRowid)->where('t_type',$type)->first()->toArray();
        }
        //dd($record);

        $t_name = $result['t_name'];
        $listShortName = $result['list_short_name'];
        $file_Name = isset($result['rpschedule']['rpschstatusmap'][0]) ? $result['rpschedule']['rpschstatusmap'][0]['file_name'] : $listShortName;
        $t_id = $result['t_id'];
        $eFolder = $result['promoexpo_folder'];
        $ToUsers = $request->input('txtTo',[]);
        $Cc = $request->input('txtCc');
        $Bcc = $request->input('txtBcc');
        $Sub = $request->input('txtSub');
        $filePath = config('constant.filePath');

        $limitedtextarea1 = $request->input('limitedtextarea1');
        $Email_Attachment = $request->input('Email_Attachment');
        $uid = Auth::user()->User_ID;

        try{
            if(count($ToUsers) > 0){
                foreach ($ToUsers as $ToUser){
                    $user = User::where('User_ID',$ToUser)->first();
                    if($user){
                        $objDemo = new \stdClass();
                        $objDemo->data = (object)$result;
                        $objDemo->To = $user->User_Email;
                        $objDemo->Cc = $Cc;
                        $objDemo->Bcc = $Bcc;
                        $cm = $type == 'A' ? ' - Report ' : ' - Campaign ';
                        $objDemo->Sub = !empty($Sub) ? $this->clientname . ' - '. $Sub : $this->clientname . $cm . $listShortName;
                        $objDemo->limitedtextarea1 = $limitedtextarea1;
                        $objDemo->Email_Attachment = $Email_Attachment;
                        $objDemo->filePath = $filePath;
                        $objDemo->sender = 'Data Square Support Team';
                        $objDemo->senderEmail = 'esupport@datasquare.com';
                        $objDemo->receiver = $user->User_FName . ' ' .$user->User_LName;
                        $objDemo->sharedByName = Auth::user()->User_FName. ' ' .Auth::user()->User_LName;
                        $objDemo->sharedByEmail = Auth::user()->User_Email;
                        $objDemo->listShortName = $listShortName;
                        $objDemo->file_Name = $file_Name;
                        $objDemo->clientname = $this->clientname;
                        $type == 'A' ? Mail::to($user->User_Email)->send(new SendReportEmail($objDemo)) : Mail::to($user->User_Email)->send(SendCampaignEmail($objDemo));

                        DB::insert("INSERT INTO UL_RepCmp_Email (User_id,camp_tmpl_id,remail_to,remail_cc,remail_bcc,remail_sub,remail_comments,t_type,Email_Status) VALUES ($uid,$t_id,'$ToUser','$Cc','$Bcc','$Sub','$limitedtextarea1','$type','Sent')");
                    }

                }
            }
            $cm = $type == 'A' ? 'Report' : 'Campaign';
            return $ajax->success()
                ->jscallback('report_sent')
                ->message($cm.' sent successfully')
                ->response();
        }catch (\Exception $exception){
            if( count(Mail::failures()) > 0 ) {

                $emsg = "There was one or more failures. They were: <br />";

                foreach(Mail::failures() as $email_address) {
                    $emsg .= " - $email_address <br />";
                }
                return $ajax->fail()
                    ->jscallback()
                    ->message($emsg)
                    ->response();

            }else{
                return $ajax->fail()
                    ->jscallback()
                    ->message($exception->getMessage())
                    ->response();
            }
        }
    }

    public function saveSendViaEmail(Request $request, Ajax $ajax){
        $t_id = $request->input('eCampid');
        $ToUsers = $request->input('txtTo',[]);
        $Cc = $request->input('txtCc');
        $Bcc = $request->input('txtBcc');
        $Sub = $request->input('txtSub');
        $type = $request->input('t_type');
        $limitedtextarea1 = $request->input('limitedtextarea1');
        $uid = Auth::user()->User_ID;
        if(count($ToUsers) > 0) {
            foreach ($ToUsers as $ToUser) {
                $user = User::where('User_ID', $ToUser)->first();
                if ($user) {
                    DB::insert("INSERT INTO UL_RepCmp_Email (User_id,camp_tmpl_id,remail_to,remail_cc,remail_bcc,remail_sub,remail_comments,t_type,Email_Status) VALUES ($uid,$t_id,'$ToUser','$Cc','$Bcc','$Sub','$limitedtextarea1','$type','Pending')");
                }
            }
        }

        $cm = $type == 'A' ? 'Report' : 'Campaign';
        return $ajax->success()
            ->jscallback('report_sent')
            ->message($cm.' sent successfully')
            ->response();
    }

    public function saveSchSendViaEmail(Request $request, Ajax $ajax){
        $type = $request->input('t_type');
        $tTable = $type == 'C' ? 'UC_Campaign_Sequence' : 'UR_Report_Sequence';
        $seqSQL = DB::select('SELECT [camp_id] as cid FROM [$tTable]');
        $aData = collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();
        $cid = 0;

        if(!empty($aData)){
            $cid = $aData[0]['cid'];
            $arSQL = DB::select("SELECT count([t_id]) as cnt FROM [$tTable] WHERE t_id = '.$cid.' AND t_type='".$type."'");
            $arData = collect($arSQL)->map(function($x){ return (array) $x; })->toArray();
            if($arData[0]['cnt'] == 0){
                echo $cid; die;
            }
        }
        $t_id = $cid + 1;
        $To = $request->input('txtTo');
        $Cc = $request->input('txtCc');
        $Bcc = $request->input('txtBcc');
        $Sub = $request->input('txtSub');
        $limitedtextarea1 = $request->input('limitedtextarea1');
        $uid = Auth::user()->User_ID;

        DB::select("INSERT INTO UL_RepCmp_Email (User_id,camp_tmpl_id,remail_to,remail_cc,remail_bcc,remail_sub,remail_comments,t_type,Email_Status) VALUES ($uid,$t_id,'$To','$Cc','$Bcc','$Sub','$limitedtextarea1','$type','Pending')");

        return $ajax->success()->jscallback()->jscallback('report_sent')->response();
    }

    public function getShare(Request $request, Ajax $ajax){
        $eCampid = $request->input('eCampid');
        $t_type = $request->input('t_type');
        $user_id = empty($request->input('user_id')) ? Auth::user()->User_ID : $request->input('user_id');
        $sSql = DB::select("SELECT * FROM UL_RepCmp_Share WHERE User_id = $user_id AND camp_tmpl_id = $eCampid AND t_type = '$t_type'");
        $users = collect($sSql)->map(function($x){ return (array) $x; })->toArray();
        $u = [];
        if(is_array($users)){
            foreach($users as $user){
                $u[] = (int)$user['Shared_With_User_id'];
            }
        }
        return $ajax->success()
            ->appendParam('shared_with_user_id',$u)
            ->response();
    }

    public function share(Request $request, Ajax $ajax){
        $eCampid = $request->input('eCampid');
        $t_type = $request->input('t_type');
        $user_id = $request->input('user_id');
        $users = $request->input('users');
        $limitedtextarea4 = $request->input('limitedtextarea4');
        $cm = $t_type == 'A' ? 'Report' : 'Campaign';

        if(Helper::shareReport($eCampid,$t_type,$user_id,$users,$limitedtextarea4,$this->clientname,1,1)){
            return $ajax->success()
                ->jscallback('ajax_success_share')
                ->message($cm.' shared successfully')
                ->response();
        }else{
            return $ajax->fail()
                ->message($cm.' can\'t share')
                ->response();
        }
    }

    public function delete(Request $request, Ajax $ajax){
        $ttype = $request->input('type');
        $del_row = $request->input('del_row');

        try{
            if($ttype == 'C'){
                $mainTb = 'UC_Campaign_Templates';
                $prefixXlsx = $this->prefix.'CAL_';
                $prefixSR = $this->prefix.'CAM_';

            }elseif ($ttype == 'A'){
                $mainTb = 'UR_Report_Templates';
                $prefixXlsx = $this->prefix.'RPL_';
                $prefixSR = $this->prefix.'RPS_';
            }

            $SchtempSQL = DB::select("Select [t_id],
(isnull(za.promoexpo_folder+'/".$prefixSR."'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf','')) as SummaryPDF, (isnull(za.promoexpo_folder+'/".$prefixSR."'+za.promoexpo_file + RIGHT(za.t_name, 14) + '.xlsx','')) as SummaryXLSX,(isnull(za.promoexpo_folder + '/" . $prefixXlsx. "' + za.promoexpo_file + RIGHT(za.t_name, 14) + '.' + za.promoexpo_ext,'')) as [List] from [$mainTb] as za Where [row_id] = '$del_row'");

            $aData1 = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData1)) {
                $camp_id = $aData1[0]['t_id'];
                $srPDFfile = $aData1[0]['SummaryPDF'];
                $srXLSXfile = $aData1[0]['SummaryXLSX'];
                $listXLSXfile = $aData1[0]['List'];
            }

            $SchtempSQL = DB::select("Select [row_id],[file_path],[file_name] from [UL_RepCmp_Completed] Where [camp_id] = '$camp_id' AND t_type='$ttype'");

            $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData)) {
                $row_id = $aData[0]['row_id'];
                $folder = $aData[0]['file_path'];
                //Delete From View List
                DB::statement("Delete from [UL_RepCmp_Completed] Where row_id = '". $row_id ."' AND t_type='$ttype'");
                //Delete From View List
            }

            $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where [camp_tmpl_id] = '$del_row'  AND t_type='$ttype'");
            $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData)) {
                $sch_id = $aData[0]['row_id'];
            }

            $SchtempSQLs = DB::select("Select [sch_status_id] from [UL_RepCmp_Sch_status_mapping] Where [sch_id] = '$sch_id'  AND t_type='$ttype'");
            $aDatas = collect($SchtempSQLs)->map(function($x){ return (array) $x; })->toArray();
            $sch_status_id = [];
            if (!empty($aDatas)) {
                foreach ($aDatas as $aData){
                    array_push($sch_status_id,$aData['sch_status_id']);
                }
            }

            //Delete Metadata table rows
            DB::statement("DELETE from [UC_Campaign_Metadata] Where [CampaignID] = '" . $camp_id . "'");

            //Delete Metadata table rows

            //Delete UC_Campaign_Data table rows
            DB::statement("DELETE from [UC_Campaign_Data] Where [CampaignID] = '" . $camp_id . "'");
            //Delete UC_Campaign_Data table rows

            //Delete From View List
            DB::statement("Delete from [UL_RepCmp_Schedules] Where [camp_tmpl_id] = '$del_row' AND t_type='$ttype'");
            //Delete From View List

            //Delete From View List
            DB::statement("Delete from [UL_RepCmp_Status] Where row_id IN (" . implode(',',$sch_status_id) .") AND t_type='$ttype'");

            //Delete From View List

            //Delete From Za List
            DB::statement("Delete from [$mainTb] Where t_id = " . $camp_id);
            //Delete From Za List

            $file = public_path($srPDFfile);
            if(file_exists($file)){
                unlink($file);
            }

            $file = public_path($srXLSXfile);
            if(file_exists($file)){
                unlink($file);
            }

            $file = public_path($listXLSXfile);
            if(file_exists($file)){
                unlink($file);
            }

            return $ajax->success()->response();

        }catch (\Exception $exception){
            return $ajax->fail()
                ->jscallback()
                ->message($exception->getMessage())
                ->response();
        }

    }

    public function getFtpData(Request $request, Ajax $ajax){
        $row_id = $request->input('row_id');
        $cSQL = DB::select("SELECT * From [UL_RepCmp_SFTP] Where row_id = " . $row_id);
        $aData = collect($cSQL)->map(function($x){ return (array) $x; })->toArray();
        return $ajax->success()
            ->appendParam('odata',$aData[0])
            ->response();
    }

    /*public function pdf(){
        @ini_set('max_execution_time',500);

        $header = View::make('layouts.pdf-header',['header' => 'Header testing'])->render();
        $footer = View::make('layouts.pdf-footer',['footer' => 'footer testing'])->render();
        $html = View::make('layouts.pdf-content')->render();
        \SPDF::loadHtml($html)
            //->setOption('images', true)
            //->setPaper('letter')
            //->setOrientation('portrait')
            //->setOption('margin-bottom', 0)
            ->setOption('header-html', $header)
            ->setOption('footer-html', $footer)
            ->setOption('load-error-handling', false)
             ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 10)
            ->setOption('enable-smart-shrinking', false)
            ->setOption('no-stop-slow-scripts', false)
            //->setOption('toc', true)
            //->setOption('toc-header-text', 'chklo')
            ->save(storage_path('myfile1.pdf'),
                true // when there is file with same name it throws file already exists so I had to set owerwrite to true
            );
    }*/
}
