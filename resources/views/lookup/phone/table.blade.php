<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table no-wrap" data-columns-visible="">
    <thead>
        <tr>

            <th class="text-center">Call</th>
            <th class="text-center" style="width:16% !important;">Status</th>
            <th >Campaign</th>
            <th>3-yr Gifts</th>
            <th>BH Gifts</th>
            <th data-visible="false">Contact</th>
            <th>HH ID</th>
            <th>Extended Name</th>
            <th>Phone</th>
            <th data-visible="false">Email</th>
            <th>Email Segment</th>
            <th data-visible="false">Email2</th>
            <th data-visible="false">Address</th>
            <th data-visible="false">City</th>
            <th data-visible="false">State</th>
            <th data-visible="false">Zip</th>
            <th data-visible="false">Company</th>
            <th data-visible="false">Updated</th>
            <th>ZSS_Segment</th>
            <th style="width: 30% !important;">Comments</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('lookup.phone.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>
