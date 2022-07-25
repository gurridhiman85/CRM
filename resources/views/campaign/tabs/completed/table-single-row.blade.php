<?php

/*[row_id] => 1
[t_id] => 128
[DCampaignID] =>
[t_name] => report_v1_20200930_1129
[t_type] => A
[list_short_name] => report_v1
[list_level] => emailable
[list_fields] => custom_fields
[filter_criteria] =>
[Customer_Exclusion_Criteria] =>
[Customer_Inclusion_Criteria] =>
[filter_condition] =>
[Customer_Exclusion_Condition] =>
[Customer_Inclusion_Condition] =>
[selected_fields] => DS_MKC_ContactID,DaysSinceLastUpdate
[sql] => SELECT  DS_MKC_ContactID,DaysSinceLastUpdate  FROM  Emailable Order By DS_MKC_ContactID ASC
[seg_def] =>
[seg_noLS] => 0
[seg_method] =>
[seg_criteria] =>
[Filter_Html] =>
[seg_selected_criteria] =>
[seg_grp_no] => 0
[seg_ctrl_grp_opt] =>
[seg_camp_grp_dtls] =>
[seg_camp_grp_proportion] =>
[seg_camp_grp_sel_cri] =>
[seg_sample] =>
[promoexpo_cd_opt] =>
[promoexpo_file_opt] => Y
[promoexpo_folder] => Private
[promoexpo_file] => report_v1_20200930_1129
[promoexpo_ext] => xlsx
[promoexpo_ecg_opt] =>
[promoexpo_data] =>
[Create_Date] => 2021-02-03 04:01:22.907
[Report_Row] =>
[Report_Column] =>
[Report_Function] => count
[Report_Sum] =>
[Report_Show] => np
[User_ID] => 3
[is_public] => N
[Custom_SQL] => N
[Axis_Scale] => lin
[Label_Value] => 0
[Chart_Type] => column
[Chart_Image] =>
[SR_Attachment] =>
[List_Format] => default
[Report_Orientation] => portrait



SELECT za.t_id as ID,za.list_level as [Level],
za.list_short_name as Name,za.t_name,za.sql,za.selected_fields,za.meta_data,
substring(za.meta_data,  P3.Pos + 1,  P4.Pos -  P3.Pos - 1) as Description
,sc.start_time as 'StartTime',sc.completed_time as 'RunTime',
case sc.file_name WHEN '-' THEN '-'
  else case za.t_type WHEN 'A' THEN za.promoexpo_folder+'/".$this->prefix."RPL_'+sc.file_name else za.promoexpo_folder+'/'+sc.file_name
  END END as [List],
  (isnull(za.promoexpo_folder+'\\".$this->prefix."RPL_'+za.promoexpo_file+'.xlsx','')) as ListXLSX,
  (isnull(za.promoexpo_folder+'\\".$this->prefix."RPL_'+za.promoexpo_file+'.pdf','')) as ListPDF,
  sc.total_records as 'Records'
,sc.ftp_flag as 'FTP',za.is_public as 'is_public', za.Custom_SQL , (isnull(za.promoexpo_folder+'/".$this->prefix."RPS_'+za.promoexpo_file+'.pdf','')) as SummaryPDF
, (isnull(za.promoexpo_folder+'/".$this->prefix."RPS_'+za.promoexpo_file+'.xlsx','')) as SummaryXLSX,
substring(za.meta_data, P11.Pos + 1, P12.Pos - P11.Pos - 1) as Action,za.row_id,
za.t_type,za.Report_Row,za.Report_Column,za.Report_Function,za.Report_Sum,za.Report_Show,za.Chart_Type,za.Axis_Scale,za.Label_Value
from UL_RepCmp_Completed sc,UR_Report_Templates za
where (sc.camp_id = za.t_id AND za.t_type = 'A'*/
//echo '<pre>'; print_r($record->rpcompleted); die;
$start_time = !empty($record->rpcompleted->start_time) ? $record->rpcompleted->start_time : date('Y-m-d h:i');
$completed_time = !empty($record->rpcompleted->completed_time) ? $record->rpcompleted->completed_time : date('Y-m-d h:i');
$ccschstatusmap = isset($record->rpschedule->ccschstatusmap) ? $record->rpschedule->ccschstatusmap : [];

