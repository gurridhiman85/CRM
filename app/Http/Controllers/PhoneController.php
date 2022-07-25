<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Model\CampaignTemplate;
use App\Model\ReportTemplate;
use App\Model\UAFieldMapping;
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
            'ZSS_Segments' => false,
            'MemberSegments' => false,
            'AddressQualities' => false,
            'DonorSegments' => false,
            'EventSegments' => false,
            'LifecycleSegments' => false,
            'Productcat1_Des'      =>  false,
            'Productcat2_Des'      =>  false,
            'Product'          =>  false,
        ]);

        /*$lLookupFirstScreen = Helper::getColumns('Phone','Catalog');
        $lLookupFirstScreenFilters = Helper::getFilterValues($lLookupFirstScreen['filter_columns']);*/

        $record = ReportTemplate::where('Attach_Phone',1)
            ->first();
        $report_row_id = 0;
        if($record){
            $report_row_id = $record->row_id;
        }

        $levels = UAFieldMapping::distinct()
            ->where('menu_level1', 'Phone')
            ->pluck('menu_level2')
            ->toArray();
        $jslevel = [];
        foreach ($levels as $level){
            $lLevelColumns = Helper::getColumns('Phone',$level);
            $lLevelFilters[$level] = Helper::getFilterValues($lLevelColumns['visible_columns']);
            $jslevel[] = str_replace('-','_h_',str_replace(' ','_',$level));
        }



        return view('lookup.phone.index',[
            'campaigns' => $filtersFieldsValues['campaigns'],
            'countries' => $filtersFieldsValues['countries'],
            'lLevelFilters'       => $lLevelFilters,
            'alllevels' => $levels,
            'jslevel' => $jslevel,
            /*'ZSS_Segments'=>$filtersFieldsValues['ZSS_Segments'],
            'MemberSegments' => $filtersFieldsValues['MemberSegments'],
            'AddressQualities' => $filtersFieldsValues['AddressQualities'],
            'DonorSegments' => $filtersFieldsValues['DonorSegments'],
            'EventSegments' => $filtersFieldsValues['EventSegments'],
            'LifecycleSegments'=>$filtersFieldsValues['LifecycleSegments'],
            'Productcat1_Des'      =>  $filtersFieldsValues['Productcat1_Des'],
            'Productcat2_Des'      =>  $filtersFieldsValues['Productcat2_Des'],
            'Product'          =>  $filtersFieldsValues['Product'],*/
            //'lLookupFirstScreenFilters'         =>  $lLookupFirstScreenFilters,
            'report_row_id' => $report_row_id
        ]);
    }

    public static function implement_query($reqlevel,$filters = [],$pagination,$add_sort_end = true,$sort = ''){
        $lLevel = Helper::getColumns('Phone',$reqlevel);
        $sort = empty($sort) ? $lLevel['sort'] : $sort;

        $levels = [
            $reqlevel => [
                'columns' => $lLevel['all_columns'],
                'visible_columns' => $lLevel['visible_columns'],
                'filter_columns' => $lLevel['filter_columns'],
                'sql'   => 'select '.implode(',',$lLevel['all_columns']).' from (SELECT *,ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS ROWNUMBER FROM '.$lLevel['table_name'].' ?where?) as t',
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
        if($tabid == 'single_campaign'){ // Outer Metadata
            $campaigns = CampaignTemplate::orderByDesc('t_id')->get(['t_id','t_name']);
            //dd($campaigns);
            $html = View::make('lookup.phone.Singlecamp.index',['campaigns' => $campaigns])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('paginationHtml','')
                ->jscallback('load_ajax_tab')
                ->response();
        }

        $sort_column = $request->input('sort_column','');
        $sort_dir = $request->input('sort_dir','DESC');

        if(!empty($sort_column)){
            $sort = "Order by ".$sort_column." ".$sort_dir." ";
        }else{
            $sort = "Order by TouchStatus DESC ";
        }

        //if($tabid == 20){

            /*$whereData = Helper::ApplyFiltersCondition($filters,Auth::user()->User_ID);
            $urCon = !empty($whereData['urCon']) ? ' WHERE ' . $whereData['urCon']. ' AND isnull(TouchCampaign,\'\') <> \'\'' : ' WHERE isnull(TouchCampaign,\'\') <> \'\'';
            $lookupClause = !empty($whereData['lookupWhere']) ? ' WHERE '.$whereData['lookupWhere'] : '';
            $phoneClause = !empty($whereData['phoneWhere']) ? $whereData['phoneWhere'] : '';
            $salesClause = !empty($whereData['salesWhere']) ? $whereData['salesWhere'] : '';*/

            //$sWhere1 = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

           /* $sSql = "SELECT * from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt,
            TouchDate,TouchStatus,TouchNotes,TouchCampaign from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle, Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By TouchDate  DESC,touchcampaign desc,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b $sWhere1";
            $records = DB::select($sSql);

            $all_records = DB::select("SELECT count(*) as count from (select *, row_number () over (partition by rown Order by touchcampaign  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle,Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment,
            TouchDate,TouchStatus,TouchNotes,TouchCampaign from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Email,EmailSegment,email2,phone,dqcode_phone,Extendedname, Company,JobTitle, Address,City,State,Zip,dqcode_address,Salutation,DharmaName,Firstname,Middlename,Lastname,Suffix,Salutation2,DharmaName2,FirstName2,Middlename2,Lastname2,Suffix2,Life2date_SpendAmt,isnull(tag,0) as tag,update_date,ZSS_Segment from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By touchcampaign desc,touchdate  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a )b");
            $total_records = collect($all_records)->map(function($x){ return (array) $x; })->toArray();*/

            $tabidWS = str_replace('_h_','-',$tabid);
            $tabidWS = str_replace('_',' ',$tabidWS);
            $pagination = " WHERE ROWNUMBER > $position and ROWNUMBER <= " . ($position + $records_per_page);

            $result = self::implement_query($tabidWS,$filters,$pagination,false,$sort);
            $tabName = 'Phone';


            if($tabidWS == 'Catalog'){
                array_splice( $result['visible_columns'], 1, 0, [[
                    'Field_Name' => 'call',
                    'Field_Display_Name' => 'Call',
                    'Class_Name'    => 'text-center',
                    'Field_Visibility'  => 2,
                    'Editable'  => null,
                ]] );
            }

            $data = [
                'records' => $result['records'],
                'contactids' => $contactids,
               // 'lookupClause' => $lookupClause,
                'visible_columns' => $result['visible_columns'],
                //'urCon' => $urCon,
                //'phoneClause' => $phoneClause,
               // 'salesClause' => $salesClause,
               // 'sWhere1' => $sWhere1,
                'sort_column' => $sort_column,
                'sort_dir' => $sort_dir,
                'tab' => $tabName,
                'filters' => json_encode($filters)
            ];
            if($rType == 'pagination'){
                $html = View::make('lookup.phone.table',$data)->render();
            }else{
                $html = View::make('lookup.phone.first-screen',$data)->render();
            }

            $paginationhtml = View::make('lookup.phone.pagination-html',[
                'total_records' => $result['total_records'],
                'records' => $result['records'],
                'position' => $position,
                'records_per_page' => $records_per_page,
                'page' => $page
            ])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('records',$result['records'])
                ->appendParam('paginationHtml',$paginationhtml)
                ->appendParam('total_records',$result['total_records'])
                ->appendParam('sSql',$result['sql'])
                ->jscallback('load_ajax_tab')
                ->response();
        //}
    }

    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function getFirstScreenIndex()
    {
        return view('phone.index');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFirstScreenData(Request $request)
    {

        $table_columns = $request->input('columns');
        $table_order = $request->input('order');
        //dd($table_columns[$table_order[0]['column']]['data']);
        $finalClause = $request->input('finalClause');

        $lookupClause = $request->input('lookupClause');
        $urCon = $request->input('urCon');
        $phoneClause = $request->input('phoneClause');
        $salesClause = $request->input('salesClause');

        $sWhere1 = $request->input('sWhere1');
        $sort_column = $table_order[0]['column'] == 0 ? '' : $table_columns[$table_order[0]['column']]['data'];
        $sort_dir = $table_order[0]['dir'];

        $sort = ($sort_column == "" || $sort_column == "select") ? "Order by TouchCampaign desc,TouchDate  DESC, TouchStatus  DESC " : "Order by $sort_column $sort_dir";

        $sSql = "SELECT * from (select *, row_number () over (partition by rown Order by TouchCampaign desc,TouchDate  DESC, TouchStatus  DESC , ds_mkc_householdid ) as ROWNUMBER  from (SELECT ROW_NUMBER() over (partition by c.DS_MKC_ContactID $sort) as ROWN,

c.DS_MKC_ContactID,c.DS_MKC_HouseholdID,TouchCampaign,TouchStatus,Extendedname,phone,Email,email2,Address,City,State,Zip,Company,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt,CurrentYr_DonorAmt,Last_2Yrs_DonorAmt,Life2date_donoramt,dayssincelastvisit,EmailSegment,TouchNotes,TouchDate from (select dS_MKC_ContactID,DS_MKC_HouseholdID,mgr1,mgr2,Extendedname,phone,Email,email2,Address,City,State,Zip,Company,update_date,ZSS_Segment,Last_3Yrs_GiftsAmt,Life_BHse_GiftsAmt,CurrentYr_DonorAmt,Last_2Yrs_DonorAmt,Life2date_donoramt,EmailSegment from Contact_View ".$lookupClause.")  c inner join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By TouchCampaign desc,TouchDate  DESC, TouchStatus  DESC,rowID DESC) as ROWNUMBERt, * from touch t ".$phoneClause.") t1 where rownumbert=1) t  on c.ds_mkc_contactid=t.ds_mkc_contactid
left join (select * from  (select ROW_NUMBER() over (partition by DS_MKC_contactid   Order By date  DESC) as ROWNUMBERs, * from sales_view ".$salesClause." ) s1 where rownumbers=1) s on c.ds_mkc_contactid=s.ds_mkc_contactid ".$urCon." ) a ) b";
        try{
            $records = DB::select($sSql);

            return Datatables::of($records)
                ->addColumn('Call',function ($data){
                    $class = 'badge badge-light';
                    if($data->TouchDate != null){
                        switch ($data->TouchStatus){
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
                    return '<span class="'.$class.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                })
                ->addColumn('DS_MKC_ContactID',function ($data){
                    return '<a href="javascript:void(0);" class="text-decoration-none ajax-Link" data-href="lookup/secondscreen/'.$data->DS_MKC_ContactID.'">'.$data->DS_MKC_ContactID.'</a>';
                })
                ->addColumn('Last_3Yrs_GiftsAmt',function ($data){
                    $Last_3Yrs_GiftsAmt = isset($data->Last_3Yrs_GiftsAmt) ? number_format($data->Last_3Yrs_GiftsAmt) : 0;
                    return $Last_3Yrs_GiftsAmt;
                })
                ->addColumn('Life_BHse_GiftsAmt',function ($data){
                    $Life_BHse_GiftsAmt = isset($data->Life_BHse_GiftsAmt) ? number_format($data->Life_BHse_GiftsAmt) : 0;
                    return $Life_BHse_GiftsAmt;
                })
                ->addColumn('CurrentYr_DonorAmt',function ($data){
                    $CurrentYr_DonorAmt = isset($data->CurrentYr_DonorAmt) ? number_format($data->CurrentYr_DonorAmt) : 0;
                    return $CurrentYr_DonorAmt;
                })
                ->addColumn('Last_2Yrs_DonorAmt',function ($data){
                    $Last_2Yrs_DonorAmt = isset($data->Last_2Yrs_DonorAmt) ? number_format($data->Last_2Yrs_DonorAmt) : 0;
                    return $Last_2Yrs_DonorAmt;
                })
                ->addColumn('Life2date_SpendAmt',function ($data){
                    $Life2date_SpendAmt = isset($data->Life2date_SpendAmt) ? number_format($data->Life2date_SpendAmt) : 0;
                    return $Life2date_SpendAmt;
                })
                ->addColumn('dayssincelastvisit',function ($data){
                    $dayssincelastvisit = isset($data->dayssincelastvisit) ? number_format($data->dayssincelastvisit) : 0;
                    return $dayssincelastvisit;
                })
                ->addColumn('Status',function ($data){

                    $is_assigned = $data->TouchStatus == 'Assigned' ? 'selected' : '';
                    $is_spoke_on_phone = $data->TouchStatus == 'Spoke on Phone' ? 'selected' : '';
                    $is_user_returned_call = $data->TouchStatus == 'User Returned Call' ? 'selected' : '';
                    $is_user_returned_text = $data->TouchStatus == 'User Returned Text' ? 'selected' : '';
                    $is_left_voicemail = $data->TouchStatus == 'Left Voicemail' ? 'selected' : '';
                    $is_could_not_leave_voicemail = $data->TouchStatus == 'Could not leave Voicemail' ? 'selected' : '';
                    $is_phone_not_in_service = $data->TouchStatus == 'Phone not in service' ? 'selected' : '';
                    $is_phone_belongs_to_someone_else = $data->TouchStatus == 'Phone belongs to someone else' ? 'selected' : '';
                    $is_suppressed = $data->TouchStatus == 'Suppressed' ? 'selected' : '';
                    return '<select
                    class="form-control-sm"
                    onchange="changeStatus($(this))"
                    data-ds_mkc_contactid="'.$data->DS_MKC_ContactID.'"
                    style="border-color: #bfe6f6;"
                >
                    <option class="badge font-12" value="">Select</option>
                    <option class="badge badge-info font-12" '.$is_assigned.' value="Assigned">Assigned</option>
                    <option class="badge badge-success font-12" '.$is_spoke_on_phone.' value="Spoke on Phone">Spoke on Phone</option>
                    <option class="badge badge-success font-12" '.$is_user_returned_call.' value="User Returned Call">User Returned Call</option>
                    <option class="badge badge-success font-12" '.$is_user_returned_text.' value="User Returned Text">User Returned Text</option>
                    <option class="badge badge-warning font-12" '.$is_left_voicemail.' value="Left Voicemail">Left Voicemail</option>
                    <option class="badge badge-danger font-12" '.$is_could_not_leave_voicemail.' value="Could not leave Voicemail">Could not leave Voicemail</option>
                    <option class="badge badge-danger font-12" '.$is_phone_not_in_service.' value="Phone not in service">Phone not in service</option>
                    <option class="badge badge-danger font-12" '.$is_phone_belongs_to_someone_else.' value="Phone belongs to someone else">Phone belongs to someone else</option>
                    <option class="badge badge-light font-12" '.$is_suppressed.' value="Suppressed">Suppressed</option>
        </select><div class="d-none">'.$data->TouchStatus.'</div>';
                })
                ->addColumn('TouchNotes',function ($data){
                    return '<input
                type="text"
                class="form-control form-control-sm border-0"
                onkeyup="fillComment($(this),event)"
                data-ds_mkc_contactid="'.$data->DS_MKC_ContactID.'"
                value="'.$data->TouchNotes.'" 
        />';
                })
                /*->setRowClass(function ($data) {
                    return 'ajax-Link';
                })*/
                ->setRowAttr([
                    /*'data-href' => function($data){
                        return 'lookup/secondscreen/'.$data->DS_MKC_ContactID;
                    },*/
                    'id' => function($data){
                        return 'row_'.$data->DS_MKC_ContactID;
                    },
                ])
                ->rawColumns(['Call','Status','TouchNotes','DS_MKC_ContactID','Last_3Yrs_GiftsAmt','Life_BHse_GiftsAmt'])
                ->make(true);
        }catch (\Exception $exception){
            die($exception->getMessage());
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
            $columns = ['DS_MKC_HouseholdID' => '','TouchStatus' => '','TouchChannel' => 'Phone','TouchCampaign' => '','TouchDate' => date('Y-m-d'),'TouchNotes' => ''];
            if($tData){
                if($tData->$column_name != trim($column_value) && $column_name == 'TouchStatus'){
                    $sSqlInsertTouch = "Insert Touch ([DS_MKC_ContactID],[DS_MKC_HouseholdID],[TouchStatus],[TouchChannel],[TouchCampaign],[TouchDate],[TouchNotes]) Values ($DS_MKC_ContactID,";
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
                if(!empty($rv)) {
                    if (strpos($rv, ',') !== false) {
                        $nrv = explode(',', $rv);
                    } else {
                        $nrv[] = $rv;
                    }
                    Helper::generateSrPDF($nrv, $cv, ucfirst($fu), $sv, $sa, $sSQL, $list_level, $listShortName, $imgTag, $imgPath, 'downloads', $t_name, $this->prefix . 'RPS_', $SR_Attachment, $rpDesc, $report_orientation);
                }
                return $ajax->success()
                    ->jscallback()
                    ->appendParam('file_name', $this->prefix . 'RPS_' . $t_name . '.pdf')
                    ->appendParam('redirect', true)
                    ->redirectTo(url('/downloads/') . '/' . $this->prefix . 'RPS_' . $t_name . '.pdf')
                    ->response();
            }catch (\Exception $exception){
                return $ajax->fail()
                    ->jscallback()
                    ->message($exception->getMessage())
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

    public function addToPhone(Request $request,Ajax $ajax){
        $camapigns = DB::table('UC_Campaign_Metadata')
            ->orderByDesc('CampaignId')
            ->get(['CampaignId','Channel','File_Name','CampaignDes']);
        $content = View::make('lookup.phone.add',[
            'camapigns' => $camapigns
        ])->render();

        $sdata = [
            'content' => $content
        ];

        $title = 'Add to Phone List';
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
            ->jscallback('loadModalLayout')->response();

    }

    public function insertToPhone(Request $request,Ajax $ajax){
        $rules = [
            'Campaign' => 'required',
            'Name' => 'required|min:3|max:10',
            'Channel' => 'required',
            'Status' => 'required'
        ];

        $messages = [
            'Campaign.required' => 'Campaign is required.',
            'Name.required' => 'Name is required',
            'Channel.required' => 'Channel is required',
            'Status.required' => 'Status is required'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $ajax->fail()
                ->form_errors($validator->errors())
                ->jscallback()
                ->response();
        }
        $Campaign = json_decode($request->input('Campaign'));
        $Current_Year_Month = $request->input('Current_Year_Month');
        $Name = $request->input('Name');
        $Channel = $request->input('Channel');
        $Status = $request->input('Status');
        $TouchCampaign = $Current_Year_Month.$Name;


       DB::statement("insert into touch ([DS_MKC_ContactID]
            ,[TouchCampaign]
            ,[TouchStatus]
            ,[TouchChannel]
            ,[TouchDate]
        ) select [DS_MKC_ContactID]
        ,'".$TouchCampaign."'
        ,'".$Status."'
        ,'".$Channel."'
        ,cast(getdate() as date)
        from UC_Campaign_Data where campaignid in (select campaignid from UC_Campaign_Metadata where CampaignDes = '".$Campaign->CampaignDes."')");

        DB::update("update touch set DS_MKC_HouseholdID= c.ds_mkc_householdid from touch t inner join contact c on t.ds_mkc_contactid=c.ds_mkc_contactid where t.ds_mkc_householdid is null");

        return $ajax->success()
            ->jscallback('ajax_add_to_phone')
            ->response();
    }

    public function getSingleCamp(Request $request,Ajax $ajax){
        $t_id = $request->input('t_id');

        $eEvalSumRecords = DB::select('EXEC sp_CRM_Phone_Campaign_Eval_Sum_Single '.$t_id);
        $eEvalDetailRecords = DB::select('EXEC sp_CRM_Phone_Campaign_Eval_Detail_Single '.$t_id);

        $eEvalSumHtml = $eEvalDetailHtml= '';
        if($eEvalSumRecords){
            $eEvalSumHtml = '<div class="title-center font-14 mb-1" style="color: #75aed0;
    font-weight: 500;
    font-size: 13px;">Campaign Performance</div>'.Helper::print_datatable($eEvalSumRecords);
        }
        if($eEvalDetailRecords){
            $eEvalDetailHtml = '<div class="title-center font-14 mb-1" style="color: #75aed0;
    font-weight: 500;
    font-size: 13px;">Campaign Segment Performance</div>'.Helper::print_datatable($eEvalDetailRecords);
        }
        return $ajax->success()
            ->appendParam('eEvalSumHtml',$eEvalSumHtml)
            ->appendParam('eEvalDetailHtml',$eEvalDetailHtml)
            ->jscallback('ajax_single_campaign')
            ->response();
    }
}
