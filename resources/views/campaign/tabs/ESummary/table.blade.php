<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" data-message="No campaign available in completed">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th>Campaign Description</th>
            <th>Universe</th>
            <th class="text-right green-text">Total Incr. Profit</th>
            <th class="text-right green-text">Total ROI</th>
            <th class="text-right green-text">Total Incr. Resp Rate</th>
            <th class="text-right orange-text">Category Profit</th>
            <th class="text-center orange-text">Category ROI</th>
            <th class="text-center orange-text">Category Resp Rate</th>
            <th class="text-center grey-text">Redemption Rate</th>
            <th class="text-center grey-text">Total Redeemers</th>
            <th class="text-center yellow-text">Open Rate</th>
            <th class="text-center yellow-text">Click Rate</th>
            <th>Objective</th>
            <th class="text-center">Brand</th>
            <th class="text-center">Channel</th>
            <th class="text-center">Offer Category</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('campaign.tabs.ESummary.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>