$date1 = new DateTime($start_time);
$date2 = new DateTime($completed_time);
$interval = $date1->diff($date2);
$category = isset($record->rpmeta->Category) ? $record->rpmeta->Category : '';
?>
<tr>
    <td class="text-center">
        <?php
        $is_tag = $record->tag == 1 ? 'checked' : '';
        ?>
        <label class="custom-control custom-checkbox m-b-0">
            <input type="checkbox" class="custom-control-input checkbox" onclick="tagcampaign($(this),'{{ $record->row_id }}','tag');" {{ $is_tag }} value="1">
            <span class="custom-control-label"></span>
        </label>
    </td>
    <td>{!! $record->t_id !!}</td>
    <td>{!! ucfirst($record->list_level) !!}</td>
    <td>{!! $record->list_short_name !!}</td>
    <td>
        @if(!empty($category))
            @php $category = strip_tags($category); @endphp
            @if (strlen($category) > 50)
                @php
                    // truncate string
                    $categoryCut = substr($category, 0, 50);
                    $endPoint = strrpos($categoryCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);
                @endphp
                <span class="teaser">{!! $string !!}</span>
                <span class="complete">{!! $category !!}</span>
                <span class="more font-14" onclick="readmore($(this))">+</span>
            @else
                {!! $category !!}
            @endif
        @endif
    </td>

    <?php
    $dDatePart = explode(" ", $start_time);
    $tTimePart = explode(":", $dDatePart[1]);
    ?>
    <td class="text-center">{!! $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1] !!}</td>
    <?php
    unset($dDatePart);
    unset($tTimePart);

    $cCompleteTime = '';
    if ($interval->h != 0) {
        $cCompleteTime .= $interval->h . ':';
    }
    if ($interval->i != 0) {
        $cCompleteTime .= $interval->h . ':';
    }
    ?>
    <td class="text-center">{!! $cCompleteTime . $interval->s !!}</td>
    <td class="text-center">{!! !empty($record->rpcompleted->ftp_flag) ? $record->rpcompleted->ftp_flag : 'N' !!}</td>
    <td class="text-center">{!! $record->is_public !!}</td>

    <?php
    /*    $sSql = "SELECT count(*) as cnt FROM UL_RepCmp_Share WHERE User_id = '".$uid."' AND camp_tmpl_id = '".$record->row_id."' AND t_type = 'A'";
        $sData = DB::select($sSql);
        $sData = collect($sData)->map(function($x){ return (array) $x; })->toArray();
        */?>
    <td class="text-center"><?php echo isset($record->rpshare) && !empty($record->rpshare->Shared_With_User_id) && $record->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N'; ?></td>
    <td class="text-center">{!! $record->Custom_SQL !!}</td>
    <td class="text-center">{!! isset($ccschstatusmap[0]['total_records']) ? number_format($ccschstatusmap[0]['total_records']) : 0 !!}</td>
    <td class="text-center">
        <?php

        $ListXLSX = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'CAL_'.$ccschstatusmap[0]['file_name'].'.'.$record->promoexpo_ext : '';
        ?>
        @if(!empty($ListXLSX) && file_exists(public_path($ListXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $ListXLSX !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
        @endif
    </td>

    <td class="text-center pl-0 pt-1">
        @php
            $ListPDF = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'CAL_'.$ccschstatusmap[0]['file_name'].'.pdf' : '';
        @endphp
        @if(!empty($ListPDF) && file_exists(public_path($ListPDF)))
            <a class="btn no-border font-16 p-0" download href="{!! $ListPDF !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;"></i>
            </a>
            &nbsp;
            <div class="checkbox">
                <input id="{!! $ListPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'list');"/>
                <label for="{!! $ListPDF !!}" style="margin-bottom: 16px;"></label>

                <div class="space"></div>
            </div>
        @endif
    </td>

    <td class="text-center">
        @if(isset($ccschstatusmap) && count($ccschstatusmap) > 1)
            <a href="javascript:void(0);" onclick="showOldReport('{{ $record->row_id }}')">
                <i class="fas fa-align-justify"></i>
            </a>
        @endif
    </td>

    <td class="text-center">
        @php
            $SummaryXLSX = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'CAM_'.$ccschstatusmap[0]['file_name'].'.xlsx' : '';
        @endphp
        @if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $SummaryXLSX !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
        @endif
    </td>

    <td class="text-center pl-0 pt-1">
        @php
            $SummaryPDF = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'CAM_'.$ccschstatusmap[0]['file_name'].'.pdf' : '';
        @endphp
        @if(!empty($SummaryPDF) && file_exists(public_path($SummaryPDF)))
            <a class="btn no-border font-16 p-0" download href="{!! $SummaryPDF !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;" ></i>
            </a>
            &nbsp;
            <div class="checkbox">
                <input id="{!! $SummaryPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'rpt');"/>
                <label for="{!! $SummaryPDF !!}" style="margin-bottom: 16px;"></label>

                <div class="space"></div>
            </div>
        @endif
    </td>

    <td class="text-center">

        <div class="checkbox">
            <input id="{!! $record->t_id !!}" type="checkbox" class="em_report" onclick="emreport()" value='{!! json_encode(['row_id' => $record->row_id,'t_id' => $record->t_id,'list_level' => $record->list_level,'list_short_name' => $record->list_short_name,'t_name' => $record->t_name,'sql' => base64_encode($record->sql),'selected_fields' => $record->selected_fields,'meta_data' => $category,'Report_Row' => $record->Report_Row,'Report_Column' => $record->Report_Column,'Report_Function' => $record->Report_Function,'Report_Sum' => $record->Report_Sum,'Report_Show' => $record->Report_Show,'Chart_Type' => trim($record->Chart_Type),'Axis_Scale' => $record->Axis_Scale,'Label_Value' => $record->Label_Value]) !!}'/>
            <label for="{!! $record->t_id !!}"></label>

            <div class="space"></div>
        </div>
    </td>

    {{-- <td class="text-center">
         @if(!empty($record->SummaryPDF) && file_exists(public_path($record->SummaryPDF)))
             <input type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this));"/>
         @endif
     </td>--}}

    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>View</option>
            <option value='new,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Save As</option>
            <option value='run,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Run Report</option>
            <option value='replica,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Run List</option>
            <option value='schedule,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Schedule</option>
            <option value='email,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Email</option>
            <option value='share,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Share</option>
            <option value='delete,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Delete</option>
        </select>
    </td>
</tr>
