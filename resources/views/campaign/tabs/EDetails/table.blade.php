<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
    <!--
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Segment ID</th>
            <th>Sub Campaign Description</th>
            <th>Universe</th>
            <th class="text-right green-text">Total Profit</th>
            <th class="text-right green-text">Total ROI</th>
            <th class="text-right green-text">Total Resp Rate</th>
            <th class="text-right orange-text">Category Profit</th>
            <th class="text-right orange-text">Category ROI</th>
            <th class="text-right orange-text">Category Resp Rate</th>
            <th class="text-right grey-text">Coupon Redemption</th>
            <th class="text-right grey-text">Coupon Redeemers</th>
            <th class="grey-text">Promoted Redeemers</th>
            <th class="grey-text">New Redeemers</th>
            <th class="grey-text">Pass Along Redeemers</th>
            <th class="text-right yellow-text">Open Rate</th>
            <th class="text-right yellow-text">Click Rate</th>
            <th>Objective</th>
            <th class="text-center">Brand</th>
            <th class="text-center">Channel</th>
            <th class="text-center">Offer Category</th>
        </tr>
        -->
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
        @include('campaign.tabs.EDetails.table-single-row')
    @endforeach
    </tbody>
</table>
