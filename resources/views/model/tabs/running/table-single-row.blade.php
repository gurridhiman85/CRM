@php
    $rpstatus = !empty($record['rpschedule']['moschstatusmap'][0]) ? $record['rpschedule']['moschstatusmap'] : [];
    $description = isset($record['modelscoremetadata']['ModelScore_Des']) ? $record['modelscoremetadata']['ModelScore_Des'] : '';
    //$universe = isset($record['modelscoremetadata']['ModelScore_Universe']) ? $record['modelscoremetadata']['ModelScore_Universe'] : '';
@endphp
<tr>
    <td>{!! $record['t_id'] !!}</td>
    <td>{!! ucfirst($record['list_level']) !!}</td>
    <td>{!! $record['Scored_File_Name'] !!}</td>
    <td>
        @if(!empty($description))
            @php $description = strip_tags($description); @endphp
            @if (strlen($description) > 50)
                @php
                    // truncate string
                    $categoryCut = substr($description, 0, 50);
                    $endPoint = strrpos($categoryCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint? substr($categoryCut, 0, $endPoint) : substr($categoryCut, 0);
                @endphp
                <span class="teaser">{!! $string !!}</span>
                <span class="complete">{!! $description !!}</span>
                <span class="more font-14" onclick="readmore($(this))">+</span>
            @else
                {!! $description !!}
            @endif
        @endif
    </td>
    {{--<td>{!! $record->ScheduleName !!}</td>--}}
    <?php
    $start_date = !empty($rpstatus) ? $rpstatus[0]['start_time'] : date('Y-m-d h:i');
    $dDatePart = explode(" ", $start_date);
    $tTimePart = explode(":", $dDatePart[1]);
    ?>
    <td class="text-center">{!! $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1] !!}</td>

    <?php
    if(!empty($rpstatus) && !empty($rpstatus[0]['next_runtime'])){
        $dDatePart = explode(" ", $rpstatus[0]['next_runtime']);
        $tTimePart = explode(":", $dDatePart[1]);
        $next_runtime = $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
    }else{
        $next_runtime = '';
    }
    ?>
    <td class="text-center">
        {!! $next_runtime !!}
    </td>
    <td class="text-center">{!! isset($rpstatus[0]['ftp_flag']) && !empty(trim($rpstatus[0]['ftp_flag'])) ? $rpstatus[0]['ftp_flag'] : 'N' !!}</td>
    <td class="text-center">{!! $record['is_public'] !!}</td>

    <td class="text-center"><?php echo isset($record['rpshare']) && !empty($record['rpshare']['Shared_With_User_id']) && $record['rpshare']['Shared_With_User_id'] == Auth::user()->User_ID > 0 ? 'Y' : 'N'; ?></td>
    <td class="text-center">{!! $record['Custom_SQL'] !!}</td>
    {{--<td><a class="ajax-Link" href="/report/delete/{!! $record->Row_id !!}"><i class="fas fa-trash font-14"></i></a> </td>--}}
    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record['row_id'] !!},"{!! $record['Scored_File_Name'] !!}",{!! $record['t_id'] !!}'>View</option>
            <option value='delete,{!! $record['row_id'] !!},"{!! $record['Scored_File_Name'] !!}",{!! $record['t_id'] !!}'>Delete</option>
        </select>
    </td>
</tr>
