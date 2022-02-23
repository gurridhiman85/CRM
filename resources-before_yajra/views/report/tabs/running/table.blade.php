<table id="yajra-table" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No reports available">
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
</table>

<script>
    $(function() {
        yajraDatatables($('#yajra-table'),                                              //element
            {                                                                               //params
                processing: true,
                serverSide: true,
                searching:  false,
                paging: true,
                lengthChange: false,
                pageLength: 15,
            },
            "{!! route('report_running.data') !!}",                                      //url
            'POST',                                                                         //type
            {                                                                               //data
                sort_column : "{!! $sort_column !!}",
                sort_dir : "{!! $sort_dir !!}",
            },
            "JSON",                                                                         //dataType
            [                                                                               //columns
                { data: 't_id', name: 't_id'},
                { data: 'list_level', name: 'list_level'},
                { data: 'list_short_name', name: 'list_short_name'},
                { data: 'Description', name: 'Description'},
                { data: 'StartTime', name: 'StartTime'},
                { data: 'next_runtime', name: 'next_runtime'},
                { data: 'FTP', name: 'FTP'},
                { data: 'is_public', name: 'is_public'},
                { data: 'is_share', name: 'is_share'},
                { data: 'Custom_SQL', name: 'Custom_SQL'},
                { data: 'action', name: 'action'},

            ],
            [                                                                               //columnDefs
                {
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "className": "text-center"
                },
                {
                    "targets": 4,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-center",
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "className": "text-center",
                },
                {
                    "targets": 8,
                    "className": "text-center",
                },
                {
                    "targets": 9,
                    "className": "text-center",
                },
                {
                    "targets": 10,
                    "className": "text-center",
                }
            ],
            [0, "desc" ]);

    });
</script>
