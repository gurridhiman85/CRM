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

class CampaignController extends Controller
{
    public $schtasks_dir;
    public $schDir;
    public $phpPath;
    public $filePath;
    public $prefix;
    public $clientname;
    public $db;

    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->schDir = config('constant.schDir');
        $this->phpPath = config('constant.phpPath');
        $this->filePath = config('constant.filePath');
        $this->prefix = config('constant.prefix');
        $this->clientname = config('constant.client_name');
        $this->db = DB::connection('sqlsrv');
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
        return view('campaign.index',[
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
            ->where('menu_level1', 'Campaign')
            ->orderBy('menu_level2')
            ->pluck('menu_level2')
            ->toArray();

        foreach ($levels as $level){
            $lLevelColumns = Helper::getColumns('Campaign',$level);
            $lLevelFilters[$level] = Helper::getFilterValues($lLevelColumns['visible_columns']);
        }

        //$results = Helper::getFiltersSummaryDetail($eSummary['visible_columns'],$eDetail['visible_columns'],$mMetadata['visible_columns']);
        return view('campaign.index',[
            /*'sumFilters' => $results['sumFilters'],
            'detailFilters' => $results['detailFilters'],
            'metadataFilters' => $results['metadataFilters'],*/
            'lLevelFilters'       => $lLevelFilters,
            'alllevels' => $levels
        ]);
    }

    public static function implement_query($reqlevel,$filters = [],$pagination,$add_sort_end = true,$sort = ''){
        $lLevel = Helper::getColumns('Campaign',$reqlevel);
        $sort = empty($sort) ? $lLevel['sort'] : $sort;

        //$sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";


        $levels = [
            $reqlevel => [
                'columns' => $lLevel['all_columns'],
                'visible_columns' => $lLevel['visible_columns'],
                'filter_columns' => $lLevel['filter_columns'],
                'sql'   => 'select '.implode(',',$lLevel['all_columns']).' from (SELECT *,ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS ROWNUMBER FROM '.$lLevel['table_name'].') as t',
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


    public function getCampaign(Request $request,Ajax $ajax){

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
        /*if($User_Type != 'Full_Access'){
            $aData = DB::select("SELECT * FROM UL_RepCmp_Share WHERE Shared_With_User_id = '".$uid."' AND t_type = 'C'");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            $shared_camp_ids = [];
            $uW = '';
            if (!empty($aData)) {
                foreach ($aData as $k => $row) {
                    $shared_camp_ids[] = $row['camp_tmpl_id'];
                }
                $uW = ' OR za.row_id IN('.implode(",",$shared_camp_ids).')';
            }

            $uWhere = "AND (User_ID = '$uid' OR is_public = 'Y' $uW)";
        }*/
        if($tabid == '0'){  // Running

            /*$query = App\Model\CampaignTemplate::query()->with(['rpmeta','rpschedule.ccschstatusmap']);
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
            $records = $query->skip($position)
                ->take($records_per_page)
                ->orderBy('row_id', 'DESC')
                ->get()
                ->toArray();
            //dd($records);
            $trQuery = App\Model\CampaignTemplate::query();
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
            $trQuery->whereHas('rpschedule.ccschstatusmap',function ($qry){
                $qry->where('status','Running');
            });
            $total_records = $trQuery->count();*/

            /*$resolver['Description'] = 'substring(meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1)';

            $resolver['Year'] = 'substring(meta_data,  P6.Pos + 1,  P7.Pos -  P6.Pos - 1)';
            $resolver['Month'] = 'substring(meta_data,  P7.Pos + 1,  P8.Pos -  P7.Pos - 1)';
            $resolver['Day'] = 'substring(meta_data,  P8.Pos + 1,  P9.Pos -  P8.Pos - 1)';


            $records = DB::select("SELECT
            za.t_id as ID,za.list_level as [Level],
            za.list_short_name as Name,
            substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description,za.is_public as 'is_public', za.Custom_SQL,
            ss.sche_name as ScheduleName,ss.templ_name as TemplateName,ss.start_time as 'StartTime',ss.next_runtime as next_runtime ,ss.ftp_flag as 'FTP',za.t_type,za.row_id,
                      case ss.file_name WHEN '-' THEN '-'
                      else ss.file_path+'/'+ss.file_name
                      END as [File Name],za.row_id as [Row_id]
                      FROM
                      UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss,
                      UC_Campaign_Templates za
                      cross apply (select (charindex('^', za.meta_data))) as P1(Pos)
            cross apply (select (charindex('^', za.meta_data,  P1.Pos+1))) as  P2(Pos)
            cross apply (select (charindex('^', za.meta_data,  P2.Pos+1))) as  P3(Pos)
            cross apply (select (charindex('^', za.meta_data,  P3.Pos+1))) as  P4(Pos)
            cross apply (select (charindex('^', za.meta_data,  P4.Pos+1))) as  P5(Pos)
            cross apply (select (charindex('^', za.meta_data,  P5.Pos+1))) as  P6(Pos)
            cross apply (select (charindex('^', za.meta_data,  P6.Pos+1))) as  P7(Pos)
            cross apply (select (charindex('^', za.meta_data,  P7.Pos+1))) as  P8(Pos)
            cross apply (select (charindex('^', za.meta_data,  P8.Pos+1))) as  P9(Pos)
            cross apply (select (charindex('^', za.meta_data,  P9.Pos+1))) as P10(Pos)
            cross apply (select (charindex('^', za.meta_data, P10.Pos+1))) as P11(Pos)
            cross apply (select (charindex('^', za.meta_data, P11.Pos+1))) as P12(Pos)
                       where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'C' and st.t_type = 'C'

                       AND ss.status = 'Running') $uWhere Order By za.row_id DESC");

            $nSQL = "SELECT count(*) as cnt
				  FROM
				  UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss,
				  UC_Campaign_Templates za
				  cross apply (select (charindex('^', za.meta_data))) as P1(Pos)
cross apply (select (charindex('^', za.meta_data,  P1.Pos+1))) as  P2(Pos)
cross apply (select (charindex('^', za.meta_data,  P2.Pos+1))) as  P3(Pos)
cross apply (select (charindex('^', za.meta_data,  P3.Pos+1))) as  P4(Pos)
cross apply (select (charindex('^', za.meta_data,  P4.Pos+1))) as  P5(Pos)
cross apply (select (charindex('^', za.meta_data,  P5.Pos+1))) as  P6(Pos)
cross apply (select (charindex('^', za.meta_data,  P6.Pos+1))) as  P7(Pos)
cross apply (select (charindex('^', za.meta_data,  P7.Pos+1))) as  P8(Pos)
cross apply (select (charindex('^', za.meta_data,  P8.Pos+1))) as  P9(Pos)
cross apply (select (charindex('^', za.meta_data,  P9.Pos+1))) as P10(Pos)
cross apply (select (charindex('^', za.meta_data, P10.Pos+1))) as P11(Pos)
cross apply (select (charindex('^', za.meta_data, P11.Pos+1))) as P12(Pos)
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'C' and st.t_type = 'C'

				   AND ss.status = 'Running') $uWhere";
            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/
            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'running';
            if($rType == 'pagination'){
                $html = View::make('campaign.tabs.running.table',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('campaign.tabs.running.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('campaign.tabs.running.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/

            return $ajax->success()
                //->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 1){ // Scheduled

            /*$query = App\Model\CampaignTemplate::query()->with('rpmeta','rpschedule.ccschstatusmap');
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
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get()->toArray();

            $trQuery = App\Model\CampaignTemplate::query();
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
            $trQuery->whereHas('rpschedule.ccschstatusmap',function ($qry){
                $qry->where('status','Scheduled');
            });
            $total_records = $trQuery->count();*/


            /*$records = DB::select("SELECT
za.t_id as ID,za.list_level as [Level],
za.list_short_name as Name,
substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description,za.is_public as 'is_public', za.Custom_SQL,
ss.sche_name as ScheduleName,ss.templ_name as TemplateName,ss.start_time as 'StartTime',ss.next_runtime as next_runtime ,ss.ftp_flag as 'FTP',za.t_type,za.row_id,
				  case ss.file_name WHEN '-' THEN '-'
				  else ss.file_path+'/'+ss.file_name
				  END as [File Name],za.row_id as [Row_id]
				  FROM
				  UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss,
				  UC_Campaign_Templates za
				  cross apply (select (charindex('^', za.meta_data))) as P1(Pos)
cross apply (select (charindex('^', za.meta_data,  P1.Pos+1))) as  P2(Pos)
cross apply (select (charindex('^', za.meta_data,  P2.Pos+1))) as  P3(Pos)
cross apply (select (charindex('^', za.meta_data,  P3.Pos+1))) as  P4(Pos)
cross apply (select (charindex('^', za.meta_data,  P4.Pos+1))) as  P5(Pos)
cross apply (select (charindex('^', za.meta_data,  P5.Pos+1))) as  P6(Pos)
cross apply (select (charindex('^', za.meta_data,  P6.Pos+1))) as  P7(Pos)
cross apply (select (charindex('^', za.meta_data,  P7.Pos+1))) as  P8(Pos)
cross apply (select (charindex('^', za.meta_data,  P8.Pos+1))) as  P9(Pos)
cross apply (select (charindex('^', za.meta_data,  P9.Pos+1))) as P10(Pos)
cross apply (select (charindex('^', za.meta_data, P10.Pos+1))) as P11(Pos)
cross apply (select (charindex('^', za.meta_data, P11.Pos+1))) as P12(Pos)
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'C' and st.t_type = 'C'

				   AND ss.status = 'Scheduled') $uWhere Order By ss.next_runtime DESC");

            $nSQL = "SELECT count(*) as cnt
				  FROM UC_Campaign_Templates za,UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'C' and st.t_type = 'C'
				   AND ss.status = 'Scheduled') $uWhere";

            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/
            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'scheduled';
            if($rType == 'pagination'){
                $html = View::make('campaign.tabs.scheduled.table',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('campaign.tabs.scheduled.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('campaign.tabs.scheduled.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/

            return $ajax->success()
                //->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 2){ // Completed
            $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
            $sort = ($sort == "") ? "Order By CampaignId DESC" : $sort == 'CampaignId' ? "Order By CampaignId DESC" : "Order By $sort $dir";

            /*$query = App\Model\CampaignTemplate::query()->with(['rpschedule.ccschstatusmap']);
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
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get();

            $trQuery = App\Model\CampaignTemplate::query();
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
            $trQuery->whereHas('rpschedule.ccschstatusmap',function ($qry){
                $qry->where('status','Completed');
            });
            $total_records = $trQuery->count();*/

            /*$sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over (Order By row_id DESC) as ROWNUMBER,* FROM (SELECT                              za.t_id as ID,za.list_level as [Level], za.list_short_name as Name,za.t_name,za.sql,za.selected_fields,za.meta_data,
                          substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description,
                          sc.start_time as 'StartTime',sc.completed_time as 'RunTime',
                          (isnull(za.promoexpo_folder + '/" . $this->prefix. "CAL_' + za.promoexpo_file + RIGHT(za.t_name, 14) + '.' + za.promoexpo_ext,'')) as [List],
                          (isnull(za.promoexpo_folder+'\\".$this->prefix."CAL_'+za.promoexpo_file + RIGHT(za.t_name, 14) + '.' + za.promoexpo_ext,'')) as ListXLSX,
          (isnull(za.promoexpo_folder+'\\".$this->prefix."CAL_'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf','')) as ListPDF,
                          za.promoexpo_ext as [ListFileExt],
                          sc.total_records as 'Records',sc.succ_flag as 'Run',
                          sc.ftp_flag as 'FTP', za.is_public as 'is_public',
                          za.Custom_SQL,
                          (isnull(za.promoexpo_folder+'/".$this->prefix."CAM_'+za.promoexpo_file + RIGHT(za.t_name, 14) +'.pdf','')) as SummaryPDF,
                          (isnull(za.promoexpo_folder+'/".$this->prefix."CAM_'+za.promoexpo_file + RIGHT(za.t_name, 14) + '.xlsx','')) as SummaryXLSX,
                          substring(za.meta_data, P11.Pos + 1, P12.Pos - P11.Pos - 1) as Action,
                          za.row_id,za.t_type,za.Report_Row,za.Report_Column,za.Report_Function,za.Report_Sum,za.Report_Show,za.Chart_Type,za.Axis_Scale,za.Label_Value
                      FROM
                            UL_RepCmp_Completed sc,UC_Campaign_Templates za

                            cross apply (select (charindex('^', za.meta_data))) as P1(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P1.Pos+1))) as  P2(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P2.Pos+1))) as  P3(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P3.Pos+1))) as  P4(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P4.Pos+1))) as  P5(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P5.Pos+1))) as  P6(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P6.Pos+1))) as  P7(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P7.Pos+1))) as  P8(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P8.Pos+1))) as  P9(Pos)
                            cross apply (select (charindex('^', za.meta_data,  P9.Pos+1))) as P10(Pos)
                            cross apply (select (charindex('^', za.meta_data, P10.Pos+1))) as P11(Pos)
                            cross apply (select (charindex('^', za.meta_data, P11.Pos+1))) as P12(Pos)
                      where
                            (sc.camp_id = za.t_id AND za.t_type = 'C') $uWhere";
            if (isset($_POST['obj'])) {
                $sSQL .= " and " . $resolver[$_POST['col']] . " = '" . $_POST['obj'] . "' ";
            }
            $sSQL .= ") a ) _myResults  $sWhere1";
            $records = DB::select($sSQL);

            //echo '<pre>'; print_r($records); die;

            $nSQL = "select count(*) as cnt from (select ROW_NUMBER() over (Order By row_id DESC) as ROWNUMBER,* from (SELECT za.row_id,
za.t_type,sc.camp_id,za.t_id
from UL_RepCmp_Completed sc,UC_Campaign_Templates za
where (sc.camp_id = za.t_id AND za.t_type = 'C') $uWhere";
            if (isset($_POST['obj'])) {
                $nSQL .= " and " . $resolver[$_POST['col']] . " = '" . $_POST['obj'] . "' ";
            }
            $nSQL .= ") a ) _myResults ";
            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/

            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'level1';
            if($rType == 'pagination'){
                $html = View::make('campaign.tabs.completed.table',[
                    //'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('campaign.tabs.completed.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('campaign.tabs.level1.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/
            return $ajax->success()
                //->appendParam('total_records',$total_records)
                //->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if ($tabid == 3){
            $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';
            $query = App\Model\CampaignTemplate::query()->with('rpschedule.ccschstatusmap');
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
            $trQuery = App\Model\CampaignTemplate::query();
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
                $html = View::make('campaign.tabs.older_versions.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }else{
                $html = View::make('campaign.tabs.older_versions.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }

            $paginationhtml = View::make('campaign.tabs.older_versions.pagination-html',[
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

            /*$sSQL = "select [Campaign_ID],
[Description],[Universe],[Objective],[Brand],[Channel],[Offer_Category],[All_Incr_Profit] as [Camp_Tot_Profit],[All_Incr_ROI] as [Camp_Tot_ROI],[All_Incr_Resp_Rate] as [Camp_Tot_Resp_Rate],[Cat1_Incr_Profit] as [Camp_Cat_Profit],[Cat1_Incr_ROI] as [Camp_Cat_ROI],[Cat1_Incr_Resp_Rate] as [Camp_Cat_Resp_Rate],[Redemption_Rate],[All_Redeemers] as [Total_Redeemers],[open_rate] as [Open_Rate],[click_rate] as [Click_Rate],[Pgm_Redeemers] as [Camp_Redeemers],[New_Redeemers],[Redeemers_Pass_Along],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Summary) _myResults  $sWhere1";*/

            //$records = DB::select($sSQL);

            $result = self::implement_query('Evaluation Summary',$filters);

            /*$nSQL = "select count(*) as cnt from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Summary) _myResults";

            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/
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
                $html = View::make('campaign.tabs.ESummary.table',$data)->render();
            }else{
                $html = View::make('campaign.tabs.ESummary.index',$data)->render();
            }

            /*$paginationhtml = View::make('campaign.tabs.ESummary.pagination-html',[
                'total_records' => $total_records[0]['cnt'],
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/
            return $ajax->success()
                //->appendParam('total_records',$total_records)
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
                $html = View::make('campaign.tabs.EDetails.table',$data)->render();
            }else{
                $html = View::make('campaign.tabs.EDetails.index',$data)->render();
            }

            /*$paginationhtml = View::make('campaign.tabs.EDetails.pagination-html',[
                //'total_records' => $total_records[0]['cnt'],
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/
            return $ajax->success()
                //->appendParam('total_records',$total_records)
                ->appendParam('records',$result['records'])
                ->appendParam('sql',$result['sql'])
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 6){ // Outer Metadata
            $result = self::implement_query('Metadata',$filters);

            $sort_column = "Campaign_ID";
            $sort_dir = "DESC";
            $tabName = 'Metadata';
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
                $html = View::make('campaign.tabs.Metadata.table',$data)->render();
            }else{
                $html = View::make('campaign.tabs.Metadata.index',$data)->render();
            }

            return $ajax->success()
                ->appendParam('records',$result['records'])
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 7){ // Outer Metadata
            $campaigns = App\Model\CampaignTemplate::orderByDesc('t_id')->get(['t_id','t_name']);
            //dd($campaigns);
            $html = View::make('campaign.tabs.Singlecamp.index',['campaigns' => $campaigns])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 8 || $tabid == 9) {
            //control groups
            $SQL = "SELECT [code_value] from [UC_Campaign_Lookup] WHERE code_type = 'CGD' order by code_value ";
            $aDataCGD = DB::select($SQL);
            $aDataCGD = collect($aDataCGD)->map(function($x){ return (array) $x; })->toArray();
            $cgoptions1 = '<option value="">---</option>';
            $cgoptions2 = '<option value="">---</option>';
            foreach ($aDataCGD as $value){
                $cgoptions1 .= '<option value="'.$value['code_value'].'">'.$value['code_value'].'</option>';
           }

            $list_levels = DB::select("select * from  UL_RepCmp_Lookup_Level_Camp");
            $html = View::make('campaign.tabs.create.new-v1', [
                'tabid' => $tabid,
                'cgoptions1' => $cgoptions1,
                'list_levels' => $list_levels
            ])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 10) {
            $html = View::make('campaign.tabs.create.segment', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 11) {
            $html = View::make('campaign.tabs.create.export', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 12) {
            $html = View::make('campaign.tabs.create.metadata', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
                ->jscallback('load_ajax_tab')
                ->response();
        }
        else if($tabid == 13) {
            $html = View::make('campaign.tabs.create.execute', ['tabid' => $tabid])->render();

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
                $html = View::make('campaign.tabs.ESummary.table',$data)->render();
            }else{
                $html = View::make('campaign.tabs.ESummary.index',$data)->render();
            }

            $paginationhtml = View::make('campaign.tabs.ESummary.pagination-html',[
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

        $query = App\Model\CampaignTemplate::query()->with(['rpmeta','rpschedule.ccschstatusmap']);
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
                    <option value=\'view,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Delete</option>            
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

        $query = App\Model\CampaignTemplate::query()->with('rpmeta','rpschedule.ccschstatusmap');
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
                    <option value=\'view,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Delete</option>            
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

        $query = App\Model\CampaignTemplate::query()->with(['rpschedule.ccschstatusmap']);
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
                $em_report_json = json_encode(['row_id' => $data->row_id,'t_id' => $data->t_id,'list_level' => $data->list_level,'list_short_name' => $data->list_short_name,'t_name' => $data->t_name,'sql' => base64_encode($data->sql),'selected_fields' => $data->selected_fields,'rpmeta' => $data->rpmeta,'Report_Row' => $data->Report_Row,'Report_Column' => $data->Report_Column,'Report_Function' => $data->Report_Function,'Report_Sum' => $data->Report_Sum,'Report_Show' => $data->Report_Show,'Chart_Type' => trim($data->Chart_Type),'Axis_Scale' => $data->Axis_Scale,'Label_Value' => $data->Label_Value]);
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
                    <option value=\'view,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>View</option>
                    <option value=\'new,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Save As</option>
                    <option value=\'run,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Run Report</option>
                    <option value=\'replica,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Run List</option>
                    <option value=\'schedule,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Schedule</option>
                    <option value=\'email,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Email</option>
                    <option value=\'share,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Share</option>
                    <option value=\'delete,'.$data->row_id.',"'.$data->list_short_name.'",'.$data->t_id.'\'>Delete</option>            
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
                  [Coupon_Redemption],[Coupon_Redeemers],[Promoted_Redeemers],[New_Redeemers],[Pass_Along_Redeemers],[Final],[Offer_Category],[Wave],[List],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code],[Condition] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Detail) _myResults ";*/
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
                  [Coupon_Redemption],[Coupon_Redeemers],[Promoted_Redeemers],[New_Redeemers],[Pass_Along_Redeemers],[Final],[Offer_Category],[Wave],[List],[Offer],[Cost],[Start_Date],[End_Date],[Shopping_Cat],[Coupon_Code],[Condition] from (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,* from UC_Campaign_Detail) _myResults ";*/
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
        return view('campaign.tabs.create.outer-schedule');
    }

    public function getList(Request $request, Ajax $ajax){
        $cSQL = DB::select("SELECT t_name From UC_Campaign_Templates");
        $aData= collect($cSQL)->map(function($x){ return (array) $x; })->toArray();
        return $ajax->success()
            ->appendParam('list',$aData)
            ->response();
    }

    public function getSeq(Request $request, Ajax $ajax){
        $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UC_Campaign_Sequence]');
        $aData= collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();
        $cid = 0;
        if(!empty($aData)){
            $cid = $aData[0]['cid'];

            $arSQL = DB::select('SELECT count([t_id]) as cnt FROM [UC_Campaign_Templates] WHERE t_id = '.$cid);
            $arData= collect($arSQL)->map(function($x){ return (array) $x; })->toArray();
            if($arData[0]['cnt'] == 0){
                return $ajax->success()
                    ->appendParam('cid',$cid)
                    ->response();
            }
        }
        $cid = $cid + 1;
        DB::update("UPDATE [UC_Campaign_Sequence] SET [camp_id] = " .$cid);
        return $ajax->success()
            ->appendParam('cid',$cid)
            ->response();
    }

    public function ccSchData(Request $request, Ajax $ajax){
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


            $CName = $params->CName;
            $t_name = $params->CName;
            $listShortName = $params->listShortName;
            $list_level = $params->list_level;
            $list_fields = $params->list_fields;
            $selected_fields = $params->selected_fields;
            $custom_sql = $params->custom_sql;
            $list_format = $params->list_format;
            $report_orientation = $params->report_orientation;

            $sSQL = ucwords($params->sSQL);
            if ($custom_sql == 'Y' && strpos($sSQL, "DS_MKC_ContactID") === false) {
                $sSQL = substr($sSQL, 0, 6) . " DS_MKC_ContactID, " . substr($sSQL, 7, strlen($sSQL));
            }
            $rStr = $params->rStr;
            $is_public = $params->is_public;
            //$eFolder = $is_public == 'Y' ? 'Public' : 'Private';
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

            /*$metaStr = $params->metaStr;
            $upMetaStr = explode('^',$metaStr);
            $upMetaStr[6] = date('Y');
            $upMetaStr[7] = date('m');
            $upMetaStr[8] = date('d');
            $rpDesc = $upMetaStr[3];
            $metaStr = implode('^',$upMetaStr);*/
            $Create_Date = date('Y-m-d h:i:s');

            //$LSD = str_replace("'", "''", $LSD);
            //$sSQL = str_replace("'", "''", $sSQL);
            $rv = $cv = $sv = $fu = $sa = '';
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


            /*$insSQL = "INSERT INTO [UC_Campaign_Templates]([t_id],[User_ID],[t_name],[t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],[Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],[Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method]
			 ,[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
			,[seg_camp_grp_proportion],[seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt]
			,[promoexpo_folder],[promoexpo_file],[promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],[meta_data],[Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation])
		VALUES
			('$CID','$uid','$CName','C','$listShortName','$list_level','$list_fields','$filterVal','$customerExclusionVal','$customerInclusionVal','$filter_condition','$Customer_Exclusion_Condition','$Customer_Inclusion_Condition','$selected_fields','$sSQL','$DFS','$noLS','$lssm'
			,'$lssc','$LSD','$noCG','$cg','$CGD','$proporation','$sel_criteria','$cellSample','$saveCD','$saveFile'
			,'$eFolder','$eFile','$eExt','$CGOpt','$eData','$metaStr','$rv','$cv','$fu','$sv','$sa','$custom_sql','$ct','$cI','$as','$lv','$SR_Attachment','$list_format','$report_orientation')";

           // echo $insSQL; die;
            DB::insert($insSQL);*/

            /*DB::insert("INSERT INTO [UC_Campaign_Templates] ([t_id],[User_ID],[t_name],[t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],[Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],[Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method]
			 ,[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
			,[seg_camp_grp_proportion],[seg_camp_grp_sel_cri],[seg_sample],[seg_filters_criteria],[seg_filter_condition],[promoexpo_cd_opt],[promoexpo_file_opt]
			,[promoexpo_folder],[promoexpo_file],[promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],[Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation],[Lookup_Type]) VALUES ('$CID','$uid','$CName','C','$listShortName','$list_level','$list_fields','$filterVal','$customerExclusionVal','$customerInclusionVal','$filter_condition','$Customer_Exclusion_Condition','$Customer_Inclusion_Condition','$selected_fields','$sSQL','$DFS','$noLS','$lssm','$lssc','$LSD','$noCG','$cg','$CGD','$proporation','$sel_criteria','$cellSample','$segFilterCriteria','$segFilterCondition','$saveCD','$saveFile','$eFolder','$eFile','$eExt','$CGOpt','$eData','$rv','$cv','$fu','$sv','$sa','$is_public','$custom_sql','$ct','$cI','$as','$lv','$SR_Attachment','$list_format','$report_orientation','$Lookup_Type')");*/

            $campaign = new App\Model\CampaignTemplate();
            $campaign->t_id                         = $CID;
            $campaign->User_ID                      = $uid;
            $campaign->t_name                       = $CName;
            $campaign->t_type                       = 'C';
            $campaign->list_short_name              = $listShortName;
            $campaign->list_level                   = $list_level;
            $campaign->list_fields                  = $list_fields;
            $campaign->filter_criteria              = $filterVal;
            $campaign->Customer_Exclusion_Criteria  = $customerExclusionVal;
            $campaign->Customer_Inclusion_Criteria  = $customerInclusionVal;
            $campaign->filter_condition             = $filter_condition;
            $campaign->Customer_Exclusion_Condition = $Customer_Exclusion_Condition;
            $campaign->Customer_Inclusion_Condition = $Customer_Inclusion_Condition;
            $campaign->selected_fields              = $selected_fields;
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


            $rpDesc = $params->Category;
            $metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $params->CampaignID;
            $metadata->Type        = 'C';
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
            $metadata->save();

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

            $file_name = $listShortName . "_" . date("Ymd", time());
            if ($saveFile == 'Y')
                $dbfilename = $file_name;
            else
                $dbfilename = '-';

            if (($rtype == 'RA') || ($rtype == 'RP') || ($rtype = 'RI')) {

                $SQL = DB::select("Select [row_id],[sql] from [UC_Campaign_Templates] Where t_name = '$CName'  AND User_ID = '$uid' AND t_type='C'");
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
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftpData','$Camp_temp_id','C','$SFTP_Attachment')");


                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'C'");

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
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';

                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','$date1','$RA_Dt $RA_time','','$dbfilename','','$ftp_flag','Scheduled','$eFolder','','C')");

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
                    $metadata_date = $metadata->Start_Date;
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftpData','$Camp_temp_id',1,'$mo','$r_Str','$metadata_date','C','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'C'");


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
                    $command = "php artisan ccSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'a');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
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
                               VALUES ('$sName','$CName','$date1','$next_runDate $rp_run_time','','$dbfilename','','$ftp_flag','Scheduled','$eFolder','$rp_end_date $rp_run_time','C')");
                    //Insert into UL_RepCmp_Status table
                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftpData','$Camp_temp_id','C','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'C'");

                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task

                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$dbfilename','','Running','$eFolder','$ftp_flag','C')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'C'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'C')");
                //Status update to Scheduled


            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {

                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];

                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
            }



            //Send Report via Email Details
            $SREmailArray = explode(":", $SREmailStr);
            if ($SREmailArray[0] == 'Y') {

                $Email_Flag = $SREmailArray[0];

                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $Email_Flag . "','" . $CID . "','" . $SREmailArray[1] . "','" . $SREmailArray[2] . "','" . $SREmailArray[3] . "','" . $SREmailArray[4] . "','" . $SREmailArray[5] . "','C','pending','" . $SREmailArray[6]. "')");
            }

            //Share Report
            $ShareArray = explode(":", $ShareStr);
            if ($ShareArray[0] == 'Y') {

                $Share_Flag = $ShareArray[0];
                $users = !empty($ShareArray[1]) ? explode(',',$ShareArray[1]) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareArray[2];
                Helper::shareReport($CID,'C',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }
            if(!empty($rv)) {
                if (strpos($rv, ',') !== false) {
                    $nrv = explode(',', $rv);
                } else {
                    $nrv[] = $rv;
                }
                Helper::generateSrPDF($nrv, $cv, ucfirst($fu), $sv, $sa, $sSQL, $list_level, $listShortName, $imgTag, $imgPath, $eFolder, $file_name, $this->prefix . 'CAM_', $SR_Attachment, $rpDesc, $report_orientation);
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

            $sSqlCheck = DB::select("SELECT * FROM [UC_Campaign_Templates] WHERE t_id = '$CID' AND t_type='C'");
            $dDataI= collect($sSqlCheck)->map(function($x){ return (array) $x; })->toArray();
            if (empty($dDataI)) {
                return $ajax->fail()
                    ->message('Campaign doesn\'t exist')
                    ->jscallback()
                    ->response();
            }

            $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UC_Campaign_Sequence]');
            $aData = collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();

            if(!empty($aData)){
                $campaign_id = $aData[0]['cid'];

            }
            $campaign_id = $campaign_id + 1;
            DB::update("UPDATE [UC_Campaign_Sequence] SET [camp_id] = " .$campaign_id);

            $dDataI = $dDataI[0];
            $row_id = $dDataI['row_id'];
            $t_name = $dDataI['list_short_name'] . '_' . date('Ymd_Hi');

            $saveCD = $dDataI['promoexpo_cd_opt'];
            $saveFile = $dDataI['promoexpo_file_opt'];
            $eFolder = $dDataI['promoexpo_folder'];
            $eFile = $t_name;
            $eExt = $dDataI['promoexpo_ext'];
            $CGOpt = $dDataI['promoexpo_ecg_opt'];
            $eData = $dDataI['promoexpo_data'];
            $list_format = $dDataI['List_Format'];
            $report_orientation = $dDataI['Report_Orientation'];
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
            if(!empty($params->cI)){

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
            }

            DB::insert("INSERT INTO [UC_Campaign_Templates] ([t_id],[User_ID],[DCampaignID], [t_name],[t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation] )  
		   SELECT $campaign_id as [t_id],'$uid' as [User_ID],[DCampaignID], 
		  '$t_name' as [t_name],'C' as [t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],'$imgPath' as [Chart_Image],[Axis_Scale],[Label_Value], [SR_Attachment],[List_Format],[Report_Orientation] FROM [UC_Campaign_Templates] 
		  WHERE  t_id = '$CID' AND t_type='C'");

            $rpDesc = isset($params->Category) ? str_replace('~',' ', $params->Category) : '';

            $metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $campaign_id;
            $metadata->Type        = 'C';
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
            $metadata->save();
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
                $SQL = DB::select("Select [row_id],[list_short_name],[sql] from [UC_Campaign_Templates] Where t_name = '$CName' AND t_type='C'");
                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Camp_temp_id = $aData[0]['row_id'];
                    $sSQL = $aData[0]['sql'];
                    $list_short_name = $aData[0]['list_short_name'];
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
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftpData','$Camp_temp_id','C','$SFTP_Attachment')");
                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'C'");
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
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';
                    // Schedule the task
                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','','$RA_Dt $RA_time','','$file_name','','$ftp_flag','Scheduled','$eFolder','','C')");
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
                    $metadata_date = $metadata->Start_Date;

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftpData','$Camp_temp_id',1,'$mo','$r_Str','$metadata_date','C','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'C'");
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

                    $fhead = fopen($this->filePath."ccschedule.bat", 'C');
                    $command = "php artisan ccSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath."ccschedule.bat", 'C');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);*/
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
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
                               VALUES ('$sName','$CName','','$next_runDate $rp_run_time','','$file_name','','$ftp_flag','Scheduled','$eFolder','$rp_end_date $rp_run_time','C')");
                    //Insert into UL_RepCmp_Status table


                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftpData','$Camp_temp_id','C','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'C'");
                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' ccSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$file_name','','Running','$eFolder','$ftp_flag','C')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'C'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'C')");
                //Status update to Scheduled
            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {
                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];
                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
            }

            //Send Report via email
            $SREmailArray = explode(":", $SREmailStr);
            if ($SREmailArray[0] == 'Y') {

                $Email_Flag = $SREmailArray[0];

                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $Email_Flag . "','" . $CID . "','" . $SREmailArray[1] . "','" . $SREmailArray[2] . "','" . $SREmailArray[3] . "','" . $SREmailArray[4] . "','" . $SREmailArray[5] . "','C','pending','" . $SREmailArray[6]. "')");
            }

            //Share Report
            $ShareArray = explode(":", $ShareStr);
            if ($ShareArray[0] == 'Y') {

                $Share_Flag = $ShareArray[0];
                $users = !empty($ShareArray[1]) ? explode(',',$ShareArray[1]) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareArray[2];
                Helper::shareReport($CID,'C',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }


            $rv = $dDataI['Report_Row'];
            $cv = $dDataI['Report_Column'];
            $fu = ucfirst($dDataI['Report_Function']);
            $sv = $dDataI['Report_Sum'];
            $sa = $dDataI['Report_Show'];
            $list_level = $dDataI['list_level'];
            $list_short_name = $dDataI['list_short_name'];
            $sSQL = $dDataI['sql'];
            if(!empty($rv)) {
                if (strpos($rv, ',') !== false) {
                    $nrv = explode(',', $rv);
                } else {
                    $nrv[] = $rv;
                }
                Helper::generateSrPDF($nrv, $cv, $fu, $sv, $sa, $sSQL, $list_level, $list_short_name, $imgTag, $imgPath, $eFolder, $file_name, $this->prefix . 'CAM_', $SR_Attachment, $rpDesc, $report_orientation);
            }
        }
    }

    public function getSingleCampaign(Request $request, Ajax $ajax){
        $tempid = $request->input('tempid');
        $record = App\Model\CampaignTemplate::with(['rpmeta'])->where('row_id',$tempid)->first()->toArray();

        $checked_fields = !empty($record['selected_fields']) ? explode(',',$record['selected_fields']) : ['DS_MKC_ContactID'];
        $aData1 = DB::select("SELECT DISTINCT [Type] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $record['list_level'] . "'");
        $aData1 = collect($aData1)->map(function($x){ return (array) $x; })->toArray();
        //$checked_fields = [];
        $hHtml = "";
        foreach ($aData1 as $tKey => $tTypeInfo) {
            $aData2 = DB::select("SELECT DISTINCT [Field_Display] from [UL_RepCmp_Lookup_Fields] WHERE List_Level = '" . $record['list_level'] . "' AND Type = '" . $tTypeInfo['Type'] . "' AND Display_For_Select = 1");
            $aData2 = collect($aData2)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData2)) {
                $hHtml .= '<div class="col-md-2">
                        <div class="form-group">
                            <select name="'.$tTypeInfo['Type'].'" class="form-control form-control-sm chosen-select" id="s_'.$tKey.'" multiple="multiple" data-placeholder="Select '.$tTypeInfo['Type'].'">';

                foreach ($aData2 as $key => $fFieldInfo) {
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
            ->appendParam('fields_Html',$hHtml)
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
        $record = App\Model\CampaignTemplate::with(['rpschedule','rpshare','rpemail','rpschedule.sftp'])->where('t_id',$tempid)
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
            $singlecampaignid = $request->input('singlecampaignid');
            $eEvalSumRecords = DB::select('EXEC sp_CRM_Campaign_Eval_sum_Single '.$singlecampaignid);
            $eEvalDetailRecords = DB::select('EXEC sp_CRM_Campaign_Eval_detail_Single '.$singlecampaignid);

            $eEvalSumHtml = Helper::print_datatable($eEvalSumRecords);


            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadhseet = $reader->loadFromString($eEvalSumHtml);
            $sheet = $spreadhseet->getActiveSheet();
            $spreadhseet->setActiveSheetIndex(0);
            $spreadhseet->getActiveSheet()->setTitle('Evaluation Summary');

            if($eEvalDetailRecords){
                $eEvalDetailHtml = Helper::print_datatable($eEvalDetailRecords);

                $spreadhseet->createSheet();
                $spreadhseet->setActiveSheetIndex(1);
                $spreadhseet->getActiveSheet()->setTitle('Evaluation Detail');

                $spreadhseet->setActiveSheetIndex(1);
                $reader->setSheetIndex(1);
                $spreadhseet = $reader->loadFromString($eEvalDetailHtml,$spreadhseet);
            }
            $spreadhseet->setActiveSheetIndex(0);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');
            $file_Name = $this->prefix.'Single_'. date('Y') . date('m') . date('d');
            $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');
            $sBaseUrl = config('constant.BaseUrl');

            return $ajax->success()
                ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
                ->jscallback('ajax_download_file')
                ->response();
        }
        $results = self::implement_query($level,$filters);
        $view = View::make('campaign.xlsx',[
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

        $file_Name =  $this->prefix . ucfirst($level) . '_' . date('Y') . date('m') . date('d');

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
                DB::update("update UC_Campaign_Metadata set ".$fieldname." = '".$fieldvalue."' where RowID = ".$rowID);
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
        $t_id = $request->input('t_id');
        $eEvalSumRecords = DB::select('EXEC sp_CRM_Campaign_Eval_sum_Single '.$t_id);
        $eEvalDetailRecords = DB::select('EXEC sp_CRM_Campaign_Eval_detail_Single '.$t_id);

        $eEvalSumHtml = $eEvalDetailHtml= '';
        if($eEvalSumRecords){
            $eEvalSumHtml = Helper::print_datatable($eEvalSumRecords);
        }
        if($eEvalDetailRecords){
            $eEvalDetailHtml = Helper::print_datatable($eEvalDetailRecords);
        }
        return $ajax->success()
            ->appendParam('eEvalSumHtml',$eEvalSumHtml)
            ->appendParam('eEvalDetailHtml',$eEvalDetailHtml)
            ->jscallback('ajax_single_campaign')
            ->response();
    }

    public function showPhone(Request $request,Ajax $ajax){

        $campaigns = App\Model\CampaignTemplate::orderByDesc('t_id')->get(['t_id','list_short_name','t_name']);
        $content = View::make('campaign.phone-campaign',[
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
            DB::statement("EXEC [dbo].[sp_CRM_Campaign_to_Phone] ".$campaign[0].", '$campaign[1]'");
            $rRecords = DB::select("select  Touchcampaign, Touchstatus,Touchdate, count(*) as Count from touch where touchcampaign= '$campaign[1]' group by touchcampaign, touchstatus, touchdate");
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
}
