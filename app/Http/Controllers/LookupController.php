<?php

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



class LookupController extends Controller
{
    public $prefix;
    public $headerCells;
    /*const FIRST_SCREEN_COLUMNS = [
        ['tag' => 'Tag'],
        ['merge' => ''],
        ['ds_mkc_contactid' => 'DS_MKC_ContactID'],
        ['ds_mkc_householdid' => 'DS_MKC_HouseholdID'],
        ['Extendedname' => 'Extendedname'],
        ['phone' => 'Phone'],
        ['Email' => 'Email'],
        ['EmailSegment' => 'EmailSegment'],
        ['email2' => 'Email 2'],
        ['Address' => 'Address'],
        ['City' => 'City'],
        ['State' => 'State'],
        ['Zip' => 'Zip'],
        ['Company' => 'Company'],
        ['update_date' => 'Updated'],
    ];*/
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

        if(!in_array('Lookup Contact',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        $filtersFieldsValues = Helper::getLookupFiltersFieldsValues([
            'campaigns' => false,
            'countries' => true,
            'ZSS_Segments' => true,
            'MemberSegments' => true,
            'AddressQualities' => true,
            'DonorSegments' => true,
            'EventSegments' => true,
            'LifecycleSegments' => true
        ]);

        return view('lookup.index',[
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
        $mKeys = [];
        if(count($mergeKeys) > 0){
            foreach ($mergeKeys as $mk){
                $mKeys[] = $mk['id'];
            }
        }
        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

        $page = $request->input('page',1);
        $records_per_page = 15;//config('constant.record_per_page');
        $position = ($page-1) * $records_per_page;
        $contactids = isset($filters['contactids']) ? explode(',',$filters['contactids'][0]) : [];
        if($tabid == 20){
            if ($sort == "DS_MKC_ContactID") {
                $sort = "DS_MKC_ContactID";
            }
            $whereClause = Helper::ApplyFiltersCondition($filters,Auth::user()->User_ID);


            $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

            $sort = ($sort == "" || $sort == "select") ? "Order by tag desc, update_date  DESC " : "Order by $sort $dir";

            $records = DB::select("SELECT * from (select *, row_number () over (partition by rown   $sort  ) as ROWNUMBER FROM   (SELECT ROW_NUMBER() over (partition by DS_MKC_ContactID $sort) as ROWN,
DS_MKC_ContactID,DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,TouchDate from Contact_View ".$whereClause['finalClause'].") _myResults where ROWN = 1 ) as a  $sWhere1 $sort");

            $all_records = DB::select("SELECT count(distinct(DS_MKC_ContactID)) as count FROM Contact_View ac ".$whereClause['finalClause']);

            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();

            if($rType == 'pagination'){
                $html = View::make('lookup.table',['records' => $records,'contactids' => $contactids,'mKeys' => $mKeys])->render();
            }else{
                $html = View::make('lookup.first-screen',['records' => $records,'contactids' => $contactids,'mKeys' => $mKeys])->render();
            }

            $paginationhtml = View::make('lookup.pagination-html',[
                'total_records' => $total_records[0]['count'],
                'records' => $records,
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page
            ])->render();
            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('query',"SELECT * from (select *, row_number () over (partition by rown   $sort  ) as ROWNUMBER FROM   (SELECT ROW_NUMBER() over (partition by DS_MKC_ContactID  $sort) as ROWN,
DS_MKC_ContactID,DS_MKC_HouseholdID,Email,EmailSegment,email2,dqcode_email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Country,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,tag,update_date from Contact_View ".$whereClause['finalClause'].") _myResults where ROWN = 1 ) as a  $sWhere1 $sort")
                ->appendParam('countqry',"SELECT count(distinct(DS_MKC_ContactID)) as count FROM Contact_View ac ".$whereClause['finalClause'])
                ->appendParam('total_records',$total_records[0]['count'])
                ->appendParam('paginationHtml',$paginationhtml)
                ->jscallback('load_ajax_tab')
                ->response();
        }
    }

    public function downloadReport(Request $request,Ajax $ajax){
        $fileprefix = $request->input('prefix');
        $screen = $request->input('screen');

        if($screen == 'first'){
            $filters = $request->input('filters',[]);
            $downloadableColumns = json_decode($request->input('downloadableColumns',''));
            $sort = $request->input('sort') ? $request->input('sort') : '';
            $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

            if ($sort == "DS_MKC_ContactID") {
                $sort = "DS_MKC_ContactID";
            }
            $where = Helper::ApplyFiltersCondition($filters,Auth::user()->User_ID);
            $lookupClause = !empty($where['finalClause']) ? $where['finalClause'] : ' ';//' WHERE ';

            $sort = ($sort == "" || $sort == "select") ? "Order by update_date  DESC " : "Order by $sort $dir";

            if ($fileprefix == 'lookup'){
                $columns = Helper::getDownloadableColumns(LookupController::FIRST_SCREEN_COLUMNS,$downloadableColumns,[1]);
                $sSQL = "SELECT * from (select *, row_number () over (partition by rown   $sort ) as ROWNUMBER FROM
(SELECT
ROW_NUMBER() over (partition by DS_MKC_ContactID order by DS_MKC_ContactID  asc) as ROWN,
$columns
from Contact_View $lookupClause) _myResults where ROWN = 1 ) as a $sort";
            }elseif ($fileprefix == 'phone'){
                $lookupClause = !empty($where['lookupWhere']) ? ' WHERE '.$where['lookupWhere'] : '';
                $phoneClause = !empty($where['phoneWhere']) ? $where['phoneWhere'] : '';
                $salesClause = !empty($where['salesWhere']) ? $where['salesWhere'] : '';
                $urCon = !empty($where['urCon']) ? ' WHERE ' . $where['urCon']. ' AND isnull(TouchCampaign,\'\') <> \'\'' : ' WHERE isnull(TouchCampaign,\'\') <> \'\'';

                $sSQL = "SELECT * from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Extendedname,phone,Email,EmailSegment,email2 as Email2,Address,City,State,Zip,
Company,update_date,ZSS_Segment,TouchStatus,TouchCampaign,TouchNotes
from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,Extendedname, Company, Address,City,State,Zip,update_date,ZSS_Segment from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By touchcampaign desc,touchdate  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b ";

            }

            ini_set('max_execution_time', 3500);
            ini_set('memory_limit', '1024M');
            ob_clean();
            try{
                if (trim($sSQL) != "") {
                    if (strpos($sSQL, "*") === true) {
                        $nSQL = str_replace("*", "TOP 10000 * ", $sSQL);
                    } else {
                        $nSQL = substr($sSQL, 0, 6) . " top 10000 " . substr($sSQL, 7, strlen($sSQL));
                    }
                    $files = glob(public_path().'/downloads/*'); // get all file names
                    foreach($files as $file){ // iterate files
                        if(is_file($file))
                            unlink($file); // delete file
                    }

                    $records = DB::select($nSQL);
                    $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();

                    $headerCells = $this->headerCells;
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
                    $uUfName = strtoupper(substr(Auth::user()->User_FName, 0, 1));
                    $uUlName = strtoupper(substr(Auth::user()->User_LName, 0, 1));
                    if(!empty($fileprefix)){
                        $uUName = $fileprefix;
                    }else{
                        $uUName = $uUfName . $uUlName;
                    }
                    $prefix = config('constant.prefix');
                    $file_Name = $prefix. date('Y') . date('m') . date('d');
                    $writer = new Xlsx($spreadsheet);
                    $writer->save(public_path()."\\downloads\\".$file_Name.".xlsx");
                    $sBaseUrl = config('constant.BaseUrl');
                    return $ajax->success()
                        ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
                        ->jscallback('ajax_download_file')
                        ->response();
                }
            }catch (\Exception $exception){
                return $ajax->fail()
                    ->appendParam('error_message',$exception->getMessage())
                    ->message('Downloading failed')
                    ->response();
            }
        }
        elseif ($screen == 'contact'){

            $contactid = $request->input('contactid');
            $sSQL = "SELECT
Extendedname,Country,DS_MKC_ContactID,DS_MKC_Household_Num,LetterName,JobTitle,ds_mkc_householdid,extendedname,Country,Address,Salutation,Salutation2,City,DharmaName,DharmaName2,State,FirstName
,Firstname2,Zip,MiddleName,Middlename2,Company,lastname,lastname2,suffix,suffix2,gender,Arrival,Transportation,AddressQuality,Nxi_Expand
,phone,phone_type,Phone2,Phone2_type,Email,Email2,
Emailable,Opt_Mail,Mailable,ZSS_Segment,MemberSegment,EmailSegment,DonorSegment,EventSegment,LifecycleSegment,Notes
,Suppression,companyinclude,mail_status,contactable,Gender2,ds_mkc_source_feed,DFLName,DFLName2,FirstSesshinDate,
Jukai_Date,Ordainment_Date,firstDate,lastDate,email_optout_reason,email_status,email2_status,opt_mail,EmailSegment,TouchCampaign,TouchStatus,TouchChannel,TouchDate,TouchNotes
            FROM Contact_View where DS_MKC_ContactID = '$contactid'";
            $records = DB::select($sSQL);
            //echo '<pre>'; print_r($records); die;

            $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();

            $view = View::make('lookup.contact-dn-tab-col-2',['record' => $aData[0],'section' => 'contact'])->render();
            //echo $view; die;
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadhseet = $reader->loadFromString($view);
            $sheet = $spreadhseet->getActiveSheet();
            $spreadhseet->setActiveSheetIndex(0);
            $spreadhseet->getActiveSheet()->setTitle('Contact');

            $spreadhseet->setActiveSheetIndex(0);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

            $dflname = !empty(trim($aData[0]['DFLName'])) && !is_null($aData[0]['DFLName'])  ? $aData[0]['DFLName']."_" : "";
            $file_Name = $this->prefix.$dflname. date('Y') . date('m') . date('d');

            $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');

            $sBaseUrl = config('constant.BaseUrl');
            return $ajax->success()
                ->appendParam('html',$view)
                ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
                ->jscallback('ajax_download_file')
                ->response();

        }
        elseif ($screen == 'summary'){
            $contactid = $request->input('contactid');

            $sSQL = "SELECT
     Life2date_SpendAmt,Last_5Yrs_SpendAmt,	Last_6Mth_SpendAmt,	CurrentYr_SpendAmt,	Prior_Yr1_SpendAmt,	Prior_Yr2_SpendAmt,Prior_Yr3_SpendAmt,	Prior_Yr4_SpendAmt,
Life2date_GiftsAmt,	Last_5Yrs_GiftsAmt,	Last_6Mth_GiftsAmt,	CurrentYr_GiftsAmt,	Prior_Yr1_GiftsAmt,	Prior_Yr2_GiftsAmt,Prior_Yr3_GiftsAmt,	Prior_Yr4_GiftsAmt,
Life2date_MembrAmt,	Last_5Yrs_MembrAmt,	Last_6Mth_MembrAmt,	CurrentYr_MembrAmt,	Prior_Yr1_MembrAmt,	Prior_Yr2_MembrAmt,Prior_Yr3_MembrAmt,	Prior_Yr4_MembrAmt,
Life2date_EventAmt, 	Last_5Yrs_EventAmt, Last_6Mth_EventAmt, 	CurrentYr_EventAmt, 	Prior_Yr1_EventAmt, 	Prior_Yr2_EventAmt, 	Prior_Yr3_EventAmt, 	Prior_Yr4_EventAmt,
Life2date_RtailAmt,	Last_5Yrs_RtailAmt,	Last_6Mth_RtailAmt,	CurrentYr_RtailAmt,	Prior_Yr1_RtailAmt,	Prior_Yr2_RtailAmt,	Prior_Yr3_RtailAmt,	Prior_Yr4_RtailAmt,
Life2date_RentlAmt,	Last_5Yrs_RentlAmt,	Last_6Mth_RentlAmt,	CurrentYr_RentlAmt,	Prior_Yr1_RentlAmt,	Prior_Yr2_RentlAmt,	Prior_Yr3_RentlAmt,	Prior_Yr4_RentlAmt,
Life2date_MisclAmt,	Last_5Yrs_MisclAmt,	Last_6Mth_MisclAmt,	CurrentYr_MisclAmt,	Prior_Yr1_MisclAmt,	Prior_Yr2_MisclAmt,	Prior_Yr3_MisclAmt,	Prior_Yr4_MisclAmt,

Life2date_NActivty,	Last_5Yrs_NActivty,	Last_6Mth_NActivty,	CurrentYr_NActivty,	Prior_Yr1_NActivty,	Prior_Yr2_NActivty,	Prior_Yr3_NActivty,	Prior_Yr4_NActivty,

Life2date_NPaidEvt,	Last_5Yrs_NPaidEvt,	Last_6Mth_NPaidEvt,	CurrentYr_NPaidEvt,	Prior_Yr1_NPaidEvt,	Prior_Yr2_NPaidEvt,	Prior_Yr3_NPaidEvt,	Prior_Yr4_NPaidEvt,

Life2date_NZSS_Ses,	Last_5Yrs_NZSS_Ses,	Last_6Mth_NZSS_Ses,	CurrentYr_NZSS_Ses,	Prior_Yr1_NZSS_Ses,	Prior_Yr2_NZSS_Ses,	Prior_Yr3_NZSS_Ses,	Prior_Yr4_NZSS_Ses,
Life2date_NOpn_Ses,	Last_5Yrs_NOpn_Ses,	Last_6Mth_NOpn_Ses,	CurrentYr_NOpn_Ses,	Prior_Yr1_NOpn_Ses,	Prior_Yr2_NOpn_Ses,	Prior_Yr3_NOpn_Ses,	Prior_Yr4_NOpn_Ses,
Life2date_NITZ_Wkd,	Last_5Yrs_NITZ_Wkd,	Last_6Mth_NITZ_Wkd,	CurrentYr_NITZ_Wkd,	Prior_Yr1_NITZ_Wkd,	Prior_Yr2_NITZ_Wkd,	Prior_Yr3_NITZ_Wkd,	Prior_Yr4_NITZ_Wkd,
Life2date_NAll_Day,	Last_5Yrs_NAll_Day,	Last_6Mth_NAll_Day,	CurrentYr_NAll_Day,	Prior_Yr1_NAll_Day,	Prior_Yr2_NAll_Day,	Prior_Yr3_NAll_Day,	Prior_Yr4_NAll_Day,
Life2date_NSitting,	Last_5Yrs_NSitting,	Last_6Mth_NSitting,	CurrentYr_NSitting,	Prior_Yr1_NSitting,	Prior_Yr2_NSitting,	Prior_Yr3_NSitting,	Prior_Yr4_NSitting,
Life2date_NZSS_Pgm,	Last_5Yrs_NZSS_Pgm,	Last_6Mth_NZSS_Pgm,	CurrentYr_NZSS_Pgm,	Prior_Yr1_NZSS_Pgm,	Prior_Yr2_NZSS_Pgm,	Prior_Yr3_NZSS_Pgm,	Prior_Yr4_NZSS_Pgm,
Life2date_NOpn_Pgm,	Last_5Yrs_NOpn_Pgm,	Last_6Mth_NOpn_Pgm,	CurrentYr_NOpn_Pgm,	Prior_Yr1_NOpn_Pgm,	Prior_Yr2_NOpn_Pgm,	Prior_Yr3_NOpn_Pgm,	Prior_Yr4_NOpn_Pgm,
Life2date_NKessei_,	Last_5Yrs_NKessei_,	Last_6Mth_NKessei_,	CurrentYr_NKessei_,	Prior_Yr1_NKessei_,	Prior_Yr2_NKessei_,	Prior_Yr3_NKessei_,	Prior_Yr4_NKessei_,
Life2date_NZM3Fold,	Last_5Yrs_NZM3Fold,	Last_6Mth_NZM3Fold,	CurrentYr_NZM3Fold,	Prior_Yr1_NZM3Fold,	Prior_Yr2_NZM3Fold,	Prior_Yr3_NZM3Fold,	Prior_Yr4_NZM3Fold,

Life2date_NFreeEvt,	Last_5Yrs_NFreeEvt,	Last_6Mth_NFreeEvt,	CurrentYr_NFreeEvt,	Prior_Yr1_NFreeEvt,	Prior_Yr2_NFreeEvt,	Prior_Yr3_NFreeEvt,	Prior_Yr4_NFreeEvt,
Life2date_NZoom_Ot,	Last_5Yrs_NZoom_Ot, Last_6Mth_NZoom_Ot,	CurrentYr_NZoom_Ot,	Prior_Yr1_NZoom_Ot,	Prior_Yr2_NZoom_Ot,	Prior_Yr3_NZoom_Ot,	Prior_Yr4_NZoom_Ot

FROM Contact_View where DS_MKC_ContactID = '$contactid'";

            $records = DB::select($sSQL);
            $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();

            $view = View::make('lookup.contact-dn-tab-col-2',['record' => $aData[0],'section' => 'summary']);
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadhseet = $reader->loadFromString($view);
            $sheet = $spreadhseet->getActiveSheet();
            $spreadhseet->setActiveSheetIndex(0);
            $spreadhseet->getActiveSheet()->setTitle('Activity Summary');

            $spreadhseet->setActiveSheetIndex(0);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');

            $sSQLFN = "SELECT DFLName FROM Contact_View where DS_MKC_ContactID = '$contactid'";
            $recordFN = DB::select($sSQLFN);
            $aDataFN = collect($recordFN)->map(function($x){ return (array) $x; })->toArray();
            $dflname = !empty(trim($aDataFN[0]['DFLName'])) && !is_null($aDataFN[0]['DFLName'])  ? $aDataFN[0]['DFLName']."_" : "";
            $file_Name = $this->prefix.$dflname. date('Y') . date('m') . date('d');

            $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');

            $sBaseUrl = config('constant.BaseUrl');
            return $ajax->success()
                ->appendParam('html',$view)
                ->appendParam('download_url',$sBaseUrl . "downloads/" . $file_Name . '.xlsx')
                ->jscallback('ajax_download_file')
                ->response();
        }
        elseif ($screen == 'detail'){
            $contactid = $request->input('contactid');
            $filters = $request->input('filters',[]);

            $where = self::Apply2ndScreenFiltersCondition($filters);
            $where  = !empty($where) ? $where.' AND ' : '';

            $sort = $request->input('sort') ? $request->input('sort') : 'sa.Date';
            $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

            $sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";

            $sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,sa.Date, cast(sa.Amount as int) as Amount,sa.Activitycat2,sa.Activitycat1,sa.Activity,sa.Class , sa.ClientMessage from Sales_View  sa INNER JOIN Contact_View ac ON
     $where sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults";
        }
        elseif ($screen == 'touch'){
            $contactid = $request->input('contactid');
            $filters = $request->input('filters',[]);

            $where = self::Apply2ndScreenFiltersCondition($filters);
            $where  = !empty($where) ? $where.' AND ' : '';

            $sort = $request->input('sort') ? $request->input('sort') : 't.RowID';
            $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

            $sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";

            //$sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,sa.Date, cast(sa.Amount as int) as Amount,sa.Activitycat2,sa.Activitycat1,sa.Activity,sa.Class , sa.ClientMessage from Sales_View  sa INNER JOIN Contact_View ac ON $where sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults";

            $sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,ac.DS_MKC_ContactID,ac.dflname,t.TouchCampaign,t.TouchStatus,t.TouchChannel,t.TouchDate,t.TouchNotes from Touch  t INNER JOIN Contact_View ac ON
     $where t.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults";
        }

        ini_set('max_execution_time', 3500);
        ini_set('memory_limit', '1024M');
        ob_clean();
        try{
            if (trim($sSQL) != "") {
                if (strpos($sSQL, "*") === true) {
                    $nSQL = str_replace("*", "TOP 10000 * ", $sSQL);
                } else {
                    $nSQL = substr($sSQL, 0, 6) . " top 10000 " . substr($sSQL, 7, strlen($sSQL));
                }
                $files = glob(public_path().'/downloads/*'); // get all file names
                foreach($files as $file){ // iterate files
                    if(is_file($file))
                        unlink($file); // delete file
                }


                $records = DB::select($nSQL);
                $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();
                $view = View::make('lookup.contact-dn-tab-col-2',['records' => $aData,'section' => $screen])->render();
                //echo $view; die;
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
                $spreadhseet = $reader->loadFromString($view);
                $sheet = $spreadhseet->getActiveSheet();
                $spreadhseet->setActiveSheetIndex(0);
                $sTitle = $screen == 'lookup' ? 'Activity Detail' : 'Touches';
                $spreadhseet->getActiveSheet()->setTitle($sTitle);

                $spreadhseet->setActiveSheetIndex(0);

                $sSQLFN = "SELECT DFLName FROM Contact_View where DS_MKC_ContactID = '$contactid'";
                $recordFN = DB::select($sSQLFN);
                $aDataFN = collect($recordFN)->map(function($x){ return (array) $x; })->toArray();
                $dflname = !empty(trim($aDataFN[0]['DFLName'])) && !is_null($aDataFN[0]['DFLName'])  ? $aDataFN[0]['DFLName']."_" : "";
                $file_Name = $this->prefix.$dflname. date('Y') . date('m') . date('d');

                $writer = new Xlsx($spreadhseet);
                $writer->save(public_path()."\\downloads\\".$file_Name.".xlsx");
                $sBaseUrl = config('constant.BaseUrl');
                return $ajax->success()
                    ->jscallback()
                    ->form_reset(false)
                    ->redirectTo($sBaseUrl . "downloads/" . $file_Name. '.xlsx')
                    ->response();
            }
        }catch (\Exception $e){
            return $ajax->fail()
                ->appendParam('error_message',$e->getMessage())
                ->message('Downloading failed')
                ->response();

        }
    }

