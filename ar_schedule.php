<?php

ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);
ini_set('log_errors', TRUE);
ini_set('html_errors', TRUE);
ini_set('error_log', '/error_log.txt');
ini_set('display_errors', TRUE);

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);


set_time_limit(30000);

require_once "includes/phpEmail/vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);


require_once "includes/constants.php";
require_once "modules/class.DB.php";
require_once 'includes/xlsxwriter.class.php';
ob_clean();
ini_set('odbc.defaultlrl', 65536);

$oDb = new DBUtils();


$sch_id = $_SERVER["argv"][1];
//$sch_id = 328;
function stripInvalidXml($value)
{
    if(is_string($value) == true){
        $ret = "";
        $current;
        if (empty($value))
        {
            return $ret;
        }

        $length = strlen($value);
        for ($i=0; $i < $length; $i++)
        {
            $current = ord($value{$i});
            if (($current == 0x9) ||
                ($current == 0xA) ||
                ($current == 0xD) ||

                (($current >= 0x28) && ($current <= 0xD7FF)) ||
                (($current >= 0xE000) && ($current <= 0xFFFD)) ||
                (($current >= 0x10000) && ($current <= 0x10FFFF)))
            {
                $ret .= chr($current);
            }
            else
            {
                $ret .= " ";
            }
        }
    }else{
        $ret = $value;
    }

    return $ret;
}

$SQL = "Select [ftp_tmpl_id],[camp_tmpl_id],[Schedule_Name],[sch_status_id],[Schedule_type],[rp_count],[ftp_tmpl_id]
               ,[smtp_flag],[semail_to],[semail_cc] ,[semail_bcc],[semail_sub],[semail_comments],
               [femail_to],[femail_cc] ,[femail_bcc],[femail_sub],[femail_comments],[rp_run_sch],[rp_months_weeks]
               ,[rp_days],[rp_end_date],[rp_run_time],[metadata_date] from [schedule_templates] Where row_id = '$sch_id' AND t_type = 'A'";


$CSVTotalRec = 0;
$aData = $oDb->executeSelect($SQL);

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

    //SMTP Details
}
//  $CGD = $aData[0]['asg_camp_dtls'];

/*   $CampSQL ="Select export_to_file,sql,promo_save,asg_camp_grp_dtls ,export_data, asg_lseg_dtls,
                    export_folder,export_filename,export_filetype,export_controlgroup,asg_lsegno,
                     asg_camp_grp_no,asg_camp_ctrl_grp,asg_def_list,asg_lseg_method,asg_camp_grp_sel,
                     meta_data,t_name,t_type,camp_id,byfield_count, asg_camp_dtls
    From Campaign_Templates Where template_id = '".$CampID."'"; */

$CampSQL = "SELECT [t_id],[t_name],[sql],[seg_def],[seg_method],[seg_criteria],[seg_selected_criteria],[seg_grp_no],[seg_ctrl_grp_opt],[seg_camp_grp_dtls]
		    ,[seg_sample],[promoexpo_cd_opt],[promoexpo_file_opt],[promoexpo_folder],[promoexpo_file],[promoexpo_ext]
		    ,[promoexpo_ecg_opt],[promoexpo_data],[meta_data],[selected_fields],[Report_Row] FROM [Ar_List_Templates] Where row_id = '" . $CampID . "' AND t_type='A'";


$aData = $oDb->executeSelect($CampSQL);

