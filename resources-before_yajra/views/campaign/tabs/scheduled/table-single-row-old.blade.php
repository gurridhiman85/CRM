
<tr>
    <td>{!! $record->ID !!}</td>
    <td>{!! ucfirst($record->Level) !!}</td>
    <td>{!! $record->Name !!}</td>
    <td>{!! $record->Description !!}</td>
    {{--<td>{!! $record->ScheduleName !!}</td>--}}
    <?php
    $dDatePart = explode(" ", $record->StartTime);
    $tTimePart = explode(":", $dDatePart[1]);
    ?>
    <td class="text-center">{!! $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1] !!}</td>

    <?php
        if(!empty($record->next_runtime)){
            $dDatePart = explode(" ", $record->next_runtime);
            $tTimePart = explode(":", $dDatePart[1]);
            $next_runtime = $dDatePart[0] . ' ' . $tTimePart[0] . ':' . $tTimePart[1];
        }else{
            $next_runtime = '';
        }
    ?>
    <td class="text-center">
        {!! $next_runtime !!}
    </td>
    <td class="text-center">{!! $record->FTP !!}</td>
    <td class="text-center">{!! $record->is_public !!}</td>

    <?php
    $sSql = "SELECT count(*) as cnt FROM UL_RepCmp_Share WHERE User_id = '".$uid."' AND camp_tmpl_id = '".$record->row_id."' AND t_type = 'C'";
    $sData = DB::select($sSql);
    $sData = collect($sData)->map(function($x){ return (array) $x; })->toArray();
    ?>
    <td class="text-center">{!! $sData[0]['cnt'] > 0 ? 'Y' : 'N' !!}</td>
    <td class="text-center">{!! $record->Custom_SQL !!}</td>
    {{--<td><a class="ajax-Link" href="/report/delete/{!! $record->Row_id !!}"><i class="fas fa-trash font-14"></i></a> </td>--}}
    <td class="text-center">
        <select  onchange='show_Create_library($(this))' class='form-control-sm' style="border-color: #bfe6f6;text-align-last: center;">
            <option value='0'>Select</option>
            <option value='view,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>View</option>
            <option value='delete,{!! $record->row_id !!},"{!! $record->Name !!}",{!! $record->t_id !!}'>Delete</option>
        </select>
    </td>
</tr>
