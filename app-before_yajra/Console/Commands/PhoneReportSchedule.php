<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Mail\SendCampaignEmail;
use App\Model\RepCmpMetaData;
use App\User;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Mail\SendReportEmail;
use Illuminate\Support\Facades\Mail;
use PDF;
use \Illuminate\Support\Facades\View as View;

class PhoneReportSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'phoneReportSchedule:run {sid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public $schtasks_dir;
    public $phpPath;
    public $filePath;
    public $prefix;
    public $headerCells;
    public $CommonHeader;
    public $clientname;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schtasks_dir = config('constant.schtasks_dir');
        $this->phpPath = config('constant.phpPath');
        $this->filePath = config('constant.filePath');
        $this->prefix = config('constant.prefix');
        $this->headerCells = config('constant.XlsxHeaderCells');
        $this->CommonHeader = config('constant.CommonHeader');;
        $this->clientname = config('constant.client_name');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        @ini_set('max_execution_time',200000);
        @ini_set('memory_limit', '512M');
        $sch_id = $this->argument('sid');


        $SQL = DB::select("Select [camp_tmpl_id] from [UL_RepCmp_Schedules] Where row_id = '$sch_id' AND t_type = 'A'");


        $CSVTotalRec = 0;
        $aData = collect($SQL)->map(function ($x) {
            return (array)$x;
        })->toArray();

        if (!empty($aData)) {
            $CampID = $aData[0]['camp_tmpl_id'];
            //SMTP Details
        }