if (!empty($aData)) {
    echo '<pre>'; print_r($aData); //die;
    foreach ($aData as $k => $row) {

        $CID = $aData[0]['t_id'];
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
        $metaData = $aData[0]['meta_data'];
        $CGDetail = $aData[0]['seg_camp_grp_dtls'];
        $promoExpo_ecg_opt = $aData[0]['promoexpo_ecg_opt'];
        $Report_Row = $aData[0]['Report_Row'];
		
        $expcols = !empty($aData[0]['selected_fields']) ? $aData[0]['selected_fields'] : 'CustomerID';
    }
}
/*   $eopt = $CampData[0];     $sql1 = $CampData[1];            $promo = $CampData[2];             $sample =  $CampData[3];
   $edata =  $CampData[4];     $LSD1 =  $CampData[5];            $efold =  $CampData[6];             $efname =  $CampData[7];
   $eff =  $CampData[8];      $efcg = $CampData[9];         $lseg =  $CampData[10];            $nocg = $CampData[11];
   $cg =  $CampData[12];      $deflist = $CampData[13];       $lssm = $CampData[14];            $lssc = $CampData[15];
   $metaStr = $CampData[16];    $cname = $CampData[17];        $ttype = $CampData[18];            $CID = $CampData[19];
   $bycount = $CampData[20];        $CGD = $CampData[21]; */


if ($SchType == 'RP') {

    $file_Name1 = $file_Name;
    $file_Name = $file_Name . "_" . $rp_count . "_" . date("Ymd", time());
    $date = date("m/d/y  H:i:s", time());
    // $date = substr($date,0,strpos($date,'.'));

    if ($rp_count > 1) {
        $seqSQL = 'SELECT [camp_id] as cid FROM [ar_sequence]';
        $aData = $oDb->executeSelect($seqSQL);

        if (!empty($aData)) {
            $cid = $aData[0]['cid'];

        }
        $cid1 = $cid + 1;
        $upSQL = "UPDATE [ar_sequence] SET [camp_id] = " . $cid1;
        $oDb->executeSQL($upSQL);

        $CID = $cid;
        //  $rp_count = $rp_count + 1;
        // $updateSchTmplSQL = "UPDATE [schedule_templates] SET [rp_count] = $rp_count Where row_id = '$sch_id'";

        //$oDb->executeSQL($updateSchTmplSQL);
    } else {

        $file_Name1 = $file_Name1 . "_" . $rp_count . "." . $file_Ext;
    }
    $rp_count = $rp_count + 1;
    $updateSchTmplSQL = "UPDATE [schedule_templates] SET [rp_count] = $rp_count Where row_id = '$sch_id' AND t_type = 'A'";
    $oDb->executeSQL($updateSchTmplSQL);

}

