<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" data-message="No campaign available">
    <thead>
    <tr>
        <th>ID</th>
        <th>Level</th>
        <th>Name</th>
        <th>Description</th>
        <th>Frequency</th>
        <th class="text-center">Start Time</th>
        <th class="text-center">Next RunTime</th>
        <th class="text-center">FTP</th>
        <th class="text-center">Public</th>
        <th class="text-center">Share</th>
        <th class="text-center">Code</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(count($records) > 0)
        @foreach($records as $record)
            @include('campaign.tabs.scheduled.table-single-row')
        @endforeach
    @endif
    </tbody>
</table>
