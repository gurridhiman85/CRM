<tr>
    @foreach($visible_columns as $visible_column)
        <td
            class="{!! $visible_column['Class_Name'] !!}"
            @if($visible_column['Field_Visibility'] == 1)
                data-visible="false"
            @endif>
                {!! isset($record[$visible_column['Field_Name']] ) ? $record[$visible_column['Field_Name']]  : '' !!}
        </td>
    @endforeach
</tr>
