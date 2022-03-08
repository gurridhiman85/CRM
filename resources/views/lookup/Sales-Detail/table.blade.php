<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available" > <!-- data-order="[[1,'desc']]" -->
    <thead>
        <tr>
            @foreach($visible_columns as $visible_column)
                @if(in_array($visible_column['Field_Visibility'],[1,2]))
                    <th
                            class="{!! $visible_column['Class_Name'] !!}"
                            onclick="custom_sorting($(this));"
                            data-field_name="{!! $visible_column['Field_Name'] !!}"
                            @if($visible_column['Field_Visibility'] == 1)
                            data-visible="false"
                            @endif>
                        {!! $visible_column['Field_Display_Name'] !!}
                    </th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            @include('lookup.Sales-Detail.table-single-row')
        @endforeach
    </tbody>
</table>

