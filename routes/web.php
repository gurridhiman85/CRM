<?php
namespace App\Http\Controllers\Auth;
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


Route::group(['middleware' => ['auth']], function () {

    //Users
    Route::post('changepassword', 'UserController@changePassword');
    Route::get('users/history', 'UserController@allUsersHistory');

    //Lookup
    Route::get('/', 'LookupController@index');
    Route::get('/lookup', 'LookupController@index');
    Route::post('/lookup/getfirstscreen', 'LookupController@getFirstScreen');
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

    //Phone
    Route::get('/phone', 'PhoneController@index');
    Route::post('/phone/getfirstscreen', 'PhoneController@getFirstScreen');
    Route::post('/phone/touch', 'PhoneController@saveTouch');
    Route::get('/phone/delete/{rid}/{contactid}', 'PhoneController@deleteTouch');
    Route::post('/phone/downloadphonereport', 'PhoneController@downloadPhoneReport');

    //Import Contacts
    Route::get('/import', 'ImportcontactController@index');
    Route::post('/import/show', 'ImportcontactController@importShow');
    Route::post('/import/execute', 'ImportcontactController@importExecute');
    Route::get('/import/checkfile', 'ImportcontactController@checkFileFound');
    Route::post('/import/updateaddress', 'ImportcontactController@updateAddress');
    Route::post('/import/updatename', 'ImportcontactController@updateName');
    Route::get('/import/figure', 'ImportcontactController@importFigure');
    Route::get('/import/testColumns', 'ImportcontactController@testColumns');

    //Import Zoom
    Route::get('/importzoom', 'ImportzoomController@index');
    Route::post('/importzoom/step1', 'ImportzoomController@importStep1');
    Route::post('/importzoom/step2', 'ImportzoomController@importStep2');
    Route::post('/importzoom/step3', 'ImportzoomController@importStep3');
    Route::post('/importzoom/step4', 'ImportzoomController@importStep4');
    Route::post('/importzoom/step5', 'ImportzoomController@importStep5');
    Route::post('/importzoom/step5', 'ImportzoomController@importStep5');
    Route::get('/importzoom/step5autofill', 'ImportzoomController@step5AutoFill');
    Route::post('/importzoom/step6', 'ImportzoomController@importStep6');
    Route::post('/importzoom/step7quickedit', 'ImportzoomController@step7quickEdit');

    Route::post('/importzoom/figure', 'ImportzoomController@importFigure');

    // Report
    Route::get('/report', 'ReportController@index');
    Route::get('/report/get', 'ReportController@getReport');
	Route::post('/report/schedule', 'ReportController@rpSchedule');
	Route::get('/report/getlist', 'ReportController@getList');
	Route::get('/report/seq', 'ReportController@getSeq');
	Route::post('/report/ar_sch_data', 'ReportController@arSchData');
	Route::get('/report/recd', 'ReportController@getSingleReport');
	Route::post('/report/reschedule', 'ReportController@reSchedule');
	Route::post('/report/callouterschedule', 'ReportController@callOuterSchedule');

	//Help
	Route::get('/helps', 'HelpController@index');
	Route::get('/helps/getsection/{id}', 'HelpController@getSection');

	/*************** Campaign - Start************/
    Route::get('/campaign', 'CampaignController@index');
    Route::get('/campaign/get', 'CampaignController@getCampaign');
    Route::post('/campaign/schedule', 'CampaignController@rpSchedule');
    Route::get('/campaign/getlist', 'CampaignController@getList');
    Route::get('/campaign/seq', 'CampaignController@getSeq');
    Route::post('/campaign/cc_sch_data', 'CampaignController@ccSchData');
    Route::get('/campaign/recd', 'CampaignController@getSingleCampaign');
    Route::post('/campaign/reschedule', 'CampaignController@reSchedule');
    Route::post('/campaign/callouterschedule', 'CampaignController@callOuterSchedule');
    Route::post('/campaign/showmeta', 'CampaignController@showMeta');

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

    /*************** Campaign - End************/

    //Common route for campaign & report
    Route::post('/sendviaemail', 'RepCmpController@sendViaEmail');
    Route::post('/savesendviaemail', 'RepCmpController@saveSendViaEmail');
    Route::post('/saveschsendviaemail', 'RepCmpController@saveSchSendViaEmail');
    Route::get('/getshare', 'RepCmpController@getShare');
    Route::post('/sharereport', 'RepCmpController@share');
    Route::post('/delete', 'RepCmpController@delete');
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

});

