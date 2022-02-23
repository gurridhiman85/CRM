<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use http\Env\Response;
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
use Yajra\Datatables\Datatables;

class ImportzoomController extends Controller {

    public $schtasks_dir;
    public $db;

    public function __construct() {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->db = DB::connection('sqlsrv');
    }

    public function index() {
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',', $permissions) : [];

        if (!in_array('Import Zoom', $visiblities) && $User_Type != 'Full_Access') {
            return view('layouts.error_pages.404');
        }

        return view('importzoom.index');
    }

    public function importStep1(Request $request, Ajax $ajax) {
        $rules = ['xlsx', 'xls', 'csv'];

        $files = $request->file('files');
        $source = $request->input('source');
        $destination = public_path('\\Import_Input\\');

        try {
            DB::statement("DROP table userinput");
        } catch (\Exception $exception) {

        }

        foreach ($files as $key => $file) {
            if (!in_array($file->getClientOriginalExtension(), $rules)) {
                return $ajax->fail()
                                ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                                ->jscallback()
                                ->response();
            }

            try {
                DB::statement("DROP table userinput1");
            } catch (\Exception $exception) {

            }

            $a_url = $file->getClientOriginalName();
            $file->move($destination, $a_url);
            $inputFileName = $destination . $a_url;

            /* $a_url = str_replace ( $illegals, "", $file->getClientOriginalName() );
              $newname = $destination.$a_url;
              rename($file->getClientOriginalName(),$newname);
              $inputFileName = $destination.$a_url; */

            /**  Identify the type of $inputFileName  * */
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            /**  Create a new Reader of the type that has been identified  * */
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  * */
            $spreadsheet = $reader->load($inputFileName);

            $sTitle = $spreadsheet->getActiveSheet()->getTitle();
            $ImportData = $spreadsheet->getActiveSheet()->toArray();


            if($file->getClientOriginalExtension() == 'csv'){
                $fname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $inputFileName = $destination.'copy_of_'.$fname.'.xlsx';
                $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $objWriter->save($destination.'copy_of_'.$fname.'.xlsx');

                if ($sTitle == trim($sTitle) && strpos($sTitle, ' ') !== false) {
                    DB::statement("SELECT * INTO userinput1 FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', ['".$sTitle."$']);");
                }else{
                    DB::statement("SELECT * INTO userinput1 FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', [".$sTitle."$]);");
                }
            }else{
                if ($sTitle == trim($sTitle) && strpos($sTitle, ' ') !== false) {
                    DB::statement("SELECT * INTO userinput1 FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', ['".$sTitle."$']);");
                }else{
                    DB::statement("SELECT * INTO userinput1 FROM OPENROWSET('Microsoft.ACE.OLEDB.12.0','Excel 12.0; Database=".$inputFileName."', [".$sTitle."$]);");
                }
            }
            $inColumns = DB::select("SELECT column_name FROM " . DB::connection()->getConfig('database') . ".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'userinput1'");
            $iColumns = [];
            foreach ($inColumns as $column) {
                $iColumns[] = "[".$column->column_name."]";
            }

            /* Check File format - Start */
            $qry = DB::select("SELECT [RowID],[Field_Display_Name],[Field_Db_Name] FROM UI_Field_Mapping");
            $columns = collect($qry)->map(function($x) {
                        return (array) $x;
                    })->toArray();

            $inColumns = DB::select("SELECT column_name FROM " . DB::connection()->getConfig('database') . ".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'userinput1'");

            $dColumns = [];
            foreach ($inColumns as $column) {
                foreach ($columns as $allowedColumn) {
                    if (strtolower($allowedColumn['Field_Display_Name']) == strtolower($column->column_name)) {
                        $dColumns[] = '[' . $allowedColumn['Field_Display_Name'] . ']';
                    }
                }
            }

            if (count($dColumns) == 0) { // Fail file format
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

            if($key == 0) {
                DB::insert("SELECT * into userinput FROM userinput1");
                DB::statement("IF COL_LENGTH('userinput','user_import_id') IS NULL BEGIN alter table userinput add user_import_id int , import_filename nvarchar(250) END");
                DB::update("UPDATE userinput SET user_import_id = '$imId',import_filename = '$a_url'");
            }
            else
                DB::insert("INSERT INTO userinput (".implode(',',$iColumns).",user_import_id,import_filename) SELECT ".implode(',',$iColumns).",'$imId','$a_url' FROM userinput1");

            //$result = DB::select(DB::raw("exec sp_CRM_Update_Zoomimport_S0_Insert"));

            $uiCountQry = DB::select("SELECT count(*) as count FROM userinput");
            $uiCount = collect($uiCountQry)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            Session::put('IM_File_Name', $a_url);
            Session::put('IM_ID', $imId);
            Session::put('IM_File_Records', isset($uiCount[0]['count']) ? $uiCount[0]['count'] : 0);
        }

        $db = $this->db->getPdo();
        $stmt = $db->prepare("EXEC dbo.sp_CRM_Update_Zoomimport_S0_Insert");
        $stmt->execute();

        $stmt2 = $db->prepare("exec sp_CRM_Update_ZoomImport_S1_MatchNoAddr");
        $stmt2->execute();

        //Step 2
        $sStep2TableRows = DB::select("select  rowid, customer_s2,dflname, email  from
(select ROW_NUMBER() over (partition by rowid  Order By online_attendee  DESC) as ROWNUMBER, * from
(select s.rowid, s.customer_s2, x.dflname, s.email , x.online_attendee from contact_temp s inner join contact x
on s.customer_s2=x.dharmaname or s.customer_s2=x.firstname + ' ' + x.lastname where isnull(s.customer_s2,'')  <> '' and isnull(s.ds_mkc_contactid,'') =''
) a)b where rownumber=1");
        $sStep2TableRows = collect($sStep2TableRows)->map(function($x) {
                    return (array) $x;
                })->toArray();
        $html = View::make('importzoom.step2-table', ['sStep2TableRows' => $sStep2TableRows])->render();

        return $ajax->success()
                        ->appendParam('html', $html)
                        ->appendParam('Import_Id', $imId)
                        ->appendParam('Import_Filename', $a_url)
                        ->appendParam('columns', $columns)
                        ->response();
    }

    public function importStep2(Request $request, Ajax $ajax) {

        $sourcefeed = $request->input('source3');
        $Import_Filename = Session::get('IM_File_Name');
        $Import_Id = Session::get('IM_ID');
        $rowids = implode(',', $request->input('rowid', []));
        $rowcondition = !empty($rowids) ? " AND rowid IN (" . $rowids . ")" : "";
        try {
            DB::update("update s set s.dflname = x.dflname from contact_temp s inner join contact x on s.customer_s2=x.dharmaname or s.customer_s2=x.firstname + ' ' + x.lastname 
where isnull(s.customer_s2,'')  <> '' and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");
            DB::update("update s set s.ds_mkc_contactid = x.ds_mkc_contactid from contact_temp s inner join contact x          on s.dflname = x.dflname        
where isnull(s.dflname ,'') <> '' and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");

            //Step 3
            $sStep3TableRows = DB::select("select s.rowid, s.customer_s2, x.dflname, s.email                              from contact_temp s inner join contact x on s.customer_s2= x.lastname or REVERSE( LEFT( REVERSE(s.customer_s2), CHARINDEX(' ', REVERSE(s.customer_s2))-1 ) ) =  x.lastname where isnull(s.customer_s2,'')  <> '' and len(x.lastname) > 2  and s.customer_s2 like '% %' and substring(s.customer_s2,1,1)=substring(x.firstname,1,1) and isnull(s.ds_mkc_contactid,'') =''
group by  s.rowid, s.customer_s2, x.online_attendee , x.dflname, s.email order by  s.rowid, s.customer_s2, x.online_attendee desc, x.dflname, s.email");
            $sStep3TableRows = collect($sStep3TableRows)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            $html = View::make('importzoom.step3-table', ['sStep2TableRows' => $sStep3TableRows])->render();

            return $ajax->success()
                            ->appendParam('html', $html)
                            ->jscallback('ajax_Step3')
                            ->response();
        } catch (\Exception $exception) {
            //Error
            DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =" . $Import_Id);

            $source_file = public_path('\\Import_Input\\' . $Import_Filename);
            $destination_path = public_path('\\Import_Error\\');

            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            return $ajax->fail()
                            ->jscallback('ajax_Step2')
                            ->appendParam('message', $exception->getMessage())
                            ->response();
        }
    }

    public function importStep3(Request $request, Ajax $ajax) {
        $rowids = implode(',', $request->input('rowid', []));
        $sourcefeed = $request->input('source3');
        $Import_Filename = Session::get('IM_File_Name');
        $Import_Id = Session::get('IM_ID');
        $rowcondition = !empty($rowids) ? " AND rowid IN (" . $rowids . ")" : "";
        try {
            DB::update("update s set s.dflname = x.dflname from contact_temp s inner join contact x on s.customer_s2= x.lastname or REVERSE( LEFT( REVERSE(s.customer_s2), CHARINDEX(' ', REVERSE(s.customer_s2))-1 ) ) =  x.lastname where isnull(s.customer_s2,'')  <> '' and len(x.lastname) > 2  and s.customer_s2 like '% %' and substring(s.customer_s2,1,1)=substring(x.firstname,1,1) and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");
            DB::update("update s set s.ds_mkc_contactid = x.ds_mkc_contactid from contact_temp s inner join contact x on s.dflname = x.dflname        where isnull(s.dflname ,'') <> '' and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");

            //Step 4
            $sStep4TableRows = DB::select("select s.rowid, s.customer_s2, x.dflname, s.email   from contact_temp s inner join contact x on substring(s.customer_s2,1,CHARINDEX(' ',s.customer_s2)) =x.dharmaname  where isnull(substring(s.customer_s2,1,CHARINDEX(' ',s.customer_s2)),'')  <> '' and isnull(s.dflname,'') ='' and x.dharmaname not in (select dharmaname from Xref_NameSuppress) and isnull(s.ds_mkc_contactid,'') =''
group by  s.rowid, s.customer_s2, x.online_attendee , x.dflname, s.email
order by  s.rowid, s.customer_s2, x.online_attendee desc, x.dflname, s.email");
            $sStep4TableRows = collect($sStep4TableRows)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            $html = View::make('importzoom.step4-table', ['sStep2TableRows' => $sStep4TableRows])->render();

            return $ajax->success()
                            ->appendParam('html', $html)
                            ->jscallback('ajax_Step4')
                            ->response();
        } catch (\Exception $exception) {
            //Error
            DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =" . $Import_Id);

            $source_file = public_path('\\Import_Input\\' . $Import_Filename);
            $destination_path = public_path('\\Import_Error\\');

            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            return $ajax->fail()
                            ->jscallback('ajax_Step4')
                            ->appendParam('message', $exception->getMessage())
                            ->response();
        }
    }

    public function importStep4(Request $request, Ajax $ajax) {
        $rowids = implode(',', $request->input('rowid', []));
        $sourcefeed = $request->input('source3');
        $Import_Filename = Session::get('IM_File_Name');
        $Import_Id = Session::get('IM_ID');
        $rowcondition = !empty($rowids) ? " AND rowid IN (" . $rowids . ")" : "";
        try {
            DB::update("update   s set s.dflname =  x.dflname from contact_temp s inner join contact x on substring(s.customer_s2,1,CHARINDEX(' ',s.customer_s2)) =x.dharmaname  where isnull(substring(s.customer_s2,1,CHARINDEX(' ',s.customer_s2)),'')  <> '' and isnull(s.dflname,'') ='' and x.dharmaname not in (select dharmaname from Xref_NameSuppress) and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");
            DB::update("update s set s.ds_mkc_contactid = x.ds_mkc_contactid from contact_temp s inner join contact x  on s.dflname = x.dflname where isnull(s.dflname ,'') <> '' and isnull(s.ds_mkc_contactid,'') ='' $rowcondition");

            //Step 5
            DB::update("update   contact_temp set dflname_Suggested = case when isnull(substring(customer_s1,1,CHARINDEX('(',customer_s1)),'')  <> '' then substring(customer_s1,1,CHARINDEX('(',customer_s1)-1) else customer_s1 end where isnull(ds_mkc_contactid,'') =''");

            $db = $this->db->getPdo();
            $stmt = $db->prepare("EXEC dbo.sp_CRM_Update_Zoomimport_S1_Sort");
            $stmt->execute();

            $sStep5TableRows = DB::select("select distinct rowid,customer_s2 , dflname_Suggested,dflname, email, DS_MKC_ContactID,countspaces_lname  from contact_temp where ds_mkc_contactid is null and isnull(customer_s2,'') <> '' group by rowid,  countspaces_lname, customer_s2 , dflname_Suggested,dflname, email, DS_MKC_ContactID order by countspaces_lname, customer_s2 , dflname_Suggested,dflname,  DS_MKC_ContactID desc, email");
            $sStep5TableRows = collect($sStep5TableRows)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            $html = View::make('importzoom.step5-table', ['sStep2TableRows' => $sStep5TableRows])->render();

            return $ajax->success()
                            ->appendParam('html', $html)
                            ->appendParam('sStep5TableRows', $sStep5TableRows)
                            ->jscallback('ajax_Step5')
                            ->response();
        } catch (\Exception $exception) {
            //Error
            DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =" . $Import_Id);

            $source_file = public_path('\\Import_Input\\' . $Import_Filename);
            $destination_path = public_path('\\Import_Error\\');

            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            return $ajax->fail()
                            ->jscallback('ajax_Step5')
                            ->appendParam('message', $exception->getMessage())
                            ->response();
        }
    }

    public function importStep5(Request $request, Ajax $ajax) {
        $rowids = implode(',', $request->input('rowid', []));
        $Import_Filename = Session::get('IM_File_Name');
        $Import_Id = Session::get('IM_ID');

        try {
            DB::update("update contact_temp set updaterecord=1 where ds_mkc_contactid is not null and  email <> ''");
            DB::update("update contact_temp set updaterecord=0 where ds_mkc_contactid is null or  email = ''");
            DB::update("update contact_temp set updaterecord=0 where email in (select email from contact) or email in (select email2 from contact) or email  in (select email3 from contact) or email in (select email4 from contact) or email in (select email5 from contact)");



            $sStep6TableRows = DB::select("select distinct rowid,customer_s2 , dflname, email , DS_MKC_ContactID from contact_temp where updaterecord=1");
            $sStep6TableRows = collect($sStep6TableRows)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            $html = View::make('importzoom.step6-table', ['sStep2TableRows' => $sStep6TableRows])->render();


            return $ajax->success()
                            ->appendParam('html', $html)
                            ->jscallback('ajax_Step6')
                            ->response();
        } catch (\Exception $exception) {
            //Error
            DB::update("Update UI_File_Name SET Import_Status = 'Error' WHERE User_Import_ID =" . $Import_Id);

            $source_file = public_path('\\Import_Input\\' . $Import_Filename);
            $destination_path = public_path('\\Import_Error\\');

            rename($source_file, $destination_path . pathinfo($source_file, PATHINFO_BASENAME));

            return $ajax->fail()
                            ->jscallback('ajax_Step6')
                            ->appendParam('message', $exception->getMessage())
                            ->response();
        }
    }

    public function importStep6(Request $request, Ajax $ajax) {
        $rowids = implode(',', $request->input('rowid', []));

        if (!empty($rowids)) {
            DB::update("update contact_temp set updaterecord=0 WHERE rowid Not IN (".$rowids.")");
            DB::update("update contact_temp set updaterecord=1 WHERE rowid IN (".$rowids.")");
        }

        $db = $this->db->getPdo();
        $stmt = $db->prepare("EXEC dbo.sp_CRM_Update_Zoomimport_S1_Update");
        $stmt->execute();

        $stmt = $db->prepare("EXEC dbo.sp_CRM_Update_Zoomimport_S1_Sort");
        $stmt->execute();

            //Step 7 first update
        $sStep7TableRows = DB::select("select distinct rowid, customer_s2, salutation, dharmaname, firstname, middlename, lastname, suffix , dflname, email,countspaces_lname  from contact_temp where ds_mkc_contactid is null order by email, countspaces_lname");
        $sStep7TableRows = collect($sStep7TableRows)->map(function($x) {
            return (array) $x;
        })->toArray();
        $html = View::make('importzoom.step7-table', ['sStep7TableRows' => $sStep7TableRows])->render();

        return $ajax->success()
            ->appendParam('html', $html)
            ->jscallback('ajax_Step7')
            ->response();

    }

    public function importFigure(Request $request,Ajax $ajax) {
        $rowids = implode(',', $request->input('rowid', []));
        if (!empty($rowids)) {
            DB::update("update contact_temp set insertrecord=1 WHERE rowid IN (".$rowids.")");
        }

        $db = $this->db->getPdo();
        $stmt = $db->prepare("EXEC dbo.sp_ZSS_Update_EnvelopeLetter_contact_temp");
        $stmt->execute();

        $stmt = $db->prepare("EXEC dbo.sp_CRM_Update_BulkImport_S4_Insert");
        $stmt->execute();

        DB::insert("insert into xref_namezoom (zoomname, dflname) select distinct customer_s1, dflname from contact_temp where DS_MKC_ContactID is not null and isnull(dflname,'') <> ''");

        $qry = DB::select("select count(rowid) as count from contact_temp where  updaterecord=1"); //matched records
        $matched = collect($qry)->map(function($x) {
                    return (array) $x;
                })->toArray();

        $qry = DB::select("select count(rowid) as count from contact_temp where  insertrecord=1");  //inserted records
        $inserted = collect($qry)->map(function($x) {
                    return (array) $x;
                })->toArray();

        $html = '<h5 class="mb-1 text-center">Congratulations!!</h5>
            <table class="table table-bordered table-hover color-table lkp-table font-16"><thead>
                <tr>
                    <th class="text-center">Updated Records</th>
                    <th class="text-center">Inserted Records</th>
                </tr>
                </thead><tbody>';

        $html .= '<tr>
                    <td class="text-center">' . $matched[0]['count'] . '</td>
                    <td class="text-center">' . $inserted[0]['count'] . '</td>
                </tr>';

        $html .= '</tbody><table>';


        $sdata = [
            'content' => $html
        ];

        $title = '';
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
                        ->jscallback('importZoomCompleted')
                        ->response();
    }

    public function step5AutoFill(Request $request, Ajax $ajax) {
        if ($request->input('update') == 'false') {
            $term = $request->input('term', '');
            $results = DB::select("select DS_MKC_ContactID,dflname from contact where dflname like '%" . $term . "%'");
            $dflname = array();
            foreach ($results as $result) {
                $dflname[] = ['id' => $result->DS_MKC_ContactID, 'label' => $result->dflname, 'value' => $result->dflname];
            }
            return json_encode($dflname);
        } else {
            $itemSelId = $request->input('itemSelId', '');
            $itemSelVal = str_ireplace("'", "''", $request->input('itemSelVal', ''));

            DB::update("update   s set s.ds_mkc_contactid=x.ds_mkc_contactid , s.dflname= x.dflname, s.dflname_suggested=x.dflname from contact_temp s inner join contact x on x.dflname ='" . $itemSelVal . "'
 and s.ds_mkc_contactid is null where rowid= $itemSelId");

            $sStep5TableRows = DB::select("select rowid,customer_s2 , dflname_Suggested,dflname, email, DS_MKC_ContactID from contact_temp where  rowid = $itemSelId");
            $sStep5TableRows = collect($sStep5TableRows)->map(function($x) {
                        return (array) $x;
                    })->toArray();
            $sStep5TableRow = $sStep5TableRows[0];
            return $ajax->success()
                            ->appendParam('sStep5TableRow', $sStep5TableRow)
                            ->appendParam('rowid',$itemSelId)
                            ->jscallback()
                            ->response();
        }
    }

    public function step5AddInsertRecord(Request $request, Ajax $ajax) {

        $itemSelId = $request->input('itemSelId', '');
        $itemSelId = $request->input('itemSelId', '');
        $itemSelVal = str_ireplace("'", "''", $request->input('itemSelVal', ''));

        DB::update("update   s set s.ds_mkc_contactid=x.ds_mkc_contactid , s.dflname= x.dflname, s.dflname_suggested=x.dflname,s.InsertRecord=1 from contact_temp s inner join contact x on x.dflname ='" . $itemSelVal . "'
and s.ds_mkc_contactid is null where rowid= $itemSelId");


        $sStep5TableRows = DB::select("select rowid,customer_s2 , dflname_Suggested,dflname, email, DS_MKC_ContactID,InsertRecord from contact_temp where  rowid = $itemSelId");
        $sStep5TableRows = collect($sStep5TableRows)->map(function($x) {
            return (array) $x;
        })->toArray();
        $sStep5TableRow = $sStep5TableRows[0];
        return $ajax->success()
            ->appendParam('sStep5TableRow', $sStep5TableRow)
            ->appendParam('rowid',$itemSelId)
            ->appendParam('updatesql',"update   s set s.ds_mkc_contactid=x.ds_mkc_contactid , s.dflname= x.dflname, s.dflname_suggested=x.dflname,s.InsertRecord=1 from contact_temp s inner join contact x on x.dflname ='" . $itemSelVal . "'
and s.ds_mkc_contactid is null where rowid= $itemSelId")
            ->jscallback()
            ->response();

    }

    public function step7quickEdit(Request $request, Ajax $ajax) {
        try {
            $tablename = 'contact_temp';
            $rowid = $request->input('rowid');
            $fieldname = $request->input('fieldname');
            $fieldvalue = $request->input('fieldvalue', '');
            $aData = DB::table($tablename)
                    ->where('rowid', $rowid)
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
                    ->where('rowid', $rowid)
                    ->update([$fieldname => trim($fieldvalue)]);

            $aData = DB::table($tablename)
                    ->where('rowid', $rowid)
                    ->get();
            $aData = collect($aData)->map(function($x) {
                        return (array) $x;
                    })->toArray();

            if (count($aData) == 0) {
                return $ajax->fail()
                                ->appendParam('message', 'Record not found')
                                ->response();
            }

            $aData = $aData[0];
            $dharmaname = !empty($aData['Dharmaname']) ? str_replace("'", "''", $aData['Dharmaname']) : ' ';
            $firstname = !empty($aData['Firstname']) ? str_replace("'", "''", $aData['Firstname']) : ' ';
            $lastname = !empty($aData['Lastname']) ? str_replace("'", "''", $aData['Lastname']) : ' ';
            DB::update("Update contact_temp set DFLName =  rtrim(ltrim(replace(replace( rtrim(ltrim(isnull('$dharmaname','') +  ' ' + isnull('$firstname','') +  ' ' +  isnull('$lastname',''))),'  ',' '),'  ',' '))) WHERE rowid = $rowid");

            //DB::update("SET ANSI_NULLS ON; SET ANSI_WARNINGS ON;exec sp_ZSS_Update_EnvelopeLetter ".$aData['DS_MKC_ContactID']);


            $aData = DB::table($tablename)
                    ->where('rowid', $rowid)
                    ->get();
            $aData = collect($aData)->map(function($x) {
                        return (array) $x;
                    })->toArray();

            return $ajax->success()
                            ->appendParam('aData', $aData[0])
                            ->appendParam('rowid', $rowid)
                            ->jscallback('ajax_update_step7')
                            ->response();
        } catch (\Exception $e) {
            return $ajax->fail()
                            ->appendParam('message', $e->getMessage())
                            ->response();
        }
    }
}
