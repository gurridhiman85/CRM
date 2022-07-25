<?php
$start_time = !empty($record->rpcompleted->start_time) ? $record->rpcompleted->start_time : date('Y-m-d h:i');
$completed_time = !empty($record->rpcompleted->completed_time) ? $record->rpcompleted->completed_time : date('Y-m-d h:i');
$ccschstatusmap = isset($record->rpschedule->moschstatusmap) ? $record->rpschedule->moschstatusmap : [];

$date1 = new DateTime($start_time);
$date2 = new DateTime($completed_time);
$interval = $date1->diff($date2);
$ModelScore_Date = isset($record->modelscoremetadata->ModelScore_Date) ? $record->modelscoremetadata->ModelScore_Date : '';
$category = isset($record->modelscoremetadata->ModelScore_Des) ? $record->modelscoremetadata->ModelScore_Des : '';
$universe = isset($record->modelscoremetadata->ModelScore_Universe) ? $record->modelscoremetadata->ModelScore_Universe : '';
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
    <td>{!! isset($record->momodel) ? $record->momodel->ModelBuildID .' - ' . $record->momodel->Model_Name : '' !!}</td>
    <td>{!! ucfirst($record->list_level) !!}</td>
    <td>{!! $record->Scored_File_Name !!}</td>
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
    <td>
        <button type="button"
                class="btn btn-light font-10 s-f ajax-Link"
                data-href="model/preview/{{ $record->ModelBuildID }}"
                title="Preview">
            <i class="fas fa-info ds-c"></i>
        </button>
        &nbsp;&nbsp;
        {!! isset($record->momodel) ? $record->momodel->Model_Technique : '' !!}</td>
    <td>
        @if(!empty($universe))
            @php $universe = strip_tags($universe); @endphp
            @if (strlen($universe) > 50)
                @php
                    // truncate string
                    $universeCut = substr($universe, 0, 50);
                    $endPoint = strrpos($universeCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint? substr($universeCut, 0, $endPoint) : substr($universeCut, 0);
                @endphp
                <span class="teaser">{!! $string !!}</span>
                <span class="complete">{!! $universe !!}</span>
                <span class="more font-14" onclick="readmore($(this))">+</span>
            @else
                {!! $universe !!}
            @endif
        @endif
    </td>

    <?php
    //$dDatePart = explode(" ", $start_time);
    $dDatePart = explode(" ", $ModelScore_Date);
    $tTimePart = explode(":", $dDatePart[1]);
    ?>
    <td class="text-center"><?= $dDatePart[0]; //. ' ' . $tTimePart[0] . ':' . $tTimePart[1] ?></td>
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
    <td class="text-center"><?php echo isset($record->rpshare) && !empty($record->rpshare->Shared_With_User_id) && $record->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N'; ?></td>
    <td class="text-center">{!! $record->Custom_SQL !!}</td>
    <td class="text-center">{!! isset($ccschstatusmap[0]['total_records']) ? number_format($ccschstatusmap[0]['total_records']) : 0 !!}</td>
    <td class="text-center">
        @if(isset($ccschstatusmap) && count($ccschstatusmap) > 1)
            <a href="javascript:void(0);" onclick="showOldReport('{{ $record->row_id }}')">
                <i class="fas fa-align-justify"></i>
            </a>
        @endif
    </td>
    <td class="text-center">
        <?php

        $ListXLSX = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'MOL_'.$ccschstatusmap[0]['file_name'].'.'.$record->promoexpo_ext : '';
        ?>
        @if(!empty($ListXLSX) && file_exists(public_path($ListXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $ListXLSX !!}"  id="DownloadBtn">
                <i  @if($record->promoexpo_ext == 'xlsx') class="fas fa-file-excel" title="XLSX" @else class="fas fa-file-alt" title="CSV" @endif style="color: #06b489;"></i>
            </a>
        @endif
    </td>


    <td class="text-center">
        @php
            $SummaryXLSX = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'MOR_'.$ccschstatusmap[0]['file_name'].'.xlsx' : '';

            $SummaryPDF = isset($ccschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'MOR_'.$ccschstatusmap[0]['file_name'].'.pdf' : '';

        @endphp

        @if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $SummaryXLSX !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
            &nbsp;&nbsp;
        @else
            <a class="btn no-border font-16 p-0 DownloadPDFBtn" download href="javascript:void(0);" onclick="generateXLSX({!! $record->row_id !!}, $(this))" title="Download">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
            &nbsp;&nbsp;
        @endif


        @if(!empty($SummaryPDF) && file_exists(public_path($SummaryPDF)))
            <a class="btn no-border font-16 p-0" download href="{!! $SummaryPDF !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;"></i>
            </a>
        @else
            <a class="btn no-border font-16 p-0 DownloadPDFBtn" download href="javascript:void(0);" onclick="generatePDF({!! $record->row_id !!}, $(this))" title="Download">
                <i class="fas fa-file-pdf" style="color: #e92639;"></i>
            </a>
        @endif

    </td>


    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>View</option>
            <option value='new,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Save As</option>
            <!--
            <option value='run,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Run Report</option>
            <option value='replica,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Run List</option>
            -->
            <option value='schedule,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Schedule</option>
            <!--<option value='email,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Email</option>
            -->
            <option value='share,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Share</option>
            <option value='delete,{!! $record->row_id !!},"{!! $record->Scored_File_Name !!}",{!! $record->t_id !!}'>Delete</option>
        </select>
    </td>
</tr>