if ((trim($file_Opt) == 'Y' && trim($sSQL) != '') || ((trim($CD_Opt) == 'Y') && trim($sSQL) != '')) {
    $date = date("m/d/y  H:i:s", time());    //Schedule stating date and time
    if (($SchType == 'RP') || ($SchType == 'RA'))
        $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Running', [start_time] = '" . $date . "' Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";
    else
        $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Running' Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";

    $oDb->executeSQL($updateStatusSQL);

    $sampleArray = !empty($sample) ? explode("^", $sample) : [];
    $sampleRecords = !empty($sampleArray[1]) ?explode(":", $sampleArray[1]) : [];

    $expcolsarray = !empty($promoexpo_data) ? explode("|", $promoexpo_data) : [];
    $flag = 0;


    //  create_temp_table($sql1,$cname,$CID,$expcols);
    $flag = 0;
    $cols = "";


    // Only when deflist is "Custom" or "ByField"
    /*   $sql1 = str_replace("From "," From ",$sql1);
       $sql1 = str_replace("Group "," Group ",$sql1);
       $sql1 = str_replace("Having "," Having ",$sql1);
       $sql1 = str_replace("Where "," Where ",$sql1);
               $sql1 = str_replace("Order "," Order ",$sql1);
       $pos  = strpos($sql1, "From "); */
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
    $pos = strpos($sSQLTemp,"ORDER BY");

    if ($pos != false)
    {
		$ordrByArr = explode('ORDER BY',$sSQLTemp);
		$ordrBy = " ORDER BY ".$ordrByArr[1];
        $sSQLTemp = substr($sSQLTemp,0,$pos-1);
    }else{
		$ordrBy = " ORDER BY CustomerID ASC";
	}

    echo $tempSQL = "Select * into $tblName From ( " . $sSQLTemp . " ) as t";

    //$tempSQL = str_replace("From "," into $tblName From ",$sSQL);

    $oDb->executeSQL($tempSQL);

    //die;
    //Check Campaign ID Exists or not
    $CampIDSQL = "SELECT  top 1 * From $tblName";

    $aData = $oDb->executeSelect($CampIDSQL);
    $campFlag = 0;
    //Check Campaign ID Exists or not

    // open file and Write the header
    if (trim($file_Opt) == 'Y') {

        $fhead = fopen($filePath . $folder_Name . "/RPL_" . $file_Name . ".xlsx", 'a');
        $expcols = $expcols . "\n";
        fwrite($fhead, $expcols);
        fclose($fhead);

    }
    // open file and Write the header

    // Check CampaignID,SegmentID,GroupID
    $colArray = explode(",", $expcols);

    $cid_f = 0;
    $sid_f = 0;
    $gid_f = 0;
    /*for ($i = 0; $i < 3; $i++) {
        if ($colArray[$i] == 'CampaignID') {
            $cid_f = 1;
        } else
            if ($colArray[$i] == 'SegmentID') {
                $sid_f = 1;
            } else
                if ($colArray[$i] == 'GroupID') {
                    $gid_f = 1;
                }
    }*/

    //Check CampaignID,SegmentID,GroupID

    // Sample %


    // Sample %

    $where_count = 1;


    echo "<br/>".$where_count;
    //$expc = is_array($colArray) ? implode(',',$colArray) : 'CustomerID';

    for ($i = 0; $i < $where_count; $i++) {
        $expc = !empty($expcols) ? $expcols : 'CustomerID';
        $newsql = "Select top 100 percent " . $expc . " From " . $tblName.' '.$ordrBy;
        //echo $newsql;
        //$aData = $oDb->executeSelect($newsql);

        /***************************** Changes 2017-04-11 Start ***********************************/

        if ((trim($file_Opt) == 'Y') && (trim($file_Ext) == "csv")) {
            //$sampleRecords = explode(":",$sampleArray[1]);
            echo $sSql = "insert into openrowset  ('Microsoft.ACE.OLEDB.12.0','Text;Database=". $filePath . $folder_Name ."/;HDR=YES;FMT=Delimited','SELECT $expcols FROM [RPL_$file_Name.csv]' ) $newsql";
            $aData = $oDb->executeSQL($sSql);
            //var_dump($aData);
            if (strpos($newsql, 'order by NEWID()') !== false) {
                $newsql = str_replace('order by NEWID()', '', $newsql);
            }

            //$sSql = "Select count(1) as cnt from ($newsql) as t";
            //$aData = $oDb->executeSelect($sSql);
            //  echo '<pre>'; print_r($aData); die;

            //$CSVTotalRec = $aData[0]['cnt'];
            $CSVTotalRec = count(file($filePath . $folder_Name ."\\RPL_$file_Name.csv", FILE_SKIP_EMPTY_LINES)) - 1;

            //var_dump($aData); die;
            $aData = array();
        } else if ((trim($file_Opt) == 'Y') && (trim($file_Ext) == "xlsx")) {
            $aData = $oDb->executeSelect($newsql);

            $header = array();
            $writer = new XLSXWriter();

            foreach ($colArray as $value){
                $header[$value] = 'string';
            }

            $writer->writeSheetHeader('Sheet1', $header );
            $writer->writeToFile($filePath.$folder_Name."\\RPL_".$file_Name.".".$file_Ext);

            /*
            foreach($aData as $k=>$row)
            {
                $xValues = array();
                foreach($row as $ccKey=>$newVal){
                    if(is_object($newVal)){
                        $ki = 1;
                        foreach($newVal as $scKey=>$subData){
                            if($ki == 1){
                                if(!empty($subData)){
                                    $subData = stripInvalidXml($subData);
                                    $xValues[] = "$subData";
                                }else if($subData === null){
                                    $xValues[] = '';
                                }else{
                                    $subData = stripInvalidXml($subData);
                                    $xValues[] = "$subData";
                                }
                            }
                            $ki++;
                        }
                    }else{
                        if(!empty($newVal)){
                            $newVal = stripInvalidXml($newVal);
                            $xValues[] = "$newVal";
                        }else if($newVal === null){
                            $xValues[] = '';
                        }else{
                            $newVal = stripInvalidXml($newVal);
                            $xValues[] = "$newVal";
                        }
                    }
                }
                $writer->writeSheetRow('Sheet1',$xValues);
            }*/

            $sSqlWriter = "insert into OPENROWSET('Microsoft.ACE.OLEDB.12.0', 'Excel 12.0;Database=".$filePath.$folder_Name."\\RPL_" . $file_Name . ".xlsx;','SELECT * FROM [Sheet1$]') $newsql";
            $rResponse = $oDb->execute_qry($sSqlWriter);

            //$writer->writeToFile($filePath.$folder_Name."/RPL_".$file_Name.'.xlsx');
            $CSVTotalRec = count($aData);//count(file("D:/$schDir/Public/RPL_$file_Name.xlsx", FILE_SKIP_EMPTY_LINES)) - 1;
            $aData = array();
        }
        //$aData = $oDb->executeSelect($newsql);
        /***************************** Changes 2017-04-11 End ***********************************/
        $c1 = 0;
        $totCount = 0;


        // Include Control Group or Not

        if ($seg_CG_Opt == 'Y') {
            $start = 0;
            $campCG = 0;
        } else {
            $start = 1;
            $campCG = 1;
        }
    } //For

    $dbfilename = $file_Name . '.' . $file_Ext;
    /*    $isql="insert into  Campaign_Export ([t_type],[t_name],[t_filetype],[t_filepath],[t_filename])
                values ('$ttype','$cname','$eff','$efold','$dbfilename')";

         $oDb->executeSQL($isql);  */
    // Don't Delete

    if (trim($CD_Opt) == 'Y') {
        // Insert into CampaignMetadata table

        $metaArray = explode('^', $metaData);
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
            $metadata_date = $metaArray[7] . '/' . $metaArray[8] . '/' . $metaArray[6];
        } else if (($SchType == 'RP') && ($rp_count > 2)) {

            $nextSQL = "SELECT [last_runtime] from [schedule_status] Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";

            $aData = $oDb->executeSelect($nextSQL);

            if (!empty($aData)) {
                $last_runtime = $aData[0]['last_runtime'];

            }

            $day_diff = dateDiff(date("Y/m/d", strtotime($last_runtime)), date('Y/m/d', time()));

            $metadata_date = date("Y/m/d", strtotime(add_date($metadata_date, $day_diff)));

            /*   switch($rp_run_sch)
               {
                   case 'daily':
                       $metadata_date =date("Y/m/d",strtotime(add_date($metadata_date,1)));
                       break;
                   case 'weekly':
                       $day_diff = dateDiff($last_runtime,date('Y/m/d',time()));
                       $metadata_date = date("Y/m/d",strtotime(add_date($metadata_date,$day_diff)));
                       break;
                   case 'monthly':
                       break;
               } */

        }

        $metadata_date = date("Y-m-d", strtotime($metadata_date));
        $updateSchTmplSQL = "UPDATE [schedule_templates] SET [metadata_date] = '$metadata_date' Where row_id = '$sch_id' AND t_type = 'A'";

        $oDb->executeSQL($updateSchTmplSQL);
        //$startDate = $date;


        // $startDate = date("m/d/y  H:i:s", time());
        $segTemp = explode("^", $criteria);
        $seg_des = explode(':', $segTemp[1]);

        //No of Selected Segments
        // $where_count  == > Noseg Selected
        $noRows = $where_count;
        //No of Selected Segments

        // Take Samples For MetaData
        $noRec = $gs + $nocg;

        $sampleRecords = explode(":", $sampleArray[1]);  //SampleArray take from $sample
        $Arry_start = 0;
        for ($j = 1; $j <= $noRows; $j++)
            for ($i = $gs; $i <= $noCG; $i++) {
                $sample_Seg[$i][$j] = $sampleRecords[$Arry_start];
                $Arry_start = $Arry_start + 1;
            }
        // Take Samples For MetaData

        //Get File Name
        // $fileName = $folder_Name."/".$file_Name.".".$file_Ext;
        $fileName = $file_Name;
        //Get File Name

    }

    //To Drop Temp table
     $dropSQL = "Drop table ".$tblName;
     $oDb->executeSQL($dropSQL);
    //To Drop Temp table


    // FTP
    if ($ftp_id != 0) {

        $ftpSQL = "SELECT [ftp_host_address],[ftp_port_no],[ftp_user_name],[ftp_password]
                  ,[folder_loc],[site_type] FROM [ftp_templates] Where [row_id] = '$ftp_id'";
        $aData = $oDb->executeSelect($ftpSQL);
        if (!empty($aData)) {
            $server = $aData[0]['ftp_host_address'];
            $port = $aData[0]['ftp_port_no'];
            $userName = $aData[0]['ftp_user_name'];
            $password = $aData[0]['ftp_password'];
            $folder = $aData[0]['folder_loc'];
            $site_type = $aData[0]['site_type'];
        }

        if($site_type == 'FTP'){
            $conn_id = ftp_connect($server, $port);
            $login_result = ftp_login($conn_id, $userName, $password);

            if (($conn_id) && ($login_result)) {
                $local_file = $filePath . $folder_Name . "\\RPL_$file_Name." . "$file_Ext";
                $remote_file = "RPL_$file_Name." . "$file_Ext";
                ftp_pasv($conn_id, true);
                ftp_chdir($conn_id, $folder);

                ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
                ftp_close($conn_id);
                $ftp_flag = 'Y';
                $updateST = "Update schedule_completed set [ftp_flag] = 'Y' Where row_id = '" . $sch_id . "' AND t_type = 'A'";
                $oDb->executeSQL($updateST);
                $smtp_flag = 'Y';


            } else {

                $ftp_flag = 'N';
                $updateST = "Update schedule_completed set [ftp_flag] = 'N' Where row_id = '" . $sch_id . "' AND t_type = 'A'";
                $oDb->executeSQL($updateST);
                $smtp_flag = 'N';
            }
        }else if ($site_type == 'SFTP'){
            /******************** Create command text file - Start ******************/
            $fh = fopen( $filePath.'psftpscript_sample2.txt', 'w' );
            fclose($fh);

            $fhead = fopen($filePath."psftpscript_sample2.txt", 'a');
            $line = "lcd ".$filePath."Public" . "\n";
            fwrite($fhead, $line);
            fclose($fhead);

            $fhead = fopen($filePath."psftpscript_sample2.txt", 'a');
            $line = "put RPL_" . $file_Name . "." . $file_Ext . "\n";
            fwrite($fhead, $line);
            fclose($fhead);

            $fhead = fopen($filePath."psftpscript_sample2.txt", 'a');
            $line = "mv RPL_" . $file_Name . "." . $file_Ext . " $folder\n ";
            fwrite($fhead, $line);
            fclose($fhead);

            $fhead = fopen($filePath."psftpscript_sample2.txt", 'a');
            $line = "quit\n ";
            fwrite($fhead, $line);
            fclose($fhead);
            /******************** Create command text file - Start ******************/


            /******************** Create Bat file - Start ******************/
            $fh = fopen( $filePath.'psftpexecution_gc_sam.bat', 'w' );
            fclose($fh);

            $fhead = fopen($filePath."psftpexecution_gc_sam.bat", 'a');
            $command = "cd/ \n";
            fwrite($fhead, $command);
            fclose($fhead);

            echo "-------------------------".$password."-------------------------";
            $fhead = fopen($filePath."psftpexecution_gc_sam.bat", 'a');
            $command = "psftp -b ".$filePath."psftpscript_sample2.txt $userName@$server -pw $password\n";
            fwrite($fhead, $command);
            fclose($fhead);

            $fhead = fopen($filePath."psftpexecution_gc_sam.bat", 'a');
            $command = "Schtasks /delete /TN ".$schDir."\\".$SchName."_bat /f";
            fwrite($fhead, $command);
            fclose($fhead);
            /******************** Create Bat file - End ******************/

            //system("cmd /c D:\RD_v14\psftpexecution_gc_sam.bat");

            $date = date("m/d/Y", time());
            $time = date("H:i:s", time() + 60);
            $date1 = date("m/d/y  H:i:s", time());

            echo "command=======>" . $command = 'schtasks /create /tn ' . $schDir . '\\' . $SchName . '_bat /tr ' . $filePath . 'psftpexecution_gc_sam.bat  /sc once /st ' . $time . ' /sd ' . $date . ' /ru Administrator';

            $result = shell_exec($command);


            $ftp_flag = 'Y';
            $updateST = "Update schedule_completed set [ftp_flag] = 'Y' Where row_id = '" . $sch_id . "' AND t_type = 'A'";
            $oDb->executeSQL($updateST);
            $smtp_flag = 'Y';
        }


    } else {
        $ftp_flag = 'N';
        $smtp_flag = 'Y';

    }

    // Delete the Schedule
    $date = date("m/d/y  H:i:s", time());  //Schedule ending date and time
    if ($SchType == 'RA') {

        $command = 'schtasks /delete /tn ' . $schDir . '\\' . $SchName . ' /f';
        $result = shell_exec($command);
        $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Completed',[completed_time] = '" . $date . "' ,[succ_flag] = 'Y' Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";
        $oDb->executeSQL($updateStatusSQL);
    } else if ($SchType == 'RP') {
        $nextSQL = "SELECT [next_runtime] from [schedule_status] Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";

        $aData = $oDb->executeSelect($nextSQL);

        if (!empty($aData)) {
            $old_runtime = $aData[0]['next_runtime'];
            $last_runtime = date('Y/m/d', strtotime($old_runtime));
        }

        switch ($rp_run_sch) {
            case 'daily':
                $old = explode(" ", $old_runtime);
                $old_date = $old[0];
                $old_time = $old[1];
                $temp_date = add_date($old_runtime, 1);
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
                        $temp_date = add_date($old_runtime, ($rp_week - 1) * 7);
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
                    $temp_runtime = add_date($temp_runtime, 0, 1);
                    $temp_month = strtoupper(date("M", strtotime($temp_runtime)));
                    if (in_array($temp_month, $monthArray))
                        $month_flag = 0;
                }
                $t = explode(" ", $temp_runtime);
                $next_runtime = $t[0] . ' ' . $old_time;
                break;

        }

        if (date("Y-m-d", strtotime($next_runtime)) < date("Y-m-d", strtotime($rp_end_date)))
            $updateStatusSQL = "UPDATE [schedule_status] SET[status] = 'Scheduled',[last_runtime] = '" . $last_runtime . "',[completed_time] = '" . $date . "',[next_runtime] = '" . $next_runtime . "',[succ_flag] = 'Y',[file_name] = '" . $file_Name . "' Where row_id = '" . $SchStatusID . "'";
        else
            $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Completed',[last_runtime] = '" . $last_runtime . "',[completed_time] = '" . $date . "',[next_runtime] = '" . $next_runtime . "',[succ_flag] = 'Y',[file_name] = '" . $file_Name . "' Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";


        $oDb->executeSQL($updateStatusSQL);


    } else if ($SchType == 'RI') {
        $command = 'schtasks /delete /tn ' . $schDir . '\\' . $SchName . ' /f';
        $result = shell_exec($command);
        $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Completed',[completed_time] = '" . $date . "' ,[succ_flag] = 'Y',[file_name] = '" . $file_Name . '.' . $file_Ext . "' Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";

        $oDb->executeSQL($updateStatusSQL);
    }

    // Delete the Schedule

    // $updateStatusSQL = "UPDATE [schedule_status] SET [status] = 'Completed',[completed_time] = '".$date."' ,[succ_flag] = 'Y' Where row_id = '".$SchStatusID."'";


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
            //$headers .= 'From: admin@crmsquare.info' . "\r\n";
            $headers .= $cCommonHeader;
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
            //$headers .= 'From: admin@crmsquare.info' . "\r\n";
            $headers .= $cCommonHeader;
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


    echo $scheTaskSQL = "Insert into [schedule_completed]([sche_name],[templ_name],[start_time],[next_runtime],[completed_time]
                     ,[file_name],[succ_flag],[ftp_flag],[status],[file_path],[camp_id],[total_records],[t_type])
                    SELECT [sche_name],[templ_name],[start_time],[next_runtime],'$date','$outfile','$sucess','$ftp_flag'
		     ,'Completed',[file_path],'$CID','$CSVTotalRec',t_type FROM [schedule_status] Where row_id = '" . $SchStatusID . "' AND t_type = 'A'";

    $oDb->executeSQL($scheTaskSQL);

    $sSqlSRE = "SELECT * FROM Sent_Reports_via_Email WHERE camp_tmpl_id = '".$CID."' AND t_type='A'";
    $result = $oDb->executeSelect($sSqlSRE);
    if(!empty($result)){
        $result = $result[0];

        $To = $result['remail_to'];
        $Cc = $result['remail_cc'];
        $Bcc = $result['remail_bcc'];
        $Sub =  $result['remail_sub'];
        $limitedtextarea1 = $result['remail_comments'];
        $type = 'A';
        $attachments = array();
        if(file_exists($filePath.$folder_Name.'\\RPL_'.$file_Name.'.xlsx')){
            array_push($attachments,[
                'path' => $filePath.$folder_Name.'\\RPL_'.$file_Name.'.xlsx',
                'name' => $file_Name,
            ]);
        }
        if(!empty($Report_Row) && file_exists($filePath.$folder_Name.'\\RPS_'.$file_Name.'.pdf')){
            array_push($attachments,[
                'path' => $filePath.$folder_Name.'\\RPS_'.$file_Name.'.pdf',
                'name' => $file_Name,
            ]);
        }

        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = '127.0.0.1';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = false;
        $mail->SMTPAutoTLS = false;
        $mail->SMTPDebug = 4;
        // SMTP password
        //$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 25;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('admin@crmsquare.com', 'CRM Square Administrator');
        $mail->addAddress($To);     // Add a recipient
        $mail->addReplyTo('admin@crmsquare.com', 'CRM Square Administrator');

        if(!empty($Cc)){
            $mail->addCC($Cc);
        }

        if(!empty($Bcc)){
            $mail->addBCC($Bcc);
        }
        echo '<pre>'; print_r($attachments);
        // Attachments
        foreach($attachments as $attachment){
            $mail->addAttachment($attachment['path'],$attachment['name']);
        }

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $Sub;
        $mail->Body    = $limitedtextarea1;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if($mail->send()){
            $sSqlSREU = "UPDATE Sent_Reports_via_Email SET Email_Status = 'Sent' WHERE camp_tmpl_id = '".$CID."' AND t_type='C'";
            $oDb->executeSQL($sSqlSREU);
        }
    }

}


//User Define Function
function add_date($givendate, $day = 0, $mth = 0, $yr = 0)
{

    $cd = strtotime($givendate);
    $newdate = date('Y-m-d h:i:s', mktime(date('h', $cd),
        date('i', $cd), date('s', $cd), date('m', $cd) + $mth,
        date('d', $cd) + $day, date('Y', $cd) + $yr));

    return $newdate;
}

function dateDiff($startDate, $endDate)
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

//User Define Function


?>