<?php
$date1 = new DateTime($record->StartTime);
$date2 = new DateTime($record->RunTime);
$interval = $date1->diff($date2);
$ccschstatusmap = isset($record->rpschedule->ccschstatusmap) ? $record->rpschedule->ccschstatusmap : [];
?>
<tr>
    <td>{!! $record->ID !!}</td>
    <td>{!! ucfirst($record->Level) !!}</td>
    <td>{!! $record->Name !!}</td>
    <td>{!! $record->Description !!}</td>

    <?php
        $dDatePart = explode(" ", $record->StartTime);
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
    <td class="text-center">{!! $record->FTP !!}</td>
    <td class="text-center">{!! $record->is_public !!}</td>

    <?php
    $sSql = "SELECT count(*) as cnt FROM UL_RepCmp_Share WHERE User_id = '".$uid."' AND camp_tmpl_id = '".$record->row_id."' AND t_type = 'A'";
    $sData = DB::select($sSql);
    $sData = collect($sData)->map(function($x){ return (array) $x; })->toArray();
    ?>
    <td class="text-center">{!! $sData[0]['cnt'] > 0 ? 'Y' : 'N' !!}</td>
    <td class="text-center">{!! $record->Custom_SQL !!}</td>
    <td class="text-center">{!! number_format($record->Records) !!}</td>
    <td class="text-center">
        @if(!empty($record->ListXLSX) && file_exists(public_path($record->ListXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $record->ListXLSX !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
        @endif
    </td>

    <td class="text-center pl-0 pt-1">
        @if(!empty($record->ListPDF) && file_exists(public_path($record->ListPDF)))

            {{--<a class="btn no-border font-16 p-0" download href="{!! $record->ListPDF !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;"></i>
            </a>--}}
            &nbsp;
            {{--<input type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'list');"/>--}}

            <a class="btn no-border font-16 p-0" download href="{!! $record->ListPDF !!}" title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;"></i>
            </a>
            &nbsp;
            <div class="checkbox">
                <input id="{!! $record->ListPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'list');"/>
                <label for="{!! $record->ListPDF !!}" style="margin-bottom: 16px;"></label>

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
        @if(!empty($record->SummaryXLSX) && file_exists(public_path($record->SummaryXLSX)))
            <a class="btn no-border font-16 p-0" download href="{!! $record->SummaryXLSX !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
        @endif
    </td>

    <td class="text-center pl-0 pt-1">
        @if(!empty($record->SummaryPDF) && file_exists(public_path($record->SummaryPDF)))

            {{--<a class="btn no-border font-16 p-0" download href="{!! $record->SummaryPDF !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;" ></i>
            </a>
            &nbsp;
            <input type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'rpt');"/>--}}
            <a class="btn no-border font-16 p-0" download href="{!! $record->SummaryPDF !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;" ></i>
            </a>
            &nbsp;
            <div class="checkbox">
                <input id="{!! $record->SummaryPDF !!}" type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this),'rpt');"/>
                <label for="{!! $record->SummaryPDF !!}" style="margin-bottom: 16px;"></label>

                <div class="space"></div>
            </div>
        @endif
    </td>

    <td class="text-center">

        <div class="checkbox">
            <input id="{!! $record->ID !!}" type="checkbox" class="em_report" value='{!! json_encode(['row_id' => $record->row_id,'t_id' => $record->ID,'list_level' => $record->Level,'list_short_name' => $record->Name,'t_name' => $record->t_name,'sql' => base64_encode($record->sql),'selected_fields' => $record->selected_fields,'meta_data' => $record->meta_data,'Report_Row' => $record->Report_Row,'Report_Column' => $record->Report_Column,'Report_Function' => $record->Report_Function,'Report_Sum' => $record->Report_Sum,'Report_Show' => $record->Report_Show,'Chart_Type' => trim($record->Chart_Type),'Axis_Scale' => $record->Axis_Scale,'Label_Value' => $record->Label_Value]) !!}'/>
            <label for="{!! $record->ID !!}"></label>

            <div class="space"></div>
        </div>
        {{--<input type="checkbox" class="em_report" value='{!! json_encode(['row_id' => $record->row_id,'t_id' => $record->ID,'list_level' => $record->Level,'list_short_name' => $record->Name,'t_name' => $record->t_name,'sql' => base64_encode($record->sql),'selected_fields' => $record->selected_fields,'meta_data' => $record->meta_data,'Report_Row' => $record->Report_Row,'Report_Column' => $record->Report_Column,'Report_Function' => $record->Report_Function,'Report_Sum' => $record->Report_Sum,'Report_Show' => $record->Report_Show,'Chart_Type' => trim($record->Chart_Type),'Axis_Scale' => $record->Axis_Scale,'Label_Value' => $record->Label_Value]) !!}'/>--}}
    </td>

   {{-- <td class="text-center">
        @if(!empty($record->SummaryPDF) && file_exists(public_path($record->SummaryPDF)))
            <input type="checkbox" class="po_status" value="{!! $record->row_id !!}" onchange="mPdfChecked($(this));"/>
        @endif
    </td>--}}

    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>View</option>
            <option value='new,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Save As</option>
            <option value='run,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Run Report</option>
            <option value='replica,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Run List</option>
            <option value='schedule,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Schedule</option>
            <option value='email,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Email</option>
            <option value='share,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Share</option>
            <option value='delete,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Delete</option>

        </select>
    </td>
</tr>
