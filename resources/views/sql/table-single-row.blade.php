
<tr>
    <td>{!! $record->ScheduleName !!}</td>
    <td>{!! $record->TemplateName !!}</td>
    <td>{!! $record->StartTime !!}</td>
    <td>{!! $record->FTP !!}</td>
    <td>{!! $record->FileName !!}</td>
    <td><a class="ajax-Link" href="/delete/{!! $record->Row_id !!}"><i class="fas fa-trash font-14"></i></a> </td>
</tr>