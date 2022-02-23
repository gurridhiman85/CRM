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

class ImportbulkccController extends Controller
{
    public  $schtasks_dir;
    public $db;

    const sources = [
        [
            'label' =>  'CC_P1_1Mth',
            'sp'    =>  'sp_CRM_Update_Email_P1',
            'table' =>  'I_CC_Contacts_Open_1Mth_S1'
        ],
        [
            'label' =>  'CC_P2_6Mth',
            'sp'    =>  'sp_CRM_Update_Email_P2',
            'table' =>  'I_CC_Contacts_Open_6Mth_S1'
        ],
        [
            'label' =>  'CC_P3_12Mth',
            'sp'    =>  'sp_CRM_Update_Email_P3',
            'table' =>  'I_CC_Contacts_Open_12Mth_S1'
        ],
        [
            'label' =>  'CC_P4_Full',
            'sp'    =>  'sp_CRM_Update_Email_P4',
            'table' =>  'I_CC_Contacts_Open_24Mth_S1'
        ]
    ];

    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->db = DB::connection('sqlsrv');
    }

    public function index(){
        $User_Type = Auth::user()->authenticate->User_Type;
        $permissions = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($permissions) ? explode(',',$permissions) : [];

        if(!in_array('Import CC',$visiblities) && $User_Type != 'Full_Access'){
            return view('layouts.error_pages.404');
        }
        return view('importbulkcc.index',['sources' => self::sources]);
    }

    public function step1(Request $request,Ajax $ajax){
        $rules = ['xlsx','xls','csv'];
        $files = $request->file('files');
        $destination = public_path('\\Import_Input\\');
        foreach($files as $file)
        {
            if(!in_array($file->getClientOriginalExtension(),$rules)){
                return $ajax->fail()
                    ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                    ->jscallback()
                    ->response();
            }
            self::uploadfiles($file, self::sources[0], $destination, $rules, $ajax, $this->db);
            $count = self::checkcount($file->getClientOriginalName(), $this->db);
        }

        return $ajax->success()
            ->appendParam('count',isset($count) ? $count : 0)
            ->jscallback()
            ->response();
    }

    public function step2(Request $request,Ajax $ajax){
        $rules = ['xlsx','xls','csv'];
        $files = $request->file('files');
        $destination = public_path('\\Import_Input\\');
        foreach($files as $file)
        {
            if(!in_array($file->getClientOriginalExtension(),$rules)){
                return $ajax->fail()
                    ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                    ->jscallback()
                    ->response();
            }
            self::uploadfiles($file, self::sources[1], $destination, $rules, $ajax, $this->db);
            $count = self::checkcount($file->getClientOriginalName(), $this->db);
        }

        return $ajax->success()
            ->appendParam('count',isset($count) ? $count : 0)
            ->jscallback()
            ->response();
    }

    public function step3(Request $request,Ajax $ajax){
        $rules = ['xlsx','xls','csv'];
        $files = $request->file('files');
        $destination = public_path('\\Import_Input\\');
        foreach($files as $file)
        {
            if(!in_array($file->getClientOriginalExtension(),$rules)){
                return $ajax->fail()
                    ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                    ->jscallback()
                    ->response();
            }
            self::uploadfiles($file, self::sources[2], $destination, $rules, $ajax, $this->db);
            $count = self::checkcount($file->getClientOriginalName(), $this->db);
        }

        return $ajax->success()
            ->appendParam('count',isset($count) ? $count : 0)
            ->jscallback()
            ->response();
    }

    public function step4(Request $request,Ajax $ajax){
        $rules = ['xlsx','xls','csv'];
        $files = $request->file('files');
        $destination = public_path('\\Import_Input\\');
        foreach($files as $file)
        {
            if(!in_array($file->getClientOriginalExtension(),$rules)){
                return $ajax->fail()
                    ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                    ->jscallback()
                    ->response();
            }
            self::uploadfiles($file, self::sources[3], $destination, $rules, $ajax, $this->db);
            $count = self::checkcount($file->getClientOriginalName(), $this->db);
        }

        return $ajax->success()
            ->appendParam('count',isset($count) ? $count : 0)
            ->jscallback()
            ->response();
    }

    public function step5(Request $request, Ajax $ajax){

        $stmt = $this->db->getPdo()->prepare("EXEC dbo.sp_CRM_Update_Email_P5_Sendto_CC");
        $stmt->execute();

        $sources = self::sources;
        $s1Qry = DB::select("SELECT count(*) as count FROM ".$sources[0]['table']);
        $s1Count = collect($s1Qry)->map(function($x){ return (array) $x; })->toArray();
        $s1Count = isset($s1Count[0]['count']) ? $s1Count[0]['count'] : 0;

        $s2Qry = DB::select("SELECT count(*) as count FROM ".$sources[1]['table']);
        $s2Count = collect($s2Qry)->map(function($x){ return (array) $x; })->toArray();
        $s2Count = isset($s2Count[0]['count']) ? $s2Count[0]['count'] : 0;

        $s3Qry = DB::select("SELECT count(*) as count FROM ".$sources[2]['table']);
        $s3Count = collect($s3Qry)->map(function($x){ return (array) $x; })->toArray();
        $s3Count = isset($s3Count[0]['count']) ? $s3Count[0]['count'] : 0;

        $s4Qry = DB::select("SELECT count(*) as count FROM ".$sources[3]['table']);
        $s4Count = collect($s4Qry)->map(function($x){ return (array) $x; })->toArray();
        $s4Count = isset($s4Count[0]['count']) ? $s4Count[0]['count'] : 0;

        $download = '/downloads/CC_ToSend.txt';
        if($s1Count > 0 && $s2Count > 0 && $s3Count > 0 && $s4Count > 0){
            $source_path = 'D:\data\ZSS\output\CC_ToSend.txt';
            $dest_path = public_path('\\downloads\\CC_ToSend.txt');
            if(file_exists($source_path)){
                if(file_exists($dest_path)){
                    unlink($dest_path);
                }
                copy($source_path, $dest_path);
            }
            return $ajax->success()
                ->jscallback('ajax_step5')
                ->message("Congratulations! You successfully imported ")
                ->appendParam('download_url', url('/') . $download)
                ->response();
        }else{
            return $ajax->fail()
                ->jscallback('ajax_step5')
                ->message('Some files are missing. Please check and try again or contact your CRMSquare administrator at esupport@datasquare.com')
                ->response();
        }


    }

    public static function uploadfiles($file, $source, $destination, $rules, $ajax, $db){
        if(!in_array($file->getClientOriginalExtension(),$rules)){
            return $ajax->fail()
                ->form_errors(json_encode(['files.0' => 'Invalid file extension, Only allowed extensions are xlsx,xls,csv']))
                ->jscallback()
                ->response();
        }

        $a_url = $file->getClientOriginalName();
        $file->move($destination, $a_url);
        $inputFileName = $destination.$a_url;

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


        $source = self::sources[0];
        $stmt = $db->getPdo()->prepare("EXEC dbo.".$source['sp']);
        $stmt->execute();

        $uiCountQry = DB::select("SELECT count(*) as count FROM userinput");
        $uiCount = collect($uiCountQry)->map(function($x){ return (array) $x; })->toArray();

        Session::put('IM_File_Name', $a_url);
        Session::put('IM_File_Records', isset($uiCount[0]['count']) ? $uiCount[0]['count'] : 0);
    }

    public static function checkcount($a_url, $db){
        $source = self::sources[0];
        $stmt = $db->getPdo()->prepare("EXEC dbo.".$source['sp']);
        $stmt->execute();

        $uiCountQry = DB::select("SELECT count(*) as count FROM userinput");
        $uiCount = collect($uiCountQry)->map(function($x){ return (array) $x; })->toArray();
        $count = isset($uiCount[0]['count']) ? $uiCount[0]['count'] : 0;

        Session::put('IM_File_Name', $a_url);
        Session::put('IM_File_Records', $count);

        return $count;
    }
}
