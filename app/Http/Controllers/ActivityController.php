<?php
/**
 * Created by PhpStorm.
 * User: Gurpreet Singh
 * Date: 05-11-2021
 * Time: 04:43 PM
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Library\Ajax;
use App\Helpers\Helper;
use App\User;
use mysql_xdevapi\Schema;
use Validator;
use Auth;
use Crypt;
use DB;
use \Illuminate\Support\Facades\View as View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Session;
use Yajra\Datatables\Datatables;

class ActivityController
{
    public $prefix;
    public $headerCells;

    const FIRST_SCREEN_COLUMNS = [
        ['tag' , 'Tag'],
        ['merge' , ''],
        ['ds_mkc_contactid' , 'DS_MKC_ContactID'],
        ['ds_mkc_householdid' , 'DS_MKC_HouseholdID'],
        ['Extendedname' , 'Extendedname'],
        ['phone' , 'Phone'],
        ['Email' , 'Email'],
        ['EmailSegment' , 'EmailSegment'],
        ['email2' , 'Email 2'],
        ['Address' , 'Address'],
        ['City' , 'City'],
        ['State' , 'State'],
        ['Zip' , 'Zip'],
        ['Company' , 'Company'],
        ['update_date' , 'Updated'],
    ];

    public function __construct()
    {
        $this->prefix = config('constant.prefix');
        $this->headerCells = config('constant.XlsxHeaderCells');;

    }

    public function index(){
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        if(!in_array('Activity Details',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        /*$filtersFieldsValues = Helper::getActivityFiltersFieldsValues([

            'Productcat1_Des'      =>  true,
            'Productcat2_Des'      =>  true,
            'Product'          =>  true,
            'Class'             =>  true
        ]);

        return view('activity.index',[
            'Productcat1_Des'      =>  $filtersFieldsValues['Productcat1_Des'],
            'Productcat2_Des'      =>  $filtersFieldsValues['Productcat2_Des'],
            'Product'          =>  $filtersFieldsValues['Product'],
            'Class'          =>  $filtersFieldsValues['Class'],
        ]);*/


        $aActivity = Helper::getColumns('Activity','Activity');
        $aActivityFilters = Helper::getFilterValues($aActivity['visible_columns']);

        return view('activity.index',[
            'activityFilters' => $aActivityFilters,
        ]);
    }

    public static function implement_query($reqlevel,$filters = [],$pagination){
        $aActivity = Helper::getColumns('Activity','Activity');
        $sort =  "Order by sa.Date DESC ";
        $levels = [
            'Activity' => [
                'columns' => $aActivity['all_columns'],
                'visible_columns' => $aActivity['visible_columns'],
                'sql'   => 'SELECT '.implode(',',$aActivity['all_columns']).' FROM (SELECT ROW_NUMBER() over (Order by Date DESC) as ROWNUMBER,* from '.$aActivity['table_name'].' ?where?) _myResults ',
                'filter' => 1
            ]
        ];



        foreach ($levels as $level=>$level_values){
            if($level == $reqlevel){
                if($level_values['filter'] == 1){
                    $where = Helper::getFiltersCondition($filters,$reqlevel,$level_values['visible_columns']);
                    $sql = str_replace('?where?',$where['Where'],$level_values['sql']);
                    $sSql = $sql.$pagination;
                    $nSql = $sql;
                }else{
                    $sql = str_replace('?where?','',$level_values['sql']);
                    $sSql = $sql.$pagination;
                    $nSql = $sql;
                }

                $records = DB::select($sSql);
                $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

                $total_records = count(DB::select($nSql));
                return [
                    'sql'  => $sql,
                    'records' => $records,
                    'total_records' => $total_records,
                    'columns' => $level_values['columns'],
                    'visible_columns' => $level_values['visible_columns'],
                ];
            }
        }
    }

    public function details(Request $request,Ajax $ajax){
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;

        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);
        $pagination = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
        $result = self::implement_query('Activity',$filters,$pagination);
        $sort_column = "Campaign_ID";
        $sort_dir = "DESC";
        $tabName = 'activity';
        $data = [
            'records' => $result['records'],
            'visible_columns' => $result['visible_columns'],
            'tab' => $tabName,
            'sort_column' => $sort_column,
            'sort_dir' => $sort_dir,
            'filters' => json_encode($filters)
        ];
        if($rType == 'pagination'){
            $html = View::make('activity.table',$data)->render();
        }else{
            $html = View::make('activity.first-screen',$data)->render();
        }

        $paginationhtml = View::make('activity.pagination-html',[
            'total_records' => $result['total_records'],
            'records' => $result['records'],
            'position' => $position,
            'records_per_page' => $records_per_page,
            'page' => $page,
            'tab' => $tabName
        ])->render();


        return $ajax->success()
            ->appendParam('total_records',$result['total_records'])
            ->appendParam('records',$result['records'])
            ->appendParam('html',$html)
            ->appendParam('sql',$result['sql'])
            ->appendParam('paginationHtml',$paginationhtml)
            ->jscallback('load_ajax_tab')
            ->response();
    }

    public static function ApplyFiltersCondition($filters){

        $txtProductcat1_Des = isset($filters['Productcat1_Des']) ? $filters['Productcat1_Des'][0] : '';
        $txtProductcat2_Des = isset($filters['Productcat2_Des']) ? $filters['Productcat2_Des'][0] : '';
        $txtProduct = isset($filters['Product']) ? $filters['Product'][0] : '';
        $txtDate_from = isset($filters['Date_from']) ? $filters['Date_from'][0] : '';
        $txtDate_to = isset($filters['Date_to']) ? $filters['Date_to'][0] : '';
        //$txtDate = isset($filters['Date']) ? $filters['Date'][0] : '';
        $txtAmount = isset($filters['Amount'][0]) ? [$filters['Amount_op'][0],$filters['Amount'][0]] : ['',''];
        $txtClass = isset($filters['Class']) ? $filters['Class'][0] : '';
        $txtMemo = isset($filters['Memo']) ? $filters['Memo'][0] : '';
        $txtAccount = isset($filters['Account']) ? $filters['Account'][0] : '';
        $txtClientMessage = isset($filters['ClientMessage']) ? $filters['ClientMessage'][0] : '';
        $txtDFLName = isset($filters['DFLName']) ? $filters['DFLName'][0] : '';
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';

        $tPC1Array = array();
        $tPC2Array = array();
        $tPArray = array();

        $sWhere = "";
        $aAd = 0;
        /* if (!empty($txtProductcat1_Des)) {
             $sWhere .= " Productcat1_Des='" . trim($txtProductcat1_Des) . "'";
             $aAd++;
         }
         if (!empty($txtProductcat2_Des)) {
             $sWhere .= $aAd > 0 ? " and " : "";
             $sWhere .= " Productcat2_Des= '" . trim($txtProductcat2_Des) . "' ";
         }
         if (!empty($txtProduct)) {
             $sWhere .= $aAd > 0 ? " and " : "";
             $sWhere .= " Product like '%" . trim($txtProduct) . "%' ";
         }*/

        if(isset($filters['Productcat1_Des'])) {
            foreach ($filters['Productcat1_Des'] as $PC1) {
                $tPC1Array[] = "'" . str_replace("'", "''", $PC1) . "'";
            }

            if (count($tPC1Array) > 0) {
                if(!empty($sWhere)){
                    $sWhere .= ' and ';
                }
                $sWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat1_Des= " . implode(" OR Productcat1_Des= ", $tPC1Array) . ")";
                $aAd++;
            }
        }

        if(isset($filters['Productcat2_Des'])) {
            foreach ($filters['Productcat2_Des'] as $PC2) {
                $tPC2Array[] = "'" . str_replace("'", "''", $PC2) . "'";
            }

            if (count($tPC2Array) > 0) {
                $sWhere .= $aAd > 0 ? " and " : "";
                $sWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where Productcat2_Des= " . implode(" OR Productcat2_Des= ", $tPC2Array) . ")";
                $aAd++;
            }
        }

        if(isset($filters['Product'])) {
            foreach ($filters['Product'] as $P) {
                $tPArray[] = "'%" . str_replace("'", "''", $P) . "%'";
            }

            if (count($tPArray) > 0) {
                $sWhere .= $aAd > 0 ? " and " : "";
                $sWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where productcat1 + '-'+product like " . implode(" OR productcat1 + '-'+product like ", $tPArray) . ")";
                $aAd++;
            }
        }

        if (!empty($txtDate_from) || !empty($txtDate_to)) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if (!empty($txtDate_from) && empty($txtDate_to)) {
                $sWhere .= " (isnull(Date,'') BETWEEN '" . $txtDate_from . "' AND '" . $txtDate_from . "')";
            }else if (empty($txtDate_from) && !empty($txtDate_to)) {
                $sWhere .= " (isnull(Date,'') BETWEEN '" . $txtDate_to . "' AND '" . $txtDate_to . "')";
            }else if (!empty($txtDate_from) && !empty($txtDate_to)) {
                $sWhere .= " (isnull(Date,'') BETWEEN '" . $txtDate_from . "' AND '" . $txtDate_to . "')";
            }
        }

        if (isset($txtAmount[1]) && !empty($txtAmount[1])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            $sWhere .= " (Amount " . $txtAmount[0] . " " . $txtAmount[1] . ")";
        }

        if ($txtClass != "" || in_array($txtClass,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtClass != "" && !in_array($txtClass,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(Class,'') like '%" . $txtClass . "%')";

            }elseif (in_array($txtClass,["blank","Blank"])){
                $sWhere .= " (isnull(Class,'') =  '')";

            }elseif (in_array($txtClass,["not blank","Not Blank"])){
                $sWhere .= " (isnull(Class,'') !=  '')";
            }
        }

        if ($txtMemo != "" || in_array($txtMemo,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtMemo != "" && !in_array($txtMemo,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(memo,'') like '%" . $txtMemo . "%')";

            }elseif (in_array($txtMemo,["blank","Blank"])){
                $sWhere .= " (isnull(memo,'') =  '')";

            }elseif (in_array($txtMemo,["not blank","Not Blank"])){
                $sWhere .= " (isnull(memo,'') !=  '')";
            }
        }

        if ($txtAccount != "" || in_array($txtAccount,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtAccount != "" && !in_array($txtAccount,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(Account,'') like '%" . $txtAccount . "%')";

            }elseif (in_array($txtAccount,["blank","Blank"])){
                $sWhere .= " (isnull(Account,'') =  '')";

            }elseif (in_array($txtAccount,["not blank","Not Blank"])){
                $sWhere .= " (isnull(Account,'') !=  '')";
            }
        }

        if ($txtClientMessage != "" || in_array($txtClientMessage,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtClientMessage != "" && !in_array($txtClientMessage,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(ClientMessage,'') like '%" . $txtClientMessage . "%')";

            }elseif (in_array($txtClientMessage,["blank","Blank"])){
                $sWhere .= " (isnull(ClientMessage,'') =  '')";

            }elseif (in_array($txtClientMessage,["not blank","Not Blank"])){
                $sWhere .= " (isnull(ClientMessage,'') !=  '')";
            }
        }

        if ($txtDFLName != "" || in_array($txtDFLName,["blank","Blank","not blank","Not Blank"])) {
            if(!empty($sWhere)){
                $sWhere .= ' and ';
            }
            if ($txtDFLName != "" && !in_array($txtDFLName,["blank","Blank","not blank","Not Blank"])) {
                $sWhere .= " (isnull(DFLName,'') like '%" . $txtDFLName . "%')";

            }elseif (in_array($txtDFLName,["blank","Blank"])){
                $sWhere .= " (isnull(DFLName,'') =  '')";

            }elseif (in_array($txtDFLName,["not blank","Not Blank"])){
                $sWhere .= " (isnull(DFLName,'') !=  '')";
            }
        }

        if ($txtSearch != "") {
            $sWhere .= $aAd > 0 ? " and " : "";
            $sWhere .= " (isnull(DS_MKC_ContactID,'') like '%" . $txtSearch . "%' OR isnull(DS_MKC_HouseholdID,'') like '%" . $txtSearch . "%' OR isnull(customer,'') like '%" . $txtSearch . "%')";
        }

        return !empty($sWhere) ? ' WHERE '.$sWhere : '';
    }

    public function download(Request $request,Ajax $ajax){
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '1024M');
        //ob_clean();

        $screen = $request->input('screen');
        $filters = $request->input('filters',[]);
        $downloadableColumns = json_decode($request->input('downloadableColumns',''));

        $results = self::implement_query('Activity',$filters,'');
        //echo '<pre>'; dd($results); die;
        $files = glob(public_path().'/downloads/*'); // get all file names
        foreach($files as $file){ // iterate files
            if(is_file($file))
                unlink($file); // delete file
        }

        $view = View::make('activity.xlsx',[
            'records' => $results['records'],
            'downloadColumnsIndex' => $downloadableColumns,
            'columns' => $results['columns'],
            'visible_columns' => $results['visible_columns']
        ])->render();

        //echo $view; die;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadhseet = $reader->loadFromString($view);
        $sheet = $spreadhseet->getActiveSheet();
        $spreadhseet->setActiveSheetIndex(0);
        $sTitle = $screen == 'lookup' ? 'Activity Detail' : 'Touches';
        $spreadhseet->getActiveSheet()->setTitle($sTitle);

        $file_Name = $this->prefix.'Activity_details_'. date('Y') . date('m') . date('d');

        $writer = new Xlsx($spreadhseet);
        $writer->save(public_path()."\\downloads\\".$file_Name.".xlsx");
        $sBaseUrl = config('constant.BaseUrl');
        return $ajax->success()
            ->jscallback()
            ->form_reset(false)
            ->redirectTo($sBaseUrl . "downloads/" . $file_Name. '.xlsx')
            ->response();
    }
}
