<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
        <tr>
            {{--<th class="text-center">#</th>--}}
            <th>Productcat1</th>
            <th>Productcat1 Description</th>

        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            @include('taxonomy.tabs.level1.table-single-row')
        @endforeach
    </tbody>
</table>
