<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table no-wrap" data-order="[[ 0, &quot;desc&quot; ]]">
    <thead>
        <tr>
            <th class="text-center" style="width:16% !important;">Status</th>
            <th class="text-center">Call</th>
            <th>Campaign</th>
            <th>Contact</th> <!--data-visible="false"-->
            <th>HH ID</th>
            <th>Extended Name</th>
            <th>Phone</th>
            <th data-visible="false">Email</th>

            <th data-visible="false">Email2</th>
            <th data-visible="false">Address</th>
            <th data-visible="false">City</th>
            <th data-visible="false">State</th>
            <th data-visible="false">Zip</th>
            <th data-visible="false">Company</th>
            <th data-visible="false">Updated</th>
            <th>ZSS_Segment</th>
            <th>3-yr Gifts</th>
            <th>BH Gifts</th>
            <th>This Yr Gifts $</th>
            <th>Last 2Yrs Gifts $</th>
            <th>Life Gifts $</th>
            <th>Last Visit-Days</th>
            <th>Email Segment</th>
            <th style="width: 30% !important;">Comments</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
            @include('lookup.phone.table-single-row')
        @endforeach
    </tbody>

</table>
<style>
    option.badge {
        text-align: left;
    }
</style>
