<tr>
        @php
                $pkey = array_search('1', array_column($visible_columns, 'Primary_Column'));
                $primary_column = $visible_columns[$pkey]['Field_Name'];
        @endphp
        @foreach($visible_columns as $visible_column)
            @if(in_array($visible_column['Field_Visibility'],[1,2]))
                <td
                       data-href="lookup/secondscreen/{!! $record['DS_MKC_ContactID'] !!}"
                        class="{!! $visible_column['Class_Name'] !!} ajax-Link"
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
                            <input class="form-control form-control-sm" data-primary_column="{{ $primary_column }}"
                                   id="{{ $record[$visible_columns[$key]['Field_Name']] }}"
                                   data-field="{{ $visible_column['Field_Name'] }}" type="text"
                                   value="{{ $record[$visible_column['Field_Name']] }}" onkeyup="addAutoComplete($(this))">
                        </div>
                    @elseif($visible_column['Field_Name'] == 'tag')
                        @php $is_tag = $record['tag'] == 1 ? 'checked' : ''; @endphp
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input checkbox"
                                   onclick="reviewContact($(this),'{{ $record['DS_MKC_ContactID'] }}','tag');"
                                   {{ $is_tag }} value="1">
                            <span class="custom-control-label"></span>
                        </label>
                    @elseif($visible_column['Field_Name'] == 'merge')
                        <input type="checkbox" class="js-switch" onchange="singleClick($(this))" name="singlecheckbox"
                               data-color="#b7dee8" data-size="small" data-switchery="true" style="display: none;"
                               value="{!! $record['DS_MKC_ContactID'] !!}" <?= in_array($record['DS_MKC_ContactID'], $contactids) ? 'checked' : ''; ?> {!! in_array($record['DS_MKC_ContactID'], $mKeys) ? 'checked' : '' !!}>
                    @else
                        {!! isset($record[$visible_column['Field_Name']] ) ? $record[$visible_column['Field_Name']]  : '' !!}
                    @endif
                </td>
            @endif
        @endforeach
</tr>
