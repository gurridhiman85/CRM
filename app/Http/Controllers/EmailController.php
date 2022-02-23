<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\EmailConfiguration;
use App\Model\ReportTemplate;
use Illuminate\Http\Request;
use App\Library\Ajax;
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
use Illuminate\Support\Facades\Artisan;
use Yajra\Datatables\Datatables;
use DateTime;


class EmailController extends Controller
{
    public $db;

    public function __construct()
    {
        $this->db = DB::connection('sqlsrv');
    }

    public function index(){
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        if(!in_array('Email',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        $camapigns = EmailConfiguration::orderByDesc('CampaignName')->pluck('CampaignName','CampaignId');
        return view('email.index',['camapigns' => $camapigns]);
    }

    public function getEmails(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : '';
        $page = $request->input('page',1);
        $records_per_page = config('constant.record_per_page');

        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);

        $sort_column = 'CampaignId';
        $sort_dir = 'DESC';

        if($tabid == 20){
            /*$query = EmailConfiguration::query();
            self::apply_filters($filters,$query);
            $records = $query->skip($position)
                ->take($records_per_page)
                ->orderByDesc('CampaignId')
                ->get([
                    'CampaignId',
                    'CampaignName',
                    'Template',
                    DB::raw('cast(Time1 as varchar(5)) as Time1'),
                    DB::raw('cast([StartDate] as date) as StartDate'),
                    DB::raw('cast([EndDate] as date) as EndDate'),
                    'Subject1',
                    'Subject2',
                    'Subject3',
                    'TestSubject',
                    'CampaignID_Data',
                    'TestSubjectPct',
                    'SubjectWin',
                ])
                ->toArray();
            $trQuery = EmailConfiguration::query();
            self::apply_filters($filters,$trQuery);
            $total_records = $trQuery->count();*/

            $tabName = 'Completed';
            if($rType == 'pagination'){
                $html = View::make('email.tabs.level1.table',[
                    //'records' => $records,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }else{
                $html = View::make('email.tabs.level1.index',[
                   // 'records' => $records,
                    'tab' => $tabName,
                    'sort_column' => $sort_column,
                    'sort_dir' => $sort_dir,
                ])->render();
            }

            /*$paginationhtml = View::make('email.tabs.level1.pagination-html',[
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
        else if (in_array($tabid, ['ECinsert','Proofs','deploy','TFD','ReDeploy','Re-ReDeploy','PR','CR'])){
            $camapigns = EmailConfiguration::orderByDesc('CampaignName')->pluck('CampaignName','CampaignId');


            /*$query = EmailConfiguration::query();
            self::apply_filters($filters,$query);
            $records = $query->skip($position)
                ->take($records_per_page)
                ->orderByDesc('CampaignId')
                ->get([
                    'CampaignId',
                    'CampaignName',
                    'Template',
                    DB::raw('cast(Time1 as varchar(5)) as Time1'),
                    DB::raw('cast([StartDate] as date) as StartDate'),
                    DB::raw('cast([EndDate] as date) as EndDate'),
                    'Subject1',
                    'Subject2',
                    'Subject3',
                    'TestSubject',
                    'CampaignID_Data',
                    'TestSubjectPct',
                    'SubjectWin',
                ])
                ->toArray();

            $trQuery = EmailConfiguration::query();
            self::apply_filters($filters,$trQuery);
            $total_records = $trQuery->count();*/
            $tabName = 'Completed';


            $html = View::make('email.tabs.new.index', [
                'camapigns' => $camapigns,
                'tabid' => $tabid,
                //'records' => $records,
                'rType' => $rType,
                'tabName' => $tabName,
                'sort_column' => $sort_column,
                'sort_dir' => $sort_dir,
            ])->render();

            /*$paginationhtml = View::make('email.tabs.level1.pagination-html',[
                'total_records' => $total_records,
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page,
                'tab' => $tabName
            ])->render();*/

            return $ajax->success()
                ->appendParam('html',$html)
                //->appendParam('records',$records)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }
    }

    public function getCompletedEmails(Request $request)
    {
        $filters = $request->input('filters',[]);
        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);

        $sort_column = $table_order[0]['column'] == 0 ? 'row_id' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];

        $query = EmailConfiguration::query();
        self::apply_filters($filters,$query);
        $records = $query->orderByDesc('CampaignId')
            ->get([
                'CampaignId',
                'CampaignName',
                'Template',
                DB::raw('cast(Time1 as varchar(5)) as Time1'),
                DB::raw('cast([StartDate] as date) as StartDate'),
                DB::raw('cast([EndDate] as date) as EndDate'),
                'Subject1',
                'Subject2',
                'Subject3',
                'TestSubject',
                'CampaignID_Data',
                'TestSubjectPct',
                'SubjectWin',
            ]);
        return Datatables::of($records)
            ->make(true);
    }

    public static function apply_filters($filters , $query){

        $campaignid = isset($filters['CampaignId']) ? $filters['CampaignId'][0] : '';
        $cname = isset($filters['CampaignName']) ? $filters['CampaignName'][0] : '';
        $template = isset($filters['template']) ? $filters['template'][0] : '';
        $Time1 = isset($filters['Time1']) ? $filters['Time1'][0] : '';

        $StartDate = isset($filters['StartDate']) ? $filters['StartDate'][0] : '';
        $EndDate = isset($filters['EndDate']) ? $filters['EndDate'][0] : '';
        $subject1 = isset($filters['subject1']) ? $filters['subject1'][0] : '';
        $subject2 = isset($filters['subject2']) ? $filters['subject2'][0] : '';
        $subject3 = isset($filters['subject3']) ? $filters['subject3'][0] : '';
        $testsubject = isset($filters['TestSubject']) ? $filters['TestSubject'][0] : '';
        $CampaignID_Data = isset($filters['CampaignID_Data']) ? $filters['CampaignID_Data'][0] : '';
        $TestSubjectPct = isset($filters['TestSubjectPct']) ? $filters['TestSubjectPct'][0] : '';
        $SubjectWin = isset($filters['SubjectWin']) ? $filters['SubjectWin'][0] : '';
        $txtSearch = isset($filters['searchterm']) ? $filters['searchterm'][0] : '';

        if(!empty($txtSearch)){
            $query->where(function ($qry) use ($txtSearch){
                //$qry->orWhere('CampaignId', 'like',  $txtSearch . '%');
                $qry->orWhere('CampaignName','like', '%'.$txtSearch.'%');
                $qry->orWhere('template','like', $txtSearch.'%');
                //$qry->orWhere('Time1','like', '%'.$txtSearch.'%');
                //$qry->orWhere('StartDate','like', '%'.$txtSearch.'%');
                //$qry->orWhere('EndDate','like', '%'.$txtSearch.'%');
                $qry->orWhere('subject1','like', $txtSearch.'%');
                $qry->orWhere('subject2','like', $txtSearch.'%');
                $qry->orWhere('subject3','like', $txtSearch.'%');
                $qry->orWhere('CampaignID_Data','like', $txtSearch.'%');
                //$qry->orWhere('TestSubject','like', '%'.$txtSearch.'%');
                //$qry->orWhere('TestSubjectPct','like', '%'.$txtSearch.'%');
                $qry->orWhere('SubjectWin','like', $txtSearch.'%');
            });


        }

        /*if (!empty($campaignid)) {
            $query->where(function ($qry) use ($campaignid) {
                $qry->where('CampaignId', 'like',  $campaignid . '%');
            });
        }
        if (!empty($cname)) {
            $query->where(function ($qry) use ($cname) {
                $qry->where('CampaignName', 'like',  $cname . '%');
            });
        }

        if (!empty($template)) {
            $query->where(function ($qry) use ($template) {
                $qry->where('template', 'like',  $template . '%');
            });
        }

        if (!empty($Time1)) {
            $query->where(function ($qry) use ($Time1) {
                $qry->where('Time1', 'like',  $Time1 . '%');
            });
        }

        if (!empty($StartDate)) {
            $query->where(function ($qry) use ($StartDate) {
                $qry->where('StartDate', 'like',  $StartDate . '%');
            });
        }

        if (!empty($EndDate)) {
            $query->where(function ($qry) use ($EndDate) {
                $qry->where('EndDate', 'like',  $EndDate . '%');
            });
        }

        if (!empty($subject1)) {
            $query->where(function ($qry) use ($subject1) {
                $qry->where('subject1', 'like',  $subject1 . '%');
            });
        }

        if (!empty($subject2)) {
            $query->where(function ($qry) use ($subject2) {
                $qry->where('subject2', 'like',  $subject2 . '%');
            });
        }

        if (!empty($subject3)) {
            $query->where(function ($qry) use ($subject3) {
                $qry->where('subject3', 'like',  $subject3 . '%');
            });
        }

        if (!empty($CampaignID_Data)) {
            $query->where(function ($qry) use ($CampaignID_Data) {
                $qry->where('CampaignID_Data', 'like',  $CampaignID_Data . '%');
            });
        }

        if (!empty($testsubject)) {
            $query->where(function ($qry) use ($testsubject) {
                $qry->where('TestSubject', 'like',  $testsubject . '%');
            });
        }

        if (!empty($TestSubjectPct)) {
            $query->where(function ($qry) use ($TestSubjectPct) {
                $qry->where('TestSubjectPct', 'like',  '%'.$TestSubjectPct . '%');
            });
        }

        if (!empty($SubjectWin)) {
            $query->where(function ($qry) use ($SubjectWin) {
                $qry->where('SubjectWin', 'like',  '%'.$SubjectWin . '%');
            });
        }*/

        return $query;
    }

    public function sendEmail(Request $request, Ajax $ajax){

        set_time_limit(7200);
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '2048M');
        $btnPos = $request->input('btnPos');
        $test_subject = $request->input('test_subject');
        $campaignID_data = $request->input('campaignID_data');
        $TestSubjectPct = $request->input('TestSubjectPct');
        $campaign_name = $request->input('campaign_name');
        $template = $request->input('template');
        $subject1 = $request->input('subject1');
        $subject2 = $request->input('subject2');
        $subject3 = $request->input('subject3');
        $no_seedlist = $request->input('no_seedlist');
        $is_segment = $request->input('is_segment');
        $by_segments = $request->input('by_segments');
        $input_html_id = $request->input('input_html_id');
        $is_j = $request->input('is_j');
        $input_email = $request->input('input_email');
        $input_firstname = $request->input('input_firstname');
        $response = array();
        $sqlQueries = [];
        if ($btnPos == 0) {  //Setup tab -- insert button
            $test_subject = !empty($test_subject) ? $test_subject : 0;
            $campaignID_data = !empty($campaignID_data) ? $campaignID_data : 'null';
            $TestSubjectPct = !empty($TestSubjectPct) ? $TestSubjectPct : 'null';

            $campaign_id = substr($campaign_name, 0,4);
            //dd($campaign_id);
            //$sSql0 = "SELECT count(*) as count FROM emails_configuration WHERE CampaignId = " . $campaign_id;
            //$rResult0 = $oDb->execute_qry($sSql0);
            $campaign = EmailConfiguration::where('CampaignId',$campaign_id)->count();
            if ($campaign > 0) {
                return $ajax->fail()
                    ->message('Campaign Already Exist')
                    ->jscallback()
                    ->response();
            }

            $sSql1 = "EXEC [dbo].[sp_RD_13_email_setup] 
        '" . str_replace("'", "&#39;", trim($campaign_name)) . "',
        '" . str_replace("'", "&#39;", trim($template)) . "',
        '" . str_replace("'", "'", trim($subject1)) . "',
        '" . str_replace("'", "'", trim($subject2)) . "',
        '" . str_replace("'", "'", trim($subject3)) . "',
        " . str_replace("'", "&#39;", trim($test_subject)) . ",
        $campaignID_data,
        null,
        $TestSubjectPct";

            $sqlQueries[1] = $sSql1;
            //die;

            $sSql7 = "select 
         [RowID]
              ,[CampaignId]
              ,[TemplateId]
              ,[CampaignName]
              ,[Template]
              ,cast([Time1] as varchar(5))
              ,cast([StartDate] as date) as StartDate
              ,cast([EndDate] as date) as Endate
              ,[CampaignID_Data]
              ,[TestSubject]
              ,[TestSubjectPct]
              ,[Subject1]
              ,[Subject2]
              ,[Subject3]
              ,[SubjectWin]
              ,[Custom1]
              ,[UnsubscribeGroupId]
              ,[FromEmail]
              ,[FromName]
              ,[InputTableName]
              ,[Frequency]
              ,cast([Time2] as varchar(5))
              ,cast([Time3] as varchar(5))
        from emails_configuration order by case when campaignid > 9000 then 1 else 0 end, campaignid desc";
            $sqlQueries[2] = $sSql7;
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $stmt->execute();

            $rResult7 = DB::select($sSql7);
            $rResult7 = collect($rResult7)->map(function($x){ return (array) $x; })->toArray();
            $aData7 = count($rResult7);
            //echo '<pre>'; print_r($rResult7); die;
            $sSql8 = DB::select("Select CampaignId,CampaignName FROM Emails_Configuration order by campaignname desc");
            $rResult8 = collect($sSql8)->map(function($x){ return (array) $x; })->toArray();

            if ($aData7) {
                $options = '<option value="">Select Campaign</option>';
                if (count($rResult8) > 0) {
                    foreach ($rResult8 as $data) {
                        $options .= '<option value=' . $data['CampaignId'] . '>' . $data['CampaignId'] . '-' . $data['CampaignName'] . '</option>';
                    }
                }
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData7]);
                $response['resultHtml'] = Helper::print_datatable($rResult7);
                $response['input_html_id_options'] = $options;
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

                return $ajax->success()
                    ->appendParam('response',$response)
                    ->jscallback()
                    ->response();
            } else {
                return $ajax->fail()
                    ->message('Operation failed')
                    ->jscallback()
                    ->response();
            }
        }
        if ($btnPos == 1 || $btnPos == 2 || $btnPos == 3) { // Proof's tab -- Send to DS , Send to RD Part and Send to RD Full

            $tablename = '';
            if ($btnPos == 1)
                $tablename = '[sp_RD_13_email_send_to_DS]';
            else if ($btnPos == 2)
                $tablename = '[sp_RD_13_email_send_to_RD_Part]';
            else if ($btnPos == 3) {
                $tablename = ($is_j && !empty($is_j) && $is_j == 'A') ? '[sp_RD_13_email_send_to_RD_Full_A]' : '[sp_RD_13_email_send_to_RD_Full_J]';
            }

            $sSql1 = "exec $tablename " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql10 = "select * from emails_toprocess where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql10;

            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $stmt->execute();

            $rResult10 = DB::select($sSql10);
            $rResult10 = collect($rResult10)->map(function($x){ return (array) $x; })->toArray();
            $aData10 = count($rResult10);

            if ($aData10) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData10]);
                $response['resultHtml'] = Helper::print_datatable($rResult10);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 4) {  // Deploy Tab -- Review button
            $sSql0 = "delete from emails_toprocess_s1 where campaignid = " . $input_html_id;
            $sqlQueries[0] = $sSql0;
            $extraIds = '';

            if ($no_seedlist == 1 && $is_segment == 1) {
                $sp = 'sp_RD_13_email_send_to_Deploy_NoSeedlist_BySegment_S1';
                $extraIds = !empty($by_segments) ? ", " . $by_segments . "" : '';

            } elseif ($no_seedlist == 1 && !isset($is_segment)) {
                $sp = 'sp_RD_13_email_send_to_Deploy_NoSeedlist_S1';

            } elseif (!isset($no_seedlist) && $is_segment == 1) {
                $sp = 'sp_RD_13_email_send_to_Deploy_BySegment_S1';
                $extraIds = !empty($by_segments) ? ", " . $by_segments . "" : '';

            } else {
                $sp = 'sp_RD_13_email_send_to_Deploy_S1';

            }
            //$sp = $no_seedlist == 1 ? 'sp_RD_13_email_send_to_Deploy_NoSeedlist_S1' : 'sp_RD_13_email_send_to_Deploy_S1';
            $sSql1 = "exec [dbo].[$sp] " . $input_html_id . $extraIds;

            $sqlQueries[1] = $sSql1;

            $sSql16 = "select 
         [RowID]
              ,[CampaignId]
              ,[TemplateId]
              ,[CampaignName]
              ,[Template]
              ,[Time1]
              ,cast([StartDate] as date) as StartDate
              ,cast([EndDate] as date) as Endate
              ,[CampaignID_Data]
              ,[TestSubject]
              ,[TestSubjectPct]
              ,[Subject1]
              ,[Subject2]
              ,[Subject3]
              ,[SubjectWin]
              ,[Custom1]
              ,[UnsubscribeGroupId]
              ,[FromEmail]
              ,[FromName]
              ,[InputTableName]
              ,[Frequency]
              ,[Time2]
              ,[Time3]
         from Emails_configuration where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql16;

            $sSql17 = "select count(*) as count, count(distinct(email)) as emailcount, testgroupid from Emails_ToProcess_S1 where campaignid=" . $input_html_id . " group by testgroupid";
            $sqlQueries[3] = $sSql17;

            $sSql18 = "select count(*) as count, subject, testgroupid from emails_toprocess_s1 where campaignid= " . $input_html_id . " and (testgroupid <> 0 or testgroupid is null) 
        group by subject, testgroupid";
            $sqlQueries[4] = $sSql18;

            $sSql19 = "select top 50 * from emails_toprocess_s1 where campaignid= " . $input_html_id . " and seed=1 and (testgroupid <> 0 or testgroupid is null)";
            $sqlQueries[5] = $sSql19;

            $sSql20 = "select top 50 * from emails_toprocess_s1 where campaignid= " . $input_html_id . " and (testgroupid <> 0 or testgroupid is null)";
            $sqlQueries[6] = $sSql20;

            //$aData0 = $oDb->execute_qry_row_affected($sSql0);
            $aData0 = DB::statement($sSql0);
            //$aData1 = $oDb->execute_qry_row_affected($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();


            $rResult16 = DB::select($sSql16);
            $rResult16 = collect($rResult16)->map(function($x){ return (array) $x; })->toArray();

            $rResult17 = DB::select($sSql17);
            $rResult17 = collect($rResult17)->map(function($x){ return (array) $x; })->toArray();

            $rResult18 = DB::select($sSql18);
            $rResult18 = collect($rResult18)->map(function($x){ return (array) $x; })->toArray();

            $rResult19 = DB::select($sSql19);
            $rResult19 = collect($rResult19)->map(function($x){ return (array) $x; })->toArray();

            $rResult20 = DB::select($sSql20);
            $rResult20 = collect($rResult20)->map(function($x){ return (array) $x; })->toArray();

            $aData16 = count($rResult16);
            $aData17 = count($rResult17);
            $aData18 = count($rResult18);
            $aData19 = count($rResult19);
            $aData20 = count($rResult20);

            if ($aData16) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData0,$aData1,$aData16,$aData17,$aData18,$aData19,$aData20]);
                $response['resultHtml'] = Helper::print_datatable($rResult16);
                $response['resultHtml'] .= Helper::print_datatable($rResult17);
                $response['resultHtml'] .= Helper::print_datatable($rResult18);
                $response['resultHtml'] .= Helper::print_datatable($rResult19);
                $response['resultHtml'] .= Helper::print_datatable($rResult20);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 5) {  // Deploy Tab -- Deploy Campaign button
            $sSql1 = "select 
                    [RowID]
                  ,[CampaignId]
                  ,[TemplateId]
                  ,[CampaignName]
                  ,[Template]
                  ,[Time1]
                  ,cast([StartDate] as date) as StartDate
                  ,cast([EndDate] as date) as Endate
                  ,[CampaignID_Data]
                  ,[TestSubject]
                  ,[TestSubjectPct]
                  ,[Subject1]
                  ,[Subject2]
                  ,[Subject3]
                  ,[SubjectWin]
                  ,[Custom1]
                  ,[UnsubscribeGroupId]
                  ,[FromEmail]
                  ,[FromName]
                  ,[InputTableName]
                  ,[Frequency]
                  ,[Time2]
                  ,[Time3]
                from Emails_configuration where campaignid=" . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "exec [dbo].[sp_RD_13_email_send_to_deploy_S2] " . $input_html_id;
            $sqlQueries[2] = $sSql2;


            $sSql3 = "delete from Emails_ToProcess_S1 where campaignid=" . $input_html_id . "  and (testgroupid <> 0 or testgroupid is null) ";
            $sqlQueries[3] = $sSql3;

            $sSql4 = "select top 50 * from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[4] = $sSql4;



            $rResult1 = DB::select($sSql1);
            $rResult1 = collect($rResult1)->map(function($x){ return (array) $x; })->toArray();

            $aData3 = DB::statement($sSql3);


            $rResult4 = DB::select($sSql4);
            $rResult4 = collect($rResult4)->map(function($x){ return (array) $x; })->toArray();


            //$aData2 = $oDb->execute_qry($sSql2);
            $stmt = $this->db->getPdo()->prepare($sSql2);
            $stmt->execute();

            $aData1 = count($rResult1);
            $aData4 = count($rResult4);


            if ($aData1) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData1,$aData3,$aData4]);
                $response['resultHtml'] = Helper::print_datatable($rResult1);
                $response['resultHtml'] .= Helper::print_datatable($rResult4);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 5.1) {  // Deploy Tab -- Deploy Auto execute Campaign button

            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_DeployAuto_S2] " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();

            if ($aData1) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData1]);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 6) {  // DeployAfterTest Tab -- Review button
            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_DeployAfter_test_S1] " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "select * from emails_configuration where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql2;

            $sSql3 = "select count(*) as count, subject from Emails_ToProcess_s1 where  campaignid=" . $input_html_id . "  and (testgroupid =0) group by subject";
            $sqlQueries[3] = $sSql3;

            $sSql4 = "select top 20
           [CampaignId],
           [CustomerID],
           segmentid,
           [Email],
           [Firstname] ,
           Subject,
           Custom1,
       cast(  [EmailMigrationDate] as date)
            from  [dbo].[Emails_ToProcess_S1] where campaignid=" . $input_html_id . "  and (testgroupid =0)";
            $sqlQueries[4] = $sSql4;


            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();

            $rResult2 = DB::select($sSql2);
            $rResult2 = collect($rResult2)->map(function($x){ return (array) $x; })->toArray();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $rResult4 = DB::select($sSql4);
            $rResult4 = collect($rResult4)->map(function($x){ return (array) $x; })->toArray();

            $aData2 = count($rResult2);
            $aData3 = count($rResult3);
            $aData4 = count($rResult4);

            if ($aData2) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData2,$aData3,$aData4]);
                $response['resultHtml'] = Helper::print_datatable($rResult2);
                $response['resultHtml'] .= Helper::print_datatable($rResult3);
                $response['resultHtml'] .= Helper::print_datatable($rResult4);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 7) {  // DeployAfterTest Tab -- Deploy Campaign button


            $sSql1 = "select * from emails_configuration where campaignid=" . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "exec [dbo].[sp_RD_13_email_send_to_DeployAfter_test_S2] " . $input_html_id;
            $sqlQueries[2] = $sSql2;

            $sSql3 = "select count(*) as count from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[3] = $sSql3;

            $sSql4 = "select top 20 * from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[4] = $sSql4;

            $sSql5 = "delete from Emails_ToProcess_S1 where campaignid=" . $input_html_id . "  and (testgroupid = 0 )";
            $sqlQueries[5] = $sSql5;



            //$aData2 = $oDb->execute_qry($sSql2);
            $stmt = $this->db->getPdo()->prepare($sSql2);
            $aData2 = $stmt->execute();

            $rResult1 = DB::select($sSql1);
            $rResult1 = collect($rResult1)->map(function($x){ return (array) $x; })->toArray();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $rResult4 = DB::select($sSql4);
            $rResult4 = collect($rResult4)->map(function($x){ return (array) $x; })->toArray();

            $aData1 = count($rResult1);
            $aData3 = count($rResult3);
            $aData4 = count($rResult4);

            $aData5 = DB::statement($sSql5);

            if ($aData1) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData1,$aData3,$aData4,$aData5]);
                $response['resultHtml'] = Helper::print_datatable($rResult1);
                $response['resultHtml'] .= Helper::print_datatable($rResult3);
                $response['resultHtml'] .= Helper::print_datatable($rResult4);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 8) {  // ReDeploy Tab -- Review button
            $sSql0 = "delete from emails_toprocess_s2 where campaignid = " . $input_html_id;
            $sqlQueries[0] = $sSql0;

            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_reDeploy_S1] " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "select Count(*) as count, count(distinct(email)) as count  from emails_toprocess_s2 where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql2;

            $sSql3 = "select top 50 * from emails_toprocess_s2 where campaignid=" . $input_html_id;
            $sqlQueries[3] = $sSql3;

            $aData0 = DB::statement($sSql0);

            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();

            $rResult2 = DB::select($sSql2);
            $rResult2 = collect($rResult2)->map(function($x){ return (array) $x; })->toArray();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $aData2 = count($rResult2);
            $aData3 = count($rResult3);

            if ($aData2) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData0,$aData2,$aData3]);
                $response['resultHtml'] = Helper::print_datatable($rResult2);
                $response['resultHtml'] .= Helper::print_datatable($rResult3);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 9) {  // ReDeploy Tab -- Deploy Campaign button

            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_reDeploy_S2] " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "select count(*) as count from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql2;

            $sSql3 = "select top 50 * from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[3] = $sSql3;

            $sSql4 = "delete from [dbo].[Emails_ToProcess_S2] where campaignid=" . $input_html_id;
            $sqlQueries[4] = $sSql4;

            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();

            $rResult2 = DB::select($sSql2);
            $rResult2 = collect($rResult2)->map(function($x){ return (array) $x; })->toArray();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $aData2 = count($rResult2);
            $aData3 = count($rResult3);
            $aData4 = DB::statement($sSql4);

            if ($aData2) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData2,$aData3,$aData4]);
                $response['resultHtml'] = Helper::print_datatable($rResult2);
                $response['resultHtml'] .= Helper::print_datatable($rResult3);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 10) { // ProcessReport tab
            $sSql1 = "select  (SELECT count(*)   FROM [Emails_ToProcess]   where    campaignid=" . $input_html_id . ")  as Toprocess  
    ,(SELECT count(distinct(email))  FROM [Emails_Processed] where     campaignid=" . $input_html_id . ")  as Processed
    ,(Select count(*)  FROM email_detail  where     campaignid='" . $input_html_id . "'  and event='delivered')  as Delivered
    ,(Select count(distinct(email))  FROM email_detail where  campaignid='" . $input_html_id . "') as OnDetailTable";
            $sqlQueries[1] = $sSql1;

            $rResult1 = DB::select($sSql1);
            $rResult1 = collect($rResult1)->map(function($x){ return (array) $x; })->toArray();

            $aData1 = count($rResult1);


            if ($aData1) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData1]);
                $response['resultHtml'] = Helper::print_datatable($rResult1);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);;

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 11) { // CampaignReport tab
            $sSql1 = "SELECT
        min(case when event in ('processed') then date else null end) as Date
        ,campaignname
        , count(distinct(email)) as sent
        , count(distinct(case when event='delivered' then email else null end)) as delivered
        , count(case when event='open' then email else null end) as opens
        , count(distinct(case when event='open' then email else null end)) as unique_opens
        , count(case when event='click' then email else null end) as clicks
        , count(distinct(case when event='click' then email else null end)) as unique_clicks
        , count(distinct(case when event like '%spam%' then email else null end)) as unique_spam
        , count(distinct(case when event like '%unsub%'  then email else null end)) as unique_unsubs
          FROM [Email_Detail]
          where campaignid='" . $input_html_id . "'
          and date  > '2018-08-01'   
          group by
         campaignname
        order by
        campaignname desc";
            $sqlQueries[1] = $sSql1;

            $rResult1 = DB::select($sSql1);
            $rResult1 = collect($rResult1)->map(function($x){ return (array) $x; })->toArray();

            $aData1 = count($rResult1);

            $response['success'] = true;
            $response['message'] = 'Successfully';
            $response['html'] = Helper::messageTabContent([$aData1]);
            $response['resultHtml'] = Helper::print_datatable($rResult1);
            $response['Sqls'] = Helper::queryTabContent($sqlQueries);;
        }
        else if ($btnPos == 12) {  //Re-redeploy button s1
            $sSql0 = "delete from emails_toprocess_s3 where campaignid = " . $input_html_id;
            $sqlQueries[0] = $sSql0;

            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_ReRedeploy_S1] " . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "select Count(*) as count, count(distinct(email)) as count  from emails_toprocess_s3 where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql2;

            $sSql3 = "select top 50 * from emails_toprocess_s3 where campaignid=" . $input_html_id;
            $sqlQueries[3] = $sSql3;


            $aData0 = DB::statement($sSql0);

            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();


            $rResult2 = DB::select($sSql2);
            $rResult2 = collect($rResult2)->map(function($x){ return (array) $x; })->toArray();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $aData2 = count($rResult2);
            $aData3 = count($rResult3);



            if ($aData2) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData0,$aData2,$aData3]);
                $response['resultHtml'] = Helper::print_datatable($rResult2);
                $response['resultHtml'] .= Helper::print_datatable($rResult3);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 13) {  //Re-redeploy button s2
            $sSql1 = "update emails_configuration set time1     = CONVERT (time, GETDATE()) where campaignid=" . $input_html_id;
            $sqlQueries[1] = $sSql1;

            $sSql2 = "exec [dbo].[sp_RD_13_email_send_to_ReRedeploy_S2] " . $input_html_id;
            $sqlQueries[2] = $sSql2;


            $sSql3 = "select count(*) as count from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[3] = $sSql3;

            $sSql4 = "select top 50 * from Emails_ToProcess where  campaignid=" . $input_html_id;
            $sqlQueries[4] = $sSql4;

            $sSql5 = "delete from [dbo].[Emails_ToProcess_S3] where campaignid=" . $input_html_id;
            $sqlQueries[5] = $sSql5;


            $aData1 = DB::update($sSql1);

            //$aData2 = $oDb->execute_qry($sSql2);
            $stmt = $this->db->getPdo()->prepare($sSql2);
            $aData2 = $stmt->execute();

            $rResult3 = DB::select($sSql3);
            $rResult3 = collect($rResult3)->map(function($x){ return (array) $x; })->toArray();

            $rResult4 = DB::select($sSql4);
            $rResult4 = collect($rResult4)->map(function($x){ return (array) $x; })->toArray();

            $aData3 = count($rResult3);
            $aData4 = count($rResult4);

            $aData5 = DB::statement($sSql5);

            if ($aData1) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData1,$aData3,$aData4,$aData5]);
                $response['resultHtml'] = Helper::print_datatable($rResult3);
                $response['resultHtml'] .= Helper::print_datatable($rResult4);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        else if ($btnPos == 14) {  //Send to custom

            $sSql1 = "exec [dbo].[sp_RD_13_email_send_to_email] " . $input_html_id . ",'" . $input_email . "','Test - " . $input_firstname . "'";
            $sqlQueries[1] = $sSql1;


            $sSql2 = "select * from emails_toprocess where campaignid=" . $input_html_id;
            $sqlQueries[2] = $sSql2;

            //$aData1 = $oDb->execute_qry($sSql1);
            $stmt = $this->db->getPdo()->prepare($sSql1);
            $aData1 = $stmt->execute();

            $rResult2 = DB::select($sSql2);
            $rResult2 = collect($rResult2)->map(function($x){ return (array) $x; })->toArray();

            $aData2 = count($rResult2);


            if ($aData2) {
                $response['success'] = true;
                $response['message'] = 'Successfully';
                $response['html'] = Helper::messageTabContent([$aData2]);
                $response['resultHtml'] = Helper::print_datatable($rResult2);
                $response['Sqls'] = Helper::queryTabContent($sqlQueries);

            } else {
                $response['success'] = false;
                $response['message'] = 'Operation failed';
            }
        }
        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback()
            ->response();
    }

    public function countToProcess(Request $request, Ajax $ajax){
        $sqlQueries = [];
        $sSql = "select count(*) as Count from emails_toprocess";
        $rResult = DB::select($sSql);
        $rResult = collect($rResult)->map(function($x){ return (array) $x; })->toArray();
        $sqlQueries[1] = $sSql;
        $aData0 = count($rResult);

        if ($rResult) {
            $response['success'] = true;
            $response['message'] = 'Successfully';
            $response['html'] = Helper::messageTabContent([$aData0]);
            $response['resultHtml'] = Helper::print_datatable($rResult);
            $response['Sqls'] = Helper::queryTabContent($sqlQueries);
            $response['completefn'] = 'ajax_db_queries_result';

        } else {
            $response['success'] = false;
            $response['message'] = 'Operation failed';
        }
        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function todayCampaigns(Request $request, Ajax $ajax){
        $sqlQueries = [];
        $sSql = "select Processed, Delivered, distinct_processed as [Distinct Processed] ,distinct_delivered as [Distinct Delivered] , c.campaignid as [CampaignID], c.campaignname as [Campaign Name], Subject1 , format(startdate, 'yyyy-MM-d') as [Start Date], format(time1, 'hh:mm:ss') as Time1, format(enddate, 'yyyy-MM-d') as [End Date] from
(select campaignid, campaignname, subject1 , startdate, time1, enddate from emails_configuration) c
left join (select count(*) as processed,  count(distinct(email)) as distinct_processed,    campaignid   from emails_processed   group by campaignid)    e3 on e3.campaignid=c.campaignid
left join (select count(*) as delivered, count(distinct(email)) as distinct_delivered,     campaignname from email_detail        where  event='delivered'
and campaignname in (select distinct campaignname from  emails_configuration where cast(startdate as date)=cast(getdate() as date)) group by campaignname)    e4 on e4.campaignname=c.campaignname
where cast(c.startdate as date)=cast(getdate()  as date) and processed is not null
order by startdate, time1";
        $rResult = DB::select($sSql);
        $rResult = collect($rResult)->map(function($x){ return (array) $x; })->toArray();
        $sqlQueries[1] = $sSql;
        $aData0 = count($rResult);
        if(!$rResult){
            $rResult[0] = [
                'Processed' => 'null',
                'Delivered' => 'null',
                'Distinct Processed' => 'null',
                'Distinct Delivered' => 'null',
                'CampaignID' => 'null',
                'Campaign Name' => 'null',
                'Subject1' => 'null',
                'Start Date' => 'null',
                'Time1' => 'null',
                'End Date' => 'null',

            ];
            /*processed, delivered, distinct_processed ,distinct_delivered , c.campaignid, c.campaignname, subject1 , format(startdate, 'yyyy-MM-d') as startdate, format(time1, 'hh:mm:ss') as time1, format(enddate, 'yyyy-MM-d') as enddate*/
        }
        if ($rResult) {
            $msghtml = '';
            $response['success'] = true;
            $response['message'] = 'Successfully';
            $response['html'] = Helper::messageTabContent([$aData0]);
            $response['resultHtml'] = Helper::print_datatable($rResult);
            $response['Sqls'] = Helper::queryTabContent($sqlQueries);
        } else {
            $response['success'] = false;
            $response['message'] = 'Operation failed';
        }
        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function dbQueries(Request $request, Ajax $ajax){
        $sqlQueries = [];
        $sSql = "SELECT sqltext.TEXT, req.session_id, req.status, req.command, req.cpu_time, req.total_elapsed_time FROM sys.dm_exec_requests req CROSS APPLY sys.dm_exec_sql_text(sql_handle) AS sqltext";
        $rResult = DB::select($sSql);
        $rResult = collect($rResult)->map(function($x){ return (array) $x; })->toArray();
        $sqlQueries[1] = $sSql;
        $aData0 = count($rResult);

        if ($rResult) {
            $response['success'] = true;
            $response['message'] = 'Successfully';
            $response['html'] = Helper::messageTabContent([$aData0]);
            $response['resultHtml'] = Helper::print_datatable($rResult);
            $response['Sqls'] = Helper::queryTabContent($sqlQueries);
        } else {
            $response['success'] = false;
            $response['message'] = 'Operation failed';
        }
        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function getShrink(Request $request, Ajax $ajax){
        $sSql = "use tempdb
                go
                CHECKPOINT;
                GO  
                DBCC DROPCLEANBUFFERS;
                DBCC FREEPROCCACHE;
                DBCC FREESYSTEMCACHE('ALL');
                DBCC FREESESSIONCACHE;
                GO
                DBCC SHRINKFILE (TEMPDEV, 1);
                DBCC SHRINKFILE (TEMPlog, 1);
                GO";
        $rResult = DB::select($sSql);
        if ($rResult) {
            return $ajax->fail()
               ->message('Campaign doesn\'t Exist')
                ->jscallback()
                ->response();
        }
    }

    public function getFileSize(Request $request, Ajax $ajax){
        $result = [
            [
                'tempdb.mdf' => Helper::format_size(filesize('D:\MSSQL\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\tempdb.mdf')),
                'templog.ldf' => Helper::format_size(filesize('D:\MSSQL\Program Files\Microsoft SQL Server\MSSQL12.MSSQLSERVER\MSSQL\DATA\templog.ldf'))
            ]
        ];
        $response['message'] = 'Successfully';
        $response['resultHtml'] = Helper::print_datatable($result);
        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function countDupesPopup(Request $request, Ajax $ajax){
        $content = '
        <div class="row">
            <div class="col-md-10">
                <div class="form-group row">
                    <label class="control-label col-md-3">Campaign ID</label>
                    <div class="col-md-9">
                        <input type="text"
                               id="campids"
                               name="campids"
                               class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <button type="button"
                        class="btn btn-light font-16 s-f ds-c3"
                        title="Count_Toprocess"
                        id="count_dupes_btn">
                    <i class="fas fa-arrow-circle-right ds-c"></i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="control-label col-md-5">Counts: </label>
                    <div class="col-md-7">
                        <input type="text" id="dupes_count_field" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="control-label col-md-6">Counts Distinct: </label>
                    <div class="col-md-6">
                        <input type="text" id="dupes_count_distinct_field" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $("body").find("#count_dupes_btn").on("click", function () { 
                    ACFn.sendAjax("email/count_dupes", "get", {campagin_id: $("#campids").val()});
                });
            })
        </script>
        ';

        $sdata = [
            'content' => $content
        ];

        $title = 'Check Dupes';
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

    public function countDupes(Request $request, Ajax $ajax){
        $campaign_ids = $request->input('campagin_id');
        $sSql = "SELECT count(*) as count, count(distinct(ds_mkc_contactid)) as count_distinct FROM UC_Campaign_Data where campaignid in ($campaign_ids)";
        $rResult = DB::select($sSql);
        $rResult = collect($rResult)->map(function($x){ return (array) $x; })->toArray();
        //if ($rResult) {
            $response['success'] = true;
            $response['flag'] = 'count_dupes';
            $response['count'] = $rResult[0]['count'];
            $response['count_distinct'] = $rResult[0]['count_distinct'];
            $response['message'] = 'Successfully Deleted';

        //}

        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function DeleteToProcess(Request $request, Ajax $ajax){
        $sSql = "delete from emails_toprocess";
        $aData0 = DB::statement($sSql);
        $response = [];
        if ($aData0) {
            $response['success'] = true;
            $response['flag'] = 'Delete_toprocess';
            $response['message'] = 'Successfully Deleted';
        }

        return $ajax->success()
            ->appendParam('response',$response)
            ->jscallback('ajax_db_queries_result')
            ->response();
    }

    public function showEditPopup(Request $request, Ajax $ajax){
        $campaignid = $request->input('campaignid');
        $field_name = $request->input('field_name');

        $custom_class = '';
        $get_field_name = $field_name;

        if($field_name == 'Time1'){
            $get_field_name = DB::raw('cast(Time1 as varchar(5)) as Time1');
            $custom_class = 'js-clockpicker';

        } elseif($field_name == 'StartDate'){
            $get_field_name = DB::raw('cast([StartDate] as date) as StartDate');
            $custom_class = 'js-datepicker';

        } elseif($field_name == 'EndDate'){
            $get_field_name = DB::raw('cast([EndDate] as date) as EndDate');
            $custom_class = 'js-datepicker';
        }


        $record = EmailConfiguration::where('CampaignId',$campaignid)->first([$get_field_name]);
        $CSRFToken = csrf_token();
        $content = '
        <form class="ajax-Form" action="email/update">
           <input type="hidden" name="_token" value="'.$CSRFToken.'">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="control-label pt-2 col-md-3">'.$field_name.'</label>
                        <input type="hidden" id="campaign_id" name="campaign_id" value="'.$campaignid.'">
                        <input type="hidden" id="field_name" name="field_name" value="'.$field_name.'">
                        <div class="col-md-9">
                            <input type="text"
                                   id="'.$field_name.'"
                                   name="'.$field_name.'"
                                   value="'.$record->$field_name.'"
                                   class="form-control '.$custom_class.'">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-info">Update</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>';

        $sdata = [
            'content' => $content
        ];

        $title = 'Update';
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

    public function updateCampaign(Request $request, Ajax $ajax){
        $response = array();
        $campaign_id = $request->input('campaign_id');
        $field_name = $request->input('field_name');
        $field_value = $request->input($field_name);

        $restricted_columns = array('CampaignName');
        try {
            $response['completefn'] = 'update_cell';
            if (in_array($field_name, $restricted_columns)) {
                $campaign = EmailConfiguration::where($field_name,$field_value)
                    ->where('CampaignId',$campaign_id)
                    ->first();
                if ($campaign) {
                    return $ajax->fail()
                        ->message('Campaign Already Exist')
                        ->jscallback()
                        ->response();
                }

            }
            $field_value = str_replace("'", "''", $field_value);
            DB::update("UPDATE emails_configuration SET $field_name = '$field_value' WHERE CampaignId = " . $campaign_id);

            return $ajax->success()
                ->jscallback('ajax_modify_camp')
                ->message('Campaign Update Successfully')
                ->response();

        } catch (Exception $e) {
            return $ajax->fail()
                ->message($e->getMessage())
                ->jscallback()
                ->response();

        }
    }
}
