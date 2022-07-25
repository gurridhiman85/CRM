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
use Yajra\Datatables\Datatables;
use DateTime;
use Doctrine\DBAL\Driver\PDOConnection;

class ModelController extends Controller
{
    public $schtasks_dir;
    public $schDir;
    public $phpPath;
    public $filePath;
    public $prefix;
    public $clientname;
    public $db;
    public $headerCells;

    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->schDir = config('constant.schDir');
        $this->phpPath = config('constant.phpPath');
        $this->filePath = config('constant.filePath');
        $this->prefix = config('constant.prefix');
        $this->clientname = config('constant.client_name');
        $this->db = DB::connection('sqlsrv');
        $this->headerCells = config('constant.XlsxHeaderCells');
    }

    public function index(){
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        /*if(!in_array('Campaign',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }*/

        /*$filtersFieldsValues = Helper::getEvalDetailFiltersFieldsValues([
            'Campaign_ID' => true,
            'Description' => true,
            'Universe' => true,
            'Objective' => true,
            'Brand' => true,
            'Channel' => true,
            'Offer_Category' => true,
        ]);
        return view('model.index',[
            'Campaign_ID' => $filtersFieldsValues['Campaign_ID'],
            'Description' => $filtersFieldsValues['Description'],
            'Universe' => $filtersFieldsValues['Universe'],
            'Objective' => $filtersFieldsValues['Objective'],
            'Brand' => $filtersFieldsValues['Brand'],
            'Channel' => $filtersFieldsValues['Channel'],
            'Offer_Category' => $filtersFieldsValues['Offer_Category'],
        ]);*/

        /*$eSummary = Helper::getColumns('Campaign','Evaluation Summary');
        $eDetail = Helper::getColumns('Campaign','Evaluation Detail');
        $mMetadata = Helper::getColumns('Campaign','Metadata');*/

        $levels = App\Model\UAFieldMapping::distinct()
            ->where('menu_level1', 'Analytics')
            ->orderBy('menu_level2')
            ->pluck('menu_level2')
            ->toArray();
        $lLevelFilters = [];
        foreach ($levels as $level){
            $lLevelColumns = Helper::getColumns('Analytics',$level);
            $lLevelFilters[$level] = Helper::getFilterValues($lLevelColumns['visible_columns']);
        }

        //$results = Helper::getFiltersSummaryDetail($eSummary['visible_columns'],$eDetail['visible_columns'],$mMetadata['visible_columns']);
        return view('model.index',[
            /*'sumFilters' => $results['sumFilters'],
            'detailFilters' => $results['detailFilters'],
            'metadataFilters' => $results['metadataFilters'],*/
            'lLevelFilters'       => $lLevelFilters,
            'alllevels' => $levels
        ]);
    }

    public static function implement_query($reqlevel,$filters = [],$pagination,$add_sort_end = true,$sort = ''){
        $lLevel = Helper::getColumns('Analytics',$reqlevel);
        $sort = empty($sort) ? $lLevel['sort'] : $sort;

        $levels = [
            $reqlevel => [
                'columns' => $lLevel['all_columns'],
                'visible_columns' => $lLevel['visible_columns'],
                'filter_columns' => $lLevel['filter_columns'],
                'sql'   => 'select '.implode(',',$lLevel['all_columns']).' from (SELECT *,ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS ROWNUMBER FROM '.$lLevel['table_name'].' ?where?) as t ',
                'filter' => 1
            ]

        ];

        foreach ($levels as $level=>$level_values){
            if($level == $reqlevel){
                if($level_values['filter'] == 1){
                    $where = Helper::getFiltersCondition($filters,$reqlevel,$level_values['filter_columns']);
                    $sql = str_replace('?where?',$where['Where'],$level_values['sql']);
                    $sSql = $add_sort_end ? $sql.$pagination.' '.$sort : $sql.$pagination;

                    $nSql = $sql;
                }else{
                    $sql = str_replace('?where?','',$level_values['sql']);
                    $sSql = $add_sort_end ? $sql.$pagination.' '.$sort : $sql.$pagination;
                    $nSql = $sql;
                }

                $records = DB::select($sSql);
                $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

                $total_records = count(DB::select($nSql));
                return [
                    'sql'  => $sSql,
                    'records' => $records,
                    'total_records' => $total_records,
                    'columns' => $level_values['columns'],
                    'visible_columns' => $level_values['visible_columns'],
                ];
            }
        }
    }


    public function getModel(Request $request,Ajax $ajax){

        $tabid = $request->input('tabid');
        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : '';
        $page = $request->input('page',1);
        $records_per_page = config('constant.record_per_page');
        $sort = ($sort == "") ? "Order By CampaignId DESC" : $sort == 'CampaignId' ? "Order By CampaignId DESC" : "Order By $sort $dir";

        $resolver['Objective'] = 'substring(meta_data, 1, P1.Pos - 1)';
        $resolver['Brand'] = 'substring(meta_data,  P1.Pos + 1,  P2.Pos -  P1.Pos - 1)';
        $resolver['Channel'] = 'substring(meta_data,  P2.Pos + 1,  P3.Pos -  P2.Pos - 1)';
        $resolver['Description'] = 'substring(meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1)';
        $resolver['Universe'] = 'substring(meta_data,  P4.Pos + 1,  P5.Pos -  P4.Pos - 1)';
        $resolver['Year'] = 'substring(meta_data,  P6.Pos + 1,  P7.Pos -  P6.Pos - 1)';
        $resolver['Month'] = 'substring(meta_data,  P7.Pos + 1,  P8.Pos -  P7.Pos - 1)';
        $resolver['Day'] = 'substring(meta_data,  P8.Pos + 1,  P9.Pos -  P8.Pos - 1)';

        $uWhere = '';
        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);
        $position = ($page-1) * $records_per_page;

        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        if($tabid == '0'){  // Running

            $query = App\Model\ModelScoreTemplate::query()->with(['rpschedule.moschstatusmap','momodel','modelscoremetadata']);
            if($User_Type != 'Full_Access') {
                $query->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $query->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Running');
            });
            //Helper::ApplyFiltersConditionForCC($filters,$query);
            $records = $query->skip($position)
                ->take($records_per_page)
                ->orderBy('row_id', 'DESC')
                ->get()
                ->toArray();
            //dd($records);
            $trQuery = App\Model\ModelScoreTemplate::query();
            if($User_Type != 'Full_Access') {
                $trQuery->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $trQuery->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Running');
            });
            //Helper::ApplyFiltersConditionForCC($filters,$trQuery);
            $total_records = $trQuery->count();

            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'running';
            if($rType == 'pagination'){
                $html = View::make('model.tabs.running.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('model.tabs.running.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            $paginationhtml = View::make('model.tabs.running.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();

            return $ajax->success()
                ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 1){ // Scheduled

            $query = App\Model\ModelScoreTemplate::query()->with('momodel','modelscoremetadata','rpschedule.moschstatusmap');
            if($User_Type != 'Full_Access') {
                $query->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $query->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Scheduled');
            });
            Helper::ApplyFiltersConditionForCC($filters,$query);
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get()->toArray();

            $trQuery = App\Model\ModelScoreTemplate::query();
            if($User_Type != 'Full_Access') {
                $trQuery->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $trQuery->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Scheduled');
            });
            Helper::ApplyFiltersConditionForCC($filters,$trQuery);
            $total_records = $trQuery->count();

            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'scheduled';
            if($rType == 'pagination'){
                $html = View::make('model.tabs.scheduled.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('model.tabs.scheduled.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            $paginationhtml = View::make('model.tabs.scheduled.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();

            return $ajax->success()
                ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 2){ // Completed
            $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
            $sort = ($sort == "") ? "Order By CampaignId DESC" : $sort == 'CampaignId' ? "Order By CampaignId DESC" : "Order By $sort $dir";

            $query = App\Model\ModelScoreTemplate::query()->with(['rpschedule.moschstatusmap','momodel','modelscoremetadata']);
            if($User_Type != 'Full_Access') {
                $query->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });

            }
            $query->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Completed');
            });
            Helper::ApplyFiltersConditionForCC($filters,$query);
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get();

            $trQuery = App\Model\ModelScoreTemplate::query();
            if($User_Type != 'Full_Access') {
                $trQuery->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $trQuery->whereHas('rpschedule.moschstatusmap',function ($qry){
                $qry->where('status','Completed');
            });
            Helper::ApplyFiltersConditionForCC($filters,$trQuery);
            $total_records = $trQuery->count();

            $fFields = DB::select("SELECT * FROM [UA_Model_Field_Mapping] WHERE Menu_Level1 = 'Model' AND Menu_Level2 = 'Catalog' ORDER BY RowID");
            $fFields = collect($fFields)->map(function($x){ return (array) $x; })->toArray();
            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'level1';
            if($rType == 'pagination'){
                $html = View::make('model.tabs.completed.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                    'fFields' => $fFields
                ])->render();
            }else{
                $html = View::make('model.tabs.completed.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                    'fFields' => $fFields
                ])->render();
            }

            $paginationhtml = View::make('model.tabs.completed.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();
            return $ajax->success()
                ->appendParam('total_records',$total_records)
                ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if ($tabid == 3){
            $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';
            $query = App\Model\ModelScoreTemplate::query()->with('rpschedule.ccschstatusmap');
            if($User_Type != 'Full_Access') {
                $query->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });

            }


            $query->whereHas('rpschedule.ccschstatusmap',function ($qry) use($txtSearch){
                $qry->whereIn('status',['Completed','Child']);
            });
            Helper::ApplyFiltersConditionForCC($filters,$query,true);
            $query->where('row_id',$request->row_id);
            $records = $query->skip($position)
                ->take($records_per_page)
                ->orderBy('row_id', 'DESC')
                ->get();
            //print_r($records); die;
            $trQuery = App\Model\ModelScoreTemplate::query();
            if($User_Type != 'Full_Access') {
                $trQuery->where(function ($qry) use($uid){
                    $qry->whereHas('rpshare',function ($subqry) use($uid){
                        $subqry->where('Shared_With_User_id',$uid);
                    });
                    $qry->orWhere(function ($subqry) use ($uid) {
                        $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                    });
                });
            }
            $trQuery->whereHas('rpschedule.ccschstatusmap',function ($qry) use ($txtSearch){
                $qry->whereIn('status',['Completed','Child']);
            });
            Helper::ApplyFiltersConditionForCC($filters,$trQuery,true);
            $trQuery->where('row_id',$request->row_id);
            $total_records = $trQuery->count();

            $tabName = 'older';
            if($rType == 'pagination'){
                $html = View::make('model.tabs.older_versions.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }else{
                $html = View::make('model.tabs.older_versions.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }

            $paginationhtml = View::make('model.tabs.older_versions.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();
            return $ajax->success()
                ->appendParam('total_records',$total_records)
                ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 4){ // Evaluation Summary
            $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
            $sort = "Order By Campaign_ID DESC";
            $result = self::implement_query('Evaluation Summary',$filters);

            $sort_column = "Campaign_ID";
            $sort_dir = "DESC";
            $tabName = 'evaluation summary';
            $data = [
                'records' => $result['records'],
                'visible_columns' => $result['visible_columns'],
                'uid' => $uid,
                'tab' => $tabName,
                'sort_column' => $sort_column,
                'sort_dir' => $sort_dir,
                'filters' => json_encode($filters)
            ];
            if($rType == 'pagination'){
                $html = View::make('model.tabs.ESummary.table',$data)->render();
            }else{
                $html = View::make('model.tabs.ESummary.index',$data)->render();
            }

            return $ajax->success()
                ->appendParam('records',$result['records'])
                ->appendParam('html',$html)
                ->appendParam('sql',$result['sql'])
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 5){ // Evaluation Details
            $result = self::implement_query('Evaluation Detail',$filters);

            $sort_column = "Campaign_ID";
            $sort_dir = "DESC";
            $tabName = 'evaluation details';
            $data = [
                'records' => $result['records'],
                'visible_columns' => $result['visible_columns'],
                'uid' => $uid,
                'tab' => $tabName,
                'sort_column' => $sort_column,
                'sort_dir' => $sort_dir,
                'filters' => json_encode($filters)
            ];
            if($rType == 'pagination'){
                $html = View::make('model.tabs.EDetails.table',$data)->render();
            }else{
                $html = View::make('model.tabs.EDetails.index',$data)->render();
            }

            return $ajax->success()
                //->appendParam('total_records',$total_records)
                ->appendParam('records',$result['records'])
                ->appendParam('sql',$result['sql'])
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 7){ // Outer Metadata
            $modelscores = App\Model\ModelScoreMetadata::orderByDesc('ModelScoreID')->get();
            //dd($campaigns);
            $html = View::make('model.tabs.Singlecamp.index',['modelscores' => $modelscores])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 8 || $tabid == 9) {
            $SQL = "SELECT [ModelBuildID],[Model_Name],[Model_Objective] from [UM_ModelBuild_Metadata]";
            $models = DB::select($SQL);

            $list_levels = DB::select("select * from  UL_RepCmp_Lookup_Level_Camp");

            $fFields = DB::select("SELECT DISTINCT [Field_Display],[Profile] from [UL_RepCmp_Lookup_Fields] WHERE [Profile] IN (1,2)");
            $fFields = collect($fFields)->map(function($x){ return (array) $x; })->toArray();

            $html = View::make('model.tabs.create.new-v1', [
                'tabid' => $tabid,
                'fFields' => $fFields,
                'models' => $models,
                'list_levels' => $list_levels
            ])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 10) {
            $html = View::make('model.tabs.create.segment', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 11) {
            $html = View::make('model.tabs.create.export', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 12) {
            $html = View::make('model.tabs.create.metadata', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 13) {
            $html = View::make('model.tabs.create.execute', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else{
            $sort = "Order By CampaignID DESC";


            $tabidWS = str_replace('_',' ',$tabid);
            $pagination = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
            $result = self::implement_query($tabidWS,$filters,$pagination,false,$sort);

            $sort_column = "CampaignID";
            $sort_dir = "DESC";
            $tabName = 'evaluation summary';
            $data = [
                'records' => $result['records'],
                'visible_columns' => $result['visible_columns'],
                'uid' => $uid,
                'tab' => $tabName,
                'sort_column' => $sort_column,
                'sort_dir' => $sort_dir,
                'filters' => json_encode($filters)
            ];
            if($rType == 'pagination'){
                $html = View::make('model.tabs.ESummary.table',$data)->render();
            }else{
                $html = View::make('model.tabs.ESummary.index',$data)->render();
            }

            $paginationhtml = View::make('model.tabs.ESummary.pagination-html',[
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

    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRunningTabData(Request $request)
    {
        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        $filters = $request->input('filters',[]);
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'row_id' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];

        $query = App\Model\ModelScoreTemplate::query()->with(['rpmeta','rpschedule.ccschstatusmap']);
        if($User_Type != 'Full_Access') {
            $query->where(function ($qry) use($uid){
                $qry->whereHas('rpshare',function ($subqry) use($uid){
                    $subqry->where('Shared_With_User_id',$uid);
                });
                $qry->orWhere(function ($subqry) use ($uid) {
                    $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                });
            });
        }
        $query->whereHas('rpschedule.ccschstatusmap',function ($qry){
            $qry->where('status','Running');
        });
        //$query->orderBy($sort_column, $sort_dir);

        //dd($sSql);
        return Datatables::of($query)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags(trim($category));
                    if (strlen($category) > 50){
                        $categoryCut = substr($category, 0, 50);
                        $endPoint = strrpos($categoryCut, ' ');

                        //if the string doesn't contain any space then it will cut without word basis.
                        $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);

                        return '<span class="teaser">'. $string .'</span>
                        <span class="complete">'. $category .'</span>
                        <span class="more font-14" onclick="readmore($(this))">+</span>';
                    }else{
                        return $category;
                    }
                }
                return $category;
            })
            ->addColumn('StartTime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['ccschstatusmap'][0]) ? $data['rpschedule']['ccschstatusmap'] : [];
                $start_date = !empty($rpstatus) ? $rpstatus[0]['start_time'] : date('Y-m-d h:i');
                $dDatePart = explode(" ", $start_date);
                $tTimePart = explode(":", $dDatePart[1]);

                return $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
            })
            ->addColumn('next_runtime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['ccschstatusmap'][0]) ? $data['rpschedule']['ccschstatusmap'] : [];
                if(!empty($rpstatus) && !empty($rpstatus[0]['next_runtime'])){
                    $dDatePart = explode(" ", $rpstatus[0]['next_runtime']);
                    $tTimePart = explode(":", $dDatePart[1]);
                    $next_runtime = $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
                }else{
                    $next_runtime = '';
                }
                return $next_runtime;
            })
            ->addColumn('FTP',function ($data){
                $ftp = !empty($data->rpcompleted->ftp_flag) ? $data->rpcompleted->ftp_flag : 'N';
                return $ftp;
            })
            ->addColumn('is_share',function ($data){
                $is_share = isset($data->rpshare) && !empty($data->rpshare->Shared_With_User_id) && $data->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N';
                return $is_share;
            })
            ->addColumn('action',function ($data) {
                $action = '<select  onchange=\'show_Create_library($(this))\' class=\'form-control-sm\' style="border-color: #bfe6f6;text-align-last: center;">
                    <option value=\'0\'>Select</option>
                    <option value=\'view,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Delete</option>            
                </select>';
                return $action;
            })
            ->rawColumns(['Description','StartTime','next_runtime','action'])
            ->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScheduledTabData(Request $request)
    {
        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        $filters = $request->input('filters',[]);
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'row_id' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];

        $query = App\Model\ModelScoreTemplate::query()->with('rpmeta','rpschedule.ccschstatusmap');
        if($User_Type != 'Full_Access') {
            $query->where(function ($qry) use($uid){
                $qry->whereHas('rpshare',function ($subqry) use($uid){
                    $subqry->where('Shared_With_User_id',$uid);
                });
                $qry->orWhere(function ($subqry) use ($uid) {
                    $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                });
            });
        }
        $query->whereHas('rpschedule.ccschstatusmap',function ($qry){
            $qry->where('status','Scheduled');
        });

        return Datatables::of($query)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags(trim($category));
                    if (strlen($category) > 50){
                        $categoryCut = substr($category, 0, 50);
                        $endPoint = strrpos($categoryCut, ' ');

                        //if the string doesn't contain any space then it will cut without word basis.
                        $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);

                        return '<span class="teaser">'. $string .'</span>
                        <span class="complete">'. $category .'</span>
                        <span class="more font-14" onclick="readmore($(this))">+</span>';
                    }else{
                        return $category;
                    }
                }
                return $category;
            })
            ->addColumn('Schedule_type',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                if($data['rpschedule']['Schedule_type'] == 'RP') {
                    return ucfirst($data['rpschedule']['rp_run_sch']);
                }elseif($data['rpschedule']['Schedule_type'] == 'RA'){
                    return 'Once';
                }else{
                    return 'Once';
                }
            })
            ->addColumn('StartTime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['ccschstatusmap'][0]) ? $data['rpschedule']['ccschstatusmap'] : [];
                $start_date = !empty($rpstatus) ? $rpstatus[0]['start_time'] : date('Y-m-d h:i');
                $dDatePart = explode(" ", $start_date);
                $tTimePart = explode(":", $dDatePart[1]);

                return $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
            })
            ->addColumn('next_runtime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['ccschstatusmap'][0]) ? $data['rpschedule']['ccschstatusmap'] : [];
                if(!empty($rpstatus) && !empty($rpstatus[0]['next_runtime'])){
                    $dDatePart = explode(" ", $rpstatus[0]['next_runtime']);
                    $tTimePart = explode(":", $dDatePart[1]);
                    $next_runtime = $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
                }else{
                    $next_runtime = '';
                }
                return $next_runtime;
            })
            ->addColumn('EndDate',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $endDate = !empty($data['rpschedule']['rp_end_date']) ? $data['rpschedule']['rp_end_date'] : '';
                return $endDate;
            })
            ->addColumn('FTP',function ($data){
                $ftp = !empty($data->rpcompleted->ftp_flag) ? $data->rpcompleted->ftp_flag : 'N';
                return $ftp;
            })
            ->addColumn('is_share',function ($data){
                $is_share = isset($data->rpshare) && !empty($data->rpshare->Shared_With_User_id) && $data->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N';
                return $is_share;
            })
            ->addColumn('action',function ($data) {
                $action = '<select  onchange=\'show_Create_library($(this))\' class=\'form-control-sm\' style="border-color: #bfe6f6;text-align-last: center;">
                    <option value=\'0\'>Select</option>
                    <option value=\'view,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Delete</option>            
                </select>';
                return $action;
            })
            ->rawColumns(['Description','Schedule_type','StartTime','next_runtime','EndDate','action'])
            ->make(true);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompleteTabData(Request $request)
    {
        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        $filters = $request->input('filters',[]);
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'row_id' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];

        $query = App\Model\ModelScoreTemplate::query()->with(['rpschedule.ccschstatusmap']);
        if($User_Type != 'Full_Access') {
            $query->where(function ($qry) use($uid){
                $qry->whereHas('rpshare',function ($subqry) use($uid){
                    $subqry->where('Shared_With_User_id',$uid);
                });
                $qry->orWhere(function ($subqry) use ($uid) {
                    $subqry->where('User_ID', $uid)->orWhere('is_public', 'Y');
                });
            });

        }
        $query->whereHas('rpschedule.ccschstatusmap',function ($qry){
            $qry->where('status','Completed');
        });
        //$query->orderBy($sort_column, $sort_dir);

        $records = $query->orderByDesc('tag')->orderByDesc('row_id')->get();
        return Datatables::of($records)
            ->addColumn('Tag',function ($data){
                $is_tag = $data->tag == 1 ? 'checked' : '';
                return '<label class="custom-control custom-checkbox m-b-0">
            <input type="checkbox" class="custom-control-input checkbox" onclick="tagcampaign($(this),'.$data->row_id.',\'tag\');" '.$is_tag.' value="1">
            <span class="custom-control-label"></span>
        </label>';
            }, 0)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags(trim($category));
                    if (strlen($category) > 50){
                        $categoryCut = substr($category, 0, 50);
                        $endPoint = strrpos($categoryCut, ' ');

                        //if the string doesn't contain any space then it will cut without word basis.
                        $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);

                        return '<span class="teaser">'. $string .'</span>
                        <span class="complete">'. $category .'</span>
                        <span class="more font-14" onclick="readmore($(this))">+</span>';
                    }else{
                        return $category;
                    }
                }
                return $category;
            })
            ->addColumn('StartTime',function ($data){
                $start_time = !empty($data->rpcompleted->start_time) ? $data->rpcompleted->start_time : date('Y-m-d h:i');
                $completed_time = !empty($data->rpcompleted->completed_time) ? $data->rpcompleted->completed_time : date('Y-m-d h:i');
                $date1 = new DateTime($start_time);
                $date2 = new DateTime($completed_time);
                $interval = $date1->diff($date2);

                $dDatePart = explode(" ", $start_time);
                $tTimePart = explode(":", $dDatePart[1]);

                return $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
            })
            ->addColumn('RunTime',function ($data){
                $start_time = !empty($data->rpcompleted->start_time) ? $data->rpcompleted->start_time : date('Y-m-d h:i');
                $completed_time = !empty($data->rpcompleted->completed_time) ? $data->rpcompleted->completed_time : date('Y-m-d h:i');
                $date1 = new DateTime($start_time);
                $date2 = new DateTime($completed_time);
                $interval = $date1->diff($date2);

                $cCompleteTime = '';
                if ($interval->h != 0) {
                    $cCompleteTime .= $interval->h . ':';
                }
                if ($interval->i != 0) {
                    $cCompleteTime .= $interval->h . ':';
                }

                return $cCompleteTime . $interval->s;
            })
            ->addColumn('FTP',function ($data){
                $ftp = !empty($data->rpcompleted->ftp_flag) ? $data->rpcompleted->ftp_flag : 'N';
                return $ftp;
            })
            ->addColumn('is_share',function ($data){
                $is_share = isset($data->rpshare) && !empty($data->rpshare->Shared_With_User_id) && $data->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N';
                return $is_share;
            })
            ->addColumn('total_records',function ($data){
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $total_records = isset($rpschstatusmap[0]['total_records']) ? number_format($rpschstatusmap[0]['total_records']) : 0;
                return $total_records;
            })
            ->addColumn('listXLSX',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $ListXLSX = $data->promoexpo_folder . '\\' . $this->prefix . 'CAL_' . $rpschstatusmap[0]['file_name'] . '.'.$data->promoexpo_ext;
                $list1 = '';
                if (!empty($ListXLSX) && file_exists(public_path($ListXLSX))){
                    $list1 .='<a class="btn no-border font-16 p-0" download href = "'.$ListXLSX.'" title = "Download" id = "DownloadBtn" >
                    <i class="fas fa-file-excel" style = "color: #06b489;" ></i >
                </a >';
                }
                return $list1;
            })
            ->addColumn('listPDF',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $ListPDF = $data->promoexpo_folder.'\\' . $this->prefix . 'CAL_'.$rpschstatusmap[0]['file_name'].'.pdf';
                $list1 = '';
                if (!empty($ListPDF) && file_exists(public_path($ListPDF))){
                    $list1 .='<a class="btn no-border font-16 p-0" download href = "'.$ListPDF.'" title = "Download" id = "DownloadBtn" > <i class="fas fa-file-pdf" style="color: #e92639;" ></i ></a >
                <div class="checkbox">
                    <input id="'.$ListPDF.'" type="checkbox" class="po_status" value="'.$data->row_id.'" onchange="mPdfChecked($(this),\'list\');"/>
                    <label for="'.$ListPDF.'" style="margin-bottom: 16px;"></label>

                    <div class="space"></div>
                </div>';
                }
                return $list1;
            })
            ->addColumn('ver',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $ver = '';
                if(isset($rpschstatusmap) && count($rpschstatusmap) > 1){
                    $ver .='<a href="javascript:void(0);" onclick="showOldReport(\''.$data->row_id.'\')">
                <i class="fas fa-align-justify"></i>
            </a>';
                }
                return $ver;
            })
            ->addColumn('SummaryXLSX',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $SummaryXLSX = $data->promoexpo_folder.'\\'.$this->prefix.'CAM_'.$rpschstatusmap[0]['file_name'].'.xlsx';
                $rpt = '';
                if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX))){
                    $rpt .= '<a class="btn no-border font-16 p-0" download href="'.$SummaryXLSX.'" download title="Download" id="DownloadBtn"><i class="fas fa-file-excel" style="color: #06b489;"></i></a>';
                }
                return $rpt;
            })->addColumn('SummaryPDF',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->ccschstatusmap) ? $data->rpschedule->ccschstatusmap : [];
                $SummaryPDF = $data->promoexpo_folder.'\\'.$this->prefix.'CAM_'.$rpschstatusmap[0]['file_name'].'.pdf';
                $rpt = '';
                if(!empty($SummaryPDF) && file_exists(public_path($SummaryPDF))){
                    $rpt .= '<a class="btn no-border font-16 p-0" download href="'.$SummaryPDF.'" download title="Download" id="DownloadBtn"><i class="fas fa-file-pdf" style="color: #e92639;"></i></a>&nbsp;';

                    $rpt .= '<div class="checkbox">
                <input id="'.$SummaryPDF.'" type="checkbox" class="po_status" value="'.$data->row_id.'" onchange="mPdfChecked($(this),\'rpt\');"/>
                <label for="'.$SummaryPDF.'" style="margin-bottom: 16px;"></label>

                <div class="space"></div>
            </div>';
                }
                return $rpt;
            })
            ->addColumn('run',function ($data) {
                $data->rpmeta->Category = isset($data->rpmeta->Category) ? str_replace(' ','|~|', $data->rpmeta->Category) : '';
                $em_report_json = json_encode(['row_id' => $data->row_id,'t_id' => $data->t_id,'list_level' => $data->list_level,'Scored_File_Name' => $data->Scored_File_Name,'t_name' => $data->t_name,'sql' => base64_encode($data->sql),'selected_fields' => $data->selected_fields,'rpmeta' => $data->rpmeta,'Report_Row' => $data->Report_Row,'Report_Column' => $data->Report_Column,'Report_Function' => $data->Report_Function,'Report_Sum' => $data->Report_Sum,'Report_Show' => $data->Report_Show,'Chart_Type' => trim($data->Chart_Type),'Axis_Scale' => $data->Axis_Scale,'Label_Value' => $data->Label_Value]);
                $run = '<div class="checkbox">
                    <input id="'.$data->t_id .'" type="checkbox" class="em_report" onclick="emreport()" value='.$em_report_json.'>
                    <label for="'.$data->t_id .'"></label>
        
                    <div class="space"></div>
                </div>';
                return $run;
            })
            ->addColumn('action',function ($data) {
                $action = '<select  onchange=\'show_Create_library($(this))\' class=\'form-control-sm\' style="border-color: #bfe6f6;text-align-last: center;">
                    <option value=\'0\'>Select</option>
                    <option value=\'view,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'new,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Save As</option>
                    <option value=\'run,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Run Report</option>
                    <option value=\'replica,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Run List</option>
                    <option value=\'schedule,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Schedule</option>
                    <option value=\'email,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Email</option>
                    <option value=\'share,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Share</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->Scored_File_Name.'",'.$data->t_id.'\'>Delete</option>            
                </select>';
                return $action;
            })
            ->rawColumns(['Tag','Description','listXLSX','listPDF','ver','SummaryXLSX','SummaryPDF','run','action'])
            ->make(true);
    }

    public function getESummaryTabData(Request $request)
    {
        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        $filters = $request->input('filters','');
        if(!empty($filters)){
            $filters = json_decode($filters,true);
        }
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'Campaign_ID' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];
        $sort = "Order By ".$sort_column." ".$sort_dir;

        /*$sSQL = "select [Campaign_ID],
                  [SegmentID] as [Sub_Campaign_ID],[Description],[Universe],[Objective],[Brand],[Channel],[Offer_Type],
                  [All_Incr_Profit] as [Camp_Tot_Profit],
                  [All_Incr_ROI] as [Camp_Tot_ROI],
                  [All_Incr_Resp_Rate] as [Camp_Tot_Resp_Rate],
                  [Cat1_Incr_Profit] as [Camp_Cat_Profit],
                  [Cat1_Incr_ROI] as [Camp_Cat_ROI],
                  [Cat1_Incr_Resp_Rate] as [Camp_Cat_Resp_Rate],
                  [open_rate] as [Open_Rate],
                  [click_rate] as [Click_Rate],
                  [Coupon_Redemption],[Coupon_Redeemers],[Promoted_Redeemers],[New_Redeemers],[Pass_Along_Redeemers],[Final],[Offer_Category],[Wave],[List],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code],[Condition] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UM_Model_Detail) _myResults ";*/
        $where = Helper::ApplyFiltersConditionForEvalSum_Detail($filters,'Esummary');
        $sSQL = "select 
                        [Campaign_ID],
                        [Description],
                        [Universe],
                        [All_Incr_Profit],
                        [All_Incr_ROI],
                        [All_Incr_Resp_Rate],
                        [Cat1_Incr_Profit],
                        [Cat1_Incr_ROI],
                        [Cat1_Incr_Resp_Rate],
                        [Redemption_Rate],
                        [All_Redeemers],
                        [open_rate],
                        [click_rate],
                        [Pgm_Redeemers],
                       [Objective],
                       [Brand],
                       [Channel],
                       [Offer_Category] 
                       from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Summary) _myResults ".$where['Where'];
        //$records = DB::select($sSQL)

        $result = self::implement_query('Esummary',$filters);
        //dd($sSQL);
        return Datatables::of($result['records'])
            //->rawColumns(['Description','listXLSX','listPDF','ver','SummaryXLSX','SummaryPDF','run','action'])
            ->make(true);
    }

    public function getEDetailsTabData(Request $request)
    {
        $uid = Auth::user()->User_ID;
        $User_Type = Auth::user()->authenticate->User_Type;
        $filters = $request->input('filters','');
        if(!empty($filters)){
            $filters = json_decode($filters,true);
        }
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'Campaign_ID' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];
        $sort = "Order By ".$sort_column." ".$sort_dir;

        /*$sSQL = "select [Campaign_ID],
                  [SegmentID] as [Sub_Campaign_ID],[Description],[Universe],[Objective],[Brand],[Channel],[Offer_Type],
                  [All_Incr_Profit] as [Camp_Tot_Profit],
                  [All_Incr_ROI] as [Camp_Tot_ROI],
                  [All_Incr_Resp_Rate] as [Camp_Tot_Resp_Rate],
                  [Cat1_Incr_Profit] as [Camp_Cat_Profit],
                  [Cat1_Incr_ROI] as [Camp_Cat_ROI],
                  [Cat1_Incr_Resp_Rate] as [Camp_Cat_Resp_Rate],
                  [open_rate] as [Open_Rate],
                  [click_rate] as [Click_Rate],
                  [Coupon_Redemption],[Coupon_Redeemers],[Promoted_Redeemers],[New_Redeemers],[Pass_Along_Redeemers],[Final],[Offer_Category],[Wave],[List],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code],[Condition] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UM_Model_Detail) _myResults ";*/
        $where = Helper::ApplyFiltersConditionForEvalSum_Detail($filters,'Edetail');
        $sSQL = "select [Campaign_ID],
                  [SegmentID],
                  [Description],[Universe],[Objective],[Brand],[Channel],[Offer_Type],
                  [All_Incr_Profit],
                  [All_Incr_ROI],
                  [All_Incr_Resp_Rate],
                  [Cat1_Incr_Profit],
                  [Cat1_Incr_ROI],
                  [Cat1_Incr_Resp_Rate],
                  [open_rate],
                  [click_rate],
                  [Coupon_Redemption],[Coupon_Redeemers],[Promoted_Redeemers],[New_Redeemers],[Pass_Along_Redeemers],[Final],[Offer_Category],[Wave],[List],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code],[Condition] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Detail) _myResults ".$where['Where'];
        //$query->orderBy($sort_column, $sort_dir);

        //dd($sSQL);
        return Datatables::of(DB::select($sSQL))
            //->rawColumns(['Description','listXLSX','listPDF','ver','SummaryXLSX','SummaryPDF','run','action'])
            ->make(true);
    }

	public function reSchedule(){
        return view('model.tabs.create.outer-schedule');
    }

    public function getList(Request $request, Ajax $ajax){
        $cSQL = DB::select("SELECT t_name From UM_ModelScore_Templates");
        $aData= collect($cSQL)->map(function($x){ return (array) $x; })->toArray();
        return $ajax->success()
            ->appendParam('list',$aData)
            ->response();
    }

    public function getSeq(Request $request, Ajax $ajax){
        $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UM_ModelScore_Sequence]');
        $aData= collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();
        $cid = 0;
        if(!empty($aData)){
            $cid = $aData[0]['cid'];

            $arSQL = DB::select('SELECT count([t_id]) as cnt FROM [UM_ModelScore_Templates] WHERE t_id = '.$cid);
            $arData= collect($arSQL)->map(function($x){ return (array) $x; })->toArray();
            if($arData[0]['cnt'] == 0){
                return $ajax->success()
                    ->appendParam('cid',$cid)
                    ->response();
            }
        }
        $cid = $cid + 1;
        DB::update("UPDATE [UM_ModelScore_Sequence] SET [camp_id] = " .$cid);
        return $ajax->success()
            ->appendParam('cid',$cid)
            ->response();
    }

    public function moSchData(Request $request, Ajax $ajax){
        define('UPLOAD_DIR', public_path().'\\'.'Chart_Images\\');
        $pgaction = $request->input('pgaction');
        if ($pgaction == 'Sch_campaign1') {

            $filterVal = $request->input('filterVal');
            $Lookup_Type = $request->input('Lookup_Type');
            //$Lookup_Type = $request->input('Lookup_Type','N') ;
            $customerExclusionVal = $request->input('customerExclusionVal','');
            $customerInclusionVal = $request->input('customerInclusionVal','');
            $params = json_decode($request->input('params'));
            $uid = Auth::user()->User_ID;
            $CID = $params->CID;
            $uid = Auth::user()->User_ID;
            $LSD = $params->LSD;
            $DFS = $params->DFS;
            $noLS = $params->noLS;
            $lssm = $params->lssm;
            $lssc = $params->lssc;
            $segFilterCriteria = $params->segFilterCriteria;
            $segFilterCondition = $params->segFilterCondition;
            $noCG = $params->noCG;
            $cg = $params->cg;
            $CGD = $params->CGD;
            $proporation = $params->proporation;
            $sel_criteria = $params->sel_criteria;
            $cellSample = $params->cellSample;
            $saveCD = $params->saveCD;
            $CGOpt = $params->CGOpt;
            $eData = $params->eData;

            $ftp_flag = $request->input('ftp_flag');
            $ftpData = $request->input('ftpData');
            $SFTP_Attachment = $request->input('SFTP_Attachment','');
            $SR_Attachment = $request->input('SR_Attachment','');
            $SREmailStr = $request->input('SREmailStr','');
            $ShareStr = $request->input('ShareStr','');
            $rtype = $request->input('rtype','');
            $saveFile = $params->saveFile;
            $SMTPStr = $request->input('SMTPStr','');


            $modelbuildid = $params->modelbuildid;
            $scored_file_name = $params->scored_file_name;
            $scored_file_description = $params->scored_file_description;
            $scored_file_universe = $params->scored_file_universe;
            $scored_file_cuttoffdate = $params->scored_file_cuttoffdate;
            $CName = $params->CName;
            $t_name = $params->CName;
            //$listShortName = $params->listShortName;
            $list_level = $params->list_level;
            $list_fields = $params->list_fields;
            $selected_fields = $params->selected_fields;
            $report_fields = json_encode($params->report_fields);
            $custom_sql = $params->custom_sql;
            $list_format = '';
            $report_orientation = $params->report_orientation;

            $sSQL = ucwords($params->sSQL);
            if ($custom_sql == 'Y' && strpos($sSQL, "DS_MKC_ContactID") === false) {
                $sSQL = substr($sSQL, 0, 6) . " DS_MKC_ContactID, " . substr($sSQL, 7, strlen($sSQL));
            }
            $rStr = ''; //$params->rStr; (because no need to save report review fields)
            $is_public = $params->is_public;
            $eFolder = $params->eFolder;
            $eFile = $params->eFile;
            $eExt = $params->eExt;

            $cI = '';
            $imgTag = '';
            $imgPath = '';

            if(!empty($params->cI)){

                $img = $params->cI;
                if (strpos($img, 'data:image/png;base64,') !== false) {
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $cI = UPLOAD_DIR . uniqid() . '.png';
                    $success = file_put_contents($cI, $data);
                    $imgPath = $cI;
                }else{
                    $cI = $params->cI;
                    $imgPath = $cI;
                }

                $imgTag = '<img src="' .$cI. '" class="img-responsive">';
            }

            $filter_condition = !empty($params->filter_condition) ? $params->filter_condition : ''; //str_replace("'", "''", $params->filter_condition);
            $Customer_Exclusion_Condition = !empty($params->Customer_Exclusion_Condition) ? $params->Customer_Exclusion_Condition : ''; //str_replace("'", "''", $params->Customer_Exclusion_Condition);
            $Customer_Inclusion_Condition = !empty($params->Customer_Inclusion_Condition) ? $params->Customer_Inclusion_Condition : ''; //str_replace("'", "''", $params->Customer_Inclusion_Condition);

            $Create_Date = date('Y-m-d h:i:s');

            //$LSD = str_replace("'", "''", $LSD);
            //$sSQL = str_replace("'", "''", $sSQL);
            $rv = $cv = $sv = $fu = $sa = $ct = $as = $lv = '';
            if (!empty($rStr)) {
                $rstr = explode('^', $rStr);
                $rv = $rstr[0];
                $cv = $rstr[1];
                $fu = $rstr[2];
                $sv = $rstr[3];
                $sa = $rstr[4];
                $ct = trim($rstr[5]);
                $as = $rstr[6];
                $lv = $rstr[7];
            }

            $filterVal = !empty($filterVal) ? str_replace("'", "@", $filterVal) : '';
            $customerExclusionVal = !empty($customerExclusionVal) ? str_replace("'", "@", $customerExclusionVal) : '';
            $customerInclusionVal = !empty($customerInclusionVal) ? str_replace("'", "@", $customerInclusionVal) : '';
            $date1 = date("m/d/y  H:i:s", time());

            $campaign = new App\Model\ModelScoreTemplate();
            $campaign->t_id                         = $CID;
            $campaign->ModelBuildID                 = $modelbuildid;
            $campaign->User_ID                      = $uid;
            $campaign->t_name                       = $CName;
            $campaign->t_type                       = 'M';
            $campaign->Scored_File_Name             = $scored_file_name;
            $campaign->list_level                   = $list_level;
            $campaign->list_fields                  = $list_fields;
            $campaign->filter_criteria              = $filterVal;
            $campaign->Customer_Exclusion_Criteria  = $customerExclusionVal;
            $campaign->Customer_Inclusion_Criteria  = $customerInclusionVal;
            $campaign->filter_condition             = $filter_condition;
            $campaign->Customer_Exclusion_Condition = $Customer_Exclusion_Condition;
            $campaign->Customer_Inclusion_Condition = $Customer_Inclusion_Condition;
            $campaign->selected_fields              = $selected_fields;
            $campaign->Report_Fields                = $report_fields;
            $campaign->sql                          = $sSQL;
            $campaign->seg_def                      = $DFS;
            $campaign->seg_noLS                     = $noLS;
            $campaign->seg_method                   = $lssm;
            $campaign->seg_criteria                 = $lssc;
            $campaign->seg_selected_criteria        = $LSD;
            $campaign->seg_grp_no                   = $noCG;
            $campaign->seg_ctrl_grp_opt             = $cg;
            $campaign->seg_camp_grp_dtls            = $CGD;
            $campaign->seg_camp_grp_proportion      = $proporation;
            $campaign->seg_camp_grp_sel_cri         = $sel_criteria;
            $campaign->seg_sample                   = $cellSample;
            $campaign->seg_filters_criteria         = $segFilterCriteria;
            $campaign->seg_filter_condition         = $segFilterCondition;
            $campaign->promoexpo_cd_opt             = $saveCD;
            $campaign->promoexpo_file_opt           = $saveFile;
            $campaign->promoexpo_folder             = $eFolder;
            $campaign->promoexpo_file               = $eFile;
            $campaign->promoexpo_ext                = $eExt;
            $campaign->promoexpo_ecg_opt            = $CGOpt;
            $campaign->promoexpo_data               = $eData;
            $campaign->Report_Row                   = $rv;
            $campaign->Report_Column                = $cv;
            $campaign->Report_Function              = $fu;
            $campaign->Report_Sum                   = $sv;
            $campaign->Report_Show                  = $sa;
            $campaign->is_public                    = $is_public;
            $campaign->Custom_SQL                   = $custom_sql;
            $campaign->Chart_Type                   = $ct;
            $campaign->Chart_Image                  = $cI;
            $campaign->Axis_Scale                   = $as;
            $campaign->Label_Value                  = $lv;
            $campaign->SR_Attachment                = $SR_Attachment;
            $campaign->List_Format                  = $list_format;
            $campaign->Report_Orientation           = $report_orientation;
            $campaign->Lookup_Type           = $Lookup_Type;
            $campaign->save();


            $rpDesc = $scored_file_description;

            $modelScoreMetadata = new App\Model\ModelScoreMetadata();
            $modelScoreMetadata->ModelScore_Name = $scored_file_name;
            $modelScoreMetadata->ModelScore_Des = $scored_file_description;
            $modelScoreMetadata->ModelScore_Universe =  $scored_file_universe;
            $modelScoreMetadata->ModelScore_Cutoff_Date = $scored_file_cuttoffdate;
            $modelScoreMetadata->ModelID =  $modelbuildid;
            $modelScoreMetadata->save();
            /*$metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $params->CampaignID;
            $metadata->Type        = 'M';
            $metadata->Objective   = $params->Objective;
            $metadata->Brand       = $params->Brand;
            $metadata->Channel     = $params->Channel;
            $metadata->Category    = $params->Category;
            $metadata->ListDes     = $params->ListDes;
            $metadata->Wave        = $params->Wave;
            $metadata->Start_Date  = $params->Start_Date;
            $metadata->Interval    = $params->Interval;
            $metadata->ProductCat1 = $params->ProductCat1;
            $metadata->ProductCat2 = $params->ProductCat2;
            $metadata->SKU         = $params->SKU;
            $metadata->Coupon      = $params->Coupon;
            $metadata->Sort_Column = $params->Sort_Column;
            $metadata->Sort_Order  = $params->Sort_Order;
            $metadata->save();*/

            $CName = $t_name;
            $sName = "S_" . $t_name;

            if ($ftp_flag == 'Y') {
                $pos = strpos($ftpData, ":");

                if ($pos != false) {
                    $ftpArray = explode(":", $ftpData);
                    DB::insert("INSERT INTO [UL_RepCmp_SFTP]([ftp_temp_name],[ftp_site_name],[ftp_host_address],[ftp_port_no]
                       ,[ftp_user_name],[ftp_password],[folder_loc],[site_type]) VALUES
                        ('$ftpArray[0]','$ftpArray[1]','$ftpArray[2]','$ftpArray[3]','$ftpArray[4]','$ftpArray[5]','$ftpArray[6]','$ftpArray[7]')");

                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_temp_name = '$ftpArray[0]'");
                    $aData= collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $ftpData = $aData[0]['row_id'];

                    }
                }
            } else
                $ftpData = 0;
            // FTP Details

            $file_name = $scored_file_name . "_" . date("Ymd", time());
            if ($saveFile == 'Y')
                $dbfilename = $file_name;
            else
                $dbfilename = '-';

            if (($rtype == 'RA') || ($rtype == 'RP') || ($rtype = 'RI')) {

                $SQL = DB::select("Select [row_id],[sql] from [UM_ModelScore_Templates] Where t_name = '$CName'  AND User_ID = '$uid' AND t_type='M'");
                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Camp_temp_id = $aData[0]['row_id'];
                    $sSQL = $aData[0]['sql'];
                }



                // Common to Both

                if ($rtype == 'RA') {
                    $RA_Dt = $request->input('RA_Dt');
                    $RA_time = $request->input('RA_time');

                    //Insert into UL_RepCmp_Schedules table

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name]
                                     ,[Schedule_type],[runat_date],[runat_time],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftpData','$Camp_temp_id','M','$SFTP_Attachment')");


                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'M'");

                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];

                        $RA_Dt = $aData[0]['runat_date'];
                        $RA_time = $aData[0]['runat_time'];

                        $tmp = explode('-', $RA_Dt);
                        $RA_Dt = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        $RA_Dt_SQL = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];
                        //$tmp = explode(':', $RA_time);
                        //$RA_time = $tmp[0] . ':' . $tmp[1] . ':00';
                        $date1 = date("m/d/y  H:i:s", time());
                    }

                    // Schedule the task
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';

                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','$date1','$RA_Dt $RA_time','','$dbfilename','','$ftp_flag','Scheduled','$eFolder','','M')");

                    //Insert into UL_RepCmp_Status table


                }
                else if ($rtype == 'RP') {
                    $rp_run_sch = $request->input('rp_run_sch');
                    $RP_Start_Dt = $request->input('RP_Start_Dt');
                    $RP_end_Dt = $request->input('RP_end_Dt');
                    $RA_time = $request->input('RA_time');
                    $monthStr = $request->input('monthStr');
                    $dayStr = $request->input('dayStr');
                    $mo = $request->input('mo');

                    if ($rp_run_sch == 'monthly')
                        $r_Str = $monthStr;
                    else if ($rp_run_sch == 'weekly')
                        $r_Str = $dayStr;
                    else
                        $r_Str = '';


                   /* $metaArray = explode('^', $metaStr);
                    $metadata_date = $metaArray[7] . '/' . $metaArray[8] . '/' . $metaArray[6];*/
                    //$metadata_date = $metadata->Start_Date;
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftpData','$Camp_temp_id',1,'$mo','$r_Str','','M','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'M'");


                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];

                        $rp_start_date = $aData[0]['rp_start_date'];

                        $tmp = explode('-', $rp_start_date);
                        $rp_start_date = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        //$rp_start_date_SQL = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

                        //$tmp = explode(':', $RA_time);
                        //$RA_time = $tmp[0] . ':' . $tmp[1] . ':00';
                        $date1 = date("m/d/y  H:i:s", time());

                        $rp_end_date = $aData[0]['rp_end_date'];
                        $tmp = explode('-', $rp_end_date);
                        $rp_end_date = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        $rp_end_date_cm = date('m/d/Y', strtotime($rp_end_date . ' +1 day'));
                        $rp_run_time = $aData[0]['rp_run_time'];
                        $tmp = explode(':', $rp_run_time);
                        $rp_run_time = $tmp[0] . ':' . $tmp[1];


                    }

                    $fh = fopen( $this->filePath.'ccschedule.bat', 'w' );
                    fclose($fh);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'a');
                    $command = "php artisan moSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'a');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;

                    }

                    Helper::schtask_curl($command);

                    switch ($rp_run_sch) {
                        case 'daily':
                            $curr_date = date("m/d/Y");
                            $curr_time = date("H:i:s");
                            if ($rp_start_date > $curr_date)
                                $next_runDate = $rp_start_date;
                            else if ($rp_start_date == $curr_date) {
                                if ($curr_time < $rp_run_time)
                                    $next_runDate = $rp_start_date;
                                else {
                                    $next_run = Helper::add_date($rp_start_date, 1);
                                    $next_runDate = date("Y-m-d", strtotime($next_run));
                                }

                            }
                            break;
                        case 'weekly':
                            $day_flag = 0;
                            $temp_date = $rp_start_date;
                            $temp_day = date("D", strtotime($temp_date));
                            $dayArray = explode(",", $dayStr);

                            while ($day_flag == 0) {
                                if (in_array(strtoupper($temp_day), $dayArray))
                                    $day_flag = 1;
                                else {
                                    $temp_date = Helper::add_date($temp_date, 1);
                                    $temp_day = date("D", strtotime($temp_date));
                                }
                            }

                            $next_runDate = date("Y-m-d", strtotime($temp_date));

                            break;
                        case 'monthly':
                            $curr_time = date("H:i:s");
                            $month_flag = 0;
                            $temp_date_Array = explode("/", $rp_start_date);

                            if ($dayStr != 'last')
                                $temp_date = $temp_date_Array[0] . "/" . $dayStr . "/" . $temp_date_Array[2];
                            else {

                                $dayStr = date('t', strtotime($rp_start_date));//fine out last date of that month
                                $temp_date = $temp_date_Array[0] . "/" . $dayStr . "/" . $temp_date_Array[2];
                            }
                            $temp_month = date("M", strtotime($temp_date));
                            $monthArray = explode(",", $monthStr);
                            if (date("m/d/Y") < date("m/d/Y", strtotime($temp_date))) {
                                $month_flag = 1;
                            } else if (date("m/d/Y") == date("m/d/Y", strtotime($temp_date))) {
                                if (date("H:i:s") < strtotime($rp_run_time))
                                    $month_flag = 1;

                            } else {
                                $month_flag = 0;
                                $temp_date = Helper::add_date($temp_date, 0, 1);
                            }

                            while ($month_flag == 0) {
                                if (in_array(strtoupper($temp_month), $monthArray))
                                    $month_flag = 1;
                                else {
                                    $temp_date = Helper::add_date($temp_date, 0, 1);
                                    $temp_month = date("M", strtotime($temp_date));

                                }
                            }

                            $next_runDate = date("Y-m-d", strtotime($temp_date));
                            break;
                    } // Switch statement
                    $next_runTime = $rp_run_time;
                    if ($saveFile == 'Y')
                        $dbfilename = $eFile . '.' . $eExt;
                    else
                        $dbfilename = '-';

                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                               ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[run_until],[t_type])
                               VALUES ('$sName','$CName','$date1','$next_runDate $rp_run_time','','$dbfilename','','$ftp_flag','Scheduled','$eFolder','$rp_end_date $rp_run_time','M')");
                    //Insert into UL_RepCmp_Status table
                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftpData','$Camp_temp_id','M','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'M'");

                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task

                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$dbfilename','','Running','$eFolder','$ftp_flag','M')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'M'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'M'");
                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'M')");
                //Status update to Scheduled


            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {

                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];

                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'M'");
            }



            //Send Report via Email Details
            $SREmailArray = explode(":", $SREmailStr);
            if ($SREmailArray[0] == 'Y') {

                $Email_Flag = $SREmailArray[0];

                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $Email_Flag . "','" . $CID . "','" . $SREmailArray[1] . "','" . $SREmailArray[2] . "','" . $SREmailArray[3] . "','" . $SREmailArray[4] . "','" . $SREmailArray[5] . "','M','pending','" . $SREmailArray[6]. "')");
            }

            //Share Report
            $ShareArray = explode(":", $ShareStr);
            if ($ShareArray[0] == 'Y') {

                $Share_Flag = $ShareArray[0];
                $users = !empty($ShareArray[1]) ? explode(',',$ShareArray[1]) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareArray[2];
                Helper::shareReport($CID,'M',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }
            if(!empty($rv)) {
                if (strpos($rv, ',') !== false) {
                    $nrv = explode(',', $rv);
                } else {
                    $nrv[] = $rv;
                }
                echo 'here';
                Helper::generateSrPDF($nrv, $cv, ucfirst($fu), $sv, $sa, $sSQL, $list_level, $scored_file_name, $imgTag, $imgPath, $eFolder, $file_name, $this->prefix . 'MOR_', $SR_Attachment, $rpDesc, $report_orientation);
            }
        }
        else if ($pgaction == 'ReSch_campaign') {
            $CID = $request->input('CID');
            $ftp_flag = $request->input('ftp_flag');
            $ftpData = $request->input('ftpData');
            $SFTP_Attachment = $request->input('SFTP_Attachment');
            $SR_Attachment = $request->input('SR_Attachment');
            $SREmailStr = $request->input('SREmailStr');
            $ShareStr = $request->input('ShareStr');
            $rtype = $request->input('rtype');
            $SMTPStr = $request->input('SMTPStr');

            $dDataI = App\Model\ModelScoreTemplate::with(['modelscoremetadata'])->where('t_id',$CID)->first()->toArray();

            //$sSqlCheck = DB::select("SELECT * FROM [UM_ModelScore_Templates] WHERE t_id = '$CID' AND t_type='M'");
            //$dDataI= collect($sSqlCheck)->map(function($x){ return (array) $x; })->toArray();
            if (!$dDataI) {
                return $ajax->fail()
                    ->message('ModelScore doesn\'t exist')
                    ->jscallback()
                    ->response();
            }

            $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UM_ModelScore_Sequence]');
            $aData = collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();

            if(!empty($aData)){
                $campaign_id = $aData[0]['cid'];

            }
            $campaign_id = $campaign_id + 1;
            DB::update("UPDATE [UM_ModelScore_Sequence] SET [camp_id] = " .$campaign_id);

            //$dDataI = $dDataI[0];
            $row_id = $dDataI['row_id'];
            $t_name = $dDataI['Scored_File_Name'] . '_' . date('Ymd_Hi');

            $saveCD = $dDataI['promoexpo_cd_opt'];
            $saveFile = $dDataI['promoexpo_file_opt'];
            $eFolder = $dDataI['promoexpo_folder'];
            $eFile = $t_name;
            $eExt = $dDataI['promoexpo_ext'];
            $CGOpt = $dDataI['promoexpo_ecg_opt'];
            $eData = $dDataI['promoexpo_data'];
            $list_format = $dDataI['List_Format'];
            $report_orientation = $dDataI['Report_Orientation'];
            //$meta_description = $request->input('meta_description');
            /*$metaStr = $dDataI['meta_data'];
            $upMetaStr = explode('^',$metaStr);
            $upMetaStr[6] = date('Y');
            $upMetaStr[7] = date('m');
            $upMetaStr[8] = date('d');
            $rpDesc = $upMetaStr[3];
            $metaStr = implode('^',$upMetaStr);*/
            $Create_Date = date('Y-m-d h:i:s');
            $uid = Auth::user()->User_ID;

            $cI = '';
            $imgTag = '';
            $imgPath = '';
            $params = $request->input('params') ? json_decode($request->input('params')) : '';
            /*if(!empty($params->cI)){

                $img = $params->cI;
                if (strpos($img, 'data:image/png;base64,') !== false) {
                    $img = str_replace('data:image/png;base64,', '', $img);
                    $img = str_replace(' ', '+', $img);
                    $data = base64_decode($img);
                    $cI = UPLOAD_DIR . uniqid() . '.png';
                    file_put_contents($cI, $data);
                    $imgPath = $cI;
                }

                $imgTag = '<img src="' .$cI. '" class="img-responsive">';
            }*/

            DB::insert("INSERT INTO [UM_ModelScore_Templates] ([t_id],[ModelBuildID],[User_ID],[DCampaignID], [t_name],[t_type],[Scored_File_Name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[Report_Fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation] )  
		   SELECT $campaign_id as [t_id],[ModelBuildID],'$uid' as [User_ID],[DCampaignID], 
		  '$t_name' as [t_name],'M' as [t_type],[Scored_File_Name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[Report_Fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],'$imgPath' as [Chart_Image],[Axis_Scale],[Label_Value], [SR_Attachment],[List_Format],[Report_Orientation] FROM [UM_ModelScore_Templates] 
		  WHERE  t_id = '$CID' AND t_type='M'");

            $modelScoreMetadata = new App\Model\ModelScoreMetadata();
            $modelScoreMetadata->ModelScore_Name = $dDataI['Scored_File_Name'];
            $modelScoreMetadata->ModelScore_Des = $dDataI['modelscoremetadata']['ModelScore_Des'];
            $modelScoreMetadata->ModelScore_Universe =  $dDataI['modelscoremetadata']['ModelScore_Universe'];
            $modelScoreMetadata->ModelScore_Cutoff_Date = $dDataI['modelscoremetadata']['ModelScore_Cutoff_Date'];
            $modelScoreMetadata->ModelID =  $dDataI['ModelBuildID'];
            $modelScoreMetadata->save();


            $rpDesc = isset($dDataI['modelscoremetadata']['ModelScore_Des']) ? $dDataI['modelscoremetadata']['ModelScore_Des'] : '';

            /*$metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $campaign_id;
            $metadata->Type        = 'M';
            $metadata->Objective   = $params->Objective;
            $metadata->Brand       = $params->Brand;
            $metadata->Channel     = $params->Channel;
            $metadata->Category    = $params->Category;
            $metadata->ListDes     = $params->ListDes;
            $metadata->Wave        = $params->Wave;
            $metadata->Start_Date  = $params->Start_Date;
            $metadata->Interval    = $params->Interval;
            $metadata->ProductCat1 = $params->ProductCat1;
            $metadata->ProductCat2 = $params->ProductCat2;
            $metadata->SKU         = $params->SKU;
            $metadata->Coupon      = $params->Coupon;
            $metadata->Sort_Column = $params->Sort_Column;
            $metadata->Sort_Order  = $params->Sort_Order;
            $metadata->save();*/
            $CName = $t_name;
            $sName = "S_" . $t_name;

            if ($ftp_flag == 'Y') {
                $pos = strpos($ftpData, ":");

                if ($pos != false) {
                    $ftpArray = explode(":", $ftpData);
                    DB::insert("INSERT INTO [UL_RepCmp_SFTP]([ftp_temp_name],[ftp_site_name],[ftp_host_address],[ftp_port_no]
                       ,[ftp_user_name],[ftp_password],[folder_loc],[site_type]) VALUES
                        ('$ftpArray[0]','$ftpArray[1]','$ftpArray[2]','$ftpArray[3]','$ftpArray[4]','$ftpArray[5]','$ftpArray[6]','$ftpArray[7]')");

                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_temp_name = '$ftpArray[0]'");
                    $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $ftpData = $aData[0]['row_id'];

                    }
                }
            } else
                $ftpData = 0;
            // FTP Details


            if (($rtype == 'RA') || ($rtype == 'RP') || ($rtype = 'RI')) {

                // Common to Both
                $SQL = DB::select("Select [row_id],[Scored_File_Name],[sql] from [UM_ModelScore_Templates] Where t_name = '$CName' AND t_type='M'");
                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Camp_temp_id = $aData[0]['row_id'];
                    $sSQL = $aData[0]['sql'];
                    $list_short_name = $aData[0]['Scored_File_Name'];
                }

                $file_name = $list_short_name . "_" . date("Ymd", time());
                if ($saveFile == 'Y')
                    $dbfilename = $file_name;
                else
                    $dbfilename = '-';

                // Common to Both

                if ($rtype == 'RA') {
                    $RA_Dt = $request->input('RA_Dt');
                    $RA_time = $request->input('RA_time');
                    //Insert into UL_RepCmp_Schedules table

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name]
                                     ,[Schedule_type],[runat_date],[runat_time],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftpData','$Camp_temp_id','M','$SFTP_Attachment')");
                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'M'");
                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];

                        $RA_Dt = $aData[0]['runat_date'];
                        $RA_time = $aData[0]['runat_time'];

                        $tmp = explode('-', $RA_Dt);
                        $RA_Dt = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        $date1 = date("m/d/y  H:i:s", time());
                        //$RA_Dt_SQL = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];
                        //$tmp = explode(':', $RA_time);
                        //$RA_time = $tmp[0] . ':' . $tmp[1];
                    }

                    // Schedule the task
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';
                    // Schedule the task
                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','','$RA_Dt $RA_time','','$file_name','','$ftp_flag','Scheduled','$eFolder','','M')");
                    //Insert into UL_RepCmp_Status table


                }
                else if ($rtype == 'RP') {
                    $rp_run_sch = $request->input('rp_run_sch');
                    $RP_Start_Dt = $request->input('RP_Start_Dt');
                    $RP_end_Dt = $request->input('RP_end_Dt');
                    $RA_time = $request->input('RA_time');
                    $monthStr = $request->input('monthStr');
                    $dayStr = $request->input('dayStr');
                    $mo = $request->input('mo');

                    if ($rp_run_sch == 'monthly')
                        $r_Str = $monthStr;
                    else if ($rp_run_sch == 'weekly')
                        $r_Str = $dayStr;
                    else
                        $r_Str = '';


                    //$metaArray = explode('^', $metaStr);
                    //$metadata_date = $metadata->Start_Date;

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftpData','$Camp_temp_id',1,'$mo','$r_Str','','M','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'M'");
                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];

                        $rp_start_date = $aData[0]['rp_start_date'];

                        $tmp = explode('-', $rp_start_date);
                        $rp_start_date = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        $rp_start_date_SQL = $tmp[0] . '-' . $tmp[1] . '-' . $tmp[2];

                        $tmp = explode(':', $RA_time);
                        $RA_time = $tmp[0] . ':' . $tmp[1] . ':00';
                        $date1 = date("m/d/y  H:i:s", time());

                        $rp_end_date = $aData[0]['rp_end_date'];
                        $tmp = explode('-', $rp_end_date);
                        $rp_end_date = $tmp[1] . '/' . $tmp[2] . '/' . $tmp[0];
                        $rp_end_date_cm = date('m/d/Y', strtotime($rp_end_date . ' +1 day'));
                        $rp_run_time = $aData[0]['rp_run_time'];
                        //$tmp = explode(':', $rp_run_time);
                        //$rp_run_time = $tmp[0] . ':' . $tmp[1] . ':00';


                    }
                    /*$fh = fopen( $this->filePath.'ccschedule.bat', 'w' );
                    fclose($fh);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'M');
                    $command = "php artisan moSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'M');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);*/
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;

                    }
                    Helper::schtask_curl($command);

                    switch ($rp_run_sch) {
                        case 'daily':
                            $curr_date = date("m/d/Y");
                            $curr_time = date("H:i:s");
                            if ($rp_start_date > $curr_date)
                                $next_runDate = $rp_start_date;
                            else if ($rp_start_date == $curr_date) {
                                if ($curr_time < $rp_run_time)
                                    $next_runDate = $rp_start_date;
                                else {
                                    $next_run = Helper::add_date($rp_start_date, 1);
                                    $next_runDate = date("Y-m-d", strtotime($next_run));
                                }

                            }
                            break;
                        case 'weekly':
                            $day_flag = 0;
                            $temp_date = $rp_start_date;
                            $temp_day = date("D", strtotime($temp_date));
                            $dayArray = explode(",", $dayStr);

                            while ($day_flag == 0) {
                                if (in_array(strtoupper($temp_day), $dayArray))
                                    $day_flag = 1;
                                else {
                                    $temp_date = Helper::add_date($temp_date, 1);
                                    $temp_day = date("D", strtotime($temp_date));
                                }
                            }

                            $next_runDate = date("Y-m-d", strtotime($temp_date));

                            break;
                        case 'monthly':
                            $curr_time = date("H:i:s");
                            $month_flag = 0;
                            $temp_date_Array = explode("/", $rp_start_date);

                            if ($dayStr != 'last')
                                $temp_date = $temp_date_Array[0] . "/" . $dayStr . "/" . $temp_date_Array[2];
                            else {

                                $dayStr = date('t', strtotime($rp_start_date));//fine out last date of that month
                                $temp_date = $temp_date_Array[0] . "/" . $dayStr . "/" . $temp_date_Array[2];
                            }
                            $temp_month = date("M", strtotime($temp_date));
                            $monthArray = explode(",", $monthStr);
                            if (date("m/d/Y") < date("m/d/Y", strtotime($temp_date))) {
                                $month_flag = 1;
                            } else if (date("m/d/Y") == date("m/d/Y", strtotime($temp_date))) {
                                if (date("H:i:s") < strtotime($rp_run_time))
                                    $month_flag = 1;

                            } else {
                                $month_flag = 0;
                                $temp_date = Helper::add_date($temp_date, 0, 1);
                            }

                            while ($month_flag == 0) {
                                if (in_array(strtoupper($temp_month), $monthArray))
                                    $month_flag = 1;
                                else {
                                    $temp_date = Helper::add_date($temp_date, 0, 1);
                                    $temp_month = date("M", strtotime($temp_date));

                                }
                            }

                            $next_runDate = date("Y-m-d", strtotime($temp_date));
                            break;
                    } // Switch statement
                    $next_runTime = $rp_run_time;


                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                               ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[run_until],[t_type])
                               VALUES ('$sName','$CName','','$next_runDate $rp_run_time','','$file_name','','$ftp_flag','Scheduled','$eFolder','$rp_end_date $rp_run_time','M')");
                    //Insert into UL_RepCmp_Status table


                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftpData','$Camp_temp_id','M','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'M'");
                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' moSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$file_name','','Running','$eFolder','$ftp_flag','M')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'M'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'M'");
                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'M')");
                //Status update to Scheduled
            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {
                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];
                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'M'");
            }

            //Send Report via email
            $SREmailArray = explode(":", $SREmailStr);
            if ($SREmailArray[0] == 'Y') {

                $Email_Flag = $SREmailArray[0];

                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $Email_Flag . "','" . $CID . "','" . $SREmailArray[1] . "','" . $SREmailArray[2] . "','" . $SREmailArray[3] . "','" . $SREmailArray[4] . "','" . $SREmailArray[5] . "','M','pending','" . $SREmailArray[6]. "')");
            }

            //Share Report
            $ShareArray = explode(":", $ShareStr);
            if ($ShareArray[0] == 'Y') {

                $Share_Flag = $ShareArray[0];
                $users = !empty($ShareArray[1]) ? explode(',',$ShareArray[1]) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareArray[2];
                Helper::shareReport($CID,'M',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }


            $rv = $dDataI['Report_Row'];
            $cv = $dDataI['Report_Column'];
            $fu = ucfirst($dDataI['Report_Function']);
            $sv = $dDataI['Report_Sum'];
            $sa = $dDataI['Report_Show'];
            $list_level = $dDataI['list_level'];
            $list_short_name = $dDataI['Scored_File_Name'];
            $sSQL = $dDataI['sql'];
            if(!empty($rv)) {
                if (strpos($rv, ',') !== false) {
                    $nrv = explode(',', $rv);
                } else {
                    $nrv[] = $rv;
                }
                //Helper::generateSrPDF($nrv, $cv, $fu, $sv, $sa, $sSQL, $list_level, $list_short_name, $imgTag, $imgPath, $eFolder, $file_name, $this->prefix . 'CAM_', $SR_Attachment, $rpDesc, $report_orientation);
            }
        }
    }

    public function getSingleCampaign(Request $request, Ajax $ajax){
        $tempid = $request->input('tempid');
        $record = App\Model\ModelScoreTemplate::with(['modelscoremetadata'])->where('row_id',$tempid)->first()->toArray();

        $checked_fields = !empty($record['selected_fields']) ? explode(',',$record['selected_fields']) : ['DS_MKC_ContactID'];
        $aData1 = DB::select("SELECT DISTINCT [Type] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $record['list_level'] . "'");
        $aData1 = collect($aData1)->map(function($x){ return (array) $x; })->toArray();
        //$checked_fields = [];
        $hHtml = "";
        $collapsehHtml = "";
        foreach ($aData1 as $tKey => $tTypeInfo) {
            $aData2 = DB::select("SELECT DISTINCT [Field_Display] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $record['list_level'] . "' AND Type = '" . $tTypeInfo['Type'] . "' AND Display_For_Select = 1");
            $aData2 = collect($aData2)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData2)) {
                if($tKey < 6) {
                    $hHtml .= '<div class="col-md-2">
                        <div class="form-group">
                            <select name="' . $tTypeInfo['Type'] . '" class="form-control form-control-sm chosen-select" id="s_' . $tKey . '" multiple="multiple" data-placeholder="Select ' . $tTypeInfo['Type'] . '">';

                    foreach ($aData2 as $key => $fFieldInfo) {
                        if (in_array($fFieldInfo['Field_Display'], $checked_fields)) {
                            $checked = "selected='selected'";
                        } else {
                            $checked = "";
                        }
                        $hHtml .= '<option ' . $checked . ' value="' . $fFieldInfo['Field_Display'] . '">' . $fFieldInfo['Field_Display'] . '</option>';
                    }
                    $hHtml .= "</select>
                        </div>
                    </div>";
                }else{
                    $collapsehHtml .= '<div class="col-md-2">
                        <div class="form-group">
                            <select name="' . $tTypeInfo['Type'] . '" class="form-control form-control-sm chosen-select" id="s_' . $tKey . '" multiple="multiple" data-placeholder="Select ' . $tTypeInfo['Type'] . '">';

                    foreach ($aData2 as $key => $fFieldInfo) {
                        if (in_array($fFieldInfo['Field_Display'], $checked_fields)) {
                            $checked = "selected='selected'";
                        } else {
                            $checked = "";
                        }
                        $collapsehHtml .= '<option ' . $checked . ' value="' . $fFieldInfo['Field_Display'] . '">' . $fFieldInfo['Field_Display'] . '</option>';
                    }
                    $collapsehHtml .= "</select>
                        </div>
                    </div>";
                }
            }
        }

        $aDataDF = DB::select("select Field_Display,Filter_Type from UL_RepCmp_Lookup_Fields where report = 1 and List_Level = '" . $record['list_level'] . "' AND Filter_Type IN('lkp','Lkp','Num')");
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
            ->appendParam('aData',$record)
            ->appendParam('fieldsHtml',$hHtml)
            ->appendParam('fieldscollapsehHtml',$collapsehHtml)
            ->appendParam('lkpOptions',$lkpOptions)
            ->appendParam('numOptions',$numOptions)
            ->response();
    }

    public function showMeta(Request $request, Ajax $ajax){
        $metaHTML = $request->input('metaHTML');


        $sdata = [
            'content' => $metaHTML
        ];

        $title = 'Meta Data';
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

    public function getExecuteData(Request $request,Ajax $ajax){
        $tempid = $request->input('tempid');
        $record = App\Model\ModelScoreTemplate::with(['rpschedule','rpshare','rpemail','rpschedule.sftp'])->where('t_id',$tempid)
            ->first();
        return $ajax->success()
            ->appendParam('aData',$record)
            ->response();
    }

    public function EvaluationDownload(Request $request,Ajax $ajax){
        $level = $request->input('tab');
        $filters = $request->input('filters',[]);
        $downloadableColumns = json_decode($request->input('downloadableColumns',''));
        if($level == 'SingleCamp'){
            $modelscoreid = $request->input('modelscoreid');

            $eEvalSumRecords = DB::select('EXEC sp_CRM_Model_Eval_sum_Single '.$modelscoreid);
            $eEvalDetailRecords = DB::select('EXEC sp_CRM_Model_Eval_detail_Single '.$modelscoreid);

            $eEvalSumHtml = $eEvalDetailHtml= '';
            if($eEvalSumRecords){
                $eEvalSumHtml = Helper::print_datatable($eEvalSumRecords,[
                    'Model Segment' => 'text-align:center;',
                    'Touched' => 'text-align:right;padding-right: 40px;',
                    'Responder' => 'text-align:right;padding-right: 40px;',
                    'Response Rate' => 'text-align:right;padding-right: 40px;'
                ]);
            }
            if($eEvalDetailRecords){
                $eEvalDetailHtml = Helper::print_datatable($eEvalDetailRecords,[
                    'Model Segment' => 'text-align:center;',
                    'Touched' => 'text-align:right;padding-right: 40px;',
                    'Responder' => 'text-align:right;padding-right: 40px;',
                    'Response Rate' => 'text-align:right;padding-right: 40px;'
                ]);
            }

            $existingEntries = $request->input('existingEntries');

            $modelScoreMetadata = App\Model\ModelScoreMetadata::where('ModelScoreID',$modelscoreid)->first();

            $PdfManage = new \PDFMerger;
            $filename = $this->prefix.'Model_'.$modelScoreMetadata->ModelScore_Name;

            $header = ucfirst('Model Evaluation - '.str_replace('Scored File',' ',$modelScoreMetadata->ModelScore_Des));
            $subheader = 'Scoring Date - '.date('Y-m-d', strtotime($modelScoreMetadata->ModelScore_Cutoff_Date)).'; Scoring ID - ' . $modelScoreMetadata->ModelScoreID;

            $aData1 = App\Model\ModelBuildMetadata::where('ModelBuildID',$modelScoreMetadata->ModelID)->get()->toArray();
            $modelbuldmetadata = Helper::print_datatable_vertical($aData1);

            $footer = ucfirst($modelScoreMetadata->ModelScore_Name);
            $papersize = $request->input('papersize','landscape');

            $mMultiPageFileName = $filename.'.pdf';
            $unifilename = rand(100,10000).$filename;
            /*$html = View::make('layouts.model-eval-pdf', [
                'header' => $header,
                'subheader' => $subheader,
                'footer' => $footer,
                'tables' => [$eEvalSumHtml,$eEvalDetailHtml,$modelbuldmetadata],
                'charthtml' => '',
                'filename' => $filename,
                'selections' => ''
            ])->render();
            echo $html; die;*/
            $subheader2 = 'Gains Chart';
            PDF::loadView('layouts.model-eval-pdf', [
                'header' => $header,
                'subheader' => $subheader,
                'subheader2' => $subheader2,
                'footer' => $footer,
                'tables' => [$eEvalSumHtml,$eEvalDetailHtml],
                'charthtml' => '',
                'filename' => $filename,
                'selections' => ''
            ])->setPaper('letter',$papersize)->setWarnings(false)->save(public_path().'/downloads/'.$unifilename.'.pdf');
            $PdfManage->addPDF(public_path().'/downloads/'.$unifilename.'.pdf', 'all');

            $subheader2 = 'Model Metadata';
            $unifilename = rand(100,10000).$filename;
            PDF::loadView('layouts.model-eval-pdf', [
                'header' => $header,
                'subheader' => $subheader,
                'subheader2' => $subheader2,
                'footer' => $footer,
                'tables' => [$modelbuldmetadata],
                'charthtml' => '',
                'filename' => $filename,
                'selections' => ''
            ])->setPaper('letter',$papersize)->setWarnings(false)->save(public_path().'/downloads/'.$unifilename.'.pdf');
            $PdfManage->addPDF(public_path().'/downloads/'.$unifilename.'.pdf', 'all');
            $subheader2 = 'Model Metadata';
            foreach ($existingEntries as $key => $existingEntry){
                $unifilename = rand(100,10000).$filename;
                //$subheader = '';//!empty($header) ? ' - '.$pdfdata['chart_title'] : $pdfdata['chart_title'];

                PDF::loadView('layouts.model-eval-pdf', [
                    'header' => $header,
                    'subheader' => $subheader,
                    'subheader2' => $existingEntry['title'],
                    'footer' => $footer,
                    'tables' => [],
                    'charthtml' => $existingEntry['img'],
                    'filename' => $filename,
                    'selections' => ''
                ])->setPaper('letter',$papersize)->setWarnings(false)->save(public_path().'/downloads/'.$unifilename.'.pdf');
                $PdfManage->addPDF(public_path().'/downloads/'.$unifilename.'.pdf', 'all');
            }
            $PdfManage->merge('file', public_path('\\downloads\\'.$mMultiPageFileName));

            return $ajax->success()->jscallback('ajax_download_sr_file')
                ->appendParam('link',url('/').'/downloads/'.$mMultiPageFileName)
                ->appendParam('filename',$mMultiPageFileName)
                ->response();
        }

        $sort = "Order By CampaignID DESC";
        $tabidWS = str_replace('_',' ',$level);
        $results = self::implement_query($tabidWS,$filters,'',false,$sort);

        $view = View::make('model.xlsx',[
            'reqlevel' => $level,
            'records' => $results['records'],
            'downloadColumnsIndex' => $downloadableColumns,
            'columns' => $results['columns'],
            'visible_columns' => $results['visible_columns']
        ])->render();


        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadhseet = $reader->loadFromString($view);
        $sheet = $spreadhseet->getActiveSheet();
        $spreadhseet->setActiveSheetIndex(0);
        $spreadhseet->getActiveSheet()->setTitle(ucfirst($level));

        $spreadhseet->setActiveSheetIndex(0);

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

        $file_Name =  $this->prefix. 'Model_' . ucfirst($level) . '_' . date('Y') . date('m') . date('d');

        $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');

        $sBaseUrl = config('constant.BaseUrl');
        return $ajax->success()
            ->appendParam('html',$view)
            ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
            ->jscallback('ajax_download_file')
            ->response();
    }

    public function MetaDataQuickUpdate(Request $request,Ajax $ajax){
        try{
            $rowID = $request->input('rowID');
            $fieldname = $request->input('fieldname');
            $fieldvalue = $request->input('fieldvalue');

            $record = App\Model\RepCmpMetaData::where('rowID',$rowID)->first();
            if($record){
                DB::update("update UM_Model_Metadata set ".$fieldname." = '".$fieldvalue."' where RowID = ".$rowID);
            }
            $record1 = App\Model\RepCmpMetaData::where('rowID',$rowID)->first();
            return $ajax->success()
                ->appendParam('record',$record1)
                ->jscallback()
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->message($exception->getMessage())
                ->response();
        }
    }

    public function getSingle(Request $request,Ajax $ajax){
        $ModelScoreID = $request->input('ModelScoreID');
        $eEvalSumRecords = DB::select('EXEC sp_CRM_Model_Eval_sum_Single '.$ModelScoreID);
        $eEvalDetailRecords = DB::select('EXEC sp_CRM_Model_Eval_detail_Single '.$ModelScoreID);

        $eEvalSumHtml = $eEvalDetailHtml= '';
        if($eEvalSumRecords){
            $eEvalSumHtml = '<div class="title-center font-14 mb-1" style="color: #75aed0;
    font-weight: 500;
    font-size: 13px;">Model Evaluation by Segment</div>'.Helper::print_datatable($eEvalSumRecords,[
                    'Model Segment' => 'text-align:center;',
                    'Touched' => 'text-align:right;padding-right: 40px;',
                    'Responder' => 'text-align:right;padding-right: 40px;',
                    'Response Rate' => 'text-align:right;padding-right: 40px;'
                ]);
        }
        if($eEvalDetailRecords){
            $eEvalDetailHtml = '<div class="title-center font-14 mb-1" style="color: #75aed0;
    font-weight: 500;
    font-size: 13px;">Model Evaluation by Decile</div>'.Helper::print_datatable($eEvalDetailRecords,[
                    'Model Segment' => 'text-align:center;',
                    'Touched' => 'text-align:right;padding-right: 40px;',
                    'Responder' => 'text-align:right;padding-right: 40px;',
                    'Response Rate' => 'text-align:right;padding-right: 40px;'
                ]);
        }
        $menu = 'Model Evaluation';
        $aData = App\Model\ModelChart::where('Menu',$menu)
            ->get()
            ->toArray();
        $chart_data = array();

        foreach($aData as $key=>$data){

            $chart_data[$key]['chart_type'] = trim($data['chart_type']);
            $chart_data[$key]['chart_position'] = trim($data['chart_position']);
            $chart_data[$key]['chart_title'] = trim($data['chart_title']);
            $chart_data[$key]['chart_legend1'] = trim($data['chart_legend1']);
            $chart_data[$key]['chart_legend2'] = trim($data['chart_legend2']);
            $chart_data[$key]['chart_legend3'] = trim($data['chart_legend3']);
            $chart_data[$key]['chart_legend4'] = trim($data['chart_legend4']);
            $chart_data[$key]['Legend1_Background_Color'] = trim($data['Legend1_Background_Color']);
            $chart_data[$key]['Legend2_Background_Color'] = trim($data['Legend2_Background_Color']);
            $chart_data[$key]['Legend3_Background_Color'] = trim($data['Legend3_Background_Color']);
            $chart_data[$key]['Legend4_Background_Color'] = trim($data['Legend4_Background_Color']);
            $chart_data[$key]['Legend5_Background_Color'] = trim($data['Legend5_Background_Color']);
            $chart_data[$key]['Legend6_Background_Color'] = trim($data['Legend6_Background_Color']);
            $chart_data[$key]['Legend1_Border_Color'] = trim($data['Legend1_Border_Color']);
            $chart_data[$key]['Legend2_Border_Color'] = trim($data['Legend2_Border_Color']);
            $chart_data[$key]['Legend3_Border_Color'] = trim($data['Legend3_Border_Color']);
            $chart_data[$key]['Legend4_Border_Color'] = trim($data['Legend4_Border_Color']);
            $chart_data[$key]['Legend5_Border_Color'] = trim($data['Legend5_Border_Color']);
            $chart_data[$key]['Legend6_Border_Color'] = trim($data['Legend6_Border_Color']);
            $chart_data[$key]['Format'] = trim($data['Format']);
            $chart_data[$key]['Chart_Scale'] = trim($data['Chart_Scale']);

            $chart_id = $data['id'];
            $aCData = App\Model\ModelChartDetails::where('chart_id',$chart_id)
                ->where('Menu',$menu)
                ->orderBy('row_id')
                ->get()
                ->toArray();
            $detail = array();
            foreach($aCData as $ckey=>$dRata){
                $detail[$ckey]['chart_label'] = trim($dRata['chart_label']);
                $detail[$ckey]['chart_value1'] = trim($dRata['chart_value1']);
                $detail[$ckey]['chart_value2'] = trim($dRata['chart_value2']);
                $detail[$ckey]['chart_value3'] = trim($dRata['chart_value3']);
                $detail[$ckey]['chart_value4'] = trim($dRata['chart_value4']);
            }
            $chart_data[$key]['chart_detail'] = $detail;
        }

        return $ajax->success()
            ->appendParam('eEvalSumHtml',$eEvalSumHtml)
            ->appendParam('eEvalDetailHtml',$eEvalDetailHtml)
            ->appendParam('chart_data',$chart_data)
            ->jscallback('ajax_single_modelscore')
            ->response();
    }

    public function showPhone(Request $request,Ajax $ajax){

        $campaigns = App\Model\ModelScoreTemplate::orderByDesc('t_id')->get(['t_id','Scored_File_Name','t_name']);
        $content = View::make('model.phone-campaign',[
            'campaigns' => $campaigns
        ])->render();

        $sdata = [
            'content' => $content
        ];

        $title = 'Phone Campaign';
        $size = 'modal-md';

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

    public function submitPhone(Request $request,Ajax $ajax){
        $campaign = $request->input('campaign');
        $rules = [
            'campaign' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }

        if(!empty($campaign)){
            $campaign = explode('::',$campaign);
            //echo "EXEC sp_CRM_Campaign_to_Phone ".$campaign[0].", '".$campaign[1]."'"; die;
            //DB::statement("EXEC [dbo].[sp_CRM_Campaign_to_Phone_P1] ".$campaign[0].", '$campaign[1]'");
            $db = $this->db->getPdo();
            //$stmt = $db->prepare("EXEC [dbo].[EXEC [dbo].[sp_CRM_Campaign_to_Phone_P1] ".$campaign[0].", '$campaign[1]'");
            $stmt = $db->prepare("EXEC [dbo].[sp_CRM_Campaign_to_Phone_P1] ".$campaign[0]);
            $stmt->execute();

          /*  echo "select  Touchcampaign, Touchstatus,Touchdate, count(*) as Count from touch where campaignid= '$campaign[0]' group by touchcampaign, touchstatus, touchdate<br>" ;
            $rRecords = DB::select("select  Touchcampaign, Touchstatus,Touchdate, count(*) as Count from touch where campaignid= '$campaign[0]' group by touchcampaign, touchstatus, touchdate");

            echo '<pre>';
            print_r($rRecords1);
            echo '#######################<br>';
            print_r($rRecords);
            die;*/
            $rRecords = DB::select("EXEC [dbo].[sp_CRM_Campaign_to_Phone_P2] $campaign[0]");
            $rRecords = collect($rRecords)->map(function($x){ return (array) $x; })->toArray();
            $html = Helper::print_datatable($rRecords);
            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('ajax_phone_campaign')
                ->response();
        }

        return $ajax->fail()
            ->jscallback('ajax_phone_campaign')
            ->response();
    }

    public function generateReport(Request $request,Ajax $ajax){
        $row_id = $request->input('row_id');
        $record = App\Model\ModelScoreTemplate::with(['rpschedule.moschstatusmap','momodel','modelscoremetadata'])->where('row_id',$row_id)->first()->toArray();
        $Report_Fields = json_decode($record['Report_Fields'], true);
        $model_variables = explode(',', $record['momodel']['Model_Variables']);
        $variables = array_merge(['Model Tier','Model Decile'],$model_variables);
        $variables = array_merge($variables,$Report_Fields);
        $modelScoreID = $record['modelscoremetadata']['ModelScoreID'];

        $response = [];
        foreach ($variables as $variable){
            $sSql4 = "select RowVariable, Value, Universe, PercentofUniverse, PredictedResponse from UM_ModelScore_Report WHERE RowVariable = '".trim($variable)."' AND ModelScoreID = ".$modelScoreID." order by RowVariable";
            $dData = DB::select($sSql4);
            $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();

            $result = Helper::model_datatable($dData);
            if(in_array($variable, ['Model Tier','Model Decile'])){
                $variable_type = 'Overall Distribution';
            }elseif (in_array($variable, $model_variables)){
                $variable_type = 'Model Variables';
            }else{
                $variable_type = 'Profile Variables';
            }
            $response[] = [
                'row_variable' => trim($variable),
                'variable_type' => trim($variable_type),
                'data' => $dData, //$result
                'result' => $result, //$result
            ];
        }

        $sSql4 = "select RowVariable, Value, Universe, PercentofUniverse, PredictedResponse from UM_ModelScore_Report WHERE ModelScoreID = ".$modelScoreID." order by RowVariable";
        $dData = DB::select($sSql4);
        //$dData = DB::select("EXEC sp_CRM_Profile2b '".$field."'");
        $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();


        return $ajax->success()
            ->appendParam('record', $record)
            ->appendParam('data',$dData)
            ->appendParam('variables', $variables)
            ->appendParam('response', $response)
            ->jscallback('ajax_generate_report')
            ->response();
    }

    public function generatePDF(Request $request,Ajax $ajax){
        $PdfManage = new \PDFMerger;
        $pdfdatas = json_decode($request->input('pdfdata'),true);
        $filename = $this->prefix.'MOR_'.$request->input('filename');

        $header = ucfirst($request->input('rpheader'));
        $footer = ucfirst($request->input('rpfooter'));
        $papersize = $request->input('papersize','portrait');
        $folder_location = $request->input('folder_location','Public');

        $mMultiPageFileName = $filename.'.pdf';
        foreach ($pdfdatas as $pdfdata){
            $unifilename = rand(100,10000).$filename;
            $subheader = !empty($header) ? $pdfdata['chart_title'] : $pdfdata['chart_title'];
            /*$html = View::make('layouts.pdf', [
                'header' => $header,
                'footer' => $footer,
                'tablehtml' => $pdfdata['chart_table'],
                'charthtml' => $pdfdata['chart_img'],
                'filename' => $this->prefix.$filename,
                'selections' => ''
            ])->render();
            echo $html;*/
            PDF::loadView('layouts.model-pdf', [
                'header' => $header,
                'subheader' => $subheader,
                'footer' => $footer,
                'tablehtml' => $pdfdata['chart_table'],
                'charthtml' => $pdfdata['chart_img'],
                'filename' => $filename,
                'selections' => ''
            ])->setPaper('letter',$papersize)->setWarnings(false)->save(public_path().'/downloads/'.$unifilename.'.pdf');
            $PdfManage->addPDF(public_path().'/downloads/'.$unifilename.'.pdf', 'all');
        }

        $PdfManage->merge('file', public_path('\\' . $folder_location . '\\'.$mMultiPageFileName));
        //die;
        return $ajax->success()->jscallback('ajax_download_sr_file')
            ->appendParam('link',url('/').'/' . $folder_location . '/'.$mMultiPageFileName)
            ->appendParam('filename',$mMultiPageFileName)
            ->appendParam('filetype','pdf')
            ->appendParam('filepath', $folder_location . '\\'.$mMultiPageFileName)
            ->response();
    }

    public function generateXLSX(Request $request,Ajax $ajax){
        $row_id = $request->input('row_id');
        $record = App\Model\ModelScoreTemplate::with(['rpschedule.moschstatusmap','momodel','modelscoremetadata'])->where('row_id',$row_id)->first()->toArray();
        $modelScoreID = $record['modelscoremetadata']['ModelScoreID'];
        $filename = $record['rpschedule']['moschstatusmap'][0]['file_name'];
        $folder_location = $record['promoexpo_folder'];
        //$filename = $this->prefix.'MOR_'.$filename;
        $filename = $this->prefix . "MOR_".$filename.".xlsx";
        $folder_location = $request->input('folder_location','Public');
        $model_variables = explode(',', $record['momodel']['Model_Variables']);


        $sSql4 = "select RowVariable, Value, Universe, PercentofUniverse, PredictedResponse from UM_ModelScore_Report WHERE ModelScoreID = ".$modelScoreID;
        $dData = DB::select($sSql4);
        //$dData = DB::select("EXEC sp_CRM_Profile2b '".$field."'");
        $dData = collect($dData)->map(function($x){ return (array) $x; })->toArray();

        $view = View::make('model.xlsx-v2',[
            'dData' => $dData,
            'overalldistribution' => ['Model Tier','Model Decile'],
            'model_variables' => $model_variables,
            'headings' => $this->headerCells
        ])->render();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadhseet = $reader->loadFromString($view);
        $sheet = $spreadhseet->getActiveSheet();
        $spreadhseet->setActiveSheetIndex(0);
        //$sTitle = 'Lookup';
        //$spreadhseet->getActiveSheet()->setTitle($sTitle);

        $writer = new Xlsx($spreadhseet);
        $writer->save(public_path()."\\".$folder_location."\\".$filename);

        return $ajax->success()->jscallback('ajax_download_sr_file')
            ->appendParam('link',url('/').'/' . $folder_location . '/'.$filename)
            ->appendParam('filename',$filename)
            ->appendParam('filetype','xlsx')
            ->appendParam('filepath', $folder_location . '\\'.$filename)
            ->response();
    }

    public function modelPreview($model_id, Request $request,Ajax $ajax){
        if($model_id == '0'){
            $aData1 = App\Model\ModelBuildMetadata::get()->toArray();
            $aData = array_slice($aData1, 0, 1000);
            $table = Helper::print_datatable($aData);
        }else{
            $aData1 = App\Model\ModelBuildMetadata::where('ModelBuildID',$model_id)->get()->toArray();
            $table = Helper::print_datatable_vertical($aData1);
        }


        $sdata = [
            'content' => '<div class="row" style="overflow: scroll;"> '.$table.'</div>'
        ];

        $title = 'Model Build Metadata Details';
        $size = ($model_id == '0') ? 'modal-xxl' : 'modal-md';

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

    public function modelScorePreview(Request $request,Ajax $ajax){
        $aData1 = App\Model\ModelScoreMetadata::get()->toArray();
        $aData = array_slice($aData1, 0, 1000);
        $table = Helper::print_datatable($aData);

        $sdata = [
            'content' => '<div class="row" style="overflow: scroll;"> '.$table.'</div>'
        ];

        $title = 'Model Score Metadata Details';
        $size =  'modal-xxl';

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
}
