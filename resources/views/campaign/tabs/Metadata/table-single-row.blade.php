<tr>
        @php
                $key = array_search('1', array_column($visible_columns, 'Primary_Column'));
                $primary_column = $visible_columns[$key]['Field_Name'];
        @endphp
        @foreach($visible_columns as $visible_column)
                <td
                        class="{!! $visible_column['Class_Name'] !!}"
                        @if($visible_column['Field_Visibility'] == 1)
                        data-visible="false"
                        @endif>
                        @if($visible_column['Editable'] == 1)

                                <input type="text"
                                       class="form-control border-0 form-control-sm "
                                       style="width: 300px;"
                                       onkeyup="ajax_field_update($(this))"
                                       data-field="{{ $visible_column['Field_Name'] }}"
                                       data-primary_column="{{ $primary_column }}"
                                       data-primary_column_value="{{ $record[$visible_columns[$key]['Field_Name']] }}"
                                       value="{{ $record[$visible_column['Field_Name']] }}">
                        @elseif($visible_column['Editable'] == 2)

                                <div class="ui-widget">
                                        <input class="form-control form-control-sm" data-primary_column="{{ $primary_column }}" id="{{ $record[$visible_columns[$key]['Field_Name']] }}" data-field="{{ $visible_column['Field_Name'] }}" type="text" value="{{ $record[$visible_column['Field_Name']] }}" onkeyup="addAutoComplete($(this))">
                                </div>
                        @else
                                {!! isset($record[$visible_column['Field_Name']] ) ? $record[$visible_column['Field_Name']]  : '' !!}
                        @endif
                </td>
        @endforeach
</tr>
