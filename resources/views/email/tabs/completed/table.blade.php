<table id="yajra-table" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
        <tr>
            {{--<th class="text-center">#</th>--}}
            <th>Campaign ID</th>
            <th>Campaign Name</th>
            <th>Template</th>
            <th class="text-center">Time</th>
            <th class="text-center">StartDate</th>
            <th class="text-center">EndDate</th>
            <th>Subject1</th>
            <th>Subject2</th>
            <th>Subject3</th>
            <th>Test Subject</th>
            <th>CampaignID_Data</th>
            <th>TestSubjectPct</th>
            <th>SubjectWin</th>
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
            "{!! route('email_completed.data') !!}",                                      //url
            'POST',                                                                         //type
            {                                                                               //data
                sort_column : "{!! $sort_column !!}",
                sort_dir : "{!! $sort_dir !!}",
            },
            "JSON",                                                                         //dataType
            [                                                                               //columns
                { data: 'CampaignId', name: 'CampaignId'},
                { data: 'CampaignName', name: 'CampaignName'},
                { data: 'Template', name: 'Template'},
                { data: 'Time1', name: 'Time1'},
                { data: 'StartDate', name: 'StartDate'},
                { data: 'EndDate', name: 'EndDate'},
                { data: 'Subject1', name: 'Subject1'},
                { data: 'Subject2', name: 'Subject2'},
                { data: 'Subject3', name: 'Subject3'},
                { data: 'TestSubject', name: 'TestSubject'},
                { data: 'CampaignID_Data', name: 'CampaignID_Data'},
                { data: 'TestSubjectPct', name: 'TestSubjectPct'},
                { data: 'SubjectWin', name: 'SubjectWin'}
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
                    "targets": 3,
                    "className": "text-center",
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
                },
                {
                    "targets": 11,
                    "className": "text-center",
                },
                {
                    "targets": 12,
                    "className": "text-center",
                }
            ],
            [0, "desc" ]);

    });
</script>
