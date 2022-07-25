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
use PDF;
use App\Mail\SendReportEmail;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Facades\View as View;


class CcSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ccSchedule:run {sid}';

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
        $sch_id = $this->argument('sid');

        $asql = DB::select("Select [ftp_tmpl_id],[camp_tmpl_id],[Schedule_Name],[sch_status_id],[Schedule_type],[rp_count],[ftp_tmpl_id]
               ,[smtp_flag],[semail_to],[semail_cc] ,[semail_bcc],[semail_sub],[semail_comments],
               [femail_to],[femail_cc] ,[femail_bcc],[femail_sub],[femail_comments],[rp_run_sch],[rp_months_weeks]
               ,[rp_days],[rp_end_date],[rp_run_time],[metadata_date],[SFTP_Attachment] from [UL_RepCmp_Schedules] Where row_id = '$sch_id' AND t_type = 'C'");
        $aData = collect($asql)->map(function($x){ return (array) $x; })->toArray();

        $CSVTotalRec = 0;

        if (!empty($aData)) {
            $FtpID = $aData[0]['ftp_tmpl_id'];
            $CampID = $aData[0]['camp_tmpl_id'];
            $SchName = $aData[0]['Schedule_Name'];
            $SchStatusID = $aData[0]['sch_status_id'];
            $SchType = $aData[0]['Schedule_type'];
            //  $runType = $aData[0]['rp_repeat'];
            $rp_count = $aData[0]['rp_count'];
            $ftp_id = $aData[0]['ftp_tmpl_id'];
            $rp_run_sch = $aData[0]['rp_run_sch'];
            $mon_week_Str = $aData[0]['rp_months_weeks'];
            $rp_week = $aData[0]['rp_days'];
            $rp_end_date = $aData[0]['rp_end_date'];
            $rp_run_time = $aData[0]['rp_run_time'];
            $metadata_date = $aData[0]['metadata_date'];
            //SMTP Details

            $mail_flag = $aData[0]['smtp_flag'];
            //success
            $smail_to = $aData[0]['semail_to'];
            $smail_cc = $aData[0]['semail_cc'];
            $smail_bcc = $aData[0]['semail_bcc'];
            $smail_sub = $aData[0]['semail_sub'];
            $smail_msg = $aData[0]['semail_comments'];
            //Fail
            $fmail_to = $aData[0]['femail_to'];
            $fmail_cc = $aData[0]['femail_cc'];
            $fmail_bcc = $aData[0]['femail_bcc'];
            $fmail_sub = $aData[0]['femail_sub'];
            $fmail_msg = $aData[0]['femail_comments'];
            $SFTP_Attachment = $aData[0]['SFTP_Attachment'];
            //SMTP Details
        }

        $SQLSSM = DB::select("Select * from [UL_RepCmp_Sch_status_mapping] Where sch_id = '$sch_id' AND t_type = 'C' ORDER BY row_id DESC");
        $aDataSSM = collect($SQLSSM)->map(function ($x) {
            return (array)$x;
        })->toArray();
        if(!empty($aDataSSM)){
            $SchStatusID = $aDataSSM[0]['sch_status_id'];
        }

        $CampSQL = DB::select("SELECT [t_id],[list_short_name],[list_level],[t_name],[sql],[seg_def],[seg_method],[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
		    ,[seg_sample],[seg_filters_criteria],[seg_filter_condition],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],[promoexpo_ext]
		    ,[promoexpo_ecg_opt],[promoexpo_data],[selected_fields],[selected_fields],[sql],[Report_Row],[Report_Column],[Report_Function],[Report_Sum],[Report_Show],[Report_Orientation],[Custom_SQL],[SR_Attachment],[List_Format],[Lookup_Type] 
		     FROM [UC_Campaign_Templates] Where row_id = '" . $CampID . "' AND t_type='C'");
        $aData = collect($CampSQL)->map(function($x){ return (array) $x; })->toArray();

        if (!empty($aData)) {
            $forEmail = $aData[0];
            foreach ($aData as $k => $row) {

                $CID = $aData[0]['t_id'];
                $list_short_name = $aData[0]['list_short_name'];
                $t_name = $aData[0]['t_name'];
                $sSQL = $aData[0]['sql'];
                $segDef = $aData[0]['seg_def'];
                $segMethod = $aData[0]['seg_method'];
                $segCriteria = $aData[0]['seg_criteria'];
                $segFiltersCriteria = $aData[0]['seg_filters_criteria'];
                $segFilterCondition = $aData[0]['seg_filter_condition'];
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
                $list_level = $aData[0]['list_level'];
                $Report_Row = $aData[0]['Report_Row'];
                $Report_Column = $aData[0]['Report_Column'];
                $Report_Function = $aData[0]['Report_Function'];
                $Report_Sum = $aData[0]['Report_Sum'];
                $Report_Show = $aData[0]['Report_Show'];
                $Report_Orientation = $aData[0]['Report_Orientation'];
                $Custom_SQL = $aData[0]['Custom_SQL'];
                $SR_Attachment = $aData[0]['SR_Attachment'];
                $List_Format = $aData[0]['List_Format'];
                $Lookup_Type = $aData[0]['Lookup_Type'];
            }
        }
        $metadata_date = date('Y-m-d');
        $metaData = RepCmpMetaData::where('CampaignID',$CID)->where('type','C')->first();
        if($metaData){
            $metadata_date = $metaData->Start_Date;
        }
        $file_Name1 = $list_short_name;
        $file_Name = $list_short_name . "_" . date("Ymd", time());

        if ($SchType == 'RP') {


            $date = date("m/d/y  H:i:s", time());
            // $date = substr($date,0,strpos($date,'.'));

            if ($rp_count > 1) {
                $seqSQL = DB::select('SELECT [camp_id] as cid FROM [UC_Campaign_Sequence]');
                $aData = collect($seqSQL)->map(function($x){ return (array) $x; })->toArray();

                if (!empty($aData)) {
                    $cid = $aData[0]['cid'];

                }
                $cid1 = $cid + 1;
                DB::update("UPDATE [UC_Campaign_Sequence] SET [camp_id] = " . $cid1);

                $CID = $cid;
                //  $rp_count = $rp_count + 1;
                // $updateSchTmplSQL = "UPDATE [UL_RepCmp_Schedules] SET [rp_count] = $rp_count Where row_id = '$sch_id'";

                //$oDb->executeSQL($updateSchTmplSQL);
            } else {

                $file_Name1 = $file_Name1 . "_" . $rp_count . "." . $file_Ext;
            }
            $rp_count = $rp_count + 1;
            DB::update("UPDATE [UL_RepCmp_Schedules] SET [rp_count] = $rp_count Where row_id = '$sch_id' AND t_type = 'C'");
        }

        if ((trim($file_Opt) == 'Y' && trim($sSQL) != '') || ((trim($CD_Opt) == 'Y') && trim($sSQL) != '')) {
            $date = date("m/d/y  H:i:s", time());    //Schedule stating date and time
            if (($SchType == 'RP') || ($SchType == 'RA')){
                //DB::update("UPDATE [UL_RepCmp_Status] SET [status] = 'Running', [start_time] = '" . $date . "' Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");
            }
            //else
            //DB::update("UPDATE [UL_RepCmp_Status] SET [status] = 'Running' Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");

            $sampleArray = explode("^", $sample);
            $sampleRecords = explode(":", $sampleArray[1]);
            if($Custom_SQL == 'Y'){
                if (strpos($sSQL, "*") !== false) {
                    $cSQL = str_replace("*", "TOP 1 * ", $sSQL);
                } else {
                    $cSQL = substr($sSQL, 0, 6) . " top 1 " . substr($sSQL, 7, strlen($sSQL));
                }

                if(stripos($cSQL, "blank") !== false){
                    $cSQL = str_replace("blank", "", $cSQL);
                }

                $getColumns = DB::select($cSQL);
                $getColumns = collect($getColumns)->map(function($x){ return (array) $x; })->toArray();
                $cColumns = array();
                array_push($cColumns,'SegmentID:true');
                array_push($cColumns,'GroupID:true');
                if(count($getColumns) > 0){
                    foreach ($getColumns[0] as $cname=> $cvalue){
                        $cColumns[]  = $cname.':true';
                    }
                    $expcolsarray = $cColumns;
                }



            }else{
                $expcolsarray = explode("|", $promoexpo_data);
            }

            $flag = 0;
            $expcols = '';
            foreach ($expcolsarray as $k => $v) {
                if(!empty($v)){
                    $kk = explode(":", $v);
                    $nNotAllowedFields = array();//array('CampaignID','SegmentID','GroupID');
                    if (trim($kk[1]) == 'true') {
                        $kk[0] = trim($kk[0]);
                        if (!in_array($kk[0], $nNotAllowedFields)) {
                            if ($flag != 1) {
                                $expcols = $expcols . "" . $kk[0];
                                $flag = 1;
                            } else{
                                $expcols = $expcols . "," . $kk[0];
                            }

                        }
                    }
                }

            }
            //  create_temp_table($sql1,$cname,$CID,$expcols);
            $flag = 0;
            $cols = "";
            $SQLCols = "";

            foreach ($expcolsarray as $k => $v) {
                if(!empty($v)){
                    $kk = explode(":", $v);

                    if (trim($kk[1]) == 'true') {

                        //if (!(($kk[0] == 'CampaignID') || ($kk[0] == 'GroupID') || ($kk[0] == 'SegmentID'))) {

                            if ($flag != 1) {
                                $SQLCols = $cols . "[" . $kk[0] . "]";
                                $cols = $cols . $kk[0];
                                $flag = 1;
                            } else {
                                $SQLCols = $SQLCols . ",[" . $kk[0] . "]";
                                $cols = $cols . "," . $kk[0];
                            }

                       // }
                    }
                }
            }

            $CustIDPos = -1;
            if ($cols != '') {
                $CustIDA = explode(",", $cols);

                $CustIDPos = array_search("DS_MKC_ContactID", $CustIDA);

                if (!(in_array("DS_MKC_ContactID", $CustIDA)) && (trim($CD_Opt) == 'Y')) {
                    $SQLCols = $SQLCols . ",[DS_MKC_ContactID] ";
                }
            } else if ((trim($CD_Opt) == 'Y')) {
                $SQLCols = "[DS_MKC_ContactID]";
            }


            $temp = explode("^", $criteria);  //change
            // Only when deflist is "Custom" or "ByField"
            if ($segDef != 'none') {
                $where = explode(":", $temp[0]);
                array_shift($where); //Remove 0th index

            }

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

            if(stripos($sSQLTemp, "blank") !== false){
                $sSQLTemp = str_replace("blank", "", $sSQLTemp);
            }
            //echo $sSQLTemp; die;
            $tblName = "tmp_". rand(0,100) . "_" . time() . "_" . date("Ymd", time());
            $tempSQL = "Select * into $tblName From ( " . $sSQLTemp . " ) as t";
            DB::statement("SET ANSI_NULLS OFF; SET ANSI_WARNINGS OFF;".$tempSQL);

            /***************************** Changes 2017-04-11 Start ***********************************/
            $sampleArray = explode("^", $sample);
            $samplePercentage = explode(":", $sampleArray[0]);
            $sSper = $samplePercentage[0];
            if ($segDef == 'none' && $segFiltersCriteria == '') {
                DB::statement("alter table $tblName add campaignid int null , groupid int null , segmentid int null,numgroups smallint null");

                DB::update("update $tblName set campaignid=$CID ,numgroups = $noCG");

                $sSql = "update $tblName set groupid=";
                echo '$seg_CG_Opt----'.$seg_CG_Opt.'---$sSper---'.$sSper.'---$noCG--'.$noCG;
                $sSql .= $seg_CG_Opt == 'Y' ? "case when (ABS(CAST((BINARY_CHECKSUM(*) *RAND()) as int)) % 100) <= $sSper and numgroups= $noCG then 0 else 1 end" : "1";
                DB::update($sSql);

                DB::update("update $tblName set segmentid=1");

                $sSqlCheck = DB::select("select top 1 * from $tblName");
                $aDataCheck = collect($sSqlCheck)->map(function($x){ return (array) $x; })->toArray();

                $emailCol = isset($aDataCheck[0]['Email']) && !empty($aDataCheck[0]['Email']) ? "Email" : "'Null' as Email";

                DB::insert("insert into UC_Campaign_Data (DS_MKC_Contactid, campaignid, groupid, segmentid, email) select DS_MKC_ContactID, campaignid, groupid, segmentid, $emailCol from $tblName");

            } else if ($segDef == 'byfield' && $segCriteria != '' && $segFiltersCriteria == '') {
                $sampleArray = explode("^", $sample);
                $samplePercentage = explode(":", $sampleArray[0]);
                $sSper = $samplePercentage[0];

                DB::statement("alter table $tblName add campaignid int null , groupid int null , segmentid int null,numgroups smallint null,ByField nvarchar(255) null");

                DB::update("update $tblName set campaignid=$CID,numgroups=$noCG");

                $segCriteriaArr = explode(':', $segCriteria);
                unset($segCriteriaArr[0]);
                foreach ($segCriteriaArr as $segCrit) {
                    if (!empty($segCrit)) {
                        $uUpdateDataTypeColumns = array('Emailable', 'Mailable', 'Textable');

                        if (in_array($segCrit, $uUpdateDataTypeColumns)) {
                            DB::statement("alter table  $tblName alter column $segCrit varchar(50)  null");
                        }
                        if (!empty($bByFeildCat))
                            $bByFeildCat = $bByFeildCat . "+cast(ltrim(rtrim(isnull($segCrit,''))) as varchar(50))";
                        else
                            $bByFeildCat = "cast(ltrim(rtrim(isnull($segCrit,''))) as varchar(50))";
                    }
                }
                //:TargetingSegment:Mailable
                DB::update("Update $tblName set ByField=  $bByFeildCat");

                DB::update("update t set t.segmentid=x.SegmentID from $tblName as t inner join (Select distinct ByField, DENSE_RANK() OVER (ORDER BY [ByField]) as SegmentID from $tblName) as x on t.ByField=x.ByField");

                $sSql = "update $tblName set groupid=";

                $sSql .= $seg_CG_Opt == 'Y' ? "case when (ABS(CAST((BINARY_CHECKSUM(*) *RAND()) as int)) % 100) <= $sSper and numgroups= $noCG then 0 else 1 end" : "1";
                echo $sSql;
                DB::update($sSql);

                $sSqlCheck = DB::select("select top 1 * from $tblName");
                $aDataCheck = collect($sSqlCheck)->map(function($x){ return (array) $x; })->toArray();

                $emailCol = isset($aDataCheck[0]['Email']) && !empty($aDataCheck[0]['Email']) ? "Email" : "'Null' as Email";
                DB::insert("insert into UC_Campaign_Data (DS_MKC_Contactid, campaignid, groupid, segmentid, email) select DS_MKC_ContactID, campaignid, groupid, segmentid, $emailCol from $tblName");
            } else if ($segDef == 'custom' && $segFiltersCriteria != '') {
                $cCon = explode('^', $criteria);
                $cConTwo = explode(":", $cCon[0]);
                array_shift($cConTwo);

                $cCondition = '';
                if (!empty($cConTwo)) {
                    foreach ($cConTwo as $key => $cCase) {
                        $i = $key + 1;
                        $cCondition .= " when $cConTwo[$key] then $i ";
                    }
                }
                // :( NPSQ1 = '10' )OR( NPSQ1 = '5' ):( NPSQ1 = '9' )^:10:9^:100:100^:40047:11254^:40047:11254
                DB::statement("alter table $tblName add campaignid int null , groupid int null , segmentid int null,numgroups smallint null");

                DB::update("update $tblName set campaignid=$CID,numgroups=$noCG, SegmentID= case $cCondition else null end");

                $sSql = "update $tblName set groupid=";

                $sSql .= $seg_CG_Opt == 'Y' ? "case when (ABS(CAST((BINARY_CHECKSUM(*) *RAND()) as int)) % 100) <= $sSper and numgroups= $noCG then 0 else 1 end" : "1";
                DB::update($sSql);
            }
            /***************************** Changes 2017-04-11 End ***********************************/
            //die;
            //Check Campaign ID Exists or not
            $CampIDSQL = DB::select("SELECT  top 1 * From $tblName");
            $aData = collect($CampIDSQL)->map(function($x){ return (array) $x; })->toArray();
            $campFlag = 0;
            if (!empty($aData)) {
                foreach ($aData[0] as $k => $v) {
                    if ($k == 'CampaignID') {
                        $campFlag = 1;
                    }
                }
            }
            /*if ($campFlag == 1) {
                $SQLCols = "[CampaignID]," . $SQLCols;
            }*/

            //Todo
            //Check Campaign ID Exists or not
            //$lastPart = explode($list_short_name,$t_name);
            //$filename = count($lastPart) > 0 ? $file_Name.$lastPart[1] : $t_name;
            // open file and Write the header
            if (trim($file_Opt) == 'Y') {
                if(in_array($SR_Attachment,['onlylist','both'])) {
                    $fhead = fopen($this->filePath . 'public\\' . $folder_Name . "\\" . $this->prefix . "CAL_" . $file_Name . "." . $file_Ext, 'a');
                    $expcols = $expcols . "\n";
                    fwrite($fhead, $expcols);
                    fclose($fhead);
                }
            }
            // open file and Write the header

            // Check CampaignID,SegmentID,GroupID
            $colArray = explode(",", $expcols);

            $cid_f = 0;
            $sid_f = 0;
            $gid_f = 0;
            for ($i = 0; $i < 3; $i++) {
                if ($colArray[$i] == 'CampaignID') {
                    $cid_f = 1;
                } else
                    if ($colArray[$i] == 'SegmentID') {
                        $sid_f = 1;
                    } else
                        if ($colArray[$i] == 'GroupID') {
                            $gid_f = 1;
                        }
            }

            //Check CampaignID,SegmentID,GroupID

            // Sample %

            $temp = explode("^", $criteria);
            $sPer = explode(":", $temp[2]);

            // Sample %

            if ($segDef == 'none')
                $where_count = 1;
            else
                $where_count = count($where);


            for ($i = 0; $i < $where_count; $i++) {

                if ($segDef != 'none') {

                    $seqID = $i + 1;

                    if ($i == 0) {
                        switch ($segMethod) {
                            case 'topNum':
                            case 'topPer':
                                $newsql = "Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[0];
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;
                                break;
                            case 'ranPer':
                            case 'ranNum':
                                $newsql = "Select * From ( Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[0];
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;

                                $newsql .= " ) as t order by NEWID()";
                                break;
                            case 'none':
                                $newsql = "Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[0];
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;
                                break;
                        }

                    } else {
                        $W = '';
                        for ($j = 0; $j < $i; $j++) {
                            if ($j != 0)
                                $W .= " or " . $where[$j];
                            else
                                $W = $where[$j];

                        }
                        if ($i != 1)
                            $W = "( " . $W . " )";
                        $p = $i + 1;
                        switch ($segMethod) {
                            case 'topNum':
                            case 'topPer':
                                $newsql = "Select top " . $sPer[$p] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[$i] . " and Not " . $W;
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;
                                break;
                            case 'ranPer':
                            case 'ranNum':
                                $newsql = "Select * From ( Select top " . $sPer[$p] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[$i] . " and Not " . $W;
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;
                                $newsql .= " ) as t order by NEWID()";
                                break;
                            case 'none':
                                $newsql = "Select top " . $sPer[$p] . " percent " . $SQLCols . " From " . $tblName . " Where " . $where[$i] . " and Not " . $W;
                                $newsql .= $seg_CG_Opt == 'Y' ? " and groupid = 1 " . $sort : " " . $sort;
                                break;
                        }
                        //$newsql ="Select ". $cols." From ( ".$sql1.") as t Where " .$where[$i] . " and Not ".$W ;
                    }
                    $segID = $i + 1;

                } else {
                    switch ($segMethod) {
                        case 'topNum':
                        case 'topPer':
                            $newsql = "Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName;
                            $newsql .= $seg_CG_Opt == 'Y' ? " WHERE groupid = 1 " . $sort : " " . $sort;
                            break;
                        case 'ranPer':
                        case 'ranNum':
                            $newsql = "Select * From ( Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName;
                            $newsql .= $seg_CG_Opt == 'Y' ? " WHERE groupid = 1 " . $sort : " " . $sort;
                            $newsql .= " ) as t order by NEWID()";
                            break;
                        case 'none':
                            $newsql = "Select top " . $sPer[1] . " percent " . $SQLCols . " From " . $tblName;
                            $newsql .= $seg_CG_Opt == 'Y' ? " WHERE groupid = 1 " . $sort : " " . $sort;
                            break;
                    }
                    // $newsql = "Select top ".$sPer[0]." percent ". $cols." From ".$tblName ;
                    // $segID = 1;
                }
                echo "-----------------------".$newsql."----------------";
                //$aData = $oDb->executeSelect($newsql);

                /***************************** Changes 2017-04-11 Start ***********************************/
                if ((trim($file_Opt) == 'Y') && (trim($file_Ext) == "csv")) {
                    //$sampleRecords = explode(":",$sampleArray[1]);
                    $newsql1 = DB::select($newsql);
                    $aDataSql = collect($newsql1)->map(function($x){ return (array) $x; })->toArray();
                    DB::statement("insert into openrowset  ('Microsoft.ACE.OLEDB.12.0','Text;Database=" . $this->filePath .'public\\'. $folder_Name . "\\;HDR=YES;FMT=Delimited','SELECT $SQLCols FROM [".$this->prefix."CAL_$file_Name.csv]' ) $newsql");
                    //var_dump($aData);
                    if (strpos($newsql, 'order by NEWID()') !== false) {
                        $newsql = str_replace('order by NEWID()', '', $newsql);
                    }

                    //$CSVTotalRec = count(file($this->filePath .'public\\'. $folder_Name . "\\".$this->prefix."CAL_$filename.csv", FILE_SKIP_EMPTY_LINES)) - 1;
                    $CSVTotalRec = count($aDataSql);

                    if(!in_array($SR_Attachment,['onlylist','both'])){
                        unlink($this->filePath .'public\\'. $folder_Name . "\\".$this->prefix."CAL_$file_Name.csv");
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
                        $writer->save(public_path() . '\\' . $folder_Name . "\\" . $this->prefix . "CAL_" . $file_Name . ".xlsx");

                        DB::statement("insert into OPENROWSET('Microsoft.ACE.OLEDB.12.0', 'Excel 12.0;Database=" . $this->filePath . 'public\\' . $folder_Name . "\\" . $this->prefix . "CAL_" . $file_Name . ".xlsx;','SELECT * FROM [Worksheet$]') $newsql");
                    }

                }
                /***************************** Changes 2017-04-11 End ***********************************/
                $c1 = 0;
                $totCount = 0;
                $totRec = 0;
                if ($seg_CG_Opt == 'Y') {
                    $universe = 0;
                    for ($c = $i * $noCG + $i; $c <= ($i * $noCG + $i + $noCG); $c++) {
                        if(isset($sampleRecords[$c])){
                            $count[$c1] = $sampleRecords[$c];
                            $universe += $sampleRecords[$c];
                            $totRec = $totRec + $count[$c1];
                            $c1 = $c1 + 1;
                        }
                    }

                } else  // if($seg_CG_Opt == 'N')
                {
                    unset($count);
                    $count = [];
                    $universe = 0;
                    for ($c = ($i * $noCG); $c < ($i * $noCG + $noCG); $c++) {
                        if (isset($sampleRecords[$c])) {
                            $count[$c1] = $sampleRecords[$c];
                            $universe += $sampleRecords[$c];
                            $totRec = $totRec + $count[$c1];
                            $c1 = $c1 + 1;
                        }
                    }
                    $countStr = '0,' . implode(",", $count);
                    $count = explode(",", $countStr);

                }


                if ($totRec == 0) {
                    continue;
                }
                // Include Control Group or Not

                if ($seg_CG_Opt == 'Y') {
                    $start = 0;
                    $campCG = 0;
                } else {
                    $start = 1;
                    $campCG = 1;
                }
                // Include Control Group or Not
                if (!empty($aData)) {
                    // initialize Count Array with Zero

                    for ($index = 0; $index <= $noCG; $index++)
                        $addCount[$index] = 0;

                    //  if(trim($eff)=="csv")
                    // {

                    // $fhead=fopen($filpath,'a');
                    // if($file_Opt == 'Y')



                    //   }   // IF CSV
                } //Else
            } //For

            $dbfilename = $file_Name . '.' . $file_Ext;
            /*    $isql="insert into  Campaign_Export ([t_type],[t_name],[t_filetype],[t_filepath],[t_filename])
                        values ('$ttype','$cname','$eff','$efold','$dbfilename')";

                 $oDb->executeSQL($isql);  */
            // Don't Delete

            if (trim($CD_Opt) == 'Y') {
                // Insert into UC_Campaign_Metadata table

                /*$metaArray = explode('^', $metaData);*/
                if ($seg_CG_Opt == 'Y')
                    $gs = 0;
                else
                    $gs = 1;
                $CGDStr = explode('^', $CGDetail); // 2nd Array
                $camp_des = explode(':', $CGDStr[0]);
                $summaryID = explode(':', $CGDStr[1]);
                $cost = explode(':', $CGDStr[2]);
                $segTemp = explode("^", $criteria);
                $seg_des = explode(':', $segTemp[0]);

                if (($SchType == 'RI') or ($SchType == 'RA') or (($SchType == 'RP') && ($rp_count == 2))) {
                    //$metadata_date = $metaArray[7] . '/' . $metaArray[8] . '/' . $metaArray[6];
                    $metadata_date = $metadata_date;
                } else if (($SchType == 'RP') && ($rp_count > 2)) {

                    $nextSQL = DB::select("SELECT [last_runtime] from [UL_RepCmp_Status] Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");
                    $aData = collect($nextSQL)->map(function($x){ return (array) $x; })->toArray();

                    if (!empty($aData)) {
                        $last_runtime = $aData[0]['last_runtime'];

                    }
                    $day_diff = self::dateDiff(date("Y/m/d", strtotime($last_runtime)), date('Y/m/d', time()));
                    $metadata_date = date("Y/m/d", strtotime(self::add_date($metadata_date, $day_diff)));
                }

                $metadata_date = date("Y-m-d", strtotime($metadata_date));
                DB::update("UPDATE [UL_RepCmp_Schedules] SET [metadata_date] = '$metadata_date' Where row_id = '$sch_id' AND t_type = 'C'");

                // $startDate = date("m/d/y  H:i:s", time());
                $segTemp = explode("^", $criteria);
                $seg_des = explode(':', $segTemp[1]);

                //No of Selected Segments
                // $where_count  == > Noseg Selected
                $noRows = $where_count;
                //No of Selected Segments

                // Take Samples For MetaData
                $noRec = $gs + $noCG;

                $sampleRecords = explode(":", $sampleArray[1]);  //SampleArray take from $sample
                //echo '<pre>--'.$noRows; print_r($sampleRecords); die;
                $Arry_start = 0;
                for ($j = 1; $j <= $noRows; $j++)
                    for ($i = $gs; $i <= $noCG; $i++) {
                        if(isset($sampleRecords[$Arry_start])){
                            $sample_Seg[$i][$j] = $sampleRecords[$Arry_start];
                            $Arry_start = $Arry_start + 1;
                        }

                    }
                // Take Samples For MetaData

                //Get File Name
                // $fileName = $folder_Name."/".$file_Name.".".$file_Ext;
                $fileName = $file_Name;
                //Get File Name
                echo $gs . ' --noCg=' . $noCG . '---noRows' . $noRows;
                for ($i = $gs; $i <= $noCG; $i++) {
                    for ($j = 1; $j <= $noRows; $j++) {
                        if(isset($sample_Seg[$i][$j])){
                            $s = $sample_Seg[$i][$j];
                            if (empty($cost[$i])) {
                                $cCost = 0;
                            } else {
                                $cCost = $cost[$i];
                            }
                            if (strpos($seg_des[$j], "'") !== FALSE){
                                $seg_des[$j] = str_replace("'","''",$seg_des[$j]);
                            }

                            DB::insert("INSERT INTO [UC_Campaign_Metadata] ([CampaignID] ,[Objective],[Brand],[Channel],[Category] 
							   ,[ListDes] ,[Wave] ,[Start_Date]  ,[Interval],[ProductCat1],[ProductCat2],[SKU],[Coupon],[SegmentID]
							   ,[SegmentDes],[GroupID],[GroupDes] ,[SummaryID],[Cost],[Quantity],[File_Name])
								 VALUES ('$CID','$metaData->Objective','$metaData->Brand','$metaData->Channel','$metaData->Category','$metaData->ListDes'
							   ,'$metaData->Wave','$metadata_date','$metaData->Interval','$metaData->ProductCat1','$metaData->ProductCat2','$metaData->SKU','$metaData->Coupon','$j','$seg_des[$j]','$i','$camp_des[$i]','$summaryID[$i]','$cCost','$s','$fileName')");
                        }


                    }
                }

                DB::update("update m set m.quantity=d.quantity from uc_campaign_metadata m inner join (select count(*) as quantity,  campaignid ,groupid,segmentid from uc_campaign_data   group by  campaignid ,groupid,segmentid) d on 
  m.campaignid=d.campaignid and m.segmentid=d.segmentid and m.groupid=d.groupid
  where d.campaignid=$CID");

                DB::statement("EXEC sp_CRM_Campaign_to_Phone_P1 ".$CID);

            }

            //To Drop Temp table
            DB::statement("Drop table " . $tblName);
            //To Drop Temp table


            // FTP
            if ($ftp_id != 0) {

                $ftpSQL = DB::select("SELECT [ftp_host_address],[ftp_port_no],[ftp_user_name],[ftp_password]
                  ,[folder_loc],[site_type] FROM [UL_RepCmp_SFTP] Where [row_id] = '$ftp_id'");
                $aData = collect($ftpSQL)->map(function($x){ return (array) $x; })->toArray();
                if (!empty($aData)) {
                    $server = $aData[0]['ftp_host_address'];
                    $port = $aData[0]['ftp_port_no'];
                    $userName = $aData[0]['ftp_user_name'];
                    $password = $aData[0]['ftp_password'];
                    $folder = $aData[0]['folder_loc'];
                    $site_type = $aData[0]['site_type'];
                }

                if ($site_type == 'FTP') {
                    $conn_id = ftp_connect($server, $port);
                    $login_result = ftp_login($conn_id, $userName, $password);

                    if (($conn_id) && ($login_result)) {
                        $local_file = $this->filePath .'public\\' .$folder_Name . "\\CAL_$file_Name." . "$file_Ext";
                        $remote_file = "CAL_$file_Name." . "$file_Ext";
                        ftp_pasv($conn_id, true);
                        ftp_chdir($conn_id, $folder);

                        ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
                        ftp_close($conn_id);
                        $ftp_flag = 'Y';
                        DB::update("Update UL_RepCmp_Completed set [ftp_flag] = 'Y' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
                        $smtp_flag = 'Y';


                    } else {

                        $ftp_flag = 'N';
                        DB::update("Update UL_RepCmp_Completed set [ftp_flag] = 'N' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
                        $smtp_flag = 'N';
                    }
                } else if ($site_type == 'SFTP') {
                    /******************** Create command text file - Start ******************/
                    $fh = fopen($this->filePath . 'psftpscript_sample2.txt', 'w');
                    fclose($fh);

                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    $line = "lcd " . $this->filePath . "public\\". $folder_Name . "\n";
                    fwrite($fhead, $line);
                    fclose($fhead);

                    $line1 = $line2 = $line3 = $line4 = '';
                    if(in_array($SFTP_Attachment,['onlylist','both'])){
                        $line1 = "put ".$this->prefix."CAL_" . $file_Name . "." . $file_Ext . "\n";
                        $line3 = "mv ".$this->prefix."CAL_" . $file_Name . "." . $file_Ext . " $folder\n ";
                    }

                    if(in_array($SFTP_Attachment,['onlyreport','both']) && !empty($Report_Row)){
                        $line2 = "put ".$this->prefix."CAM_" . $file_Name . ".pdf" . "\n";
                        $line4 = "mv ".$this->prefix."CAM_" . $file_Name . ".pdf" . " $folder\n ";
                    }

                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    fwrite($fhead, $line1);
                    fclose($fhead);

                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    fwrite($fhead, $line2);
                    fclose($fhead);

                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    fwrite($fhead, $line3);
                    fclose($fhead);

                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    fwrite($fhead, $line4);
                    fclose($fhead);


                    $fhead = fopen($this->filePath . "psftpscript_sample2.txt", 'a');
                    $line = "quit\n ";
                    fwrite($fhead, $line);
                    fclose($fhead);
                    /******************** Create command text file - Start ******************/


                    /******************** Create Bat file - Start ******************/
                    $fh = fopen($this->filePath . 'psftpexecution_gc_sam.bat', 'w');
                    fclose($fh);

                    $fhead = fopen($this->filePath . "psftpexecution_gc_sam.bat", 'a');
                    $command = "cd/ \n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    echo "-------------------------" . $password . "-------------------------";
                    $fhead = fopen($this->filePath . "psftpexecution_gc_sam.bat", 'a');
                    $command = "psftp -b " . $this->filePath . "psftpscript_sample2.txt $userName@$server -pw $password\n";
                    fwrite($fhead, $command);
                    fclose($fhead);

                    $fhead = fopen($this->filePath . "psftpexecution_gc_sam.bat", 'a');
                    $command = "Schtasks /delete /TN " . $this->schtasks_dir . "\\" . $SchName . "_bat /f";
                    fwrite($fhead, $command);
                    fclose($fhead);
                    /******************** Create Bat file - End ******************/

                    //system("cmd /c D:\RD_v14\psftpexecution_gc_sam.bat");

                    $date = date("m/d/Y", time());
                    $time = date("H:i:s", time() + 60);
                    $date1 = date("m/d/y  H:i:s", time());

                    $command = 'schtasks /create /tn ' . $this->schtasks_dir . '\\' . $SchName . '_bat /tr ' . $this->filePath . 'psftpexecution_gc_sam.bat  /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';
                    Helper::schtask_curl($command);

                    $ftp_flag = 'Y';
                    DB::update("Update UL_RepCmp_Completed set [ftp_flag] = 'Y' Where row_id = '" . $sch_id . "' AND t_type = 'C'");
                    $smtp_flag = 'Y';
                }


            } else {
                $ftp_flag = 'N';
                $smtp_flag = 'Y';

            }

            // Delete the Schedule
            $date = date("m/d/y  H:i:s", time());  //Schedule ending date and time
            if ($SchType == 'RA') {

                $command = 'schtasks /delete /tn ' . $this->schtasks_dir . '\\' . $SchName . ' /f';
                $result = shell_exec($command);
                DB::update("UPDATE [UL_RepCmp_Status] SET [status] = 'Completed',[completed_time] = '" . $date . "' ,[succ_flag] = 'Y',[file_name] = '" . $file_Name . "',total_records= '$CSVTotalRec' Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");
            } else if ($SchType == 'RP') {
                $nextSQL = DB::select("SELECT * from [UL_RepCmp_Status] Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");
                $aData = collect($nextSQL)->map(function($x){ return (array) $x; })->toArray();

                if (!empty($aData)) {
                    $old_runtime = $aData[0]['next_runtime'];
                    $sche_name = $aData[0]['sche_name'];
                    $templ_name = $aData[0]['templ_name'];
                    $start_time = $aData[0]['start_time'];
                    $completed_time = $aData[0]['completed_time'];
                    $file_name = $aData[0]['file_name'];
                    $succ_flag = $aData[0]['succ_flag'];
                    $status = $aData[0]['status'];
                    $file_path = $aData[0]['file_path'];
                    $ftp_flag = !empty($aData[0]['ftp_flag']) ? $aData[0]['ftp_flag'] : 'N';
                    $t_type = $aData[0]['t_type'];
                    $last_runtime = date('Y/m/d', strtotime($old_runtime));
                }

                switch ($rp_run_sch) {
                    case 'daily':
                        $old = explode(" ", $old_runtime);
                        $old_date = $old[0];
                        $old_time = $old[1];
                        $temp_date = self::add_date($old_runtime, 1);
                        $t = explode(" ", $temp_date);
                        $next_runtime = $t[0] . ' ' . $old_time;
                        break;
                    case 'weekly':
                        $dayArray = explode(",", $mon_week_Str);
                        $old = explode(" ", $old_runtime);
                        $old_date = $old[0];
                        $old_time = $old[1];
                        if (strtoupper(date("D", strtotime($old_runtime))) != end($dayArray)) {
                            $pos = array_search(strtoupper(date("D", strtotime($old_runtime))), $dayArray);

                            $pos = $pos + 1;
                            $temp_date = date("m-d-Y", strtotime("next " . $dayArray[$pos], strtotime($old_runtime)));

                        } else {
                            if ($rp_week == 1) {
                                $temp_date = date("m-d-Y", strtotime("next " . $dayArray[0], strtotime($old_runtime)));
                            } else {
                                $temp_date = self::add_date($old_runtime, ($rp_week - 1) * 7);
                                $temp_date = date("m-d-Y", strtotime("next " . $dayArray[0], strtotime($temp_date)));
                            }
                        }
                        $t = explode(" ", $temp_date);
                        $next_runtime = $t[0] . ' ' . $old_time;

                        break;

                    case 'monthly':
                        $old = explode(" ", $old_runtime);
                        $old_date = $old[0];
                        $old_time = $old[1];

                        $monthArray = explode(",", $mon_week_Str);
                        $month_flag = 1;
                        $temp_runtime = $old_runtime;
                        while ($month_flag) {
                            $temp_runtime = self::add_date($temp_runtime, 0, 1);
                            $temp_month = strtoupper(date("M", strtotime($temp_runtime)));
                            if (in_array($temp_month, $monthArray))
                                $month_flag = 0;
                        }
                        $t = explode(" ", $temp_runtime);
                        $next_runtime = $t[0] . ' ' . $old_time;
                        break;

                }

                if (date("Y-m-d", strtotime($next_runtime)) < date("Y-m-d", strtotime($rp_end_date))){
                    $prevStatus = 'Child';
                }elseif (date("Y-m-d", strtotime($next_runtime)) == date("Y-m-d", strtotime($rp_end_date))){
                    $prevStatus = 'Completed';

                    $command = 'schtasks /delete /tn ' . $this->schtasks_dir . '\\' . $SchName . ' /f';
                    Helper::schtask_curl($command);
                }
                DB::update("UPDATE [UL_RepCmp_Status] SET[status] = '".$prevStatus."',[completed_time] = '" . $date . "',[succ_flag] = 'Y',[file_name] = '" . $file_Name . "',total_records= '$CSVTotalRec' Where row_id = '" . $SchStatusID . "'");

                if (date("Y-m-d", strtotime($next_runtime)) < date("Y-m-d", strtotime($rp_end_date))){
                    DB::insert("INSERT INTO [UL_RepCmp_Status]([sche_name],[templ_name],[start_time]
                                   ,[completed_time],[file_name],[succ_flag],[status],[last_runtime],[next_runtime],[file_path],[ftp_flag],[t_type])
                                   VALUES ('$sche_name','$templ_name','$start_time','','-','','Scheduled','" . $last_runtime . "','" . $next_runtime . "','".$file_path."','$ftp_flag','C')");

                    $SQL = DB::select("Select [row_id] from [UL_RepCmp_Status] Where sche_name = '$sche_name' AND t_type = 'C' order by row_id desc");

                    $aData = collect($SQL)->map(function($x){ return (array) $x; })->toArray();
                    if (!empty($aData)) {
                        $Sch_row_id = $aData[0]['row_id'];
                    }

                    DB::insert("INSERT INTO [UL_RepCmp_Sch_status_mapping] ([sch_id],[sch_status_id],[t_type]) VALUES ('".$sch_id."','".$Sch_row_id."', 'C')");
                }

                $rv = $Report_Row;
                $cv = $Report_Column;
                $fu = ucfirst($Report_Function);
                $sv = $Report_Sum;
                $sa = $Report_Show;

                $imgTag = '';
                $imgPath = '';

                $sqlMetaData = DB::select("SELECT Category FROM UL_RepCmp_MetaData WHERE CampaignID = '$CID'");
                $aDataMetaData = collect($sqlMetaData)->map(function($x){ return (array) $x; })->toArray();
                $rpDesc = '';
                if (!empty($aDataMetaData)) {
                    $rpDesc = $aDataMetaData[0]['Category'];
                }

                Helper::generateSrPDF($rv,$cv,$fu,$sv,$sa,$sSQL,$list_level,$list_short_name,$imgTag,$imgPath,$file_path,$file_Name,$this->prefix  . 'CAM_',$SR_Attachment,$rpDesc,$Report_Orientation);

                /*if (date("Y-m-d", strtotime($next_runtime)) < date("Y-m-d", strtotime($rp_end_date)))
                    $updateStatusSQL = "UPDATE [UL_RepCmp_Status] SET[status] = 'Scheduled',[last_runtime] = '" . $last_runtime . "',[completed_time] = '" . $date . "',[next_runtime] = '" . $next_runtime . "',[succ_flag] = 'Y',[file_name] = '" . $file_Name . "' Where row_id = '" . $SchStatusID . "'";
                else
                    $updateStatusSQL = "UPDATE [UL_RepCmp_Status] SET [status] = 'Completed',[last_runtime] = '" . $last_runtime . "',[completed_time] = '" . $date . "',[next_runtime] = '" . $next_runtime . "',[succ_flag] = 'Y',[file_name] = '" . $file_Name . "' Where row_id = '" . $SchStatusID . "' AND t_type = 'C'";


                DB::update($updateStatusSQL);*/


            } else if ($SchType == 'RI') {
                $command = 'schtasks /delete /tn ' . $this->schtasks_dir . '\\' . $SchName . ' /f';
                $result = shell_exec($command);
                DB::update("UPDATE [UL_RepCmp_Status] SET [status] = 'Completed',[completed_time] = '" . $date . "' ,[succ_flag] = 'Y',[file_name] = '" . $file_Name . "',total_records= '$CSVTotalRec' Where row_id = '" . $SchStatusID . "' AND t_type = 'C'");
            }

            // Delete the Schedule

            // $updateStatusSQL = "UPDATE [UL_RepCmp_Status] SET [status] = 'Completed',[completed_time] = '".$date."' ,[succ_flag] = 'Y' Where row_id = '".$SchStatusID."'";

            //SMTP
            // Sent Mail
            $mail_flagArray = explode(":", $mail_flag);

            if ($smtp_flag == 'Y') {
                if ($mail_flagArray[0] == 'Y') {
                    //send mail
                    //mail($mail_to,$mail_sub,$mail_msg
                    if (empty($mail_sub))
                        $mail_sub = "[None]";
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'To:' . $smail_to . "\r\n";
                    $headers .= 'From: admin@crmsquare.info' . "\r\n";
                    $headers .= 'Cc:' . $smail_cc . "\r\n";
                    $headers .= 'Bcc:' . $smail_bcc . "\r\n";
                    mail($smail_to, $smail_sub, $smail_msg, $headers);


                }
            }
            else {
                if ($mail_flagArray[1] == 'Y') {
                    //send mail
                    if (empty($mail_sub))
                        $mail_sub = "[None]";
                    $headers = 'MIME-Version: 1.0' . "\r\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers .= 'To:' . $fmail_to . "\r\n";
                    $headers .= 'From: admin@crmsquare.info' . "\r\n";
                    $headers .= 'Cc:' . $fmail_cc . "\r\n";
                    $headers .= 'Bcc:' . $fmail_bcc . "\r\n";
                    mail($fmail_to, $fmail_sub, $fmail_msg);
                }
            }


            //SMTP
            if ($file_Name != "") {
                $outfile = $file_Name . '.' . $file_Ext;
            } else
                $outfile = '-';

            $sucess = 'Y';
            if ($ftp_id != 0) {
                if ($ftp_flag == 'N') {
                    $sucess = 'N';
                }
            }


            DB::insert("Insert into [UL_RepCmp_Completed]([sche_name],[templ_name],[start_time],[next_runtime],[completed_time]
                     ,[file_name],[succ_flag],[ftp_flag],[status],[file_path],[camp_id],[total_records],[t_type])
                    SELECT [sche_name],[templ_name],[start_time],[next_runtime],'$date','$outfile','$sucess','$ftp_flag'
		     ,'Completed',[file_path],'$CID','$CSVTotalRec',t_type FROM [UL_RepCmp_Status] Where row_id = '" . $SchStatusID . "' AND t_type = 'C' ORDER BY row_id DESC");


        }

        if($List_Format != 'default'){
            $tableHtml = View::make('layouts.table',['records' => $aDataSql])->render();

            /*$upMetaStr = explode('^',$metaData);
            $rpDesc = $upMetaStr[3];*/
            $header = ucfirst($metaData->Category);
            $footer = ucfirst($list_short_name); //ucfirst('test');
            try{

                PDF::loadView('layouts.pdf-v2', [
                    'header' => $header,
                    'footer' => $footer,
                    'tablehtml' => $tableHtml,
                    'charthtml' => '',
                    'filename' => $file_Name.'.pdf',
                    'selections' => ''
                ])->setPaper('letter',$List_Format)->setWarnings(false)->save(public_path($folder_Name.'\\'.$this->prefix.'CAL_'.$file_Name.'.pdf'));

            } catch (\Exception $exception){
                dd($exception->getMessage());
            }
        }

        $sSqlSRE = DB::select("SELECT * FROM UL_RepCmp_Email WHERE camp_tmpl_id = '" . $CID . "' AND t_type='C'");
        $result = collect($sSqlSRE)->map(function($x){ return (array) $x; })->toArray();
        if (!empty($result)) {
            $result = $result[0];


            if( strpos($result['remail_to'], ',') !== false ) {
                $ToUsers = explode(',',$result['remail_to']);
            }else{
                $ToUsers = [$result['remail_to']];
            }
            $senderUser = Helper::getUserRecord($result['User_id'],'User_id');

            foreach ($ToUsers as $ToUser){
                $Cc = $result['remail_cc'];
                $Bcc = $result['remail_bcc'];
                $Sub = $result['remail_sub'];
                $limitedtextarea1 = $result['remail_comments'];
                $Email_Attachment = $result['Email_Attachment'];
                $type = 'C';
                $user = Helper::getUserRecord($ToUser, 'User_Email');
                if($user) {
                    $objDemo = new \stdClass();
                    $objDemo->data = (object)$forEmail;
                    $objDemo->To = $ToUser;
                    $objDemo->Cc = $Cc;
                    $objDemo->Bcc = $Bcc;
                    $objDemo->Sub = $Sub;
                    $objDemo->limitedtextarea1 = $limitedtextarea1;
                    $objDemo->Email_Attachment = $Email_Attachment;
                    $objDemo->filePath = $this->filePath;
                    $objDemo->sender = 'CRM Square Administrator';
                    $objDemo->receiver = $user['User_FName'] . ' ' . $user['User_LName'];
                    $objDemo->clientname = $this->clientname;
                    $objDemo->listShortName = $list_short_name;
                    $objDemo->sharedByName = $senderUser['User_FName'] . ' ' . $senderUser['User_LName'];
                    $objDemo->sharedByEmail = $senderUser['User_Email'];
                    $objDemo->senderEmail = 'esupport@datasquare.com';

                    Mail::to($ToUser)->send(new SendCampaignEmail($objDemo));

                    if (count(Mail::failures()) > 0) {

                        $emsg = "There was one or more failures. They were: <br />";

                        foreach (Mail::failures() as $email_address) {
                            $emsg .= " - $email_address <br />";
                        }

                    } else {
                        DB::update("UPDATE UL_RepCmp_Email SET Email_Status = 'Sent' WHERE camp_tmpl_id = '" . $CID . "' AND t_type='C'");
                    }
                }
            }

        }


        $sSqlSRE = DB::select("SELECT Shared_With_User_id FROM UL_RepCmp_Share WHERE Is_Delivered <> 1 AND camp_tmpl_id='".$CID."' AND t_type='C'");
        $records = collect($sSqlSRE)->map(function($x){ return (array) $x; })->toArray();
        if(isset($records) && count($records) > 0){
            foreach ($records as $record){
                $user = User::where('User_ID',$record['Shared_With_User_id'])->first();
                if($user){
                    Helper::sendShareEmail($record,$user,$this->clientname,$record['Custom_Message'],$list_short_name,'C');
                }
            }
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
