<?php
namespace App\Http\Controllers\Auth;
use App\Helpers\Helper;
use App\Library\Ajax;
use App\Model\UserDetail;
use App\Model\UserRole;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Validator;
use DB;
use \Illuminate\Support\Facades\View as View;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('datatables/data', 'DatatablesController@anyData')->name('datatables.data');

Route::get('datatables', 'DatatablesController@getIndex');



//testing
Route::get('/test', function() {
    $sSQL = "select touchstatus, touchcampaign  from contact_view wHere touchstatus is not null and touchcampaign in (select max(touchcampaign) from touch)";
    echo $sSQL."<br/>";
    $sSQL = str_ireplace('where','WHERE',$sSQL);
    echo $sSQL."<br/>";
    $pos = stripos($sSQL, "WHERE");
    $sSQL = substr($sSQL, 0, $pos - 1);
    echo 'pos -- '.$pos.'-----'.$sSQL;
});

//Clear Cache
Route::get('clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

//Register
Route::get('register',array('as'=>'register',function(){
    if(Auth::check()){
        return redirect('lookup');
    }
    return view('users.register');
}));
Route::post('postregister','UserController@postRegister');

//Password
Route::get('password/emails',array('as'=>'resetpassword',function(){
    return view('users.forgetpassword');
}));
Route::post('forgetpassword', 'UserController@forgetPassword');

//Login
Route::get('login',array('as'=>'login',function(){
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    if(Auth::check()){
        return redirect(isset(Auth::user()->authenticate->LandingPage) ? strtolower(Auth::user()->authenticate->LandingPage) : 'lookup');
    }
    return view('users.login');
}));
Route::post('login', 'UserController@login');
Route::get('logout',function (){
    Auth::logout();
    return redirect('login');
});

//Mail
Route::get('mail', 'MailController@send');
Route::get('pdf', 'RepCmpController@pdf');

//Dashboard
$dashboard_types = DB::select("SELECT * FROM ZChart_Links");
foreach ($dashboard_types as $dashboard_type){
    Route::get('/'.$dashboard_type->link, 'DashboardController@index')->name($dashboard_type->link);
}


Route::post('/getdashboardinfo', 'DashboardController@getDashboardInfo');
Route::get('dlogin',array('as'=>'dlogin',function(){
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return view('users.dlogin');
}));
Route::post('dlogin', 'UserController@dlogin');

Route::get('/taxonomy', 'TaxonomyController@index');
Route::get('/taxonomy/get', 'TaxonomyController@getTaxonomy');
Route::get('/taxonomy/quickupdate', 'TaxonomyController@quickUpdate');
Route::get('/taxonomy/download', 'TaxonomyController@download');

Route::get('/taxonomy-xref', 'TaxonomyXrefController@index');
Route::get('/taxonomy-xref/get', 'TaxonomyXrefController@getTaxonomy');
Route::get('/taxonomy-xref/quickupdate', 'TaxonomyXrefController@quickUpdate');
Route::get('/taxonomy-xref/download', 'TaxonomyXrefController@download');

Route::group(['middleware' => ['auth']], function () {

    //Users
    Route::post('changepassword', 'UserController@changePassword');
    Route::get('users/history', 'UserController@allUsersHistory');

    //Lookup
    Route::get('/', 'LookupController@index');
    Route::get('/lookup', 'LookupController@index');
    Route::post('/lookup/getfirstscreen', 'LookupController@getFirstScreen');

    Route::get('/lookup/getfirstscreenindex', 'LookupController@getFirstScreenIndex');
    Route::post('/lookup/getfirstscreendata', 'LookupController@getFirstScreenData')->name('lookupfirstscreen.data');

    Route::get('/lookup/downloadreport', 'LookupController@downloadReport');
    Route::get('/lookup/downloadallreports/{cid}', 'LookupController@downloadAllReports');
    Route::post('/lookup/domerge', 'LookupController@doMerge');
    Route::get('/lookup/secondscreen/{id}', 'LookupController@secondScreen');
    Route::get('/lookup/refreshcontact/{id}', 'LookupController@refreshContact');
    Route::get('/lookup/add', 'LookupController@addContact');
    Route::get('/lookup/sadetails/{id}', 'LookupController@SADetails');
    Route::get('/lookup/touchesdetails/{id}', 'LookupController@getTouchesDetails');
    Route::post('/lookup/quickedit', 'LookupController@quickEdit');
    Route::post('/lookup/quickadd', 'LookupController@quickAdd');
    Route::post('/lookup/manualsave', 'LookupController@manualSave');
    Route::get('/lookup/finddupes/{type}', 'LookupController@findDupes');
    Route::post('/lookup/bulkmerge', 'LookupController@bulkMerge');
    Route::get('/lookup/reviewcontact', 'LookupController@reviewContact');
    Route::get('/testDn', 'LookupController@testDn');
    Route::get('/testreader', 'LookupController@testReader');
    Route::get('/lookup/showcreatecampaign', 'LookupController@showCreateCampaign');
    Route::get('/lookup/subtabs', 'LookupController@subTabs');
    Route::get('/lookup/pagesetting', 'LookupController@pageSetting');
    Route::post('/lookup/savepagesettings', 'LookupController@savePageSettings');
    Route::post('/lookup/executesql', 'LookupController@executeSql');

    //activitydetails
    Route::get('/activity', 'ActivityController@index');
    Route::post('/activity/details', 'ActivityController@details');
    Route::get('/activity/downloadreport', 'ActivityController@download');
    //Phone
    Route::get('/phone', 'PhoneController@index');
    Route::post('/phone/getfirstscreen', 'PhoneController@getFirstScreen');
    Route::get('/phone/getfirstscreenindex', 'PhoneController@getFirstScreenIndex');
    Route::post('/phone/getfirstscreendata', 'PhoneController@getFirstScreenData')->name('phonefirstscreen.data');

    Route::post('/phone/touch', 'PhoneController@saveTouch');
    Route::get('/phone/delete/{rid}/{contactid}', 'PhoneController@deleteTouch');
    Route::post('/phone/downloadphonereport', 'PhoneController@downloadPhoneReport');
    Route::get('/phone/add', 'PhoneController@addToPhone');
    Route::post('/phone/add', 'PhoneController@insertToPhone');
    Route::get('/phone/singlecamp', 'PhoneController@getSingleCamp');

    //Import Bulk CC
    Route::get('/importbulkcc', 'ImportbulkccController@index');
    Route::post('/importbulkcc/step1', 'ImportbulkccController@step1');
    Route::post('/importbulkcc/step2', 'ImportbulkccController@step2');
    Route::post('/importbulkcc/step3', 'ImportbulkccController@step3');
    Route::post('/importbulkcc/step4', 'ImportbulkccController@step4');
    Route::post('/importbulkcc/step5', 'ImportbulkccController@step5');


    //Import Contacts
    Route::get('/import', 'ImportcontactController@index');
    Route::post('/import/show', 'ImportcontactController@importShow');
    Route::post('/import/execute', 'ImportcontactController@importExecute');
    Route::get('/import/checkfile', 'ImportcontactController@checkFileFound');
    Route::post('/import/updateaddress', 'ImportcontactController@updateAddress');
    Route::post('/import/updatename', 'ImportcontactController@updateName');
    Route::get('/import/figure', 'ImportcontactController@importFigure');
    Route::get('/import/testColumns', 'ImportcontactController@testColumns');
    Route::get('/import/test1', 'ImportcontactController@test1');

    //Import Zoom
    Route::get('/importzoom', 'ImportzoomController@index');
    Route::post('/importzoom/step1', 'ImportzoomController@importStep1');
    Route::post('/importzoom/step2', 'ImportzoomController@importStep2');
    Route::post('/importzoom/step3', 'ImportzoomController@importStep3');
    Route::post('/importzoom/step4', 'ImportzoomController@importStep4');
    Route::post('/importzoom/step5', 'ImportzoomController@importStep5');
    Route::post('/importzoom/step5', 'ImportzoomController@importStep5');
    Route::get('/importzoom/step5autofill', 'ImportzoomController@step5AutoFill');
    Route::post('/importzoom/step6', 'ImportzoomController@importStep6')->name('importzoom_step6.data');;
    Route::post('/importzoom/step7quickedit', 'ImportzoomController@step7quickEdit');
    Route::get('/importzoom/step5addinsertrecord', 'ImportzoomController@step5AddInsertRecord');
    Route::post('/importzoom/figure', 'ImportzoomController@importFigure');


    //Zoom Cleanse
    Route::get('/zoomcleanse', 'ZoomcleanseController@index');
    Route::post('/zoomcleanse/step1', 'ZoomcleanseController@importStep1');
    Route::post('/zoomcleanse/step2', 'ZoomcleanseController@importStep2');
    Route::post('/zoomcleanse/step3', 'ZoomcleanseController@importStep3');
    Route::post('/zoomcleanse/step4', 'ZoomcleanseController@importStep4');
    Route::post('/zoomcleanse/step5', 'ZoomcleanseController@importStep5');
    Route::post('/zoomcleanse/step5', 'ZoomcleanseController@importStep5');
    Route::get('/zoomcleanse/step5autofill', 'ZoomcleanseController@step5AutoFill');
    Route::get('/zoomcleanse/step5addinsertrecord', 'ZoomcleanseController@step5AddInsertRecord');
    Route::post('/zoomcleanse/step6', 'ZoomcleanseController@importStep6')->name('zoomcleanse_step6.data');
    Route::post('/zoomcleanse/step7quickedit', 'ZoomcleanseController@step7quickEdit');
    Route::post('/zoomcleanse/figure', 'ZoomcleanseController@importFigure');

    // Report
    Route::get('/report', 'ReportController@index');
    Route::get('/report/get', 'ReportController@getReport');

    Route::post('/report/getrunningtabdata', 'ReportController@getRunningTabData')->name('report_running.data');
    Route::post('/report/getscheduledtabdata', 'ReportController@getScheduledTabData')->name('report_scheduled.data');
    Route::post('/report/getcompletetabdata', 'ReportController@getCompleteTabData')->name('report_completed.data');

	Route::post('/report/schedule', 'ReportController@rpSchedule');
	Route::get('/report/getlist', 'ReportController@getList');
	Route::get('/report/seq', 'ReportController@getSeq');
	Route::post('/report/ar_sch_data', 'ReportController@arSchData');
	Route::get('/report/recd', 'ReportController@getSingleReport');
	Route::post('/report/reschedule', 'ReportController@reSchedule');
	Route::post('/report/callouterschedule', 'ReportController@callOuterSchedule');
	Route::post('/report/addtophone', 'ReportController@addToPhone');

	//Report Execute tab
    Route::post('/report/getexecutedata', 'ReportController@getExecuteData');


    // Profile
    Route::get('/profile', 'ProfileController@index');
    Route::get('/profile/get', 'ProfileController@getReport');

    Route::post('/profile/getrunningtabdata', 'ProfileController@getRunningTabData')->name('report_running.data');
    Route::post('/profile/getscheduledtabdata', 'ProfileController@getScheduledTabData')->name('report_scheduled.data');
    Route::post('/profile/getcompletetabdata', 'ProfileController@getCompleteTabData')->name('report_completed.data');

    Route::post('/profile/schedule', 'ProfileController@rpSchedule');
    Route::get('/profile/getlist', 'ProfileController@getList');
    Route::get('/profile/seq', 'ProfileController@getSeq');
    Route::post('/profile/ar_sch_data', 'ProfileController@arSchData');
    Route::get('/profile/recd', 'ProfileController@getSingleProfile');
    Route::post('/profile/reschedule', 'ProfileController@reSchedule');
    Route::post('/profile/callouterschedule', 'ProfileController@callOuterSchedule');
    Route::post('/profile/addtophone', 'ProfileController@addToPhone');
    Route::post('/profile/generatesummaryreport', 'ProfileController@generateSummaryReport');
    Route::post('/profile/generateXLSX', 'ProfileController@generateXLSX');
    Route::post('/profile/generatePDF', 'ProfileController@generatePDF');
    Route::post('/profile/updateprofile', 'ProfileController@updateProfile');
    Route::post('/profile/getexecutedata', 'ProfileController@getExecuteData');
	//Help
	Route::get('/helps', 'HelpController@index');
	Route::get('/helps/getsection/{id}', 'HelpController@getSection');

	/*************** Campaign - Start************/
    Route::get('/campaign', 'CampaignController@index');
    Route::post('/campaign/get', 'CampaignController@getCampaign');

    Route::post('/campaign/getrunningtabdata', 'CampaignController@getRunningTabData')->name('campaign_running.data');
    Route::post('/campaign/getscheduledtabdata', 'CampaignController@getScheduledTabData')->name('campaign_scheduled.data');
    Route::post('/campaign/getcompletetabdata', 'CampaignController@getCompleteTabData')->name('campaign_completed.data');
    Route::post('/campaign/getesummarytabdata', 'CampaignController@getESummaryTabData')->name('campaign_esummary.data');
    Route::post('/campaign/getedetailstabdata', 'CampaignController@getEDetailsTabData')->name('campaign_edetails.data');

    Route::get('/campaign/edownload', 'CampaignController@EvaluationDownload');
    Route::get('/campaign/metadataquickupdate', 'CampaignController@MetaDataQuickUpdate');
    Route::get('/campaign/single', 'CampaignController@getSingle');
    Route::post('/campaign/schedule', 'CampaignController@rpSchedule');
    Route::get('/campaign/getlist', 'CampaignController@getList');
    Route::get('/campaign/seq', 'CampaignController@getSeq');
    Route::post('/campaign/cc_sch_data', 'CampaignController@ccSchData');
    Route::get('/campaign/recd', 'CampaignController@getSingleCampaign');
    Route::post('/campaign/reschedule', 'CampaignController@reSchedule');
    Route::post('/campaign/callouterschedule', 'CampaignController@callOuterSchedule');
    Route::post('/campaign/showmeta', 'CampaignController@showMeta');
    Route::get('/campaign/phone', 'CampaignController@showPhone');
    Route::post('/campaign/phone', 'CampaignController@submitPhone');

    //generatequickmeta
    Route::post('/campaign/generatequickmeta', 'CampaignSegmentController@generateQuickMeta');

    //Segment Tab
    Route::post('/campaign/getaddsubval', 'CampaignSegmentController@getAddsubVal');
    Route::post('/campaign/getcol', 'CampaignSegmentController@getCol');
    Route::post('/campaign/cg', 'CampaignSegmentController@CG');
    Route::post('/campaign/lsdetails', 'CampaignSegmentController@LSDetails');
    Route::post('/campaign/byfieldcheck', 'CampaignSegmentController@byFieldCheck');

    //Export
    Route::post('/campaign/expgetcol', 'CampaignExportController@getCol');
    Route::post('/campaign/expgetpromodata', 'CampaignExportController@getPromoData');
    Route::post('/campaign/expfileexists', 'CampaignExportController@fileExists');

    //MetaData
    Route::post('/campaign/getmetadata', 'CampaignMetaDataController@getMetadata');
    Route::post('/campaign/metasavelkp', 'CampaignMetaDataController@metaSaveLkp');

    //Execute
    Route::post('/campaign/getexecutedata', 'CampaignController@getExecuteData');
    /*************** Campaign - End************/

    /*************** model - Start************/
    Route::get('/model', 'ModelController@index');
    Route::post('/model/get', 'ModelController@getModel');

    Route::post('/model/getrunningtabdata', 'ModelController@getRunningTabData')->name('model_running.data');
    Route::post('/model/getscheduledtabdata', 'ModelController@getScheduledTabData')->name('model_scheduled.data');
    Route::post('/model/getcompletetabdata', 'ModelController@getCompleteTabData')->name('model_completed.data');
    Route::post('/model/getesummarytabdata', 'ModelController@getESummaryTabData')->name('model_esummary.data');
    Route::post('/model/getedetailstabdata', 'ModelController@getEDetailsTabData')->name('model_edetails.data');

    Route::post('/model/edownload', 'ModelController@EvaluationDownload');
    Route::get('/model/metadataquickupdate', 'ModelController@MetaDataQuickUpdate');
    Route::get('/model/single', 'ModelController@getSingle');
    Route::post('/model/schedule', 'ModelController@rpSchedule');
    Route::get('/model/getlist', 'ModelController@getList');
    Route::get('/model/seq', 'ModelController@getSeq');
    Route::post('/model/mo_sch_data', 'ModelController@moSchData');
    Route::get('/model/recd', 'ModelController@getSingleCampaign');
    Route::post('/model/reschedule', 'ModelController@reSchedule');
    Route::post('/model/callouterschedule', 'ModelController@callOuterSchedule');
    Route::post('/model/showmeta', 'ModelController@showMeta');
    Route::get('/model/phone', 'ModelController@showPhone');
    Route::post('/model/phone', 'ModelController@submitPhone');
    Route::post('/model/generatereport', 'modelController@generateReport');
    Route::post('/model/generatePDF', 'modelController@generatePDF');
    Route::post('/model/generateXLSX', 'modelController@generateXLSX');
    Route::get('/model/preview/{modelid?}', 'modelController@modelPreview');
    Route::get('/model/scorepreview', 'modelController@modelScorePreview');

    //generatequickmeta
    Route::post('/model/generatequickmeta', 'modelSegmentController@generateQuickMeta');

    //Segment Tab
    Route::post('/model/getaddsubval', 'modelSegmentController@getAddsubVal');
    Route::post('/model/getcol', 'modelSegmentController@getCol');
    Route::post('/model/cg', 'modelSegmentController@CG');
    Route::post('/model/lsdetails', 'modelSegmentController@LSDetails');
    Route::post('/model/byfieldcheck', 'modelSegmentController@byFieldCheck');

    //Export
    Route::post('/model/expgetcol', 'modelExportController@getCol');
    Route::post('/model/expgetpromodata', 'modelExportController@getPromoData');
    Route::post('/model/expfileexists', 'modelExportController@fileExists');

    //MetaData
    Route::post('/model/getmetadata', 'modelMetaDataController@getMetadata');
    Route::post('/model/metasavelkp', 'modelMetaDataController@metaSaveLkp');

    //Execute
    Route::post('/model/getexecutedata', 'ModelController@getExecuteData');
    /*************** Campaign - End************/

    /*************** Email - Start************/
    Route::get('/email', 'EmailController@index');
    Route::get('/email/get', 'EmailController@getEmails');

    Route::post('/email/getcompletedemails', 'EmailController@getCompletedEmails')->name('email_completed.data');

    Route::post('/email/sendemail', 'EmailController@sendEmail');
    Route::get('/email/count_toprocess', 'EmailController@countToProcess');
    Route::get('/email/today_campaigns', 'EmailController@todayCampaigns');
    Route::get('/email/db_queries', 'EmailController@dbQueries');
    Route::get('/email/getshrink', 'EmailController@getShrink');
    Route::get('/email/getfilesize', 'EmailController@getFileSize');
    Route::get('/email/count_dupes_popup', 'EmailController@countDupesPopup');
    Route::get('/email/count_dupes', 'EmailController@countDupes');
    Route::get('/email/Delete_toprocess', 'EmailController@DeleteToProcess');
    Route::get('/email/showeditpopup', 'EmailController@showEditPopup');
    Route::post('/email/update', 'EmailController@updateCampaign');
    /*************** Email - End************/

    //Common route for campaign & report
    Route::post('/sendviaemail', 'RepCmpController@sendViaEmail');
    Route::post('/savesendviaemail', 'RepCmpController@saveSendViaEmail');
    Route::post('/saveschsendviaemail', 'RepCmpController@saveSchSendViaEmail');
    Route::get('/getshare', 'RepCmpController@getShare');
    Route::post('/sharereport', 'RepCmpController@share');
    Route::post('/delete', 'RepCmpController@delete');
    Route::post('/delete_older_version', 'RepCmpController@deleteOlderVersion');
    Route::get('/getftpdata', 'RepCmpController@getFtpData');
    Route::get('/getfieldtypes', 'RepCmpController@getFieldTypes');
    Route::get('/getdistributionpu/{ll?}', 'RepCmpController@getDistriPopUp');
    Route::get('/run', 'RepCmpController@reportRun');
    Route::post('/HTMLtoPDF', 'RepCmpController@HTMLtoPDF');
    Route::post('/showpdfupload', 'RepCmpController@showPdfUpload');
    Route::post('/downloadmultiplepdf', 'RepCmpController@downloadMultiplePDF');
    Route::post('/convertToXLSX', 'RepCmpController@convertToXLSX');
    Route::get('/getfieldtypesforfilter', 'RepCmpController@getFieldTypesForFilter');
    Route::get('/getfields', 'RepCmpController@getFields');
    Route::get('/getcolbycustom', 'RepCmpController@getColByCustom');
    Route::get('/countsql', 'RepCmpController@getCountSql');
    Route::get('/preview', 'RepCmpController@showPreview');
    Route::get('/download10K', 'RepCmpController@download10K');
    Route::post('/tag', 'RepCmpController@tag');

    Route::get('/common/showeditable', 'CommonController@showEditable');
    Route::get('/common/updateeditable', 'CommonController@updateEditable');



    Route::get('/testing', function (){
        //testing code
    });

    Route::get('/sqlinterfaceview', function (){
        return view('sql.index',['section' => 'view']);
    });

    Route::post('/sqlinterface', function (Request $request,Ajax $ajax){
        $sqlQuery = $request->input('sqlquery');
        $sSQL = DB::select($sqlQuery);
        $aData = collect($sSQL)->map(function ($x) {
            return (array)$x;
        })->toArray();

        //$html = View::make('sql.index',['records' => $aData, 'section'=>'result'])->render();

        return $ajax->success()->appendParam('aData',$aData)->response();
    });

    Route::get('/integrateMetaField', function (){
        @ini_set('max_execution_time',5000);
        $aData = DB::select("Select t_id,meta_data from UR_Report_Templates");
        $aData = collect($aData)->map(function ($x) {
            return (array)$x;
        })->toArray();

        //DB::statement("Truncate table [UL_RepCmp_Metadata]");
        //echo '<pre>'; print_r($aData); die;
        foreach ($aData as $data){
            $metaArray = explode('^',$data['meta_data']);
            $CID = $data['t_id'];
            $metadata_date = $metaArray[7] . '/' . $metaArray[8] . '/' . $metaArray[6];
            $metaArray[13] = $metaArray[13] == 'DS_MKC_ContactID||ASC' ? '' : $metaArray[13];
            //echo '<pre>'; print_r($metadata);
           /* echo "<br/>INSERT INTO [UL_RepCmp_Metadata] ([CampaignID],[Type] ,[Objective],[Brand],[Channel],[Category]
                           ,[ListDes] ,[Wave] ,[Start_Date]  ,[Interval],[ProductCat1],[ProductCat2],[SKU],[Coupon])
                             VALUES ('$CID','C','$metaArray[0]','$metaArray[1]','$metaArray[2]','$metaArray[3]','$metaArray[4]'
                           ,'$metaArray[5]','$metadata_date','$metaArray[9]','$metaArray[10]','$metaArray[11]','$metaArray[12]','$metaArray[13]')";*/
            /*DB::insert("INSERT INTO [UL_RepCmp_Metadata] ([CampaignID],[Type] ,[Objective],[Brand],[Channel],[Category]
                           ,[ListDes] ,[Wave] ,[Start_Date]  ,[Interval],[ProductCat1],[ProductCat2],[SKU],[Coupon])
                             VALUES ('$CID','A','$metaArray[0]','$metaArray[1]','$metaArray[2]','$metaArray[3]','$metaArray[4]'
                           ,'$metaArray[5]','$metadata_date','$metaArray[9]','$metaArray[10]','$metaArray[11]','$metaArray[12]','$metaArray[13]')");*/
            /**/
        }

    });

    Route::get('/integrateReportMapping', function (){
        @ini_set('max_execution_time',5000);

        $aData = DB::select("Select rp.t_id,rs.row_id as sch_id,rs.sch_status_id,rst.*,rp.promoexpo_file from UC_Report_Templates rp INNER JOIN UL_RepCmp_Schedules rs on rp.row_id = rs.camp_tmpl_id INNER JOIN UL_RepCmp_Status rst on rs.sch_status_id = rst.row_id AND rs.sch_status_id <> '' AND rp.t_type = 'A'");
        $aData = collect($aData)->map(function ($x) {
            return (array)$x;
        })->toArray();

        DB::statement("Delete from UL_RepCmp_Sch_status_mapping WHERE t_type = 'A'");

        //DB::statement("Truncate table [UL_RepCmp_Metadata]");
        //echo '<pre>'; print_r($aData); die;
        foreach ($aData as $data){
            DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$data['sch_id']."','".$data['sch_status_id']."', 'A')");

            DB::update("UPDATE UL_RepCmp_Status SET file_name='".$data['templ_name']."' WHERE row_id = '".$data['sch_status_id']."'");
        }

    });

    Route::get('/integrateCampaignMapping', function (){
        @ini_set('max_execution_time',5000);
        $aData = DB::select("Select rp.t_id,rs.row_id as sch_id,rs.sch_status_id,rst.*,rp.promoexpo_file from UC_Campaign_Templates rp INNER JOIN UL_RepCmp_Schedules rs on rp.row_id = rs.camp_tmpl_id INNER JOIN UL_RepCmp_Status rst on rs.sch_status_id = rst.row_id AND rs.sch_status_id <> '' AND rp.t_type = 'C'");
        $aData = collect($aData)->map(function ($x) {
            return (array)$x;
        })->toArray();
        DB::statement("Delete from UL_RepCmp_Sch_status_mapping WHERE t_type = 'A'");
        //DB::statement("Truncate table [UL_RepCmp_Metadata]");
        //echo '<pre>'; print_r($aData); die;
        foreach ($aData as $data){
            DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$data['sch_id']."','".$data['sch_status_id']."', 'C')");

            DB::update("UPDATE UL_RepCmp_Status SET file_name='".$data['templ_name']."' WHERE row_id = '".$data['sch_status_id']."'");
        }

    });


    Route::get('/testSch', function (){
        @ini_set('max_execution_time',5000);
        $schtasks_dir = config('constant.schtasks_dir');
        $phpPath = config('constant.phpPath');
        $filePath = config('constant.filePath');


        $sName = 'S_testing';
        $sch_id = 12;
        $rp_run_sch = 'daily';
        $rp_start_date1 = '05/31/2021';
        $rp_start_date1 = date('m-d-Y', strtotime($rp_start_date1 . ' +1 day'));
        echo $rp_start_date1; die;
        $rp_start_date = '05/31/2021';
        $rp_end_date = '06/07/2021';
        $rp_run_time = '07:12';
        $rp_end_time = '23:59';
        $command = 'schtasks /create /tn ' . $schtasks_dir. '\\' . $sName . ' /tr "\'' . $phpPath . '\' -f \'' . $filePath . 'artisan\' arSchedule1:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date .' /et  23:59 /z /ru Administrator';

        $command = 'schtasks /create /tn ' . $schtasks_dir. '\\' . $sName . ' /tr "\'' . $phpPath . '\' -f \'' . $filePath . 'artisan\' arSchedule1:run '.$sch_id.' " /sc ' . $rp_run_sch . ' /sd ' . $rp_start_date . ' /st ' . $rp_run_time . '  /ed ' . $rp_end_date . ' /et ' .$rp_end_time.'  /z /ru Administrator';
        echo $command."<br/>";
        Helper::schtask_curl($command);

    });

});
