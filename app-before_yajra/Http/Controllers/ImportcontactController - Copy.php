<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Library\Ajax;
use App\User;
use mysql_xdevapi\Schema;
use Validator;
use Auth;
use Crypt;
use DB;
use Session;
use \Illuminate\Support\Facades\View as View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportcontactController extends Controller
{
    public  $schtasks_dir;
    public $db;

    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->db = DB::connection('sqlsrv');
    }

    public function index(){
        $User_Type = Session::get('User_Type');
        $permissions = Session::get('Visibilities');
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        if(!in_array('Import Contact',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        return view('lookup.import.index',[]);
    }

    public function importShow(Request $request,Ajax $ajax){
        $rules = ['xlsx','xls','csv'];

        $files = $request->file('files');
        $source = $request->input('source');
        $destination = public_path('\\Import_Input\\');
        foreach($files as $file)
        {
            if(!in_array($file->getClientOriginalExtension(),$rules)){
                return $ajax->fail()
                    ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                    ->jscallback()
                    ->response();
            }

            $a_url = $file->getClientOriginalName();
            $file->move($destination, $a_url);
            $inputFileName = $destination.$a_url;

            /*$a_url = str_replace ( $illegals, "", $file->getClientOriginalName() );
            $newname = $destination.$a_url;
            rename($file->getClientOriginalName(),$newname);
            $inputFileName = $destination.$a_url;*/

            /**  Identify the type of $inputFileName  **/
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($inputFileName);

            $sTitle = $spreadsheet->getActiveSheet()->getTitle();
            $ImportData = $spreadsheet->getActiveSheet()->toArray();

            try{
                DB::statement("DROP table userinput");
            }catch (\Exception $exception){}

            if($file->getClientOriginalExtension() == 'csv'){
                $fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $inputFileName = $destination.'copy_of_'.$fname.'.xlsx';
                $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $objWriter->save($destination.'copy_of_'.$fname.'.xlsx');

                if ($sTitle == trim($sTitle) && strpos($sTitle, ' ') !== false) {
                    DB::statement("SELECT * INTO userinput FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', ['".$sTitle."$']);");
                }else{
                    DB::statement("SELECT * INTO userinput FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', [".$sTitle."$]);");
                }
            }else{
                if ($sTitle == trim($sTitle) && strpos($sTitle, ' ') !== false) {
                    DB::statement("SELECT * INTO userinput FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', ['".$sTitle."$']);");
                }else{
                    DB::statement("SELECT * INTO userinput FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', [".$sTitle."$]);");
                }
            }

            /* Check File format - Start */
            $qry = DB::select("SELECT [RowID],[Field_Display_Name],[Field_Db_Name] FROM UI_Field_Mapping");
            $columns = collect($qry)->map(function($x){ return (array) $x; })->toArray();

            $inColumns = DB::select("SELECT column_name FROM ".DB::connection()->getConfig('database').".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'userinput'");

            $dColumns = [];
            foreach ($inColumns as $column){
                foreach ($columns as $allowedColumn){
                    if(strtolower($allowedColumn['Field_Display_Name']) == strtolower($column->column_name)){
                        $dColumns[] = '['.$allowedColumn['Field_Display_Name'].']';
                    }
                }
            }

            if(count($dColumns) == 0){ // Fail file format
                return $ajax->fail()
                    ->message('Invalid file format, Please try again with correct format')
                    ->response();
            }
            /* Check File format - Start */

            //insert import summary
            $imId = DB::table('UI_File_Name')->insertGetId([
                'User_ID' => Auth::user()->User_ID,
                'User_FName' => Auth::user()->User_FName,
                'User_LName' => Auth::user()->User_LName,
                'Source' => $source,
                'Import_Date' => date('Y-m-d H:i:s'),
                'Import_Filename' => $a_url,
                'Import_Status' => 'Input',
            ]);

            $uiCountQry = DB::select("SELECT count(*) as count FROM userinput");
            $uiCount = collect($uiCountQry)->map(function($x){ return (array) $x; })->toArray();
            Session::put('IM_File_Name', $a_url);
            Session::put('IM_ID', $imId);
            Session::put('IM_File_Records', isset($uiCount[0]['count']) ? $uiCount[0]['count'] : 0);

            $html = View::make('lookup.import.table',['ImportData' => $ImportData,'columns' => $columns])->render();
        }

        return $ajax->success()
            ->appendParam('html',$html)
            ->appendParam('Import_Id',$imId)
            ->appendParam('Import_Filename',$a_url)
            ->appendParam('columns',$columns)
            ->jscallback('ajax_overview_import')
            ->response();
    }

    public function importExecute(Request $request,Ajax $ajax){

        $sourcefeed = $request->input('source3');
        $Import_Filename = $request->input('Import_Filename');
        $Import_Id = $request->input('Import_Id');
        $no_address = $request->input('no_address');

        DB::statement("TRUNCATE TABLE ui_file_detail_update");

        $inColumnsQry = DB::select("SELECT column_name FROM ".DB::connection()->getConfig('database').".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'userinput'");
        $inColumns = collect($inColumnsQry)->map(function($x){ return (array) $x; })->toArray();

        for($i = 0; $i < 50; $i++){
            if($request->input('col_hidden_'.$i) && !empty($request->input('col_hidden_'.$i))){
                $mtColumns[$i] = $request->input('col_hidden_'.$i);
            }
        }

        $qry = DB::select("SELECT [RowID],[Field_Display_Name],[Field_Db_Name] FROM UI_Field_Mapping");
        $allowedColumns = collect($qry)->map(function($x){ return (array) $x; })->toArray();

        $dColumns = [];
        $dbColumns = [];
        /*foreach ($inColumns as $column){
            foreach ($allowedColumns as $allowedColumn){
                if(trim(strtolower($allowedColumn['Field_Display_Name'])) == trim(strtolower($column->column_name))){
                    $dColumns[] = '['.trim($column->column_name).']';
                    //$dbColumns[] = '['.$allowedColumn['Field_Db_Name'].']';
                }
            }
        }*/


        foreach ($mtColumns as $key=>$column){
            foreach ($allowedColumns as $allowedColumn){
                if(trim(strtolower($allowedColumn['Field_Db_Name'])) == trim(strtolower($column))){
                    $dColumns[] = '['.$inColumns[$key]['column_name'].']';
                    $dbColumns[] = '['.$allowedColumn['Field_Db_Name'].']';
                }
            }
        }
        /*echo '<pre>'; print_r($inColumns);
        echo '<pre>'; print_r($dbColumns);
        echo '<pre>'; print_r($dColumns);
        die('cool');*/

        try{

            DB::statement("insert into ui_file_detail_update (".implode(',',$dbColumns)." , import_date, ds_mkc_source_feed,ds_mkc_input_file) select ".implode(',',$dColumns).", getdate() , '$sourcefeed','$Import_Filename' from userinput");

            $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S0_Insert");
            $stmt->execute();

            if($no_address == 1){
                $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S2_Match_NoAddr");
                $stmt->execute();    

                $qry = DB::select("select  t.ds_mkc_contactid,o.Dharmaname as Old_Dharmaname, o.Firstname as Old_Firstname, o.Middlename as Old_Middlename,  o.Lastname as Old_Lastname,  o.Extendedname as Old_Extendedname ,  t.Dharmaname as New_Dharmaname, t.Firstname as New_Firstname , t.Middlename as New_Middlename,  t.Lastname as New_Lastname,  t.Extendedname as New_Extendedname from contact o inner join contact_temp t on t.ds_mkc_contactid=o.ds_mkc_contactid where  t.ds_mkc_contactid is not null and (o.lastname <> t.lastname or o.firstname <> t.firstname) and (t.firstname <> '' or t.lastname <> '')");
                $records = collect($qry)->map(function($x){ return (array) $x; })->toArray();

                $html = View::make('lookup.import.name',['records' => $records])->render();

                return $ajax->success()
                    ->appendParam('html',$html)
                    ->appendParam('recCount',count($records) > 0 ? true : false)
                    ->jscallback('ajax_import_execute')
                    ->appendParam('no_address', true)
                    ->response();
            }

            $input = 'D:\data\generic\input\GENERIC_HYGIENE_INPUT.txt';
        
            if(file_exists($input)) {
                unlink($input);
            }

            $cleansed = 'D:\\data\\generic\\cleansed\\pGENERIC_HYGIENE_INPUT.txt';
            if(file_exists($cleansed)){
                unlink($cleansed);
            }
            $stmt1 = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S1_ForCASS_P1");
            $stmt1->execute();
            return $ajax->success()
                ->jscallback('ajax_import_execute')
                ->response();

        }catch (\Exception $exception){
            //Error
            DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =".$Import_Id);

            $source_file = public_path('\\Import_Input\\'.$Import_Filename);
            $destination_path = public_path('\\Import_Error\\');

            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            return $ajax->fail()
                ->jscallback('ajax_import_execute')
                ->appendParam('message',$exception->getMessage())
                ->response();
        }
    }

    public function checkFileFound(Request $request, Ajax $ajax){

        $step = $request->input('step');
        if($step == '4a'){

            $fileExist = file_exists('D:\data\generic\input\GENERIC_HYGIENE_INPUT.txt');
            if($fileExist){
                $fileSize = filesize('D:\data\generic\input\GENERIC_HYGIENE_INPUT.txt');
                if($fileSize == 0){
                    return $ajax->fail()
                        ->message('This file could not be processed. Please check the file and try again or contact your CRMSquare administrator at esupport@datasquare.com')
                        ->response();
                }

                $date = date("m/d/Y", time());
                $time = date("H:i:s", time() + 60 + 10);

                shell_exec('schtasks /delete /tn ' . $this->schtasks_dir . '\\accuzipopen2_bat /f');

                $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\accuzipopen2_bat /tr ' . public_path('\\accuzipopen2.bat') . ' /sc once /st ' . $time . ' /sd ' . $date .' /ru Administrator';
                Helper::schtask_curl($command);

            }else{
                $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S1_ForCASS_P2");
                $stmt->execute(); 
            }
            return $ajax->success()
                ->appendParam('file_found',$fileExist)
                ->response();

        }elseif ($step == '5'){
            $html = '';
            $fileExist = file_exists('D:\\data\\generic\\cleansed\\pGENERIC_HYGIENE_INPUT.txt');
            if($fileExist){
                $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S2_Match");
                $stmt->execute();

                $cSQL = DB::select("select count(*) as count from contact_temp where ds_mkc_contactid is not null");
                $checkCount = collect($cSQL)->map(function($x){ return (array) $x; })->toArray();

                if(isset($checkCount[0]['count']) && $checkCount[0]['count'] > 0){

                    $tSQL = DB::select("select t.ds_mkc_contactid, o.address as [Old Address], t.address as [New Address], o.city as [Old City], t.city as [New City], o.state as [Old State], t.state as [New State], o.zip as [Old Zip], t.zip as [New Zip],  case when substring(t.addressquality,1,1) > substring(o.addressquality,1,1) then 'New is better' when  substring(t.addressquality,1,1) = substring(o.addressquality,1,1) then 'Same quality' when substring(t.addressquality,1,1) < substring(o.addressquality,1,1) then 'Old is better' else '' end as [Compare Address Quality],o.addressquality as [Old Address Quality] , t.addressquality as [New Address Quality]
from contact_temp t
inner join contact o on t.ds_mkc_contactid=o.ds_mkc_contactid where  t.ds_mkc_contactid is not null and o.address <> t.address and t.address <> '' and o.address <> '' Order By case when 
 substring(t.addressquality,1,1) > substring(o.addressquality,1,1) then 'New is better' when  substring(t.addressquality,1,1) = substring(o.addressquality,1,1) then 'No Difference'
when substring(t.addressquality,1,1) < substring(o.addressquality,1,1) then 'Old is better' else '' end");
                    $records = collect($tSQL)->map(function($x){ return (array) $x; })->toArray();
                    $html = View::make('lookup.import.address',['records' => $records])->render();

                }else{
                    $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_BulkImport_S4_Insert");
                    $stmt->execute();
                    
                    $IM_File_Name = Session::get('IM_File_Name');
                    $IM_ID = Session::get('IM_ID');

                    DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =".$IM_ID);

                    $source_file = public_path('\\Import_Input\\'.$IM_File_Name);
                    $destination_path = public_path('\\Import_Error\\');
                    rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

                    return $ajax->fail()
                        ->appendParam('move_on_11_step',true)
                        ->response();
                }
            }
            return $ajax->success()
                ->appendParam('file_found',$fileExist)
                ->appendParam('html',$html)
                ->response();
        }
    }
    public function addressQuickEdit(Request $request, Ajax $ajax) {
        try {
            $tablename = 'contact_temp';
            $ds_mkc_contactid = $request->input('ds_mkc_contactid');
            $fieldname = $request->input('fieldname');
            $fieldvalue = $request->input('fieldvalue', '');
            $aData = DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->get();
            $aData = collect($aData)->map(function($x) {
                return (array) $x;
            })->toArray();
            //echo '<pre>'; print_r($aData); die;
            if (count($aData) == 0) {
                return $ajax->fail()
                    ->appendParam('message', 'Record not found')
                    ->response();
            }

            DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->update([$fieldname => trim($fieldvalue)]);

            $aData = DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->get();
            $aData = collect($aData)->map(function($x) {
                return (array) $x;
            })->toArray();

            return $ajax->success()
                ->appendParam('aData', $aData[0])
                ->appendParam('ds_mkc_contactid', $ds_mkc_contactid)
                ->jscallback('ajax_edit_address')
                ->response();
        } catch (\Exception $e) {
            return $ajax->fail()
                ->appendParam('message', $e->getMessage())
                ->response();
        }
    }

    public function updateAddress(Ajax $ajax,Request $request){
        $cids = $request->input('cids',[]);

        try{
            if(count($cids) > 0) {
                foreach ($cids as $cid) {
                    $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S3_Address " . $cid);
                    $stmt->execute();
                }
            }

            $qry = DB::select("select  t.ds_mkc_contactid,o.Dharmaname as Old_Dharmaname, o.Firstname as Old_Firstname, o.Middlename as Old_Middlename,  o.Lastname as Old_Lastname,  o.Extendedname as Old_Extendedname ,  t.Dharmaname as New_Dharmaname, t.Firstname as New_Firstname , t.Middlename as New_Middlename,  t.Lastname as New_Lastname,  t.Extendedname as New_Extendedname from contact o inner join contact_temp t on t.ds_mkc_contactid=o.ds_mkc_contactid where  t.ds_mkc_contactid is not null and (o.lastname <> t.lastname or o.firstname <> t.firstname) and (t.firstname <> '' or t.lastname <> '')");
            $records = collect($qry)->map(function($x){ return (array) $x; })->toArray();

            $html = View::make('lookup.import.name',['records' => $records])->render();

            return $ajax->success()
                ->appendParam('html',$html)
                ->appendParam('recCount',count($records) > 0 ? true : false)
                ->message('Please tag the records where name should be updated.')
                ->jscallback('ajax_update_address')
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->message($exception->getMessage())
                ->response();
        }
    }

    public function nameQuickEdit(Request $request, Ajax $ajax) {
        try {
            $tablename = 'contact_temp';
            $ds_mkc_contactid = $request->input('ds_mkc_contactid');
            $fieldname = $request->input('fieldname');
            $fieldvalue = $request->input('fieldvalue', '');
            $aData = DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->get();
            $aData = collect($aData)->map(function($x) {
                return (array) $x;
            })->toArray();
            //echo '<pre>'; print_r($aData); die;
            if (count($aData) == 0) {
                return $ajax->fail()
                    ->appendParam('message', 'Record not found')
                    ->response();
            }

            DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->update([$fieldname => trim($fieldvalue)]);

            $aData = DB::table($tablename)
                ->where('ds_mkc_contactid', $ds_mkc_contactid)
                ->get();
            $aData = collect($aData)->map(function($x) {
                return (array) $x;
            })->toArray();

            return $ajax->success()
                ->appendParam('aData', $aData[0])
                ->appendParam('ds_mkc_contactid', $ds_mkc_contactid)
                ->jscallback('ajax_edit_name')
                ->response();
        } catch (\Exception $e) {
            return $ajax->fail()
                ->appendParam('message', $e->getMessage())
                ->response();
        }
    }

    public function updateName(Request $request, Ajax $ajax){
        $cids = $request->input('cids',[]);

        try{
            if(count($cids) > 0) {
                foreach ($cids as $cid) {
                    $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Bulkimport_S3_Name " . $cid);
                    $stmt->execute();
                }
            }

            $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_BulkImport_S4_Insert");
            $stmt->execute();

            /*matched records */
            $qry = DB::select("select count(*) as count from contact_temp where ds_mkc_contactid is not null");
            $matched = collect($qry)->map(function($x){ return (array) $x; })->toArray();
            $ms = isset($matched[0]['count']) ? $matched[0]['count'] : 0;

            /*inserted records*/
            $qry = DB::select("select count(*) as count from contact_temp where ds_mkc_contactid is null");
            $inserted = collect($qry)->map(function($x){ return (array) $x; })->toArray();
            $in = isset($inserted[0]['count']) ? $inserted[0]['count'] : 0;

            $IM_File_Name = Session::get('IM_File_Name');
            $IM_ID = Session::get('IM_ID');
            $IM_File_Records = Session::get('IM_File_Records');

            DB::update("UPDATE UI_File_Name SET Import_Status = 'Completed' WHERE User_Import_ID = ".$IM_ID);
            $source_file = public_path('\\Import_Input\\'.$IM_File_Name);
            $destination_path = public_path('\\Import_Completed\\');
            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            $msg = "Congratulations!<br/> You successfully imported ".$IM_File_Name." with ".$IM_File_Records." records. <br/>".$ms." records were updated and ".$in." new records were inserted";
            return $ajax->success()
                ->message($msg)
                ->jscallback('ajax_import_completed')
                ->response();
        }catch (\Exception $exception){
            return $ajax->fail()
                ->message('Something is wrong !')
                ->response();
        }
    }

    public function importFigure(Ajax $ajax){

        $qry = DB::select("select count(*) as count from contact_temp where ds_mkc_contactid is not null"); //matched records
        $matched = collect($qry)->map(function($x){ return (array) $x; })->toArray();

        $qry = DB::select("select count(*) as count from contact_temp where ds_mkc_contactid is null");  //inserted records
        $inserted = collect($qry)->map(function($x){ return (array) $x; })->toArray();

        $html = '<h5>Congratulations! You successfully updated address.</h5><table id="basic_table2" class="table table-bordered table-hover color-table lkp-table font-16"><thead>
                <tr>
                    <th>Updated Records</th>
                    <th>Inserted Records</th>
                </tr>
                </thead><tbody>';

        $html .= '<tr>
                    <td>'.$matched[0]['count'].'</td>
                    <td>'.$inserted[0]['count'].'</td>
                </tr>';

        $html .= '</tbody><table>';


        $sdata = [
            'content' => $html
        ];

        $title = 'Alert';
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

    function testColumns(){
        /*$ImportData = [
            [
                'Email',
                'OptedIn',
                'Salutation',
                'First Name',
                'Last Name',
                'Address',
                'City',
                'State',
                'Zip',
                'Country',
                'Company',
                'Phone',
            ]
        ];
        $qry = DB::select("SELECT [RowID],[Field_Display_Name],[Field_Db_Name] FROM UI_Field_Mapping");
        $columns = collect($qry)->map(function($x){ return (array) $x; })->toArray();
        foreach( $ImportData[0] as $key=>$cell ){
            $found_key = array_search(trim($cell), array_column($columns, 'Field_Display_Name'));
            if($found_key > -1){
                $columns[$found_key] = [
                    'RowID' => $columns[$found_key]['RowID'],
                    'Field_Display_Name' => $columns[$found_key]['Field_Display_Name'],
                    'Field_Db_Name' => $columns[$found_key]['Field_Db_Name'],
                    'is_display' => 0,
                ];
            }
        }
        echo '<pre>';
        print_r($columns);
        die;*/
        /*$date = date("m/d/Y", time());
        $time = date("H:i:s", time() + 60 + 10);

        $schDir = config('constant.schDir');
        $schtasks_dir = config('constant.schtasks_dir');

        $command = 'schtasks /create /tn ' . $schtasks_dir . '\\accuzipopen2_bat /tr ' . public_path('\\accuzipopen2.bat') . ' /sc once /st ' . $time . ' /sd ' . $date .' /ru Administrator';
        Helper::schtask_curl($command);*/
    }
}
