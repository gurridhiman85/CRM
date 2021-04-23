<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
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
use App\Mail\ShareReportEmail;
use Illuminate\Support\Facades\Mail;
use \LynX39\LaraPdfMerger\PdfManage;
use Session;

class CampaignSegmentController extends Controller
{
    public $schtasks_dir;
    public $schDir;
    public $phpPath;
    public $filePath;
    public $prefix;
    public $clientname;

    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->schDir = config('constant.schDir');
        $this->phpPath = config('constant.phpPath');
        $this->filePath = config('constant.filePath');
        $this->prefix = config('constant.prefix');
        $this->clientname = config('constant.client_name');
    }

    public function getAddsubVal(Request $request,Ajax $ajax){
        $tempid = $request->input('tempid');
        $record = App\Model\CampaignTemplate::with(['rpmeta'])->where('row_id',$tempid)->first(['row_id','t_id','seg_def','seg_noLS','seg_method','seg_criteria','seg_selected_criteria','seg_grp_no'
		,'seg_ctrl_grp_opt','seg_camp_grp_dtls','seg_camp_grp_proportion','seg_camp_grp_sel_cri','seg_sample'
		,'promoexpo_cd_opt','promoexpo_file_opt','promoexpo_folder','promoexpo_file','promoexpo_ext'
		,'promoexpo_ecg_opt','promoexpo_data'])->toArray();
        /*$SQL = "SELECT [seg_def],[seg_noLS],[seg_method],[seg_criteria],[seg_selected_criteria],[seg_grp_no]
		,[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],[seg_camp_grp_sel_cri],[seg_sample]
		,[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],[promoexpo_ext]
		,[promoexpo_ecg_opt],[promoexpo_data],[meta_data]
	 FROM [UC_Campaign_Templates] WHERE [row_id] = '$tempid'";

        $aData = DB::select($SQL);
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();*/
        return $ajax->success()
            ->appendParam('aData',count($record) > 0 ? $record : [])
            ->response();
    }

    public function getCol(Request $request,Ajax $ajax){
        $sSQL = $request->input('sSQL');
        if (trim($sSQL) != "") {

            $sSQL = iconv("UTF-8", "ISO-8859-1", $sSQL);
            if (strpos($sSQL, "*") === true) {
                $nSQL = str_replace("*", "TOP 1 * ", $sSQL);
            } else {
                $nSQL = substr($sSQL, 0, 6) . " top 1 " . substr($sSQL, 7, strlen($sSQL));
            }
            //echo $nSQL; die;
            $aData = DB::select($nSQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            $sD = [];
            if (!empty($aData)) {
                foreach ($aData[0] as $k => $v) {
                    $sD[] = $k;
                }
            }
            return $ajax->success()
                ->appendParam('columns',$sD)
                ->response();
        }
    }

    public function CG(Request $request,Ajax $ajax){
        $SQL = "SELECT [code_value] from [UC_Campaign_Lookup] WHERE code_type = 'CGD' order by code_value ";
        $aDataCGD = DB::select($SQL);
        $aDataCGD = collect($aDataCGD)->map(function($x){ return (array) $x; })->toArray();

        $SQL = "SELECT [code_value] from [UC_Campaign_Lookup] WHERE code_type = 'COff'";
        $aDataCOff = DB::select($SQL);
        $aDataCOff = collect($aDataCOff)->map(function($x){ return (array) $x; })->toArray();

        return $ajax->success()
            ->appendParam('aDataCGD',$aDataCGD)
            ->appendParam('aDataCOff',$aDataCOff)
            ->response();
    }

    public function LSDetails(Request $request,Ajax $ajax){
        $pgaction = $request->input('pgaction');
        $sSQL = $request->input('sSQL');
        if($pgaction == 'getCount') //none
        {
            $sSQL = iconv("UTF-8", "ISO-8859-1", $sSQL);
            $pos = strpos($sSQL, "Order By");
            if ($pos != false) {
                $sSQL = substr($sSQL, 0, $pos - 1);
            }
            $countSQL = "SELECT  count(*) as Count from " . "( " . $sSQL . " ) as t";
            //die;
            $aData = DB::select($countSQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData)) {
                $count = $aData[0]['Count'];
            } else {
                $count = 0;

            }
            return $ajax->success()
                ->appendParam('count',$count)
                ->response();
        }
        elseif ($pgaction == 'byFieldValue'){ //byfield
            $colName = $request->input('colName');

            $colArray = explode(",", $colName);
            $pos = strpos($sSQL,"Order By");

            if ($pos != false)
            {
                $sSQL = substr($sSQL,0,$pos-1);
            }
            $cols1 = join(" ,",$colArray);
            switch(count($colArray))
            {
                case 1:

                    $SQL = "SELECT ".$colArray[0].", COUNT(*) as Count From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."') group by ".$colArray[0];
                    break;
                case 2:
                    $SQL = "SELECT ".$cols1.", COUNT(*) as Count From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."')
				 and ".$colArray[1]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[1]."')
				 group by ".$cols1;
                    break;
                case 3:
                    $SQL = "SELECT ".$cols1.", COUNT(*) as Count From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."')
				 and ".$colArray[1]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[1]."')
				 and ".$colArray[2]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[2]."')
				 group by ".$cols1;
            }

            $aData = DB::select($SQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            $Str ='';
            if(!empty($aData)){
                foreach($aData as $k=>$row) {
                    $Str .= implode(",",$row).":";
                }
            }
            return $ajax->success()
                ->appendParam('str',$Str)
                ->response();
        }
        elseif ($pgaction == 'count'){
            $WhereArray = $request->input('WhereArray');
            //To Cut Orderby
            $pos = strpos($sSQL,"Order By");
            if($pos > 0)
                $sSQL =  substr($sSQL,0,$pos);
            /* Old code for segment adjustment */
            /*if (!empty($WhereArray)) {
                $where=explode(":WHERE:", $WhereArray);
                for($i=1;$i<sizeof($where);$i=$i+1){
                    if($where[$i]== "NULL"){
                        $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a ";
                    }else{
                        $w = '';
                        $cc_flag = 0;
                        for($j=0;$j<$i;$j++)
                        {
                            if($where[$j] == 'NULL')
                                continue;
                            if($cc_flag == 0)
                            {
                                $w .= $where[$j];
                                $cc_flag = 1;
                            }else
                                $w .= " or ".$where[$j];
                        }
                        if ($i != 0)
                            $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a  Where ". $where[$i] . " AND ( ".$w ." )";
                        else
                            $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a Where ". $where[$i];
                    }

                    //echo "<br />".$SQL;// die;
                    $aData = DB::select($SQL);
                    $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                    if(!empty($aData)){
                        $count[$i]= $aData[0]['Count'];
                    }else{
                        $count[$i]=0;
                    }

                }
                return $ajax->success()
                    ->appendParam('noRows',$i)
                    ->appendParam('count',$count)
                    ->response();
            }*/
            if (!empty($WhereArray)) {
                $where=explode(":WHERE:", $WhereArray);
                for($i=0;$i<sizeof($where);$i=$i+1){
                    if($where[$i]== "NULL"){
                        $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a ";
                    }else{
                        $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a Where ". $where[$i];
                    }

                    //echo "<br />".$SQL;// die;
                    $aData = DB::select($SQL);
                    $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                    if(!empty($aData)){
                        $count[$i]= $aData[0]['Count'];
                    }else{
                        $count[$i]=0;
                    }

                }
                return $ajax->success()
                    ->appendParam('noRows',$i)
                    ->appendParam('count',$count)
                    ->response();
            }
            else{
                $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a ";
                $aData=DB::select($SQL);
                $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                if(!empty($aData)){
                    $count[]= $aData[0]['Count'];
                }else{
                    $count[]=0;
                }
                return $ajax->success()->appendParam('count',$count)->response();
            }

        }

        elseif ($pgaction == 'getCountNopreview'){ //none
            $pos = strpos($sSQL,"Order By");

            if ($pos != false)
            {
                $sSQL = substr($sSQL,0,$pos-1);
            }

            $countSQL = "SELECT   from ". "( ".$sSQL." ) as t";
            $aData = DB::select($countSQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            if(!empty($aData)){
                $count= $aData[0]['Count'];
            }else
                $count = 0;
            return $ajax->success()->appendParam('count',$count)->response();
        }
        else if($pgaction == 'byFieldValueNopreview')
        {
            $colName = $request->input('colName');
            $colArray = explode(",", $colName);
            $pos = strpos($sSQL,"Order By");

            if ($pos != false)
            {
                $sSQL = substr($sSQL,0,$pos-1);
            }
            $cols1 = join(" ,",$colArray);
            switch(count($colArray))
            {
                case 1:

                    $SQL = "SELECT ".$colArray[0]." From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."') group by ".$colArray[0];
                    break;
                case 2:
                    $SQL = "SELECT ".$cols1." From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."')
				 and ".$colArray[1]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[1]."')
				 group by ".$cols1;
                    break;
                case 3:
                    $SQL = "SELECT ".$cols1." From ( ".$sSQL. ") as t". " WHERE ".$colArray[0]."
				 in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[0]."')
				 and ".$colArray[1]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[1]."')
				 and ".$colArray[2]." in (select code_value from UC_Campaign_Lookup where code_type = '".$colArray[2]."')
				 group by ".$cols1;
            }

            //echo "query=>".$SQL;
            //echo "<br>";
            $aData = DB::select($SQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            $Str ='';
            if(!empty($aData)){
                foreach($aData as $k=>$row) {
                    $Str .= implode(",",$row).":";
                }
            }
            return $ajax->success()->appendParam('str',$Str)->response();
        }
        else if ($pgaction=='countNopreview') {
            $WhereArray = $request->input('WhereArray');
            //To Cut Orderby
            $pos = strpos($sSQL,"Order By");
            if($pos > 0)
                $sSQL =  substr($sSQL,0,$pos);

            //To Cut Orderby
            $_SESSION['where'] = $WhereArray;
            $where=explode(":WHERE:", $WhereArray);

            for($i=0;$i<sizeof($where);$i=$i+1)
            {
                if($where[$i]== "NULL")
                {
                    $SQL = "SELECT  count(*) as Count from ". "( ".$sSQL." ) as a ";

                }else
                {
                    $w = '';
                    $cc_flag = 0;
                    for($j=0;$j<$i;$j++)
                    {
                        if($where[$j] == 'NULL')
                            continue;
                        if($cc_flag == 0)
                        {
                            $w .= $where[$j];
                            $cc_flag = 1;
                        }else
                            $w .= " or ".$where[$j];


                    }
                    if ($i != 0)
                        $SQL = "SELECT   ". "( ".$sSQL." ) as a  Where ". $where[$i] . " and not ( ".$w ." )";
                    else
                        $SQL = "SELECT   from ". "( ".$sSQL." ) as a Where ". $where[$i];
                }


                $aData = DB::select($SQL);
                if(!empty($aData)){
                    $count[$i]= $aData[0]['Count'];
                }else {$count[$i]=0;}

            }
            $s=implode(",", $count);
            return $ajax->success()->appendParam('str',$s)->response();
        }
    }

    public function byFieldCheck(Request $request,Ajax $ajax){
        $colName = $request->input('colName');
        $SQL = "SELECT [code_value] from [UC_Campaign_Lookup] WHERE code_type = '" . $colName ."'";
        $str = 'NOT';
        $aData=DB::select($SQL);
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        if(!empty($aData)){
            $str = '';
            foreach($aData as $k=>$row) {
                $sD[]= implode(",",$row);

            }
            $str = implode(",", $sD);
        }

        return $ajax->success()->appendParam('str',$str)->response();
    }

    public function generateQuickMeta(Request $request,Ajax $ajax){
        $pgaction = $request->input('pgaction');
        $sSQL = $request->input('sSQL');
        if($pgaction == 'getCount') //none
        {
            $sSQL = iconv("UTF-8", "ISO-8859-1", $sSQL);
            $pos = strpos($sSQL, "Order By");
            if ($pos != false) {
                $sSQL = substr($sSQL, 0, $pos - 1);
            }
            $countSQL = "SELECT  count(*) as Count from " . "( " . $sSQL . " ) as t";
            //die;
            $aData = DB::select($countSQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData)) {
                $count = $aData[0]['Count'];
            } else {
                $count = 0;

            }

           /* $content = View::make('campaign.tabs.create.quick-meta-popup',[
                'popup' => true
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
            $html = $view->render();*/

            return $ajax->success()
                ->appendParam('count',$count)
           //     ->appendParam('html', $html)
                ->response();
        }
    }
}
