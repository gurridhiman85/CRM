<table id="yajra-table" class="table table-bordered table-hover color-table lkp-table no-wrap" data-columns-visible="" style="width:100%">
    <thead>
        <tr>

            <th class="text-center">Call</th>
            <th class="text-center" style="width:16% !important;">Status</th>
            <th>Campaign</th>
            <th>Contact</th> <!--data-visible="false"-->
            <th>HH ID</th>
            <th>Extended Name</th>
            <th>Phone</th>
            <th data-visible="false">Email</th>

            <th data-visible="false">Email2</th>
            <th data-visible="false">Address</th>
            <th data-visible="false">City</th>
            <th data-visible="false">State</th>
            <th data-visible="false">Zip</th>
            <th data-visible="false">Company</th>
            <th data-visible="false">Updated</th>
            <th>ZSS_Segment</th>
            <th>3-yr Gifts</th>
            <th>BH Gifts</th>
            <th>This Yr Gifts $</th>
            <th>Last 2Yrs Gifts $</th>
            <th>Life Gifts $</th>
            <th>Last Visit-Days</th>
            <th>Email Segment</th>
            <th style="width: 30% !important;">Comments</th>
        </tr>
    </thead>

</table>
<style>
    option.badge {
        text-align: left;
    }
</style>
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
            "{!! route('phonefirstscreen.data') !!}",                                      //url
            'POST',                                                                         //type
            {                                                                               //data
                lookupClause : "{!! $lookupClause !!}",
                urCon : "{!! $urCon !!}",
                phoneClause : "{!! $phoneClause !!}",
                salesClause : "{!! $salesClause !!}",
                sWhere1 : "{!! $sWhere1 !!}",
                sort_column : "{!! $sort_column !!}",
                sort_dir : "{!! $sort_dir !!}",
            },
            "JSON",                                                                         //dataType
            [                                                                               //columns
                { data: 'Call', name: 'Call', sortable : false},
                { data: 'Status', name: 'Status'},
                { data: 'TouchCampaign', name: 'TouchCampaign', sortable : true },
                { data: 'DS_MKC_ContactID', name: 'DS_MKC_ContactID', sortable : true },
                { data: 'DS_MKC_HouseholdID', name: 'DS_MKC_HouseholdID' },
                { data: 'Extendedname', name: 'Extendedname' },
                { data: 'phone', name: 'phone' },
                { data: 'Email', name: 'Email' },
                { data: 'email2', name: 'email2' },
                { data: 'Address', name: 'Address' },
                { data: 'City', name: 'City' },
                { data: 'State', name: 'State' },
                { data: 'Zip', name: 'Zip' },
                { data: 'Company', name: 'Company' },
                { data: 'update_date', name: 'update_date' },
                { data: 'ZSS_Segment', name: 'ZSS_Segment' },
                { data: 'Last_3Yrs_GiftsAmt', name: 'Last_3Yrs_GiftsAmt' },
                { data: 'Life_BHse_GiftsAmt', name: 'Life_BHse_GiftsAmt' },
                { data: 'CurrentYr_DonorAmt', name: 'CurrentYr_DonorAmt' },
                { data: 'Last_2Yrs_DonorAmt', name: 'Last_2Yrs_DonorAmt' },
                { data: 'Life2date_donoramt', name: 'Life2date_donoramt' },
                { data: 'dayssincelastvisit', name: 'dayssincelastvisit' },
                { data: 'EmailSegment', name: 'EmailSegment' },
                { data: 'TouchNotes', name: 'TouchNotes' },
            ],
            [                                                                               //columnDefs
                {
                    "targets": 0,
                    "className": "text-center Call",
                    "bSortable": true
                },
                {
                    "targets": 1,
                    "className": "text-left Status",
                    "bSortable": false
                },
                {
                    "targets": 2,
                    "className": "text-center TouchCampaign",
                    "bSortable": true
                },
                {
                    "targets": 3,
                    "className": "text-center DS_MKC_ContactID",
                    "bSortable": true
                },
                {
                    "targets": 4,
                    "className": "text-center DS_MKC_HouseholdID",
                    "bSortable": true
                },
                {
                    "targets": 5,
                    "className": "text-left Extendedname",
                    "bSortable": true
                },
                {
                    "targets": 6,
                    "className": "text-center phone",
                    "bSortable": true
                },
                {
                    "targets": 7,
                    "className": "text-center Email",
                    "bSortable": true
                },
                {
                    "targets": 8,
                    "className": "text-center email2",
                    "bSortable": true
                },
                {
                    "targets": 9,
                    "className": "text-center Address",
                    "bSortable": true
                },
                {
                    "targets": 10,
                    "className": "text-center City",
                    "bSortable": true
                },
                {
                    "targets": 11,
                    "className": "text-center State",
                    "bSortable": true
                },
                {
                    "targets": 12,
                    "className": "text-center Zip",
                    "bSortable": true
                },
                {
                    "targets": 13,
                    "className": "text-center Company",
                    "bSortable": true
                },
                {
                    "targets": 14,
                    "className": "text-center update_date",
                    "bSortable": true
                },
                {
                    "targets": 15,
                    "className": "text-center ZSS_Segment",
                    "bSortable": true
                },
                {
                    "targets": 16,
                    "className": "text-right pr-3 Last_3Yrs_GiftsAmt",
                    "bSortable": true
                },
                {
                    "targets": 17,
                    "className": "text-right pr-3 Life_BHse_GiftsAmt",
                    "bSortable": false
                },
                {
                    "targets": 18,
                    "className": "text-right pr-3 CurrentYr_DonorAmt",
                    "bSortable": false
                },
                {
                    "targets": 19,
                    "className": "text-right pr-3 Last_2Yrs_DonorAmt",
                    "bSortable": false
                },
                {
                    "targets": 20,
                    "className": "text-right pr-3 Life2date_donoramt",
                    "bSortable": false
                },
                {
                    "targets": 21,
                    "className": "text-right pr-3 dayssincelastvisit",
                    "bSortable": false
                },
                {
                    "targets": 22,
                    "className": "text-center EmailSegment",
                    "bSortable": true
                },
                {
                    "targets": 23,
                    "className": "text-center TouchNotes",
                    "bSortable": true
                },
            ]);

    });
</script>
