<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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



class PhoneController extends Controller
{
    public $prefix;
    public $headerCells;

    const FIRST_SCREEN_COLUMNS = [
        ['call' , ''],
        ['TouchStatus' , 'Status'],
        ['TouchCampaign' , 'Campaign'],
        ['c.DS_MKC_ContactID' , 'DS_MKC_ContactID'],
        ['c.DS_MKC_HouseholdID' , 'DS_MKC_HouseholdID'],
        ['Extendedname' , 'Extended Name'],
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
        ['ZSS_Segment' , 'ZSS_Segment'],
        ['TouchNotes' , 'Comments'],
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

        if(!in_array('Phone',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        $filtersFieldsValues = Helper::getLookupFiltersFieldsValues([
            'campaigns' => true,
            'countries' => true,
            'ZSS_Segments' => true,
            'MemberSegments' => true,
            'AddressQualities' => true,
            'DonorSegments' => true,
            'EventSegments' => true,
            'LifecycleSegments' => true
        ]);

        return view('lookup.phone.index',[
            'campaigns' => $filtersFieldsValues['campaigns'],
            'countries' => $filtersFieldsValues['countries'],
            'ZSS_Segments'=>$filtersFieldsValues['ZSS_Segments'],
            'MemberSegments' => $filtersFieldsValues['MemberSegments'],
            'AddressQualities' => $filtersFieldsValues['AddressQualities'],
            'DonorSegments' => $filtersFieldsValues['DonorSegments'],
            'EventSegments' => $filtersFieldsValues['EventSegments'],
            'LifecycleSegments'=>$filtersFieldsValues['LifecycleSegments'],
        ]);
    }

    public function getFirstScreen(Request $request,Ajax $ajax){
        $tabid = $request->input('tabid');
        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);
        $mergeKeys = !is_null($request->input('mergeKeys')) ? $request->input('mergeKeys') : [];
        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

        $page = $request->input('page',1);
        $records_per_page = 15; //config('constant.record_per_page');
        $position = ($page-1) * $records_per_page;
        $contactids = isset($filters['contactids']) ? explode(',',$filters['contactids'][0]) : [];
        if($tabid == 20){
            if ($sort == "DS_MKC_ContactID") {
                $sort = "DS_MKC_ContactID";
            }

            $whereData = Helper::ApplyFiltersCondition($filters,Auth::user()->User_ID);
            $urCon = !empty($whereData['urCon']) ? ' WHERE ' . $whereData['urCon']. ' AND isnull(TouchCampaign,\'\') <> \'\'' : ' WHERE isnull(TouchCampaign,\'\') <> \'\'';
            $lookupClause = !empty($whereData['lookupWhere']) ? ' WHERE '.$whereData['lookupWhere'] : '';
            $phoneClause = !empty($whereData['phoneWhere']) ? $whereData['phoneWhere'] : '';
            $salesClause = !empty($whereData['salesWhere']) ? $whereData['salesWhere'] : '';

            $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);
            $sort = ($sort == "" || $sort == "select") ? "Order by tag desc, update_date  DESC " : "Order by $sort $dir";

            $records = DB::select("SELECT * from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt,
            TouchDate,TouchStatus,TouchNotes,TouchCampaign from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle, Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By touchcampaign desc,touchdate  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b $sWhere1");

            $all_records = DB::select("SELECT count(*) as count from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,
            TouchDate,TouchStatus,TouchNotes,TouchCampaign from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle, Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By touchcampaign desc,touchdate  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b");
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();

            if($rType == 'pagination'){
                $html = View::make('lookup.phone.table',['records' => $records,'contactids' => $contactids])->render();
            }else{
                $html = View::make('lookup.phone.first-screen',['records' => $records,'contactids' => $contactids])->render();
            }

            $paginationhtml = View::make('lookup.phone.pagination-html',[
                'total_records' => $total_records[0]['count'],
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page
            ])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('sql',"SELECT * from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,
            TouchDate,TouchStatus,TouchNotes,TouchCampaign from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle, Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By touchcampaign desc,touchdate  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
 left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b")
                ->appendParam('records',$records)
                ->appendParam('total_records',$total_records[0]['count'])
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();
        }
    }

    public function saveTouch(Request $request,Ajax $ajax){
        $DS_MKC_ContactID = $request->input('ds_mkc_contactid');
        $column_name = $request->input('column_name');
        $column_value = $request->input('column_value');
        if(!empty($column_value)){
            $tData = DB::table('Touch')
                ->where('DS_MKC_ContactID',$DS_MKC_ContactID)
                ->orderByDesc('RowID')
                ->first();
            $columns = ['TouchStatus' => '','TouchChannel' => 'Phone','TouchCampaign' => '','TouchDate' => date('Y-m-d'),'TouchNotes' => ''];
            if($tData){
                if($tData->$column_name != trim($column_value) && $column_name == 'TouchStatus'){
                    $sSqlInsertTouch = "Insert Touch ([DS_MKC_ContactID],[TouchStatus],[TouchChannel],[TouchCampaign],[TouchDate],[TouchNotes]) Values ($DS_MKC_ContactID,";
                    $i = 0;
                    foreach ($columns as $cName => $dVal){
                        $com = ($i+1) !== count($columns) ? ',' : '';
                        if($cName == $column_name){
                            $sSqlInsertTouch .= "N'$column_value'".$com;
                        }else if($cName == 'TouchDate'){
                            $sSqlInsertTouch .= "N'".date('Y-m-d')."'".$com;
                        }else{
                            $sSqlInsertTouch .= "N'".$tData->$cName."'".$com;
                        }
                        $i++;
                    }
                    $sSqlInsertTouch .= ")";
                    DB::insert($sSqlInsertTouch);
                }else{
                    $sSqlUpdateTouch = "UPDATE Touch  SET ";
                    $i = 0;
                    foreach ($columns as $cName => $dVal){
                        $com = ($i+1) !== count($columns) ? ',' : '';
                        if($cName == $column_name){
                            $sSqlUpdateTouch .= "$cName = N'$column_value'".$com;
                        }else if($cName == 'TouchDate'){
                            $sSqlUpdateTouch .= "$cName = N'".date('Y-m-d')."'".$com;
                        }else{
                            $sSqlUpdateTouch .= "$cName = N'".$tData->$cName."'".$com;
                        }
                        $i++;
                    }
                    $sSqlUpdateTouch .= " WHERE RowID = (SELECT max(RowID) FROM Touch WHERE DS_MKC_ContactID = '$DS_MKC_ContactID')";
                    //$sSqlUpdateTouch .= " WHERE DS_MKC_ContactID = '$DS_MKC_ContactID'";
                    DB::update($sSqlUpdateTouch);
                }
            }else{

                $sSqlInsertTouch = "Insert Touch ([DS_MKC_ContactID],[TouchStatus],[TouchChannel],[TouchCampaign],[TouchDate],[TouchNotes]) Values ($DS_MKC_ContactID,";
                $i = 0;
                foreach ($columns as $cName => $dVal){
                    $com = ($i+1) !== count($columns) ? ',' : '';
                    if($cName == $column_name){
                        $sSqlInsertTouch .= "N'$column_value'".$com;
                    }else{
                        $sSqlInsertTouch .= "N'".$dVal."'".$com;
                    }
                    $i++;
                }
                $sSqlInsertTouch .= ")";
                //echo $sSqlInsertTouch; die;
                DB::insert($sSqlInsertTouch);
            }


            $tData = DB::table('Touch')
                ->where('RowID',DB::raw("(SELECT max(RowID) FROM Touch WHERE DS_MKC_ContactID = '$DS_MKC_ContactID')"))
                //->where('DS_MKC_ContactID',$DS_MKC_ContactID)
                ->orderByDesc('RowID')
                ->first();

            $class = 'badge badge-light';
            if($tData->TouchDate != null){
                switch ($tData->TouchStatus){
                    case 'Assigned':
                        $class = 'badge badge-info';
                        break;

                    case 'Spoke on Phone':
                        $class = 'badge badge-success';
                        break;

                    case 'User Returned Call':
                        $class = 'badge badge-success';
                        break;

                    case 'User Returned Text':
                        $class = 'badge badge-success';
                        break;

                    case 'Left Voicemail':
                        $class = 'badge badge-warning';
                        break;

                    case 'Could not leave Voicemail':
                        $class = 'badge badge-danger';
                        break;

                    case 'Phone not in service':
                        $class = 'badge badge-danger';
                        break;

                    case 'Phone belongs to someone else':
                        $class = 'badge badge-danger';
                        break;

                    case 'Suppressed':
                        $class = 'badge badge-light';
                        break;

                    default:
                        $class = 'badge badge-light';
                        break;
                }
            }
            return $ajax->success()
                ->jscallback('ajax_touch')
                ->appendParam('tocuh_data',$tData)
                ->appendParam('call_class',$class)
                ->response();
        }
    }

    public function deleteTouch($rowid,$contactid,Request $request,Ajax $ajax){
        try{
            DB::statement("DELETE FROM Touch WHERE rowID = '".$rowid."' AND DS_MKC_ContactID = '".$contactid."'");

            $request->setMethod('POST');
            $request->request->add([
                'page' => 1,
                'rtype' => '',
                'filters' => []
            ]);
            $lookup = new LookupController();
            $response = $lookup->getTouchesDetails($contactid, $request,$ajax);
            $encoresult = json_decode(json_encode($response),true);
            return $ajax->success(isset($encoresult['original']['success']) ? $encoresult['original']['success'] : false)
                ->appendParam('html',isset($encoresult['original']['html']) ? $encoresult['original']['html'] : '')
                ->appendParam('pagination_html',isset($encoresult['original']['pagination_html']) ? $encoresult['original']['pagination_html'] : '')
                ->jscallback((isset($encoresult['original']['success']) && $encoresult['original']['success']) && isset($encoresult['original']['completefn']) ? $encoresult['original']['completefn'] : '')
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->jscallback()
                ->message($exception->getMessage())
                ->response();
        }
    }

    public function downloadPhoneReport(Request $request,Ajax $ajax){

        define('UPLOAD_DIR', public_path().'\\'.'Chart_Images\\');
        $uid = Auth::user()->User_ID;
        $cI = '';
        $imgTag = '';
        $imgPath = '';
        $report = ReportTemplate::with(['rpmeta','rpschedule'])->where('row_id',trim($request->input('rowID')))->first();
        if (!$report) {
            return $ajax->fail()
                ->message('Report doesn\'t exist')
                ->jscallback()
                ->response();
        }

        $SR_Attachment = $report->SR_Attachment;
        $rv = $report->Report_Row;
        $t_name = $report->t_name;
        if($request->input('request_type') == 'report'){
            if (empty($rv) || !in_array($SR_Attachment,['onlyreport','both'])) {
                return $ajax->fail()
                    ->message('Summary Report doesn\'t exist')
                    ->jscallback()
                    ->response();
            }
            try {
                if (!empty($request->input('cI'))) {
                    $img = $request->input('cI');
                    if (strpos($img, 'data:image/png;base64,') !== false) {
                        $img = str_replace('data:image/png;base64,', '', $img);
                        $img = str_replace(' ', '+', $img);
                        $data = base64_decode($img);
                        $cI = UPLOAD_DIR . uniqid() . '.png';
                        file_put_contents($cI, $data);
                        $imgPath = $cI;
                    }

                    $imgTag = '<img src="' . $cI . '" class="img-responsive">';


                    $cv = $report->Report_Column;
                    $fu = ucfirst($report->Report_Function);
                    $sv = $report->Report_Sum;
                    $sa = $report->Report_Show;
                    $list_level = $report->list_level;
                    $listShortName = $report->list_short_name;
                    $sSQL = $report->sql;
                    $row_id = $report->row_id;
                    $t_name = $report->t_name;
                    $saveCD = $report->promoexpo_cd_opt;
                    $saveFile = $report->promoexpo_file_opt;
                    $eFolder = $report->promoexpo_folder;
                    $eFile = $t_name;
                    $eExt = $report->promoexpo_ext;
                    $CGOpt = $report->promoexpo_ecg_opt;
                    $eData = $report->promoexpo_data;
                    $list_format = $report->List_Format;
                    $report_orientation = $report->Report_Orientation;
                    $SR_Attachment = $report->SR_Attachment;
                    $rpDesc = $report->rpmeta->Category;
                }

                Helper::generateSrPDF($rv, $cv, ucfirst($fu), $sv, $sa, $sSQL, $list_level, $listShortName, $imgTag, $imgPath, 'downloads', $t_name, $this->prefix . 'RPS_', $SR_Attachment, $rpDesc, $report_orientation);

                return $ajax->success()
                    ->jscallback()
                    ->appendParam('file_name', $this->prefix . 'RPS_' . $t_name . '.pdf')
                    ->appendParam('redirect', true)
                    ->redirectTo(url('/downloads/') . '/' . $this->prefix . 'RPS_' . $t_name . '.pdf')
                    ->response();
            }catch (\Exception $exception){
                return $ajax->fail()
                    ->jscallback()
                    ->message('Something is wrong')
                    ->response();
            }

        }
        elseif ($request->input('request_type') == 'list'){
            if (!in_array($SR_Attachment,['onlylist','both'])) {
                return $ajax->fail()
                    ->message('Report List doesn\'t exist')
                    ->jscallback()
                    ->response();
            }
            
            try{
                Artisan::call('phoneReportSchedule:run', ['sid' => "{$report->rpschedule->row_id}"]);

                return $ajax->success()
                    ->jscallback()
                    ->appendParam('file_name',$this->prefix . 'RPL_'. $t_name . '.xlsx')
                    ->appendParam('redirect',true)
                    ->redirectTo(url('/downloads/'. '/' . $this->prefix . 'RPL_'. $t_name . '.xlsx'))
                    ->message('Done')
                    ->response();
            }catch (\Exception $exception){
                return $ajax->fail()
                    ->jscallback()
                    ->message('Something is wrong')
                    ->response();
            }
        }
    }
}
