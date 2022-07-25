<?php
$start_time = !empty($record->rpcompleted->start_time) ? $record->rpcompleted->start_time : date('Y-m-d h:i');
$completed_time = !empty($record->rpcompleted->completed_time) ? $record->rpcompleted->completed_time : date('Y-m-d h:i');
$rpschstatusmap = isset($record->rpschedule->prschstatusmap) ? $record->rpschedule->prschstatusmap : [];

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
            <input type="checkbox" class="custom-control-input checkbox" onclick="tagreport($(this),'{{ $record->row_id }}','tag');" {{ $is_tag }} value="1">
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
    <td class="text-center">{!! $record->is_public !!}</td>
    <td class="text-center"><?php echo isset($record->rpshare) && !empty($record->rpshare->Shared_With_User_id) && $record->rpshare->Shared_With_User_id == Auth::user()->User_ID > 0 ? 'Y' : 'N'; ?></td>
    {{--<td class="text-center">{!! isset($rpschstatusmap[0]['total_records']) ? number_format($rpschstatusmap[0]['total_records']) : 0 !!}</td>--}}

    <td class="text-center">
        @if(isset($rpschstatusmap) && count($rpschstatusmap) > 1)
            <a href="javascript:void(0);" onclick="showOldReport('{{ $record->row_id }}')">
                <i class="fas fa-align-justify"></i>
            </a>
        @endif
    </td>

    <td class="text-center pr-2">
        @php
            $SummaryXLSX = isset($rpschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'PRF_'.$rpschstatusmap[0]['file_name'].'.xlsx' : '';
        @endphp
        @if(!empty($SummaryXLSX) && file_exists(public_path($SummaryXLSX)))
            <a class="btn no-border font-16 p-0" href="{!! $SummaryXLSX !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-excel" style="color: #06b489;"></i>
            </a>
        @else
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
        &nbsp;&nbsp;
        @php
            $SummaryPDF = isset($rpschstatusmap[0]['file_name']) ? $record->promoexpo_folder.'\\'.$prefix.'PRF_'.$rpschstatusmap[0]['file_name'].'.pdf' : '';
        @endphp
        @if(!empty($SummaryPDF) && file_exists(public_path($SummaryPDF)))
            <a class="btn no-border font-16 p-0" href="{!! $SummaryPDF !!}" download title="Download" id="DownloadBtn">
                <i class="fas fa-file-pdf" style="color: #e92639;" ></i>
            </a>
        @else
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        @endif
    </td>

    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>View</option>
            <option value='new,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Save As</option>
            @if($record->t_type == 'P')
                <option value='run,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Run Report</option>
                <option value='schedule,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Schedule</option>
                <option value='email,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Email</option>
                <option value='share,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Share</option>
                <option value='delete,{!! $record->row_id !!},"{!! $record->list_short_name !!}",{!! $record->t_id !!}'>Delete</option>
            @endif
        </select>
    </td>
</tr>
