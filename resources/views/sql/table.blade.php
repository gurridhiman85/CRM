<table id="basic_table2" class="table table-bordered table-hover color-table lkp-table">  
    <thead>
        <tr>
            <th>Schedule Name</th>
            <th>Template Name</th>
            <th>Start Time</th>
            <th>FTP</th>
            <th>File Name</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        @if(count($records) > 0)
            @foreach($records as $record)
                @include('sql.table-single-row')
            @endforeach
        @endif
    </tbody>
</table>