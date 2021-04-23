<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table no-wrap" data-columns-visible="">
    <thead>
        <tr>
            <th>Tag</th>
            <th>Merge</th>
            <th>Contact</th>
            <th>HH ID</th>
            <th>Extended Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Email Segment</th>
            <th>Email2</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Company</th>
            <th>Updated</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('lookup.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>
