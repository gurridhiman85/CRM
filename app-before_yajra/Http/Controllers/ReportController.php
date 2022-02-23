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

class ReportController extends Controller
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

    public function index(){
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        if(!in_array('Report',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        return view('report.index');
    }

    public function getReport(Request $request,Ajax $ajax){

        $tabid = $request->input('tabid');
        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : '';
        $page = $request->input('page',1);
        $records_per_page = config('constant.record_per_page');
       /* $sort = ($sort == "") ? "Order By CampaignId DESC" : $sort == 'CampaignId' ? "Order By CampaignId DESC" : "Order By $sort $dir";

        $resolver['Objective'] = 'substring(meta_data, 1, P1.Pos - 1)';
        $resolver['Brand'] = 'substring(meta_data,  P1.Pos + 1,  P2.Pos -  P1.Pos - 1)';
        $resolver['Channel'] = 'substring(meta_data,  P2.Pos + 1,  P3.Pos -  P2.Pos - 1)';
        $resolver['Description'] = 'substring(meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1)';
        $resolver['Universe'] = 'substring(meta_data,  P4.Pos + 1,  P5.Pos -  P4.Pos - 1)';
        $resolver['Year'] = 'substring(meta_data,  P6.Pos + 1,  P7.Pos -  P6.Pos - 1)';
        $resolver['Month'] = 'substring(meta_data,  P7.Pos + 1,  P8.Pos -  P7.Pos - 1)';
        $resolver['Day'] = 'substring(meta_data,  P8.Pos + 1,  P9.Pos -  P8.Pos - 1)';*/

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
        if($tabid == 20){  // Running

            /*$query = App\Model\ReportTemplate::query()->with(['rpmeta','rpschedule.rpschstatusmap']);
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
            $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Running');
            });
            Helper::ApplyFiltersConditionForRP($filters,$query);

            $records = $query->skip($position)
                            ->take($records_per_page)
                            ->orderBy('row_id', 'DESC')
                            ->get()
                            ->toArray();

            $trQuery = App\Model\ReportTemplate::query();
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
            $trQuery->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Running');
            });
            Helper::ApplyFiltersConditionForRP($filters,$trQuery);
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
                      UR_Report_Templates za
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
                       where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'A' and st.t_type = 'A'

                       AND ss.status = 'Running') $uWhere Order By za.row_id DESC");

            $nSQL = "SELECT count(*) as cnt
				  FROM
				  UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss,
				  UR_Report_Templates za
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
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'A' and st.t_type = 'A'

				   AND ss.status = 'Running') $uWhere";

            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/
            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'running';
            if($rType == 'pagination'){
                $html = View::make('report.tabs.running.table',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('report.tabs.running.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('report.tabs.running.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/

            return $ajax->success()
               // ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 21){ // Scheduled

            /*$query = App\Model\ReportTemplate::query()->with('rpmeta','rpschedule.rpschstatusmap');
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
            $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Scheduled');
            });
            Helper::ApplyFiltersConditionForRP($filters,$query);
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get()->toArray();

            $trQuery = App\Model\ReportTemplate::query();
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
            $trQuery->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Scheduled');
            });
            Helper::ApplyFiltersConditionForRP($filters,$trQuery);
            $total_records = $trQuery->count();*/

           /* $records = DB::select("SELECT
za.t_id as ID,za.list_level as [Level],
za.list_short_name as Name,
substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description,za.is_public as 'is_public', za.Custom_SQL,
ss.sche_name as ScheduleName,ss.templ_name as TemplateName,ss.start_time as 'StartTime',ss.next_runtime as next_runtime ,ss.ftp_flag as 'FTP',za.t_type,za.row_id,
				  case ss.file_name WHEN '-' THEN '-'
				  else ss.file_path+'/'+ss.file_name
				  END as [File Name],za.row_id as [Row_id]
				  FROM
				  UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss,
				  UR_Report_Templates za
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
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'A' and st.t_type = 'A'

				   AND ss.status = 'Scheduled') $uWhere Order By ss.next_runtime DESC");

            $nSQL = "SELECT count(*) as cnt
				  FROM UR_Report_Templates za,UL_RepCmp_Schedules st,[UL_RepCmp_Status] ss
				   where (za.row_id = st.camp_tmpl_id and st.sch_status_id=ss.row_id AND za.t_type = 'A' and st.t_type = 'A'
				   AND ss.status = 'Scheduled') $uWhere";

            $all_records = DB::select($nSQL);
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/
            $sort_column = 'row_id';
            $sort_dir = 'DESC';
            $tabName = 'scheduled';
            if($rType == 'pagination'){
                $html = View::make('report.tabs.scheduled.table',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('report.tabs.scheduled.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('report.tabs.scheduled.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/

            return $ajax->success()
               // ->appendParam('records',$records)
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();

        }
        else if($tabid == 22){ // Completed

           /* $query = App\Model\ReportTemplate::query()->with(['rpschedule.rpschstatusmap']);
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
            $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Completed');
            });
            Helper::ApplyFiltersConditionForRP($filters,$query);
            $records = $query->skip($position)->take($records_per_page)->orderBy('row_id', 'DESC')->get();

            $trQuery = App\Model\ReportTemplate::query();
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
            $trQuery->whereHas('rpschedule.rpschstatusmap',function ($qry){
                $qry->where('status','Completed');
            });
            Helper::ApplyFiltersConditionForRP($filters,$trQuery);
            $total_records = $trQuery->count();*/

            /*$sSQL = "select * from (select ROW_NUMBER() over (Order By row_id DESC) as ROWNUMBER,* from (SELECT za.t_id as ID,za.list_level as [Level],
za.list_short_name as Name,za.t_name,za.sql,za.selected_fields,za.meta_data,
substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description
,sc.start_time as 'StartTime',sc.completed_time as 'RunTime',
     case sc.file_name WHEN '-' THEN '-'
          else case za.t_type WHEN 'A' THEN za.promoexpo_folder+'/".$this->prefix."RPL_'+sc.file_name else za.promoexpo_folder+'/'+sc.file_name
          END END as [List],
          (isnull(za.promoexpo_folder+'\\".$this->prefix."RPL_'+za.promoexpo_file+'.xlsx','')) as ListXLSX,
          (isnull(za.promoexpo_folder+'\\".$this->prefix."RPL_'+za.promoexpo_file+'.pdf','')) as ListPDF,
          sc.total_records as 'Records'
     ,sc.ftp_flag as 'FTP',za.is_public as 'is_public', za.Custom_SQL , (isnull(za.promoexpo_folder+'/".$this->prefix."RPS_'+za.promoexpo_file+'.pdf','')) as SummaryPDF
, (isnull(za.promoexpo_folder+'/".$this->prefix."RPS_'+za.promoexpo_file+'.xlsx','')) as SummaryXLSX,
substring(za.meta_data, P11.Pos + 1, P12.Pos - P11.Pos - 1) as Action,za.row_id,
za.t_type,za.Report_Row,za.Report_Column,za.Report_Function,za.Report_Sum,za.Report_Show,za.Chart_Type,za.Axis_Scale,za.Label_Value
from UL_RepCmp_Completed sc,UR_Report_Templates za
where (sc.camp_id = za.t_id AND za.t_type = 'A') $uWhere";
            if (isset($_POST['obj'])) {
                $sSQL .= " and " . $resolver[$_POST['col']] . " = '" . $_POST['obj'] . "' ";
            }
            $sSQL .= ") a ) _myResults  $sWhere1";
            $records = DB::select($sSQL);*/

            /*$nSQL = "select count(*) as cnt from (select ROW_NUMBER() over (Order By row_id DESC) as ROWNUMBER,* from (SELECT za.t_id as ID,za.list_level as [Level],
za.list_short_name as Name,
substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description
,sc.start_time as 'StartTime',sc.completed_time as 'RunTime',
     case sc.file_name WHEN '-' THEN '-'
          else case za.t_type WHEN 'A' THEN za.promoexpo_folder+'/RPL_'+sc.file_name else za.promoexpo_folder+'/'+sc.file_name
          END END as [List],sc.total_records as 'Records',sc.succ_flag as 'Run'
     ,sc.ftp_flag as 'FTP',za.is_public as 'is_public',
za.Report_Row , (isnull(za.promoexpo_folder+'/RPS_'+za.promoexpo_file+'.pdf','')) as Summary,
substring(za.meta_data, P11.Pos + 1, P12.Pos - P11.Pos - 1) as Action,za.row_id,
za.t_type
from UL_RepCmp_Completed sc,UR_Report_Templates za

where (sc.camp_id = za.t_id AND za.t_type = 'A') $uWhere";
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
                $html = View::make('report.tabs.level1.table',[
                   // 'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    //'filters' => $filters,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('report.tabs.level1.index',[
                    //'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName,
                    //'filters' => $filters,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('report.tabs.level1.pagination-html',[
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
        else if($tabid == 23 || $tabid == 24){
            $list_levels = DB::select("select * from  UL_RepCmp_Lookup_Level_Camp");
            $html = View::make('report.tabs.create.new-v1',[
                'tabid' => $tabid,
                'list_levels' => $list_levels
            ])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->jscallback('load_ajax_tab')
                ->response();
        }

        else if ($tabid == 25){
            $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';
            $query = App\Model\ReportTemplate::query()->with('rpschedule.rpschstatusmap');
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


            $query->whereHas('rpschedule.rpschstatusmap',function ($qry) use($txtSearch){
                $qry->whereIn('status',['Completed','Child']);
            });
            Helper::ApplyFiltersConditionForRP($filters,$query,true);
            $query->where('row_id',$request->row_id);
            $records = $query->skip($position)
                            ->take($records_per_page)
                            ->orderBy('row_id', 'DESC')
                            ->get();
            //print_r($records); die;
            $trQuery = App\Model\ReportTemplate::query();
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
            $trQuery->whereHas('rpschedule.rpschstatusmap',function ($qry) use ($txtSearch){
                $qry->whereIn('status',['Completed','Child']);
            });
            Helper::ApplyFiltersConditionForRP($filters,$trQuery,true);
            $trQuery->where('row_id',$request->row_id);
            $total_records = $trQuery->count();

            $tabName = 'older';
            if($rType == 'pagination'){
                $html = View::make('report.tabs.older_versions.table',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }else{
                $html = View::make('report.tabs.older_versions.index',[
                    'records' => $records,
                    'uid' => $uid,
                    'prefix' => $this->prefix,
                    'tab' => $tabName
                ])->render();
            }

            $paginationhtml = View::make('report.tabs.older_versions.pagination-html',[
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
        else if($tabid == 26) {
            $html = View::make('report.tabs.create.execute', ['tabid' => $tabid])->render();

            return $ajax->success()
                ->appendParam('html', $html)
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

        $query = App\Model\ReportTemplate::query()->with(['rpmeta','rpschedule.rpschstatusmap']);
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
        $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
            $qry->where('status','Running');
        });
        Helper::ApplyFiltersConditionForRP($filters,$query);
        //$query->orderBy($sort_column, $sort_dir);

        //dd($sSql);
        return Datatables::of($query)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags($category);
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
                $rpstatus = !empty($data['rpschedule']['rpschstatusmap'][0]) ? $data['rpschedule']['rpschstatusmap'] : [];
                $start_date = !empty($rpstatus) ? $rpstatus[0]['start_time'] : date('Y-m-d h:i');
                $dDatePart = explode(" ", $start_date);
                $tTimePart = explode(":", $dDatePart[1]);

                return $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
            })
            ->addColumn('next_runtime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['rpschstatusmap'][0]) ? $data['rpschedule']['rpschstatusmap'] : [];
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

        $query = App\Model\ReportTemplate::query()->with('rpmeta','rpschedule.rpschstatusmap');
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
        $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
            $qry->where('status','Scheduled');
        });
        Helper::ApplyFiltersConditionForRP($filters,$query);
        //$query->orderBy($sort_column, $sort_dir);

        //dd($sSql);
        return Datatables::of($query)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags($category);
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
                $rpstatus = !empty($data['rpschedule']['rpschstatusmap'][0]) ? $data['rpschedule']['rpschstatusmap'] : [];
                $start_date = !empty($rpstatus) ? $rpstatus[0]['start_time'] : date('Y-m-d h:i');
                $dDatePart = explode(" ", $start_date);
                $tTimePart = explode(":", $dDatePart[1]);

                return $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
            })
            ->addColumn('next_runtime',function ($data){
                $data= collect($data)->map(function($x){ return (array) $x; })->toArray();
                $rpstatus = !empty($data['rpschedule']['rpschstatusmap'][0]) ? $data['rpschedule']['rpschstatusmap'] : [];
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
            ->rawColumns(['Description','Schedule_type','StartTime','next_runtime','action'])
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

        $query = App\Model\ReportTemplate::query()->with(['rpschedule.rpschstatusmap']);
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
        $query->whereHas('rpschedule.rpschstatusmap',function ($qry){
            $qry->where('status','Completed');
        });
        Helper::ApplyFiltersConditionForRP($filters,$query);
        //$query->orderBy($sort_column, $sort_dir);

        //dd($sSql);
        return Datatables::of($query)
            ->addColumn('Description',function ($data){
                $category = isset($data->rpmeta->Category) ? $data->rpmeta->Category : '';
                if(!empty($category)){
                    $category = strip_tags($category);
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
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $total_records = isset($rpschstatusmap[0]['total_records']) ? number_format($rpschstatusmap[0]['total_records']) : 0;
                return $total_records;
            })
            ->addColumn('listXLSX',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $ListXLSX = $data->promoexpo_folder . '\\' . $this->prefix . 'RPL_' . $rpschstatusmap[0]['file_name'] . '.xlsx';
                $list1 = '';
                if (!empty($ListXLSX) && file_exists(public_path($ListXLSX))){
                    $list1 .='<a class="btn no-border font-16 p-0" download href = "'.$ListXLSX.'" title = "Download" id = "DownloadBtn" >
                    <i class="fas fa-file-excel" style = "color: #06b489;" ></i >
                </a >';
                }
                return $list1;
            })
            ->addColumn('listPDF',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $ListPDF = $data->promoexpo_folder.'\\' . $this->prefix . 'RPL_'.$rpschstatusmap[0]['file_name'].'.pdf';
                $list1 = '';
                if (!empty($ListPDF) && file_exists(public_path($ListPDF))){
                    $list1 .='<a class="btn no-border font-16 p-0" download href = "'.$ListPDF.'" title = "Download" id = "DownloadBtn" > <i class="fas fa-file-pdf" style="color: #e92639;" ></i ></a >
                <div class="checkbox">
                    <input id="{!! $ListPDF !!}" type="checkbox" class="po_status" value="'.$data->row_id.'" onchange="mPdfChecked($(this),\'list\');"/>
                    <label for="'.$ListPDF.'" style="margin-bottom: 16px;"></label>

                    <div class="space"></div>
                </div>';
                }
                return $list1;
            })
            ->addColumn('ver',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $ver = '';
                if(isset($rpschstatusmap) && count($rpschstatusmap) > 1){
                    $ver .='<a href="javascript:void(0);" onclick="showOldReport(\''.$data->row_id.'\')">
                <i class="fas fa-align-justify"></i>
            </a>';
                }
                return $ver;
            })
            ->addColumn('SummaryXLSX',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $SummaryXLSX = $data->promoexpo_folder.'\\'.$this->prefix.'RPS_'.$rpschstatusmap[0]['file_name'].'.xlsx';
                $rpt = '';
                if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX))){
                    $rpt .= '<a class="btn no-border font-16 p-0" download href="'.$SummaryXLSX.'" download title="Download" id="DownloadBtn"><i class="fas fa-file-excel" style="color: #06b489;"></i></a>';
                }
                return $rpt;
            })->addColumn('SummaryPDF',function ($data) {
                $rpschstatusmap = isset($data->rpschedule->rpschstatusmap) ? $data->rpschedule->rpschstatusmap : [];
                $SummaryPDF = $data->promoexpo_folder.'\\'.$this->prefix.'RPS_'.$rpschstatusmap[0]['file_name'].'.pdf';
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
                $em_report_json = json_encode(['row_id' => $data->row_id,'t_id' => $data->t_id,'list_level' => $data->list_level,'list_short_name' => $data->list_short_name,'t_name' => $data->t_name,'sql' => base64_encode($data->sql),'selected_fields' => $data->selected_fields,'meta_data' => $data,'Report_Row' => $data->Report_Row,'Report_Column' => $data->Report_Column,'Report_Function' => $data->Report_Function,'Report_Sum' => $data->Report_Sum,'Report_Show' => $data->Report_Show,'Chart_Type' => trim($data->Chart_Type),'Axis_Scale' => $data->Axis_Scale,'Label_Value' => $data->Label_Value]);
                $run = '<div class="checkbox">
                    <input id="'.$data->t_id .'" type="checkbox" class="em_report" value='.$em_report_json.'/>
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
            ->rawColumns(['Description','listXLSX','listPDF','ver','SummaryXLSX','SummaryPDF','run','action'])
            ->make(true);
    }

	public function reSchedule(){
        return view('report.tabs.create.outer-schedule');
    }

    public function getList(Request $request, Ajax $ajax){
        $cSQL = DB::select("SELECT t_name From UR_Report_Templates");
        $aData= collect($cSQL)->map(function($x){ return (array) $x; })->toArray();
        return $ajax->success()
            ->appendParam('list',$aData)
            ->response();
    }

    public function getSeq(Request $request, Ajax $ajax){
        $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UR_Report_Sequence]');
        $aData= collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();
        $cid = 0;
        if(!empty($aData)){
            $cid = $aData[0]['cid'];

            $arSQL = DB::select('SELECT count([t_id]) as cnt FROM [UR_Report_Templates] WHERE t_id = '.$cid);
            $arData= collect($arSQL)->map(function($x){ return (array) $x; })->toArray();
            if($arData[0]['cnt'] == 0){
                return $ajax->success()
                    ->appendParam('cid',$cid)
                    ->response();
            }
        }
        $cid = $cid + 1;
        DB::update("UPDATE [UR_Report_Sequence] SET [camp_id] = " .$cid);
        return $ajax->success()
            ->appendParam('cid',$cid)
            ->response();
    }

    public function arSchData(Request $request, Ajax $ajax){
        //echo '<pre>'; print_r($request->all()); die;
        define('UPLOAD_DIR', public_path().'\\'.'Chart_Images\\');
        $pgaction = $request->input('pgaction');

        if ($pgaction == 'Sch_campaign1') {

            $filterVal = $request->input('filterVal');
            $customerExclusionVal = $request->input('customerExclusionVal');
            $customerInclusionVal = $request->input('customerInclusionVal');
            $CID = $request->input('CID');
            $uid = Auth::user()->User_ID;
            $LSD = $request->input('LSD');
            $DFS = $request->input('DFS');
            $noLS = $request->input('noLS');
            $lssm = $request->input('lssm');
            $lssc = $request->input('lssc');
            $noCG = $request->input('noCG');
            $cg = $request->input('cg');
            $CGD = $request->input('CGD');
            $proporation = $request->input('proporation');
            $sel_criteria = $request->input('sel_criteria');
            $cellSample = $request->input('cellSample');
            $saveCD = $request->input('saveCD');
            $CGOpt = $request->input('CGOpt');
            $eData = $request->input('eData');

            $ftp_data = $request->input('ftp_data');
            //$ftpData = $request->input('ftpData');
            $SFTP_Attachment = $request->input('SFTP_Attachment');
            $SR_Attachment = $request->input('SR_Attachment');
            $SREmailData = $request->input('SREmailData');
            $ShareData = $request->input('ShareData');
            $rtype = $request->input('rtype');
            $saveFile = $request->input('saveFile');
            $SMTPStr = $request->input('SMTPStr');

            $params = json_decode($request->input('params'));
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

            //$filter_condition = str_replace("'", "''", $params->filter_condition);
            ///$Customer_Exclusion_Condition = str_replace("'", "''", $params->Customer_Exclusion_Condition);
            //$Customer_Inclusion_Condition = str_replace("'", "''", $params->Customer_Inclusion_Condition);

            $filter_condition = $params->filter_condition;
            $Customer_Exclusion_Condition = $params->Customer_Exclusion_Condition;
            $Customer_Inclusion_Condition = $params->Customer_Inclusion_Condition;

            /*$metaStr = $params->metaStr;
            $upMetaStr = explode('^',$metaStr);
            $upMetaStr[6] = date('Y');
            $upMetaStr[7] = date('m');
            $upMetaStr[8] = date('d');
            $rpDesc = $params->Category;
            $metaStr = implode('^',$upMetaStr);*/


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

            $filterVal = str_replace("'", "@", $filterVal);
            $customerExclusionVal = str_replace("'", "@", $customerExclusionVal);
            $customerInclusionVal = str_replace("'", "@", $customerInclusionVal);
            $date1 = date("m/d/y  H:i:s", time());
            $eFolder = $is_public == 'Y' ? 'Public' : 'Private';
            $eFile = $t_name;
            $eExt = 'xlsx';
            /*$insSQL = "INSERT INTO [UR_Report_Templates]([t_id],[User_ID],[t_name],[t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],[Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],[Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method]
,[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
,[seg_camp_grp_proportion],[seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt]
,[promoexpo_folder],[promoexpo_file],[promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],[meta_data],[Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation])
VALUES
('$CID','$uid','$CName','A','$listShortName','$list_level','$list_fields','$filterVal','$customerExclusionVal','$customerInclusionVal','$filter_condition','$Customer_Exclusion_Condition','$Customer_Inclusion_Condition','$selected_fields','$sSQL','$DFS','$noLS','$lssm'
,'$lssc','$LSD','$noCG','$cg','$CGD','$proporation','$sel_criteria','$cellSample','$saveCD','Y'
,'$eFolder','$eFile','$eExt','$CGOpt','$eData','$metaStr','$rv','$cv','$fu','$sv','$sa','$is_public','$custom_sql','$ct','$cI','$as','$lv','$SR_Attachment','$list_format','$report_orientation')";

            DB::insert($insSQL);*/

            $report = new App\Model\ReportTemplate();
            $report->t_id                         = $CID;
            $report->User_ID                      = $uid;
            $report->t_name                       = $CName;
            $report->t_type                       = 'A';
            $report->list_short_name              = $listShortName;
            $report->list_level                   = $list_level;
            $report->list_fields                  = $list_fields;
            $report->filter_criteria              = $filterVal;
            $report->Customer_Exclusion_Criteria  = $customerExclusionVal;
            $report->Customer_Inclusion_Criteria  = $customerInclusionVal;
            $report->filter_condition             = $filter_condition;
            $report->Customer_Exclusion_Condition = $Customer_Exclusion_Condition;
            $report->Customer_Inclusion_Condition = $Customer_Inclusion_Condition;
            $report->selected_fields              = $selected_fields;
            $report->sql                          = $sSQL;
            $report->seg_def                      = $DFS;
            $report->seg_noLS                     = $noLS;
            $report->seg_method                   = $lssm;
            $report->seg_criteria                 = $lssc;
            $report->seg_selected_criteria        = $LSD;
            $report->seg_grp_no                   = $noCG;
            $report->seg_ctrl_grp_opt             = $cg;
            $report->seg_camp_grp_dtls            = $CGD;
            $report->seg_camp_grp_proportion      = $proporation;
            $report->seg_camp_grp_sel_cri         = $sel_criteria;
            $report->seg_sample                   = $cellSample;
            $report->promoexpo_cd_opt             = $saveCD;
            $report->promoexpo_file_opt           = 'Y';
            $report->promoexpo_folder             = $eFolder;
            $report->promoexpo_file               = $t_name;
            $report->promoexpo_ext                = $eExt;
            $report->promoexpo_ecg_opt            = $CGOpt;
            $report->promoexpo_data               = $eData;
            $report->Report_Row                   = $rv;
            $report->Report_Column                = $cv;
            $report->Report_Function              = $fu;
            $report->Report_Sum                   = $sv;
            $report->Report_Show                  = $sa;
            $report->is_public                    = $is_public;
            $report->Custom_SQL                   = $custom_sql;
            $report->Chart_Type                   = $ct;
            $report->Chart_Image                  = $cI;
            $report->Axis_Scale                   = $as;
            $report->Label_Value                  = $lv;
            $report->SR_Attachment                = $SR_Attachment;
            $report->List_Format                  = $list_format;
            $report->Report_Orientation           = $report_orientation;
            $report->save();

            $rpDesc = $params->Category;
            $metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $params->CampaignID;
            $metadata->Type        = $params->Type;
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

            if (isset($ftp_data['enable']) && $ftp_data['enable'] == 'Y') {

                if (!isset($ftp_data['ftp_id'])) {
                    //$ftpArray = explode(":", $ftpData);
                    DB::insert("INSERT INTO [UL_RepCmp_SFTP]([ftp_temp_name],[ftp_site_name],[ftp_host_address],[ftp_port_no]
                       ,[ftp_user_name],[ftp_password],[folder_loc],[site_type]) VALUES
                        ('".$ftp_data['ftp_name']."','".$ftp_data['ftp_name']."','".$ftp_data['host']."','".$ftp_data['port']."','".$ftp_data['user']."','".$ftp_data['pass']."','".$ftp_data['loc']."','".$ftp_data['site_type']."')");

                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_temp_name = '".$ftp_data['ftp_name']."'");
                    $aData= collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                }else{
                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_id = ".$ftp_data['ftp_id']);
                    $aData= collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                }

                $ftp_id = $aData[0]['row_id'];
            } else
                $ftp_id = 0;
            // FTP Details
            $file_name = $listShortName . "_" . date("Ymd", time());
            if ($saveFile == 'Y')
                $dbfilename = $file_name;
            else
                $dbfilename = '-';

            if (($rtype == 'RA') || ($rtype == 'RP') || ($rtype = 'RI')) {

                $SQL = DB::select("Select [row_id],[sql] from [UR_Report_Templates] Where t_name = '$CName' AND User_ID = '$uid' AND t_type='A'");
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
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftp_id','$Camp_temp_id','A','$SFTP_Attachment')");


                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'A'");

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
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';

                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','$date1','$RA_Dt $RA_time','','$dbfilename','','".$ftp_data['enable']."','Scheduled','$eFolder','','A')");

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
                    $metadata_date = $params->Start_Date;

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftp_id','$Camp_temp_id',1,'$mo','$r_Str','$metadata_date','A','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'A'");


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

                    /*$fh = fopen( $this->filePath.'arschedule.bat', 'w' );
                    fclose($fh);

                    $fhead = fopen($this->filePath."arschedule.bat", 'a');
                    $command = "php artisan arSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);*/

                    /*$fhead = fopen($this->filePath."arschedule.bat", 'a');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);*/
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
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
                               VALUES ('$sName','$CName','$date1','$next_runDate $rp_run_time','','$dbfilename','','".$ftp_data['enable']."','Scheduled','$eFolder','$rp_end_date $rp_run_time','A')");
                    //Insert into UL_RepCmp_Status table
                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftp_id','$Camp_temp_id','A','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'A'");

                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$dbfilename','','Running','$eFolder','".$ftp_data['enable']."','A')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'A'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'A'");
                //Status update to Scheduled

                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'A')");

            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {

                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];

                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'A'");
            }

            //Send Report via Email Details
            //$SREmailArray = explode(":", $SREmailStr);
            if (isset($SREmailData['enable']) && $SREmailData['enable'] == 'Y') {

                //$Email_Flag = $SREmailArray[0];
                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $SREmailData['enable'] . "','" . $CID . "','" . $SREmailData['to'] . "','" . $SREmailData['cc'] . "','" . $SREmailData['bcc'] . "','" . $SREmailData['sub'] . "','" . $SREmailData['message'] . "','A','pending','" . $SREmailData['Email_Attachment']. "')");
            }

            //Share Report
           // $ShareArray = explode(":", $ShareStr);
            if (isset($ShareData['enable']) && $ShareData['enable'] == 'Y') {
                $Share_Flag = $ShareData['enable'];
                $users = !empty($ShareData['to']) ? explode(',',$ShareData['to']) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareData['message'];
                Helper::shareReport($CID,'A',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }

            Helper::generateSrPDF($rv,$cv,ucfirst($fu),$sv,$sa,$sSQL,$list_level,$listShortName,$imgTag,$imgPath,$eFolder,$file_name,$this->prefix . 'RPS_',$SR_Attachment,$rpDesc,$report_orientation);
        }

        else if ($pgaction == 'ReSch_campaign') {
            $CID = $request->input('CID');
            $ftp_data = $request->input('ftp_data');
            //$ftpData = $request->input('ftpData');
            $SFTP_Attachment = $request->input('SFTP_Attachment');
            $SR_Attachment = $request->input('SR_Attachment');
            $SREmailData = $request->input('SREmailData');
            $ShareData = $request->input('ShareData');
            $rtype = $request->input('rtype');
            $SMTPStr = $request->input('SMTPStr');

            $sSqlCheck = DB::select("SELECT * FROM [UR_Report_Templates] WHERE t_id = '$CID' AND t_type='A'");
            $dDataI= collect($sSqlCheck)->map(function($x){ return (array) $x; })->toArray();
            if (empty($dDataI)) {
                return $ajax->fail()
                    ->message('Report doesn\'t exist')
                    ->jscallback()
                    ->response();
            }

            $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UR_Report_Sequence]');
            $aData = collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();

            if(!empty($aData)){
                $campaign_id = $aData[0]['cid'];

            }
            $campaign_id = $campaign_id + 1;
            DB::update("UPDATE [UR_Report_Sequence] SET [camp_id] = " .$campaign_id);

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
            $upMetaStr[8] = date('d');*/

            //$rpDesc = $upMetaStr[3];

            //$metaStr = implode('^',$upMetaStr);
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

            DB::insert("INSERT INTO [UR_Report_Templates] ([t_id],[User_ID],[DCampaignID], [t_name],[t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation] )  
		   SELECT $campaign_id as [t_id],'$uid' as [User_ID],[DCampaignID], 
		  '$t_name' as [t_name],'A' as [t_type],[list_short_name],[list_level],[list_fields],[filter_criteria],
		  [Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],
		  [Customer_Inclusion_Condition],[selected_fields],[sql],[seg_def],[seg_noLS],[seg_method],[seg_criteria],
		  [seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls],[seg_camp_grp_proportion],
		  [seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],'$t_name' as [promoexpo_file],
		  [promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data],
		  [Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[is_public],[Custom_SQL],[Chart_Type],'$imgPath' as [Chart_Image],[Axis_Scale],[Label_Value],'$SR_Attachment' as [SR_Attachment],[List_Format],[Report_Orientation] FROM [UR_Report_Templates] 
		  WHERE  t_id = '$CID' AND t_type='A'");




            $rpDesc = $params->Category;
            $metadata = new App\Model\RepCmpMetaData();
            $metadata->CampaignID  = $campaign_id;
            $metadata->Type        = 'A';
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

            if (isset($ftp_data['enable']) && $ftp_data['enable'] == 'Y') {

                if (!isset($ftp_data['ftp_id'])) {
                    //$ftpArray = explode(":", $ftpData);
                    DB::insert("INSERT INTO [UL_RepCmp_SFTP]([ftp_temp_name],[ftp_site_name],[ftp_host_address],[ftp_port_no]
                       ,[ftp_user_name],[ftp_password],[folder_loc],[site_type]) VALUES
                        ('".$ftp_data['ftp_name']."','".$ftp_data['ftp_name']."','".$ftp_data['host']."','".$ftp_data['port']."','".$ftp_data['user']."','".$ftp_data['pass']."','".$ftp_data['loc']."','".$ftp_data['site_type']."')");

                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_temp_name = '".$ftp_data['ftp_name']."'");
                    $aData= collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                }else{
                    $SQL = DB::select("Select [row_id]  from [UL_RepCmp_SFTP] Where ftp_id = ".$ftp_data['ftp_id']);
                    $aData= collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                }

                $ftp_id = $aData[0]['row_id'];
            } else
                $ftp_id = 0;
            // FTP Details



            if (($rtype == 'RA') || ($rtype == 'RP') || ($rtype = 'RI')) {

                // Common to Both
                $SQL = DB::select("Select [row_id],[list_short_name],[sql] from [UR_Report_Templates] Where t_name = '$CName' AND User_ID = '$uid' AND t_type='A'");
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
                                     values ('$sName','$rtype','$RA_Dt','$RA_time','$ftp_id','$Camp_temp_id','A','$SFTP_Attachment')");
                    //Insert into UL_RepCmp_Schedules table

                    $SchtempSQL = DB::select("Select [row_id],[runat_date],[runat_time] from [UL_RepCmp_Schedules] Where Schedule_Name = '$sName' AND t_type = 'A'");
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
                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.'  " /sc once /st ' . $RA_time . ' /sd ' . $RA_Dt . ' /ru Administrator';
                    // Schedule the task
                    Helper::schtask_curl($command);
                    // Schedule the task

                    //Insert into UL_RepCmp_Status table

                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time],[next_runtime]
                                       ,[completed_time],[file_name],[succ_flag],[ftp_flag],[status],[file_path],[last_runtime],[t_type])
                                       VALUES ('$sName','$CName','$date1','$RA_Dt $RA_time','','$file_name','','".$ftp_data['enable']."','Scheduled','$eFolder','','A')");
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

                    $metadata_date = $params->Start_Date;

                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[rp_start_date]
                ,[rp_end_date],[rp_run_sch],[rp_run_time],[ftp_tmpl_id],[camp_tmpl_id],[rp_count],[rp_days],[rp_months_weeks],[metadata_date],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$RP_Start_Dt','$RP_end_Dt','$rp_run_sch','$RA_time'
                                ,'$ftp_id','$Camp_temp_id',1,'$mo','$r_Str','$metadata_date','A','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id],[rp_start_date],[rp_end_date],[rp_run_time] from [UL_RepCmp_Schedules]
                                    Where Schedule_Name = '$sName' AND t_type = 'A'");
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
                    /*$fh = fopen( $this->filePath.'arschedule.bat', 'w' );
                    fclose($fh);

                    $fhead = fopen($this->filePath."arschedule.bat", 'a');
                    $command = "php artisan arSchedule:run ".$sch_id." \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath."arschedule.bat", 'a');
                    $command = "Schtasks /delete /TN ".$this->schtasks_dir."\\".$sName." /f";
                    fwrite($fhead, $command);
                    fclose($fhead);*/
                    /******************** Create Bat file - End ******************/

                    switch ($rp_run_sch) {
                        case 'daily':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir. '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . '  /z /ru Administrator';

                            break;
                        case 'weekly':
                            $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /mo ' . $mo . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /d ' . $dayStr . ' /z /ru Administrator';
                            break;
                        case 'monthly':
                            if ($dayStr == 'last')
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /m ' . $monthStr . ' /mo lastday /z /ru Administrator';
                            else
                                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date_cm . ' /m ' . $monthStr . ' /d ' . $dayStr . ' /z /ru Administrator';
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
                               VALUES ('$sName','$CName','$date1','$next_runDate $rp_run_time','','$file_name','','".$ftp_data['enable']."','Scheduled','$eFolder','$rp_end_date $rp_run_time','A')");
                    //Insert into UL_RepCmp_Status table


                }
                else if ($rtype == 'RI') {
                    $sName = "S_" . str_replace(" ", "_", $CName);
                    DB::insert("INSERT INTO [UL_RepCmp_Schedules]([Schedule_Name],[Schedule_type],[ftp_tmpl_id],[camp_tmpl_id],[t_type],[SFTP_Attachment])
                                VALUES ('$sName','$rtype','$ftp_id','$Camp_temp_id','A','$SFTP_Attachment')");

                    $SchtempSQL = DB::select("Select [row_id] from [UL_RepCmp_Schedules] Where Schedule_Name COLLATE Latin1_General_CS_AS = '$sName' AND t_type = 'A'");
                    $aData = collect($SchtempSQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $sch_id = $aData[0]['row_id'];
                    }
                    // Schedule the task
                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60 + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $sName . ' /tr "\'' . $this->phpPath . '\' -f \'' . $this->filePath . 'artisan\' arSchedule:run '.$sch_id.'  " /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    // Schedule the task
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sName','$CName','$date1','','$file_name','','Running','$eFolder','".$ftp_data['enable']."','A')");
                }  //if($rtype == 'RI')

                //Get Schdule row_id to update the status
                //   if($rtype == 'RI')
                $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sName' AND t_type = 'A'");
                /*  else
                       $SQL = "Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$CName'";*/

                $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $Sch_row_id = $aData[0]['row_id'];

                }
                //Get Schdule row_id to update the status

                //Status update to Scheduled
                //DB::update("Update UL_RepCmp_Schedules set [sch_status_id] = '" . $Sch_row_id . "' Where row_id = '" . $sch_id . "' AND t_type = 'A'");

                DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'A')");
                //Status update to Scheduled
            }  // if For Run AT or RP

            //SMTP Details
            $SMTPArray = explode(":", $SMTPStr);
            if ($SMTPArray[0] == 'Y') {
                $smtp_flag = $SMTPArray[1] . ":" . $SMTPArray[2];
                DB::update("Update UL_RepCmp_Schedules set [smtp_flag]= '" . $smtp_flag . "',[semail_to] = '" . $SMTPArray[3] . "',[semail_cc] = '" . $SMTPArray[4] . "',[semail_bcc] = '" . $SMTPArray[5] . "',[semail_sub] = '" . $SMTPArray[6] . "',[semail_comments] = '" . $SMTPArray[7] . "'
                                     ,[femail_to] = '" . $SMTPArray[8] . "',[femail_cc] = '" . $SMTPArray[9] . "',[femail_bcc] = '" . $SMTPArray[10] . "',[femail_sub] = '" . $SMTPArray[11] . "',[femail_comments] = '" . $SMTPArray[12] . "' Where row_id = '" . $sch_id . "' AND t_type = 'A'");
            }

            if (isset($SREmailData['enable']) && $SREmailData['enable'] == 'Y') {

                //$Email_Flag = $SREmailArray[0];
                DB::insert("INSERT INTO UL_RepCmp_Email ([User_id],[Email_Flag],[camp_tmpl_id],[remail_to],[remail_cc],[remail_bcc],[remail_sub],[remail_comments],[t_type],[Email_Status],[Email_Attachment]) values ('" . Auth::user()->User_ID . "','" . $SREmailData['enable'] . "','" . $CID . "','" . $SREmailData['to'] . "','" . $SREmailData['cc'] . "','" . $SREmailData['bcc'] . "','" . $SREmailData['sub'] . "','" . $SREmailData['message'] . "','A','pending','" . $SREmailData['Email_Attachment']. "')");
            }

            //Share Report
            if (isset($ShareData['enable']) && $ShareData['enable'] == 'Y') {
                $Share_Flag = $ShareData['enable'];
                $users = !empty($ShareData['to']) ? explode(',',$ShareData['to']) : [];
                $user_id = Auth::user()->User_ID;
                $limitedtextarea4 = $ShareData['message'];
                Helper::shareReport($CID,'A',$user_id,$users,$limitedtextarea4,$this->clientname,0,0);
            }


            $rv = $dDataI['Report_Row'];
            $cv = $dDataI['Report_Column'];
            $fu = ucfirst($dDataI['Report_Function']);
            $sv = $dDataI['Report_Sum'];
            $sa = $dDataI['Report_Show'];
            $list_level = $dDataI['list_level'];
            $list_short_name = $dDataI['list_short_name'];
            $sSQL = $dDataI['sql'];


            Helper::generateSrPDF($rv,$cv,$fu,$sv,$sa,$sSQL,$list_level,$list_short_name,$imgTag,$imgPath,$eFolder,$file_name,$this->prefix  . 'RPS_',$SR_Attachment,$rpDesc,$report_orientation);
        }
    }

    public function getSingleReport(Request $request, Ajax $ajax){
        $tempid = $request->input('tempid');
        /*$sSQL = DB::select("SELECT [t_id],[t_name],[list_short_name],[list_level],[list_fields],[filter_criteria],[Customer_Exclusion_Criteria],[Customer_Inclusion_Criteria],[filter_condition],[Customer_Exclusion_Condition],[Customer_Inclusion_Condition],[selected_fields],[sql],[Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[Report_Orientation],[is_public],[Custom_SQL],[Chart_Type],[Chart_Image],[Axis_Scale],[Label_Value],[SR_Attachment],[List_Format],[Report_Orientation] FROM [UR_Report_Templates] WHERE [row_id] = '$tempid'");*/

       /* $sSQL = DB::select("SELECT rt.row_id,rt.t_id,rt.t_name,rt.list_short_name,rt.list_level,rt.list_fields,rt.filter_criteria,rt.Customer_Exclusion_Criteria,
rt.Customer_Inclusion_Criteria,rt.filter_condition,rt.Customer_Exclusion_Condition,rt.Customer_Inclusion_Condition,
rt.selected_fields,rt.sql,rt.Report_Row,rt.Report_Column,rt.Report_Function,rt.Report_Sum,rt.Report_Show,rt.is_public,
rt.Custom_SQL,rt.Chart_Type,rt.Chart_Image,rt.Axis_Scale,rt.Label_Value,rt.SR_Attachment,rt.List_Format,rt.Report_Orientation,
md.Objective,md.Brand, md.Channel,md.Category,md.ListDes,md.Wave,md.Start_Date,md.Interval,md.ProductCat1,md.ProductCat2,
md.SKU,md.Coupon,md.Sort_Column,md.Sort_Order FROM [UR_Report_Templates] rt INNER JOIN [UL_RepCmp_MetaData] md ON rt.t_id = md.CampaignID AND md.type = 'A' AND
 rt.row_id = '$tempid'");
        $aData= collect($sSQL)->map(function($x){ return (array) $x; })->toArray();
        $aData = $aData[0];*/

        $record = App\Model\ReportTemplate::with(['rpmeta'])->where('row_id',$tempid)->first()->toArray();

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

    public function getExecuteData(Request $request,Ajax $ajax){
        $tempid = $request->input('tempid');
        $record = App\Model\ReportTemplate::with(['rpschedule','rpshare','rpemail','rpschedule.sftp'])->where('t_id',$tempid)
            ->first();
        return $ajax->success()
            ->appendParam('aData',$record)
            ->response();
    }
}
