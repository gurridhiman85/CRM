<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available" > <!-- data-order="[[1,'desc']]" -->
    <thead>
        <tr>
            @foreach($visible_columns as $visible_column)
                <th
                        class="{!! $visible_column['Class_Name'] !!}"
                        @if($visible_column['Field_Visibility'] == 1)
                        data-visible="false"
                        @endif>
                    {!! $visible_column['Field_Display_Name'] !!}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            @include('taxonomy.tabs.level.table-single-row')
        @endforeach
    </tbody>
</table>

