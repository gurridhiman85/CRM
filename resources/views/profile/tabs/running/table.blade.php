<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No reports available">
    <thead>
        <tr>
            <th>ID</th>
            <th>Level</th>
            <th>Name</th>
            <th>Description</th>
            {{--<th>Schedule Name</th>--}}
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
    @foreach($records as $record)
        @include('profile.tabs.running.table-single-row')
    @endforeach
    </tbody>
</table>