//  $CGD = $aData[0]['asg_camp_dtls'];

        /*   $CampSQL ="Select export_to_file,sql,promo_save,asg_camp_grp_dtls ,export_data, asg_lseg_dtls,
                            export_folder,export_filename,export_filetype,export_controlgroup,asg_lsegno,
                             asg_camp_grp_no,asg_camp_ctrl_grp,asg_def_list,asg_lseg_method,asg_camp_grp_sel,
                             meta_data,t_name,t_type,camp_id,byfield_count, asg_camp_dtls
            From Campaign_Templates Where template_id = '".$CampID."'"; */

        $CampSQL = DB::select("SELECT [t_id],[list_short_name],[t_name],[sql],[seg_def],[seg_method],[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
		    ,[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],[promoexpo_ext]
		    ,[promoexpo_ecg_opt],[promoexpo_data],[selected_fields],[Report_Row],[Custom_SQL],[SR_Attachment],[List_Format] FROM [UR_Report_Templates] Where row_id = '" . $CampID . "' AND t_type='A'");


        $aData = collect($CampSQL)->map(function ($x) {
            return (array)$x;
        })->toArray();

        if (!empty($aData)) {
            $forEmail = $aData[0];
            foreach ($aData as $k => $row) {

                $CID = $aData[0]['t_id'];
                $list_short_name = $aData[0]['list_short_name'];
                $sSQL = $aData[0]['sql'];
                $segDef = $aData[0]['seg_def'];
                $segMethod = $aData[0]['seg_method'];
                $segCriteria = $aData[0]['seg_criteria'];

                $criteria = $aData[0]['seg_selected_criteria'];

                $noCG = $aData[0]['seg_grp_no'];
                $seg_CG_Opt = $aData[0]['seg_ctrl_grp_opt'];
                $sample = $aData[0]['seg_sample'];

                $CD_Opt = $aData[0]['promoexpo_cd_opt'];
                $file_Opt = $aData[0]['promoexpo_file_opt'];
                $promoexpo_data = $aData[0]['promoexpo_data'];
                $folder_Name = $aData[0]['promoexpo_folder'];
                $file_Name = $aData[0]['promoexpo_file'];
                $file_Ext = $aData[0]['promoexpo_ext'];
                //$metaData = $aData[0]['meta_data'];
                $CGDetail = $aData[0]['seg_camp_grp_dtls'];
                $promoExpo_ecg_opt = $aData[0]['promoexpo_ecg_opt'];
                $Report_Row = $aData[0]['Report_Row'];
                $Custom_SQL = $aData[0]['Custom_SQL'];
                $SR_Attachment = $aData[0]['SR_Attachment'];
                $List_Format = $aData[0]['List_Format'];

                $expcols = !empty($aData[0]['selected_fields']) ? $aData[0]['selected_fields'] : 'DS_MKC_ContactID';
                if($Custom_SQL == 'Y'){
                    $sSQL = str_replace("::", ",", $sSQL);

                    if (strpos($sSQL, "*") === true) {
                        $nSQL = str_replace("*", "TOP 1 * ", $sSQL);
                    } else {
                        $nSQL = substr($sSQL, 0, 6) . " top 1 " . substr($sSQL, 7, strlen($sSQL));
                    }
                    $aData = DB::select($nSQL);
                    $columns = [];
                    if (isset($aData[0])) {
                        foreach ($aData[0] as $ckey=>$datum){
                            $columns[] =  ucwords($ckey);
                        }
                        $expcols = implode(',',$columns);
                    }
                }

            }
        }

        $metadata_date = date('Y-m-d');
        $metaData = RepCmpMetaData::where('CampaignID',$CID)->where('type','A')->first();
        if($metaData){
            $metadata_date = $metaData->Start_Date;
        }


        if ((trim($file_Opt) == 'Y' && trim($sSQL) != '') || ((trim($CD_Opt) == 'Y') && trim($sSQL) != '')) {
            $date = date("m/d/y  H:i:s", time());    //Schedule stating date and time
            $sampleArray = !empty($sample) ? explode("^", $sample) : [];
            $sampleRecords = !empty($sampleArray[1]) ? explode(":", $sampleArray[1]) : [];
            $expcolsarray = !empty($promoexpo_data) ? explode("|", $promoexpo_data) : [];
            $flag = 0;

            //  create_temp_table($sql1,$cname,$CID,$expcols);
            $flag = 0;
            $cols = "";

            $pos = strpos($sSQL, "Order By");
            if ($pos != false) {
                $sSQLTemp = substr($sSQL, 0, $pos - 1);
                $orderStr = substr($sSQL, $pos + 9);
                $orderWords = explode(" ", $orderStr);
                $index = 0;
                for ($i = 0; $i < count($orderWords); $i++) {

                    $dotPos = strpos($orderWords[$i], '.');
                    if ($dotPos != false) {
                        $orderColArray = explode(".", $orderWords[$i]);
                        $colIndex = count($orderColArray) - 1;
                        $orderCol[$index] = $orderColArray[$colIndex];
                    } else {
                        $orderCol[$index] = $orderWords[$i];
                    }
                    $index = $index + 1;

                }
                $sort = "Order By " . implode(" ", $orderCol);

            } else {
                $sSQLTemp = $sSQL;
                $sort = "";
            }
            $tblName = "tmp_" . time() . "_" . date("Ymd", time());
            $pos = strpos($sSQLTemp, "Order By");

            if ($pos != false) {
                $ordrByArr = explode('Order By', $sSQLTemp);
                $ordrBy = " Order By " . $ordrByArr[1];
                $sSQLTemp = substr($sSQLTemp, 0, $pos - 1);
            } else {
                $ordrBy = " Order By DS_MKC_ContactID ASC";
            }
            $tempSQL = "Select * into $tblName From ( " . $sSQLTemp . " ) as t";
            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;".$tempSQL);
            //Check Campaign ID Exists or not

            // open file and Write the header
            if (trim($file_Opt) == 'Y') {
                if(in_array($SR_Attachment,['onlylist','both'])) {
                    $fhead = fopen($this->filePath . 'public\\downloads\\' . $this->prefix . "RPL_" . $file_Name . ".xlsx", 'a');
                    $expcols = $expcols . "\n";
                    fwrite($fhead, $expcols);
                    fclose($fhead);
                }
            }
            // Check CampaignID,SegmentID,GroupID
            $colArray = explode(",", $expcols);
            $where_count = 1;
            //$expc = is_array($colArray) ? implode(',',$colArray) : 'DS_MKC_ContactID';

            for ($i = 0; $i < $where_count; $i++) {
                $expc = !empty($expcols) ? $expcols : 'DS_MKC_ContactID';
                $newsql = "Select top 100 percent " . $expc . " From " . $tblName . ' ' . $ordrBy;
                //echo $newsql;
                //$aData = $oDb->executeSelect($newsql);

                /***************************** Changes 2017-04-11 Start ***********************************/

                if ((trim($file_Opt) == 'Y') && (trim($file_Ext) == "csv")) {
                    $newsql1 = DB::select($newsql);
                    $aDataSql = collect($newsql1)->map(function($x){ return (array) $x; })->toArray();
                    DB::statement("insert into openrowset  ('Microsoft.ACE.OLEDB.12.0','Text;Database=" . $this->filePath .'public\\downloads\\;' . "HDR=YES;FMT=Delimited','SELECT $expcols FROM ['.$this->prefix.'RPL_$file_Name.csv]' ) $newsql");

                    $CSVTotalRec = count(file($this->filePath .'public\\downloads\\' . "\\".$this->prefix."RPL_$file_Name.csv", FILE_SKIP_EMPTY_LINES)) - 1;

                    if(!in_array($SR_Attachment,['onlylist','both'])){
                        unlink($this->filePath .'public\\downloads\\' .$this->prefix."RPL_$file_Name.csv");
                    }
                } else if ((trim($file_Opt) == 'Y') && (trim($file_Ext) == "xlsx")) {
                    $newsql1 = DB::select($newsql);
                    $aDataSql = collect($newsql1)->map(function($x){ return (array) $x; })->toArray();
                    $CSVTotalRec = count($aDataSql);

                    if(in_array($SR_Attachment,['onlylist','both'])) {
                        $headerCells = $this->headerCells;
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();

                        $i = 0;
                        foreach ($colArray as $value) {
                            $sheet->setCellValue($headerCells[$i] . '1', $value);
                            $i++;
                        }

                        $writer = new Xlsx($spreadsheet);
                        $writer->save(public_path() . '\\downloads\\' . $this->prefix . "RPL_" . $file_Name . ".xlsx");
                        DB::statement("insert into OPENROWSET('Microsoft.ACE.OLEDB.12.0', 'Excel 12.0;Database=" . $this->filePath . 'public\\downloads\\' . $this->prefix . "RPL_" . $file_Name . ".xlsx;','SELECT * FROM [Worksheet$]') $newsql");
                    }
                }

                /***************************** Changes 2017-04-11 End ***********************************/
            } //For
            //To Drop Temp table
            DB::statement("Drop table " . $tblName);
            //To Drop Temp table
        }

    }

    //User Define Function
    public static function add_date($givendate, $day = 0, $mth = 0, $yr = 0)
    {

        $cd = strtotime($givendate);
        $newdate = date('Y-m-d h:i:s', mktime(date('h', $cd),
            date('i', $cd), date('s', $cd), date('m', $cd) + $mth,
            date('d', $cd) + $day, date('Y', $cd) + $yr));

        return $newdate;
    }

    public static function dateDiff($startDate, $endDate)
    {
        // Parse dates for conversion
        $startArry = date_parse($startDate);
        $endArry = date_parse($endDate);

        // Convert dates to Julian Days
        $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
        $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

        // Return difference
        return round(($end_date - $start_date), 0);
    }
}
