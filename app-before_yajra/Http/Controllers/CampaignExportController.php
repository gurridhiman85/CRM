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

class CampaignExportController extends Controller
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

    public function getCol(Request $request,Ajax $ajax){
        $sSQL = $request->input('sSQL');
        $selected_fields = $request->input('selected_fields');
        if (trim($sSQL) != "") {
            if (strpos($sSQL, "*") === true) {
                $nSQL = str_replace("*", "TOP 1 * ", $sSQL);
            } else {
                $splitedSql = explode('FROM', $sSQL);
                //$selected_fieldsArr = explode('<|>', $selected_fields); //todo
                //$nSQL = "SELECT top 1 " . $selected_fieldsArr[0] . " FROM " . $splitedSql[1]; //todo

                $nSQL = "SELECT top 1 " . $selected_fields . " FROM " . $splitedSql[1];
                //$nSQL = substr($sSQL, 0, 6) . " top 1 " . substr($sSQL, 7, strlen($sSQL));
            }
            $aData = DB::select($nSQL);
            $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
            if (!empty($aData)) {
                foreach ($aData[0] as $k => $v) {
                    $sD[] = $k;
                }
            }
            $s = implode(",", $sD);
        }
        return $ajax->success()->appendParam('columns',$s)->response();
    }

    public function getPromoData(Request $request,Ajax $ajax){
        $tempid = $request->input('tempid');
        $SQL = "SELECT [promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file]
			,[promoexpo_ext],[promoexpo_ecg_opt],[promoexpo_data]
      
		  FROM [UC_Campaign_Templates] Where [t_id] = '$tempid'";
        $aData = DB::select($SQL);
        $aData = collect($aData)->map(function($x){ return (array) $x; })->toArray();
        if (!empty($aData)) {
            foreach ($aData as $k => $row) {
                $sD[] = implode(",", $row);
            }
        }
        $str = implode(",", $sD);
        return $ajax->success()->appendParam('promo_data',$str)->response();
    }

    public function fileExists(Request $request,Ajax $ajax){
        $filePath = $request->input('filePath');
        $Folder = $request->input('Folder');
        $FName = $request->input('FName');
        $FType = $request->input('FType');

        $FP = $filePath . $Folder . "/" . $FName . "." . $FType;
        $st = true;
        if (!file_exists($FP))
            $st = false;
        return $ajax->success($st)->response();
    }
}
