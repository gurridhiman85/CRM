<table id="basic_table" class="table table-bordered table-hover color-table lkp-table no-wrap">
    <thead>
        <tr>
            <th>Contact ID</th>
            <th>HH ID</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Class</th>
            <th>Activity Cat 1</th>
            <th>Activity Cat 2</th>
            <th>Activity</th>
            <th>Memo</th>
            <th>Account</th>
            <th>Client Message</th>
            <th>Customer</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('lookup.Sales-Detail.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>
