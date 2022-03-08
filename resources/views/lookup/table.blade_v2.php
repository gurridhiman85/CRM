<table id="basic_table_without_dynamic_pagination" class="table table-bordered table-hover color-table lkp-table no-wrap" data-columns-visible="" style="width:100%">
    <thead>
        <tr>
            <th>Tag</th>
            <th>Merge</th>
            <th>Contact</th>
            <th>HH ID</th>
            <th>Extended Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th data-visible="false">Emailname</th>
            <th>Email Segment</th>
            <th>Email2</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Zip</th>
            <th>Company</th>
            <th>Updated</th>
            <th>Created</th>
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
        "{!! route('lookupfirstscreen.data') !!}",                                      //url
        'POST',                                                                         //type
        {                                                                               //data
            sql : "{!! $sql !!}",
            finalClause : "{!! $finalClause !!}",
            sWhere1 : "{!! $sWhere1 !!}",
            sort_column : "{!! $sort_column !!}",
            sort_dir : "{!! $sort_dir !!}",
        },
        "JSON",                                                                         //dataType
        [                                                                               //columns
            { data: 'Tag', name: 'Tag', sortable : false},
            { data: 'Merge', name: 'Merge', sortable : false},
            { data: 'DS_MKC_ContactID', name: 'DS_MKC_ContactID', sortable : true },
            { data: 'DS_MKC_HouseholdID', name: 'DS_MKC_HouseholdID' },
            { data: 'Extendedname', name: 'Extendedname' },
            { data: 'phone', name: 'phone' },
            { data: 'Email', name: 'Email' },
            { data: 'Emailname', name: 'Emailname' },
            { data: 'EmailSegment', name: 'EmailSegment' },
            { data: 'email2', name: 'email2' },
            { data: 'Address', name: 'Address' },
            { data: 'City', name: 'City' },
            { data: 'State', name: 'State' },
            { data: 'Zip', name: 'Zip' },
            { data: 'Company', name: 'Company' },
            { data: 'update_date', name: 'update_date' },
            { data: 'create_date', name: 'create_date' },
        ],
        [                                                                               //columnDefs
            {
                "targets": 0,
                "className": "text-center",
                "bSortable": false
            },
            {
                "targets": 1,
                "className": "text-center",
                "bSortable": false
            }
        ]);

    });
</script>