    public function downloadAllReports($contactid, Request $request,Ajax $ajax){

        try{
            /******************** Contact screen - Start ***********************/
            $sSQL = "SELECT
Extendedname,Country,DS_MKC_ContactID,DS_MKC_Household_Num,LetterName,JobTitle,ds_mkc_householdid,extendedname,Country,Address,Salutation,Salutation2,City,DharmaName,DharmaName2,State,FirstName
,Firstname2,Zip,MiddleName,Middlename2,Company,lastname,lastname2,suffix,suffix2,gender,Arrival,Transportation,AddressQuality,Nxi_Expand
,phone,phone_type,Phone2,Phone2_type,Email,Email2,opt_email2,
Opt_Email,Emailable,Opt_Mail,Mailable,ZSS_Segment,MemberSegment,EmailSegment,DonorSegment,EventSegment,LifecycleSegment,Notes
,Suppression,companyinclude,mail_status,contactable,Gender2,ds_mkc_source_feed,DFLName,DFLName2,FirstSesshinDate,
Jukai_Date,Ordainment_Date,firstDate,lastDate,email_optout_reason,email_status,email2_status,opt_mail,TouchCampaign,TouchStatus,TouchChannel,TouchDate,TouchNotes
            FROM Contact_View where DS_MKC_ContactID = '$contactid'";
            $records = DB::select($sSQL);

            $aData = collect($records)->map(function($x){ return (array) $x; })->toArray();

            $view = View::make('lookup.contact-dn-tab-col-2',['record' => $aData[0],'section' => 'contact'])->render();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
            $spreadhseet = $reader->loadFromString($view);
            $sheet = $spreadhseet->getActiveSheet();
            $spreadhseet->setActiveSheetIndex(0);
            $spreadhseet->getActiveSheet()->setTitle('Contact');

            /******************** Contact screen - End ***********************/

            /******************** Activity Summary screen - Start ***********************/
            $sSQL = "SELECT
      Life2date_SpendAmt,Last_5Yrs_SpendAmt,	Last_6Mth_SpendAmt,	CurrentYr_SpendAmt,	Prior_Yr1_SpendAmt,	Prior_Yr2_SpendAmt,Prior_Yr3_SpendAmt,	Prior_Yr4_SpendAmt,
Life2date_GiftsAmt,	Last_5Yrs_GiftsAmt,	Last_6Mth_GiftsAmt,	CurrentYr_GiftsAmt,	Prior_Yr1_GiftsAmt,	Prior_Yr2_GiftsAmt,Prior_Yr3_GiftsAmt,	Prior_Yr4_GiftsAmt,
Life2date_MembrAmt,	Last_5Yrs_MembrAmt,	Last_6Mth_MembrAmt,	CurrentYr_MembrAmt,	Prior_Yr1_MembrAmt,	Prior_Yr2_MembrAmt,Prior_Yr3_MembrAmt,	Prior_Yr4_MembrAmt,
Life2date_EventAmt, 	Last_5Yrs_EventAmt, Last_6Mth_EventAmt, 	CurrentYr_EventAmt, 	Prior_Yr1_EventAmt, 	Prior_Yr2_EventAmt, 	Prior_Yr3_EventAmt, 	Prior_Yr4_EventAmt,
Life2date_RtailAmt,	Last_5Yrs_RtailAmt,	Last_6Mth_RtailAmt,	CurrentYr_RtailAmt,	Prior_Yr1_RtailAmt,	Prior_Yr2_RtailAmt,	Prior_Yr3_RtailAmt,	Prior_Yr4_RtailAmt,
Life2date_RentlAmt,	Last_5Yrs_RentlAmt,	Last_6Mth_RentlAmt,	CurrentYr_RentlAmt,	Prior_Yr1_RentlAmt,	Prior_Yr2_RentlAmt,	Prior_Yr3_RentlAmt,	Prior_Yr4_RentlAmt,
Life2date_MisclAmt,	Last_5Yrs_MisclAmt,	Last_6Mth_MisclAmt,	CurrentYr_MisclAmt,	Prior_Yr1_MisclAmt,	Prior_Yr2_MisclAmt,	Prior_Yr3_MisclAmt,	Prior_Yr4_MisclAmt,

Life2date_NActivty,	Last_5Yrs_NActivty,	Last_6Mth_NActivty,	CurrentYr_NActivty,	Prior_Yr1_NActivty,	Prior_Yr2_NActivty,	Prior_Yr3_NActivty,	Prior_Yr4_NActivty,

Life2date_NPaidEvt,	Last_5Yrs_NPaidEvt,	Last_6Mth_NPaidEvt,	CurrentYr_NPaidEvt,	Prior_Yr1_NPaidEvt,	Prior_Yr2_NPaidEvt,	Prior_Yr3_NPaidEvt,	Prior_Yr4_NPaidEvt,

Life2date_NZSS_Ses,	Last_5Yrs_NZSS_Ses,	Last_6Mth_NZSS_Ses,	CurrentYr_NZSS_Ses,	Prior_Yr1_NZSS_Ses,	Prior_Yr2_NZSS_Ses,	Prior_Yr3_NZSS_Ses,	Prior_Yr4_NZSS_Ses,
Life2date_NOpn_Ses,	Last_5Yrs_NOpn_Ses,	Last_6Mth_NOpn_Ses,	CurrentYr_NOpn_Ses,	Prior_Yr1_NOpn_Ses,	Prior_Yr2_NOpn_Ses,	Prior_Yr3_NOpn_Ses,	Prior_Yr4_NOpn_Ses,
Life2date_NITZ_Wkd,	Last_5Yrs_NITZ_Wkd,	Last_6Mth_NITZ_Wkd,	CurrentYr_NITZ_Wkd,	Prior_Yr1_NITZ_Wkd,	Prior_Yr2_NITZ_Wkd,	Prior_Yr3_NITZ_Wkd,	Prior_Yr4_NITZ_Wkd,
Life2date_NAll_Day,	Last_5Yrs_NAll_Day,	Last_6Mth_NAll_Day,	CurrentYr_NAll_Day,	Prior_Yr1_NAll_Day,	Prior_Yr2_NAll_Day,	Prior_Yr3_NAll_Day,	Prior_Yr4_NAll_Day,
Life2date_NSitting,	Last_5Yrs_NSitting,	Last_6Mth_NSitting,	CurrentYr_NSitting,	Prior_Yr1_NSitting,	Prior_Yr2_NSitting,	Prior_Yr3_NSitting,	Prior_Yr4_NSitting,
Life2date_NZSS_Pgm,	Last_5Yrs_NZSS_Pgm,	Last_6Mth_NZSS_Pgm,	CurrentYr_NZSS_Pgm,	Prior_Yr1_NZSS_Pgm,	Prior_Yr2_NZSS_Pgm,	Prior_Yr3_NZSS_Pgm,	Prior_Yr4_NZSS_Pgm,
Life2date_NOpn_Pgm,	Last_5Yrs_NOpn_Pgm,	Last_6Mth_NOpn_Pgm,	CurrentYr_NOpn_Pgm,	Prior_Yr1_NOpn_Pgm,	Prior_Yr2_NOpn_Pgm,	Prior_Yr3_NOpn_Pgm,	Prior_Yr4_NOpn_Pgm,
Life2date_NKessei_,	Last_5Yrs_NKessei_,	Last_6Mth_NKessei_,	CurrentYr_NKessei_,	Prior_Yr1_NKessei_,	Prior_Yr2_NKessei_,	Prior_Yr3_NKessei_,	Prior_Yr4_NKessei_,
Life2date_NZM3Fold,	Last_5Yrs_NZM3Fold,	Last_6Mth_NZM3Fold,	CurrentYr_NZM3Fold,	Prior_Yr1_NZM3Fold,	Prior_Yr2_NZM3Fold,	Prior_Yr3_NZM3Fold,	Prior_Yr4_NZM3Fold,

Life2date_NFreeEvt,	Last_5Yrs_NFreeEvt,	Last_6Mth_NFreeEvt,	CurrentYr_NFreeEvt,	Prior_Yr1_NFreeEvt,	Prior_Yr2_NFreeEvt,	Prior_Yr3_NFreeEvt,	Prior_Yr4_NFreeEvt,
Life2date_NZoom_Ot,	Last_5Yrs_NZoom_Ot, Last_6Mth_NZoom_Ot,	CurrentYr_NZoom_Ot,	Prior_Yr1_NZoom_Ot,	Prior_Yr2_NZoom_Ot,	Prior_Yr3_NZoom_Ot,	Prior_Yr4_NZoom_Ot


 FROM Contact_View where DS_MKC_ContactID = '$contactid'";
           // echo $sSQL; die;
            $records = DB::select($sSQL);
            $aDataAS = collect($records)->map(function($x){ return (array) $x; })->toArray();

            $spreadhseet->createSheet();
            $spreadhseet->setActiveSheetIndex(1);
            $spreadhseet->getActiveSheet()->setTitle('Activity Summary');
            $view1 = View::make('lookup.contact-dn-tab-col-2',['record' => $aDataAS[0],'section' => 'summary']);
            $spreadhseet->setActiveSheetIndex(1);
            $reader->setSheetIndex(1);
            $spreadhseet = $reader->loadFromString($view1,$spreadhseet);

            /******************** Activity Summary screen - End ***********************/

            /******************** Activity Detail screen - Start ***********************/

            $filters = $request->input('filters',[]);

            $where = self::Apply2ndScreenFiltersCondition($filters);
            $where  = !empty($where) ? $where.' AND ' : '';

            $sort = $request->input('sort') ? $request->input('sort') : 'sa.Date';
            $dir = $request->input('dir') ? $request->input('dir') : 'DESC';

            $sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";

            $sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,sa.Date, cast(sa.Amount as int) as Amount,sa.Activitycat2,sa.Activitycat1,sa.Activity,sa.Class , sa.ClientMessage from Sales_View  sa INNER JOIN Contact_View ac ON
     $where  sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults";

            ini_set('max_execution_time', 3500);
            ini_set('memory_limit', '1024M');
            ob_clean();

        	$files = glob(public_path().'/downloads/*'); // get all file names
            foreach($files as $file){ // iterate files
                if(is_file($file))
                    unlink($file); // delete file
            }

            if (trim($sSQL) != "") {
                if (strpos($sSQL, "*") === true) {
                    $nSQL = str_replace("*", "TOP 10000 * ", $sSQL);
                } else {
                    $nSQL = substr($sSQL, 0, 6) . " top 10000 " . substr($sSQL, 7, strlen($sSQL));
                }


                $records = DB::select($nSQL);
                $aDataAD = collect($records)->map(function($x){ return (array) $x; })->toArray();
                $spreadhseet->createSheet();
                $spreadhseet->setActiveSheetIndex(2);
                $spreadhseet->getActiveSheet()->setTitle('Activity Detail');
                $sheet = $spreadhseet->getActiveSheet();
                $view2 = View::make('lookup.contact-dn-tab-col-2',['records' => $aDataAD,'section' => 'detail']);
                $spreadhseet->setActiveSheetIndex(2);
                $reader->setSheetIndex(2);
                $spreadhseet = $reader->loadFromString($view2,$spreadhseet);
            }

            /******************** Activity Detail screen - End ***********************/

            /******************** Touch screen - Start ***********************/
			$sort = $request->input('sort') ? $request->input('sort') : 't.rowID';
			$dir = $request->input('dir') ? $request->input('dir') : 'DESC';

            $sort = ($sort == "") ? "Order by t.rowID DESC " : "Order by $sort $dir";

            $sSQL = "SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER,ac.DS_MKC_ContactID,ac.dflname,t.TouchCampaign,t.TouchStatus,t.TouchChannel,t.TouchDate,t.TouchNotes from Touch  t INNER JOIN Contact_View ac ON
     $where t.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults";

     		if (trim($sSQL) != "") {
                if (strpos($sSQL, "*") === true) {
                    $nSQL = str_replace("*", "TOP 10000 * ", $sSQL);
                } else {
                    $nSQL = substr($sSQL, 0, 6) . " top 10000 " . substr($sSQL, 7, strlen($sSQL));
                }


                $records = DB::select($nSQL);
                $aDataAD = collect($records)->map(function($x){ return (array) $x; })->toArray();
                $spreadhseet->createSheet();
                $spreadhseet->setActiveSheetIndex(3);
                $spreadhseet->getActiveSheet()->setTitle('Touches');
                $sheet = $spreadhseet->getActiveSheet();
                $view3 = View::make('lookup.contact-dn-tab-col-2',['records' => $aDataAD,'section' => 'touch']);
                $spreadhseet->setActiveSheetIndex(3);
                $reader->setSheetIndex(3);
                $spreadhseet = $reader->loadFromString($view3,$spreadhseet);
            }
     		/******************** Touch screen - End ***********************/

            $spreadhseet->setActiveSheetIndex(0);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadhseet, 'Xlsx');
            $dflname = !empty(trim($aData[0]['DFLName'])) && !is_null($aData[0]['DFLName'])  ? $aData[0]['DFLName']."_" : "";
            $file_Name = $this->prefix.$dflname. date('Y') . date('m') . date('d');
            $writer->save(public_path()."\\downloads\\".$file_Name.'.xlsx');
            $sBaseUrl = config('constant.BaseUrl');
            return $ajax->success()
                ->jscallback()
                ->form_reset(false)
                ->redirectTo($sBaseUrl . "downloads/" . $file_Name. '.xlsx')
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->jscallback()
                ->message($exception->getMessage())
                ->response();
        }
    }

    public function doMerge(Request $request,Ajax $ajax){
        $MergeKeys = $request->input('MergeKeys');
        $username = $request->input('username');
        if(count($MergeKeys) == 2){
            $_1stid = $MergeKeys[0]['id'];
            $_2ndid = $MergeKeys[1]['id'];

            $db = DB::connection('sqlsrv');
            $stmt = $db->getPdo()->prepare("exec sp_ZSS_Update_Merge $_1stid, $_2ndid, '$username'");
            if($stmt->execute()){
                return $ajax->success()
                    ->response();
            }

        }
    }

    public function secondScreen($contactid, Request $request,Ajax $ajax){

        $records = DB::select("SELECT
Extendedname,Country,DS_MKC_ContactID,DS_MKC_Household_Num,LetterName,JobTitle,ds_mkc_householdid,extendedname,Country,Address,Salutation,Salutation2,City,DharmaName,DharmaName2,State,FirstName
,Firstname2,Zip,MiddleName,Middlename2,Company,lastname,lastname2,suffix,suffix2,gender,Arrival,Transportation,AddressQuality,Nxi_Expand
,phone,phone_type,Phone2,Phone2_type,Email,Email2,Emailable,Opt_Mail,Mailable,ZSS_Segment,MemberSegment,EmailSegment,DonorSegment,EventSegment,LifecycleSegment,Notes
,Suppression,companyinclude,mail_status,contactable,Gender2,DFLName,DFLName2,
TouchCampaign,TouchStatus,TouchChannel,TouchDate,TouchNotes,
FirstSesshinDate,Jukai_Date,Ordainment_Date,email_optout_reason,email_status,email2_status,opt_mail,EmailSegment,
     Life2date_SpendAmt,Last_5Yrs_SpendAmt,	Last_6Mth_SpendAmt,	CurrentYr_SpendAmt,	Prior_Yr1_SpendAmt,	Prior_Yr2_SpendAmt,Prior_Yr3_SpendAmt,	Prior_Yr4_SpendAmt,
Life2date_GiftsAmt,	Last_5Yrs_GiftsAmt,	Last_6Mth_GiftsAmt,	CurrentYr_GiftsAmt,	Prior_Yr1_GiftsAmt,	Prior_Yr2_GiftsAmt,Prior_Yr3_GiftsAmt,	Prior_Yr4_GiftsAmt,
Life2date_MembrAmt,	Last_5Yrs_MembrAmt,	Last_6Mth_MembrAmt,	CurrentYr_MembrAmt,	Prior_Yr1_MembrAmt,	Prior_Yr2_MembrAmt,Prior_Yr3_MembrAmt,	Prior_Yr4_MembrAmt,
Life2date_EventAmt, 	Last_5Yrs_EventAmt, Last_6Mth_EventAmt, 	CurrentYr_EventAmt, 	Prior_Yr1_EventAmt, 	Prior_Yr2_EventAmt, 	Prior_Yr3_EventAmt, 	Prior_Yr4_EventAmt,
Life2date_RtailAmt,	Last_5Yrs_RtailAmt,	Last_6Mth_RtailAmt,	CurrentYr_RtailAmt,	Prior_Yr1_RtailAmt,	Prior_Yr2_RtailAmt,	Prior_Yr3_RtailAmt,	Prior_Yr4_RtailAmt,
Life2date_RentlAmt,	Last_5Yrs_RentlAmt,	Last_6Mth_RentlAmt,	CurrentYr_RentlAmt,	Prior_Yr1_RentlAmt,	Prior_Yr2_RentlAmt,	Prior_Yr3_RentlAmt,	Prior_Yr4_RentlAmt,
Life2date_MisclAmt,	Last_5Yrs_MisclAmt,	Last_6Mth_MisclAmt,	CurrentYr_MisclAmt,	Prior_Yr1_MisclAmt,	Prior_Yr2_MisclAmt,	Prior_Yr3_MisclAmt,	Prior_Yr4_MisclAmt,

Life2date_NActivty,	Last_5Yrs_NActivty,	Last_6Mth_NActivty,	CurrentYr_NActivty,	Prior_Yr1_NActivty,	Prior_Yr2_NActivty,	Prior_Yr3_NActivty,	Prior_Yr4_NActivty,

Life2date_NPaidEvt,	Last_5Yrs_NPaidEvt,	Last_6Mth_NPaidEvt,	CurrentYr_NPaidEvt,	Prior_Yr1_NPaidEvt,	Prior_Yr2_NPaidEvt,	Prior_Yr3_NPaidEvt,	Prior_Yr4_NPaidEvt,


Life2date_NZSS_Ses,	Last_5Yrs_NZSS_Ses,	Last_6Mth_NZSS_Ses,	CurrentYr_NZSS_Ses,	Prior_Yr1_NZSS_Ses,	Prior_Yr2_NZSS_Ses,	Prior_Yr3_NZSS_Ses,	Prior_Yr4_NZSS_Ses,
Life2date_NOpn_Ses,	Last_5Yrs_NOpn_Ses,	Last_6Mth_NOpn_Ses,	CurrentYr_NOpn_Ses,	Prior_Yr1_NOpn_Ses,	Prior_Yr2_NOpn_Ses,	Prior_Yr3_NOpn_Ses,	Prior_Yr4_NOpn_Ses,
Life2date_NITZ_Wkd,	Last_5Yrs_NITZ_Wkd,	Last_6Mth_NITZ_Wkd,	CurrentYr_NITZ_Wkd,	Prior_Yr1_NITZ_Wkd,	Prior_Yr2_NITZ_Wkd,	Prior_Yr3_NITZ_Wkd,	Prior_Yr4_NITZ_Wkd,
Life2date_NAll_Day,	Last_5Yrs_NAll_Day,	Last_6Mth_NAll_Day,	CurrentYr_NAll_Day,	Prior_Yr1_NAll_Day,	Prior_Yr2_NAll_Day,	Prior_Yr3_NAll_Day,	Prior_Yr4_NAll_Day,
Life2date_NSitting,	Last_5Yrs_NSitting,	Last_6Mth_NSitting,	CurrentYr_NSitting,	Prior_Yr1_NSitting,	Prior_Yr2_NSitting,	Prior_Yr3_NSitting,	Prior_Yr4_NSitting,
Life2date_NZSS_Pgm,	Last_5Yrs_NZSS_Pgm,	Last_6Mth_NZSS_Pgm,	CurrentYr_NZSS_Pgm,	Prior_Yr1_NZSS_Pgm,	Prior_Yr2_NZSS_Pgm,	Prior_Yr3_NZSS_Pgm,	Prior_Yr4_NZSS_Pgm,
Life2date_NOpn_Pgm,	Last_5Yrs_NOpn_Pgm,	Last_6Mth_NOpn_Pgm,	CurrentYr_NOpn_Pgm,	Prior_Yr1_NOpn_Pgm,	Prior_Yr2_NOpn_Pgm,	Prior_Yr3_NOpn_Pgm,	Prior_Yr4_NOpn_Pgm,
Life2date_NKessei_,	Last_5Yrs_NKessei_,	Last_6Mth_NKessei_,	CurrentYr_NKessei_,	Prior_Yr1_NKessei_,	Prior_Yr2_NKessei_,	Prior_Yr3_NKessei_,	Prior_Yr4_NKessei_,

Life2date_NZM3Fold,	Last_5Yrs_NZM3Fold,	Last_6Mth_NZM3Fold,	CurrentYr_NZM3Fold,	Prior_Yr1_NZM3Fold,	Prior_Yr2_NZM3Fold,	Prior_Yr3_NZM3Fold,	Prior_Yr4_NZM3Fold,

Life2date_NFreeEvt,	Last_5Yrs_NFreeEvt,	Last_6Mth_NFreeEvt,	CurrentYr_NFreeEvt,	Prior_Yr1_NFreeEvt,	Prior_Yr2_NFreeEvt,	Prior_Yr3_NFreeEvt,	Prior_Yr4_NFreeEvt,
Life2date_NZoom_Ot,	Last_5Yrs_NZoom_Ot, Last_6Mth_NZoom_Ot,	CurrentYr_NZoom_Ot,	Prior_Yr1_NZoom_Ot,	Prior_Yr2_NZoom_Ot,	Prior_Yr3_NZoom_Ot,	Prior_Yr4_NZoom_Ot

			  FROM Contact_View where  DS_MKC_ContactID = $contactid");
        $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

        $html = View::make('lookup.second-screen-col-2',['add'=>false])->render();
        return $ajax->success()
            ->appendParam('html',$html)
            ->appendParam('contactid',$contactid)
            ->appendParam('response',$records[0])
            ->jscallback('ajax_second_screen')
            ->response();
    }

    public static function Apply2ndScreenFiltersCondition($filters){

        $txtActivityCat1 = isset($filters['ActivityCat1']) ? $filters['ActivityCat1'][0] : '';
        $txtActivityCat2 = isset($filters['ActivityCat2']) ? $filters['ActivityCat2'][0] : '';
        $txtActivity = isset($filters['Activity']) ? $filters['Activity'][0] : '';

        $sWhere = "";

        $specWhere = "";
        $aAd = 0;
        if (!empty($txtActivityCat1)) {
            $specWhere .= "ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where activitycat1='" . trim($txtActivityCat1) . "')";
            $aAd++;
        }
        if (!empty($txtActivityCat2)) {
            $specWhere .= $aAd > 0 ? " and " : "";
            $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where activitycat2= '" . trim($txtActivityCat2) . "')";
        }
        if (!empty($txtActivity)) {
            $specWhere .= $aAd > 0 ? " and " : "";
            $specWhere .= " ds_mkc_contactid in (select distinct ds_mkc_contactid from Sales_View where activity like '%" . trim($txtActivity) . "%')";
        }

        if(!empty($specWhere)){
            $sWhere .=  ' WHERE '.$specWhere;
        }

        return !empty($sWhere) ? ' '.$sWhere : '';
    }

    public function SADetails($contactid,Request $request,Ajax $ajax){
        $page = $request->input('page',1);
        $records_per_page = 15;
        $position = ($page-1) * $records_per_page;

        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);

        $where = self::Apply2ndScreenFiltersCondition($filters);
        $where  = !empty($where) ? $where.' AND ' : '';
        $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : '';

        $sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";

        $records = DB::select("SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER, sa.DS_MKC_ContactID,sa.DS_MKC_HouseholdID,sa.Date, cast(sa.Amount as int) as Amount,sa.Activitycat2,sa.Activitycat1,sa.Activity,sa.memo,sa.Account,sa.Class , sa.ClientMessage,sa.customer from Sales_View  sa INNER JOIN Contact_View ac ON
		 $where sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults $sWhere1");

        $all_records = DB::select("SELECT count(*) as count from Sales_View sa INNER JOIN Contact_View ac ON $where sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid");

        $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();

        if($rType == 'pagination'){
            $html = View::make('lookup.Sales-Detail.table',['records' => $records,'contactid' => $contactid])->render();
        }else {
            $html = View::make('lookup.Sales-Detail.SA-Detail', ['records' => $records, 'contactid' => $contactid,
                'add' => false])->render();
        }
        $paginationhtml = View::make('lookup.Sales-Detail.pagination-html',[
            'total_records' => $total_records[0]['count'],
            'records' => $records,
            'position' => $position,
            'records_per_page' => $records_per_page,
            'contactid' => $contactid,
            'page' => $page
        ])->render();
        return $ajax->success()
            ->appendParam('sql',"SELECT * FROM (SELECT ROW_NUMBER() over ($sort) as ROWNUMBER, sa.DS_MKC_ContactID,sa.DS_MKC_HouseholdID,sa.Date, cast(sa.Amount as int) as Amount,sa.Activitycat2,sa.Activitycat1,sa.Activity,sa.memo,sa.Account,sa.Class , sa.ClientMessage,sa.customer from Sales_View  sa INNER JOIN Contact_View ac ON
		 $where sa.DS_MKC_ContactID = ac.DS_MKC_ContactID AND ac.DS_MKC_ContactID = $contactid) _myResults $sWhere1")
            ->appendParam('records',$records)
            ->appendParam('html',$html)
            ->appendParam('pagination_html',$paginationhtml)
            ->jscallback('ajax_SA_Details')
            ->response();
    }

    public function getTouchesDetails($contactid,Request $request,Ajax $ajax){
        $page = $request->input('page',1);
        $records_per_page = 20;
        $position = ($page-1) * $records_per_page;

        $rType = $request->input('rtype','');
        $filters = $request->input('filters',[]);

        //$where = self::Apply2ndScreenFiltersCondition($filters);
        //$sort = $request->input('sort') ? $request->input('sort') : '';
        //$dir = $request->input('dir') ? $request->input('dir') : '';
        //$sort = ($sort == "") ? "Order by sa.Date DESC " : "Order by $sort $dir";

        $records = DB::table('contact')
            ->join('touch', 'touch.DS_MKC_ContactID', '=', 'contact.DS_MKC_ContactID')
            ->where('touch.DS_MKC_ContactID', '=', $contactid)
            ->select('contact.DS_MKC_ContactID','contact.dflname', 'touch.RowID','touch.TouchCampaign','touch.TouchStatus','touch.TouchChannel','touch.TouchDate','touch.TouchNotes')
            ->skip($position)
            ->take($records_per_page)
            ->orderByDesc('touch.rowID')
            ->orderByDesc('touch.TouchDate')
            ->orderBy('touch.ds_mkc_contactid')
            ->get();

        $total_records = DB::table('contact')
            ->join('touch', 'touch.ds_mkc_contactid', '=', 'contact.ds_mkc_contactid')
            ->where('touch.ds_mkc_contactid', '=', $contactid)
            ->select('contact.ds_mkc_contactid')
            ->count();

        if($rType == 'pagination'){
            $html = View::make('lookup.phone.second_screen.table',['records' => $records,'contactid' => $contactid])->render();
        }else {
            $html = View::make('lookup.phone.second_screen.index', ['records' => $records, 'contactid' => $contactid,
                'add' => false])->render();
        }
        $paginationhtml = View::make('lookup.phone.second_screen.pagination-html',[
            'total_records' => $total_records,
            'records' => $records,
            'position' => $position,
            'records_per_page' => $records_per_page,
            'contactid' => $contactid,
            'page' => $page
        ])->render();

        return $ajax->success()
            ->appendParam('html',$html)
            ->appendParam('pagination_html',$paginationhtml)
            ->jscallback('ajax_touch_Details')
            ->response();
    }

    public function addContact(Request $request,Ajax $ajax){
        //$aData = DB::select("select  max(DS_MKC_ContactID)+1 as newids from Contact_View");
        $aData = DB::select("select max(ds_mkc_sequenceid) + 9000001 as newids from contact_full");
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        $newid = 1;
        if(!empty($aData)){
            $newid = $aData[0]['newids'];
        }

        $html = View::make('lookup.second-screen',['add'=>true])->render();
        return $ajax->success()
            ->appendParam('html',$html)
            ->appendParam('newid',$newid)
            ->jscallback('ajax_add_contact')
            ->response();
    }

    public function quickEdit(Request $request,Ajax $ajax){
        try{
            $tablename = 'Contact_View';
            $contactid= $request->input('recordid');
            $fieldname = $request->input('fieldname');
            $username = $request->input('username');
            $texteditor = $request->input('texteditor');
            $aData = DB::table($tablename)
                ->where('DS_MKC_ContactID',$contactid)
                ->get([$fieldname,'firstname','lastname']);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            $oldtexteditor = count($aData) ? $aData[0][$fieldname] : '';
            $firstname = count($aData) ? $aData[0]['firstname'] : '';
            $lastname = count($aData) ? $aData[0]['lastname'] : '';
            $UItype = 'Update';

            DB::table($tablename)
                ->where('DS_MKC_ContactID',$contactid)
                ->update([$fieldname => trim($texteditor)]);

            $db = DB::connection('sqlsrv');
            if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Returned'){

                $stmt1 = $db->getPdo()->prepare("exec sp_ZSS_Update_Returned $contactid, '$username'");
                $stmt1->execute();

                $records = DB::table('Contact_View')
                    ->where('DS_MKC_ContactID',$contactid)
                    ->get(['Extendedname','Country','DS_MKC_ContactID','DS_MKC_Household_Num','LetterName','JobTitle','ds_mkc_householdid','extendedname','Country','Address','Salutation','Salutation2','City','DharmaName','DharmaName2','State','FirstName'
                        ,'Firstname2','Zip','MiddleName','Middlename2','Company','lastname','lastname2','suffix','suffix2','gender','Arrival','Transportation','AddressQuality','Nxi_Expand'
                        ,'phone','phone_type','Phone2','Phone2_type','Email','Email2','opt_email2',
                        'Opt_Email','Emailable','Opt_Mail','Mailable','ZSS_Segment','MemberSegment','EmailSegment','DonorSegment','EventSegment','LifecycleSegment','Notes'
                        ,'Suppression','companyinclude','mail_status','contactable']);

                $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

                return $ajax->success()
                    ->appendParam('contactid',$contactid)
                    ->appendParam('response',$records[0])
                    ->jscallback('ajax_update_detail')
                    ->response();
            }
            if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Updated'){

                $stmt2 = $db->getPdo()->prepare("exec sp_ZSS_Update_Address $contactid");
                $stmt2->execute();

                $UItype = 'AddressUpdate';
            }

            if(trim($fieldname) == 'suppression'){

                $stmt3 = $db->getPdo()->prepare("exec sp_ZSS_Update_Contactable $contactid");
                $stmt3->execute();
            }
            if(trim($fieldname) == 'opt_mail' && trim($texteditor) == 'Optout'){
                $stmt4 = $db->getPdo()->prepare("exec sp_ZSS_Update_Mailable $contactid");
                $stmt4->execute();
            }
            if(trim($fieldname) == 'opt_email' && trim($texteditor) == 'Optout'){
                $stmt5 = $db->getPdo()->prepare("exec sp_ZSS_Update_Emailable $contactid");
                $stmt5->execute();
            }

            if(trim($fieldname) == 'address' || trim($fieldname) == 'state' || trim($fieldname) == 'city' || trim($fieldname) == 'zip' || trim($fieldname) == 'country'){
                $stmt6 = $db->getPdo()->prepare("exec sp_ZSS_Update_Address $contactid");
                $stmt6->execute();
            }

            $data= [
                'Type' => $UItype,
                'Tablename' => trim($tablename),
                'DS_MKC_ContactID' => $contactid,
                'firstname' => trim($firstname),
                'lastname' => trim($lastname),
                'columnname' => trim($fieldname),
                'oldvalue' => trim($oldtexteditor),
                'username' => trim($username),
                'newvalue' => trim($texteditor),
            ];
            DB::table('User_Input')->insert($data);

            $stmt7 = $db->getPdo()->prepare("exec sp_ZSS_Update_EnvelopeLetter $contactid");
            $stmt7->execute();

            $records = DB::table('Contact_View')
                ->where('DS_MKC_ContactID',$contactid)
                ->get(['Extendedname','Country','DS_MKC_ContactID','DS_MKC_Household_Num','LetterName','JobTitle','ds_mkc_householdid','extendedname','Country','Address','Salutation','Salutation2','City','DharmaName','DharmaName2','State','FirstName'
,'Firstname2','Zip','MiddleName','Middlename2','Company','lastname','lastname2','suffix','suffix2','gender','Arrival','Transportation','AddressQuality','Nxi_Expand'
,'phone','phone_type','Phone2','Phone2_type','Email','Email2','opt_email2',
'Opt_Email','Emailable','Opt_Mail','Mailable','ZSS_Segment','MemberSegment','EmailSegment','DonorSegment','EventSegment','LifecycleSegment','Notes'
,'Suppression','companyinclude','mail_status','contactable']);

            $records = collect($records)->map(function($x){ return (array) $x; })->toArray();

            $html = View::make('lookup.contact-tab',['add'=>false])->render();


            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('response',$records[0])
                ->appendParam('contactid',$contactid)
                ->jscallback('ajax_update_detail')
                ->response();
        }catch (\Exception $e){
            return $ajax->fail()
                ->appendParam('message',$e->getMessage())
                ->response();
        }
    }

    public function quickAdd(Request $request,Ajax $ajax){
        try{
            $db = DB::connection('sqlsrv');

            $tablename = 'Contact_View';//$_REQUEST['tablename'];

            $fieldname = $request->input('fieldname');
            $username = $request->input('username');
            $texteditor = $request->input('texteditor');

            DB::insert("insert into contact(DS_MKC_ContactID,ds_mkc_householdid, create_date, update_date) values ((select  max(DS_MKC_ContactID)+1 from Contact_View) , (select  max(DS_MKC_ContactID)+1 from Contact_View), cast (getdate() as date), cast (getdate() as date))");

            $aData = DB::select("select top 1 DS_MKC_ContactID from  Contact_View order by DS_MKC_ContactID desc");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            $contactid= $aData[0]['DS_MKC_ContactID'];
            $oldtexteditor = $firstname = $lastname = '';

            DB::update("UPDATE $tablename SET $fieldname = '".trim($texteditor)."' WHERE DS_MKC_ContactID = $contactid");

            DB::insert("INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,firstname, lastname,columnname,oldvalue,username,newvalue) VALUES ('Insert','".trim($tablename)."',".$contactid.", '".trim($firstname)."', '".trim($lastname)."', '".trim($fieldname)."', '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."')");

            $stmt = $db->getPdo()->prepare("exec sp_ZSS_Update_EnvelopeLetter $contactid");
            $stmt->execute();
            return $ajax->success()
                ->jscallback('ajax_quick_add')
                ->appendParam('contactid',$contactid)
                ->message('Congratulations! You successfully added a contact.')
                ->response();
        }catch (Exception $e){
            return $ajax->fail()
                ->message($e->getMessage())
                ->response();
        }
    }

    public function manualSaveOld(Request $request,Ajax $ajax){
        $tablename = $request->input('tablename');
        $process_type = $request->input('process_type');
        $username = $request->input('username');
        $elementsdata = $request->input('elementsdata');

        $oldtexteditor = $firstname = $lastname = '';
        if($process_type == 'new'){

            //DB::insert("insert into contact(DS_MKC_ContactID,ds_mkc_householdid, create_date, update_date) values ((select  max(DS_MKC_ContactID)+1 from Contact_View) , (select  max(DS_MKC_ContactID)+1 from Contact_View), cast (getdate() as date), cast (getdate() as date))");

            DB::insert("insert into contact_full(DS_MKC_ContactID,create_date, update_date,ds_mkc_source_feed) values ((select  max(DS_MKC_sequenceID)+90000001 from Contact_full) , cast (getdate() as date), cast (getdate() as date),'Manual')");

            //$aData = DB::select("select DS_MKC_ContactID  from contact_full where ds_mkc_sequenceid = max(ds_mkc_sequenceid)");
            $aData = DB::select("select top 1 DS_MKC_ContactID  from contact_full ORDER BY DS_MKC_ContactID DESC");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

            $contactid= $aData[0]['DS_MKC_ContactID'];
            DB::insert("insert into contact (DS_MKC_ContactID,create_date, update_date,ds_mkc_source_feed) values ($contactid , cast (getdate() as date), cast (getdate() as date),'Manual')");

            $msg = 'Congratulations! You successfully added a contact.';
        }else{
            $contactid = $request->input('contactid');
            $msg = 'You successfully updated a contact.';
        }



        $notAllowed = [];
        $notAllowedUI = ['mail_statusReturned'];
        $mailStatus = $suppression = $opt_mail = $opt_email = $Update_Address = false;
        $is_updatable = false;
        $tablename = 'contact_full';
        $sSqlUpdate = "UPDATE $tablename SET import_date=cast (getdate() as date), ds_mkc_householdid=$contactid, ds_mkc_source_multiplier=$contactid,";

        $sSqlUpdate1 = "UPDATE Contact SET import_date=cast (getdate() as date), ds_mkc_householdid=$contactid,";

        $fields = $values = '';
        foreach($elementsdata as $key => $element){
            if(!in_array($element['name'], $notAllowed)){
                $fieldname = trim($element['name']);
                $texteditor = !empty(trim($element['value'])) ? trim($element['value']) : null ;

                $element['value'] = !empty(trim($element['value'])) ? trim($element['value']) : null ;

                if(!in_array($fieldname.$texteditor,$notAllowedUI)){
                    if ($process_type == 'old'){

                        $aData = DB::table($tablename)
                            ->where('DS_MKC_ContactID',$contactid)
                            ->get([$fieldname,'firstname','lastname']);
                        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

                        $oldtexteditor = count($aData) ? trim($aData[0][$fieldname]) : '';
                        $firstname = count($aData) ? $aData[0]['firstname'] : '';
                        $lastname = count($aData) ? $aData[0]['lastname'] : '';
                        if($oldtexteditor != $texteditor){
                            $UIType = $fieldname.$texteditor == 'mail_statusUpdated' ? 'AddressUpdate' : 'Update';
                            DB::insert("INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,firstname, lastname,columnname,oldvalue,username,newvalue) VALUES ('".$UIType."','Contact',".$contactid.", '".trim($firstname)."', '".trim($lastname)."', '".trim($fieldname)."', '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."')");
                        }

                    }
                    else {
                        DB::insert("INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,firstname, lastname,columnname,oldvalue,username,newvalue) VALUES ('Insert','Contact',".$contactid.", '".trim($firstname)."', '".trim($lastname)."', '".trim($fieldname)."', '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."')");
                    }
                }

                $com = (count($elementsdata) - 1) > $key  ? ', ' : ' ';
                if(!is_null($element['value'])){
                    $sSqlUpdate .= $element['name']." = N'".$element['value']."'".$com;
                    $sSqlUpdate1 .= $element['name']." = N'".$element['value']."'".$com;
                    $fields .= $element['name'].$com;
                    $values .= " N'".$element['value']."'".$com;
                }else{
                    $sSqlUpdate .= $element['name']." = null".$com;
                    $sSqlUpdate1 .= $element['name']." = null".$com;
                    $fields .= $element['name'].$com;
                    $values .= " null".$com;
                }
                $is_updatable = true;

                if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Returned'){
                    $mailStatus = true;
                }

                if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Updated'){
                    $Update_Address = true;
                }

                if(trim($fieldname) == 'suppression'){
                    $suppression = true;
                }
                if(trim($fieldname) == 'opt_mail' && trim($texteditor) == 'Optout'){
                    $opt_mail = true;
                }
                if(trim($fieldname) == 'opt_email' && trim($texteditor) == 'Optout'){
                    $opt_email = true;
                }

                if(trim($fieldname) == 'address' || trim($fieldname) == 'state' || trim($fieldname) == 'city' || trim($fieldname) == 'zip' || trim($fieldname) == 'country'){
                    $Update_Address = true;
                }
            }
        }

        if($is_updatable == true){
            $sSqlUpdate .= "WHERE DS_MKC_ContactID = ".$contactid;
            DB::update($sSqlUpdate);

            $sSqlUpdate1 .= "WHERE DS_MKC_ContactID = ".$contactid;
            DB::update($sSqlUpdate1);

            $sSqlUpdate2 = "UPDATE $tablename SET suppression = 'None' where suppression is null";
            DB::update($sSqlUpdate2);

            // $sSqlInsert .= " (Create_Date, Import_Date,ds_mkc_householdid, ds_mkc_contactid,".$fields.") VALUES (cast(getdate() as date), cast(getdate() as date),".$contactid.",".$contactid.",".$values.")";
            //echo $sSqlInsert; die;
            //DB::insert($sSqlInsert);
            $sSqlUpdate3 = "UPDATE contact SET suppression = 'None' where suppression is null";
            DB::update($sSqlUpdate3);
        }

        if($mailStatus){
            DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_Returned $contactid, '$username'");
        }

        if($suppression){
            DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_Contactable $contactid");
        }
        if($opt_mail){
            DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_Mailable $contactid");
        }
        if($opt_email){
            DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_Emailable $contactid");
        }

        if($Update_Address){
            DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_Address $contactid");
        }

        DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_EnvelopeLetter $contactid");

        return $ajax->success()
            ->appendParam('process_type',$process_type)
            ->appendParam('sSqlUpdate',$sSqlUpdate)
            //->appendParam('sSqlInsert',$sSqlInsert)
            ->appendParam('contactid',$contactid)
            ->message($msg)
            ->jscallback('ajax_manual_add')
            ->response();
    }

    public function manualSave(Request $request,Ajax $ajax){
        $tablename = $request->input('tablename');
        $process_type = $request->input('process_type');
        $username = $request->input('username');
        $elementsdata = $request->input('elementsdata');
        $db = DB::connection('sqlsrv');

        $oldtexteditor = $firstname = $lastname = '';
        if($process_type == 'new'){
            $stmt = $db->getPdo()->prepare("exec sp_ZSS_Update_Add ". Auth::user()->User_ID);
            $stmt->execute();

            $aData = DB::select("select max(DS_MKC_ContactID) as DS_MKC_ContactID  from contact");
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            $contactid= $aData[0]['DS_MKC_ContactID'];

            $msg = 'Congratulations! You successfully added a contact.';
        }else{
            $contactid = $request->input('contactid');
            $msg = 'You successfully updated a contact.';
        }

        $notAllowed = ['touchcampaign','touchstatus','touchchannel','touchdate','touchnotes'];
        //$notAllowedUI = ['mail_statusReturned'];
        $notAllowedUI = [''];
        $mailStatus = $suppression = $opt_mail = $opt_email = $Update_Address = false;
        $is_updatable = false;
        $tablename = 'contact_full';
        $sSqlUpdate = "UPDATE $tablename SET import_date=cast (getdate() as date), ds_mkc_householdid=$contactid, ds_mkc_source_multiplier=$contactid,";

        $sSqlUpdate1 = "UPDATE Contact SET import_date=cast (getdate() as date), ds_mkc_householdid=$contactid,";
        $is_touch = false;
        $sSqlInsertTouch = "Insert Touch ([DS_MKC_ContactID],[TouchStatus],[TouchChannel],[TouchCampaign],[TouchDate],[TouchNotes]) Values ($contactid,";
        $fields = $oldvalues = $values = '';
        foreach($elementsdata as $key => $element){
            $fieldname = trim($element['name']);
            $texteditor = !empty(trim($element['value'])) ? trim($element['value']) : null ;
            $element['value'] = !empty(trim($element['value'])) ? trim($element['value']) : null ;
            $com = (count($elementsdata) - 1) > $key  ? ', ' : ' ';

            if(!in_array($element['name'], $notAllowed)){
                if(!in_array($fieldname.$texteditor,$notAllowedUI)){
                    if ($process_type == 'old'){

                        $aData = DB::table($tablename)
                            ->where('DS_MKC_ContactID',$contactid)
                            ->get([$fieldname,'firstname','lastname']);
                        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();

                        $oldtexteditor = count($aData) ? trim($aData[0][$fieldname]) : '';
                        if($oldtexteditor != $texteditor){
                            $oldvalues .= " N'".$oldtexteditor."'".$com;
                        }else{
                            if(!is_null($texteditor)){
                                $oldvalues .= " N'".$texteditor."'".$com;
                            }else{
                                $oldvalues .= "''".$com;
                            }
                        }
                    }
                }

                if(!is_null($element['value'])){
                    $sSqlUpdate .= $element['name']." = N'".$element['value']."'".$com;
                    $sSqlUpdate1 .= $element['name']." = N'".$element['value']."'".$com;
                    $fields .= $element['name'].$com;
                    $values .= " N'".$element['value']."'".$com;

                }else{
                    $sSqlUpdate .= $element['name']." = ''".$com;
                    $sSqlUpdate1 .= $element['name']." = ''".$com;
                    $fields .= $element['name'].$com;
                    $values .= " ''".$com;
                }
                $is_updatable = true;

                if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Returned'){
                    $mailStatus = true;
                }

                if(trim($fieldname) == 'mail_status' && trim($texteditor) == 'Updated'){
                    $Update_Address = true;
                }

                if(trim($fieldname) == 'suppression'){
                    $suppression = true;
                }
                if(trim($fieldname) == 'opt_mail' && trim($texteditor) == 'Optout'){
                    $opt_mail = true;
                }
                if(trim($fieldname) == 'opt_email' && trim($texteditor) == 'Optout'){
                    $opt_email = true;
                }
                if(trim($fieldname) == 'email'){
                    $email = true;
                }
                if(trim($fieldname) == 'email2'){
                    $email2 = true;
                }

                if(trim($fieldname) == 'address' || trim($fieldname) == 'state' || trim($fieldname) == 'city' || trim($fieldname) == 'zip' || trim($fieldname) == 'country'){
                    $Update_Address = true;
                }
            }else{

                //For Touches
                $aData = DB::table('Touch')
                    ->where('DS_MKC_ContactID',$contactid)
                    ->orderByDESC('RowID')->get([$fieldname]);
                $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
                //echo '<pre>'; print_r($aData); die;
                $oldtexteditor = count($aData) > 0 ? trim($aData[0][$fieldname]) : '';
                $com = trim($element['name']) != 'touchnotes' ? $com : '';
                if($oldtexteditor != $texteditor){
                    $oldvalues .= " N'".$oldtexteditor."'".$com;
                    if(!is_null($element['value'])){
                        $sSqlInsertTouch .= "N'".$element['value']."'".$com;
                    }else{
                        $sSqlInsertTouch .= "''".$com;
                    }
                    if(!$is_touch){
                        $is_touch = true;
                    }
                }else{
                    if(!is_null($oldtexteditor)){
                        $sSqlInsertTouch .= "N'".$oldtexteditor."'".$com;
                    }else{
                        $sSqlInsertTouch .= "''".$com;
                    }
                }
            }
        }

        if($is_touch){
            $sSqlInsertTouch .= ")";
            DB::insert($sSqlInsertTouch);
        }

        if($is_updatable == true){
            $sSqlUpdate .= "WHERE DS_MKC_ContactID = ".$contactid;
            DB::update($sSqlUpdate);

            $sSqlUpdate1 .= "WHERE DS_MKC_ContactID = ".$contactid;
            DB::update($sSqlUpdate1);

            if($process_type == 'new' && !$is_touch){
                $sSqlInsert = "INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,oldvalue,username,newvalue,".$fields.",new) VALUES ('Insert','Contact',".$contactid.", '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."',".$values.",1)";
                DB::insert($sSqlInsert);

            }else if($process_type == 'old' && !$is_touch){
                $aData = DB::table('User_Input')
                    ->where('DS_MKC_ContactID',$contactid)
                    ->where('Type','Update')
                    ->where('New',1)
                    ->first();
                if($aData){
                    DB::table('User_Input')
                       ->where('DS_MKC_ContactID',$contactid)
                       ->where('Type','Update')
                       ->where('New',1)
                       ->delete();

                    $sSqlInsert = "INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,oldvalue,username,newvalue,".$fields.",New) VALUES ('Update','Contact',".$contactid.", '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."',".$values.",1)";
                    //echo $sSqlInsert; die;
                    DB::insert($sSqlInsert);
                }else{
                    $sSqlInsert = "INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,oldvalue,username,newvalue,".$fields.",New) VALUES ('Update','Contact',".$contactid.", '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."',".$oldvalues.",0)";
                    DB::insert($sSqlInsert);

                    $sSqlInsert = "INSERT INTO User_Input (Type,Tablename,DS_MKC_ContactID,oldvalue,username,newvalue,".$fields.",New) VALUES ('Update','Contact',".$contactid.", '".trim($oldtexteditor)."', '".trim($username)."','".trim($texteditor)."',".$values.",1)";
                    DB::insert($sSqlInsert);
                }
            }
        }

        if($mailStatus){
            $stmt1 = $db->getPdo()->prepare("exec sp_ZSS_Update_Returned $contactid, '$username'");
            $stmt1->execute();
        }

        if($suppression){
            $stmt2 = $db->getPdo()->prepare("exec sp_ZSS_Update_Contactable $contactid");
            $stmt2->execute();
        }
        if($opt_mail){
            $stmt3 = $db->getPdo()->prepare("exec sp_ZSS_Update_Mailable $contactid");
            $stmt3->execute();
        }
        if($opt_email){
            $stmt4 = $db->getPdo()->prepare("exec sp_ZSS_Update_Emailable $contactid");
            $stmt4->execute();
        }
        //email and also email2[sp_ZSS_Update_Email]/[sp_ZSS_Update_Email2]/
        if($email){
            $stmt5 = $db->getPdo()->prepare("exec sp_ZSS_Update_Email $contactid");
            $stmt5->execute();
        }
        if($email2){
            $stmt6 = $db->getPdo()->prepare("exec sp_ZSS_Update_Email2 $contactid");
            $stmt6->execute();
        }

        if($Update_Address){
            $stmt7 = $db->getPdo()->prepare("exec sp_ZSS_Update_Address $contactid");
            $stmt7->execute();
        }

        $stmt8 = $db->getPdo()->prepare("exec sp_ZSS_Update_EnvelopeLetter $contactid");
        $stmt8->execute();

        return $ajax->success()
            ->appendParam('process_type',$process_type)
            ->appendParam('sSqlUpdate',$sSqlUpdate)
            ->appendParam('contactid',$contactid)
            ->message($msg)
            ->jscallback('ajax_manual_add')
            ->response();
    }

    public function testDn(){
        $data = [12,"Hey",123,4234,5632435,"Nope",345,345,345,345];
        Excel::download($data,'Report2016.xlsx',public_path());
    }

    public function findDupes($type,Request $request,Ajax $ajax){
        $db = DB::connection('sqlsrv');

        $sort = $request->input('sort') ? $request->input('sort') : '';
        $dir = $request->input('dir') ? $request->input('dir') : 'DESC';
        $page = $request->input('page',1);
        $records_per_page = 4; //config('constant.record_per_page');
        $position = ($page-1) * $records_per_page;

        $rType = $request->input('rtype','');
        $sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

        $sort = ($sort == "" || $sort == "select") ? "Order by ds_contactid_s2, ds_mkc_contactid  ASC " : "Order by $sort $dir";

        if($type == 'tight'){
            $stmt1 = $db->getPdo()->prepare("EXEC dbo.sp_ZSS_Update_Merge_Bulk_Step1_Tight");
            $stmt1->execute();
        }elseif ($type == 'loose'){
            $stmt2 = $db->getPdo()->prepare("EXEC dbo.sp_ZSS_Update_Merge_Bulk_Step1_Loose");
            $stmt2->execute();
        }elseif ($type == 'newtight'){
            $stmt3 = $db->getPdo()->prepare("EXEC dbo.sp_ZSS_Update_Merge_Bulk_Step1_Tight_New");
            $stmt3->execute();
        }elseif ($type == 'newloose'){
            $stmt4 = $db->getPdo()->prepare("EXEC dbo.sp_ZSS_Update_Merge_Bulk_Step1_Loose_New");
            $stmt4->execute();
        }

        $sSQL = DB::select("SELECT * from (SELECT  DENSE_RANK() OVER (ORDER BY s.ds_contactid_s2) as  ROWNUMBER ,s.ds_contactid_s2, s.ds_mkc_contactid,s.ds_mkc_householdid, email, email2,dqcode_email, phone, extendedname , Company,  address , city , state, zip,dqcode_address,isnull(tag,0) as tag from Contact_View s inner join (select distinct ds_contactid_s2 from contact where  ds_contactid_s2 <> ds_mkc_contactid)  o on s.ds_contactid_s2= o.ds_contactid_s2) _myResults $sWhere1 $sort");

        $records = collect($sSQL)->map(function($x){ return (array) $x; })->toArray();

        $tSQL = DB::select("select count(distinct(ds_contactid_s2)) as count from contact where  ds_contactid_s2 <> ds_mkc_contactid");

        $totalrecords = collect($tSQL)->map(function($x){ return (array) $x; })->toArray();

        $paginationhtml = View::make('lookup.find-duplicate.pagination-html',[
            'total_records' => count($totalrecords) > 0 ? $totalrecords[0]['count'] : 0,
            'records' => $records,
            'position' => $position,
            'records_per_page' => $records_per_page,
            'page' => $page,
            'atype' => $type
        ])->render();

        if($rType == 'pagination'){
            $html = View::make('lookup.find-duplicate.table',[
                'records' => $records
            ])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('paginationHtml',$paginationhtml)
                ->appendParam('qry', "SELECT * from (SELECT  DENSE_RANK() OVER (ORDER BY s.ds_contactid_s2) as  ROWNUMBER ,s.ds_contactid_s2, s.ds_mkc_contactid,s.ds_mkc_householdid, email, phone, extendedname , Company,LetterName,  address , city , state, zip, zss_segment from Contact_View s inner join (select distinct ds_contactid_s2 from contact where  ds_contactid_s2 <> ds_mkc_contactid)  o on s.ds_contactid_s2= o.ds_contactid_s2) _myResults $sWhere1 $sort")
                ->jscallback()
                ->response();
        }else{
            $content = View::make('lookup.find-duplicate.popup',[
                'records' => $records,
                'type' => $type
            ])->render();
        }
        $btn = '';
        if(count($records) > 0){
            $btn = '<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
        <div class="input-group pull-right">
            <button type="button" class="btn btn-info font-12 s-f" title="Merge Contact" onclick="mergeBulk()" id="mergeContactBtn">Merge</button>
        </div>
    </div>';
        }

        $sdata = [
            'content' => "<div class='row'><div class='col-md-6'><div class='dupes-page'>".$paginationhtml."</div><div class='loading-info' style='display: none;'>Loading...</div></div><div class='col-md-6'>".$btn."</div></div>".$content
        ];

        if(strtolower($type) == 'newloose'){
            $lb = 'Possible Duplicate Contacts Identified via Loose Match - New Records';

        }elseif (strtolower($type) == 'newtight'){
            $lb = 'Possible Duplicate Contacts Identified via Tight Match - New Records';

        }else{
            $lb = 'Possible Duplicate Contacts Identified via '.ucfirst($type).' Match';
        }

        $title = $lb;
        $size = 'modal-xxl';

        if (isset($title)) {
            $sdata['title'] = $title;
        }
        if (isset($size)) {
            $sdata['size'] = $size;
        }

        $view = View::make('layouts.modal-popup-layout', $sdata);
        $html = $view->render();

        return $ajax->success()->appendParam('html', $html)->jscallback('loadModalLayout')->response();

    }

    public function bulkMerge(Request $request,Ajax $ajax){
        $mids = $request->input('mids');
        $type = $request->input('type');
        //echo '<pre>'; print_r(Auth::user()); die;
        //$nmids = array_values(array_unique($mids));
        $db = DB::connection('sqlsrv');

        try{
            $res = '';
            if($type == 'loose'){
                $sp = 'sp_ZSS_Update_Merge_Bulk_Step2_Loose';
            }else if ($type == 'tight'){
                $sp = 'sp_ZSS_Update_Merge_Bulk_Step2_Tight';
            }else if ($type == 'newtight'){
                $sp = 'sp_ZSS_Update_Merge_Bulk_Step2_Tight_New';
            }else if ($type == 'newloose'){
                $sp = 'sp_ZSS_Update_Merge_Bulk_Step2_Loose_New';
            }

            foreach ($mids as $cid1 => $cid2){
                $stmt1 = $db->getPdo()->prepare("EXEC dbo.".$sp." ".$cid1.",".$cid2.",'".Auth::user()->User_Confirm."'");
                $stmt1->execute();
                //echo "######## SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec ".$sp." ".$cid1.",".$cid2.",'".Auth::user()->User_Confirm."'";
            }
            return $ajax->success()->appendParam('res',$res)->jscallback('ajax_bulk_merge')->response();
        }catch (\Exception $exception){
            return $ajax->fail()->appendParam('res',$res)->jscallback('ajax_bulk_merge')->response();
        }
    }

    public function reviewContact(Request $request,Ajax $ajax){
        $actionType = $request->input('actionType','tag');
        $tag = $request->input('tag',0);
        $type = $request->input('type','con');
        $contactid = $request->input('contactid',0);

        if($actionType == 'tag'){
            $updateCol  =  ($tag == 1) ? "tag = 1" : "tag = 0";
            $msg = ($tag == 1) ? "Tagged" : "";
            DB::update("update contact set $updateCol where ds_mkc_contactid = ".$contactid);
            ($tag == 1) ? $ajax->message('Contact '.$msg.' Successfully.') : '';

        }elseif ($actionType== 'call'){
            $msg = ($tag == 1) ? 'Touch' : '';
            if($tag == 1) {
                DB::insert("INSERT Touch ([ds_mkc_contactid],[TouchCampaign],[TouchStatus],[TouchChannel],[TouchDate],[TouchNotes]) values ($contactid,'','Called','Phone',getdate(),'')");
                $ajax->message('Contact '.$msg.' Successfully.');
            }
        }
        if(!empty($msg)){

        }


        return $ajax->fail()
            ->jscallback()
            ->response();
    }

    public function testReader(){
        //DB::statement(DB::raw("exec sp_CRM_Update_Bulkimport_S1_ForCASS_P1"));
        //DB::statement(DB::raw("exec sp_CRM_Update_Bulkimport_S1_ForCASS_P2"));
        DB::statement(DB::raw("exec sp_CRM_Update_Bulkimport_S1_ForCASS"));

         //   echo '<pre>'; print_r($schdeules); die;
    }
}
