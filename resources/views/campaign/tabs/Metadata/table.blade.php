<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
        <tr>
            <th>CampaignID</th>
            <th>Objective</th>
            <th>Brand</th>
            <th>Channel</th>
            <th>Category</th>
            <th>List Description</th>
            <th>Wave</th>
            <th>Start Date</th>
            <th>Interval</th>
            <th>ProductCat1</th>
            <th>ProductCat2</th>
            <th>SKU</th>
            <th>Coupon</th>
            <th>Segment ID</th>
            <th>Segment Description</th>
            <th>GroupID</th>
            <th>Group Description</th>
            <th>Summary ID</th>
            <th>Cost</th>
            <th>Quantity</th>
            <th>File Name</th>
            <th>DS Analysis</th>
            <th>End Date</th>
            <th>Campaign Description</th>

        </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        @include('campaign.tabs.Metadata.table-single-row')
    @endforeach
    </tbody>
</table>
