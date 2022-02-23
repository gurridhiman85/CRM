<table class="table table-bordered table-hover color-table lkp-table no-wrap">
    <thead>
        <tr>
            <th>DFL Name</th>
            <th>Campaign</th>
            <th>Status</th>
            <th>Channel</th>
            <th>Date</th>
            <th>Comment</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('lookup.phone.second_screen.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>
