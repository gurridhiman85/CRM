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

class ModelMetaDataController extends Controller
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

    public function getMetadata(Request $request,Ajax $ajax){
        $tempid = $request->input('tempid');
        /*$mSQL = DB::select("SELECT [t_id],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
		    ,[seg_camp_grp_proportion],[seg_camp_grp_sel_cri],[seg_sample],[promoexpo_cd_opt],[meta_data]

            FROM [UC_Campaign_Templates] Where [row_id] = " . $tempid);

        $aData = collect($mSQL)->map(function($x){ return (array) $x; })->toArray();*/

        $record = App\Model\CampaignTemplate::with(['rpmeta'])->where('row_id',$tempid)->first(['row_id','t_id','seg_selected_criteria','seg_grp_no'
            ,'seg_ctrl_grp_opt','seg_camp_grp_dtls','seg_camp_grp_proportion','seg_camp_grp_sel_cri','seg_sample'
            ,'promoexpo_cd_opt'])->toArray();

        /*$sD = [];
        if(!empty($record)){
            //foreach($record as $k=>$row) {
                $rpmeta = isset($record['rpmeta']) ? $record['rpmeta'] : [];
                echo '<pre>'; print_r($rpmeta);
                $sD[]= implode("|Meta|",$rpmeta);
            //}

        }
        $str = implode(":", $sD);*/
        return $ajax->success()
            ->appendParam('aData',$record)
            //->appendParam('str',$str)
            ->response();
    }

    public function metaSaveLkp(Request $request,Ajax $ajax){
        $LValue = $request->input('LValue');
        $LValueArray = explode(":",$LValue);

        //ProductCat1
        if($LValueArray[0] != '')
        {
            $exSQL = "Select * from [Lookup] where [code_type] = 'PC1' and [code_value] = '".$LValueArray[0]."'";
            $aData = DB::select($exSQL);

            //If its not already exists... Insert
            if(empty($aData)){
                $LookupSQL = "INSERT INTO [Lookup]([code_type],[code_value]) VALUES ( 'PC1','$LValueArray[0]' )";
                DB::insert($LookupSQL);
            }
        }

        //ProductCat1

        //ProductCat2
        if($LValueArray[1] != '')
        {
            $exSQL = "Select * from [Lookup] where [code_type] = 'PC2' and [code_value] = '".$LValueArray[1]."'";
            $aData = DB::select($exSQL);

            //If its not already exists... Insert
            if(empty($aData)){
                $LookupSQL = "INSERT INTO [Lookup]([code_type],[code_value]) VALUES ( 'PC2','$LValueArray[1]' )";
                DB::insert($LookupSQL);
            }
        }
        //ProductCat2

        //Objective
        if($LValueArray[2] != '')
        {
            $exSQL = "Select * from [Lookup] where [code_type] = 'Obj' and [code_value] = '".$LValueArray[2]."'";
            $aData = DB::select($exSQL);
            //If its not already exists... Insert
            if(empty($aData)){
                $LookupSQL = "INSERT INTO [Lookup]([code_type],[code_value]) VALUES ( 'Obj','$LValueArray[2]' )";
                DB::insert($LookupSQL);
            }
        }
        return $ajax->success()
            ->response();
    }
}
