<style>


    .space {
        height: 8px;
    }

    .checkbox * {
        box-sizing: border-box;
        position: relative;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .checkbox {
        display: inline-block;
    }

    .checkbox > input {
        display: none;
    }

    .checkbox > label {
        vertical-align: middle;
        font-size: 18px;
        padding-left: 10px;
    }

    .checkbox > [type="checkbox"] + label:before {
        color: #777;
        content: '';
        position: absolute;
        left: 0px;
        display: inline-block;
        min-height: 15px;
        height: 15px;
        width: 15px;
        border: 1px solid #d0d0d0;
        font-size: 15px;
        vertical-align: middle;
        text-align: center;
        transition: all 0.2s ease-in;
        content: '';
        /*top: 4px;*/
    }

    .checkbox.radio-square > [type="checkbox"] + label:before {
        border-radius: 0px;
    }

    .checkbox.radio-rounded > [type="checkbox"] + label:before {
        border-radius: 25%;
    }

    .checkbox.radio-blue > [type="checkbox"] + label:before {
        border: 2px solid #ccc;
    }

    /*.checkbox > [type="checkbox"] + label:hover:before {
        border-color: lightgreen;
    }*/

    .checkbox > [type="checkbox"]:checked + label:before {
        width: 7px;
        height: 7px;
        border-top: transparent;
        border-left: transparent;
        border-color: #e92639;
        border-width: 2px;
        transform: rotate(45deg);
        /*top: 4px;*/
        left: 4px;
        margin-bottom: 16px;
    }

</style>
<table id="yajra-table" class="table table-bordered table-hover color-table lkp-table" style="width: 100%;" data-message="No campaign available">
    <thead>
    <tr>
        <th>Tag</th>
        <th>ID</th>
        <th>Level</th>
        <th>Name</th>
        <th>Description</th>
        <th class="text-center">Last Run</th>
        <th class="text-center">Time</th>
        <th class="text-center">FTP</th>
        <th class="text-center">Public</th>
        <th class="text-center">Share</th>
        <th class="text-center">Code</th>
        <th class="text-center">Records</th>
        <th class="text-center">List <i class="fas fa-file-excel" style = "color: #06b489;" ></i ></th>
        <th class="text-center">List <i class="fas fa-file-pdf" style="color: #e92639;" ></i ></th>
        <th class="text-center">Ver</th>
        <th class="text-center">Rpt <i class="fas fa-file-excel" style = "color: #06b489;" ></i ></th>
        <th class="text-center">Rpt <i class="fas fa-file-pdf" style="color: #e92639;" ></i ></th>
        <th class="text-center">Run</th>
        {{-- <th class="text-center">Int</th>--}}
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
            "{!! route('campaign_completed.data') !!}",                                      //url
            'POST',                                                                         //type
            {                                                                               //data
                sort_column : "{!! $sort_column !!}",
                sort_dir : "{!! $sort_dir !!}",
            },
            "JSON",                                                                         //dataType
            [
                { data: 'Tag', name: 'Tag',sortable : false}, //columns
                { data: 't_id', name: 't_id',sortable : false},
                { data: 'list_level', name: 'list_level'},
                { data: 'list_short_name', name: 'list_short_name'},
                { data: 'Description', name: 'Description'},
                { data: 'StartTime', name: 'StartTime'},
                { data: 'RunTime', name: 'RunTime'},
                { data: 'FTP', name: 'FTP'},
                { data: 'is_public', name: 'is_public'},
                { data: 'is_share', name: 'is_share'},
                { data: 'Custom_SQL', name: 'Custom_SQL'},
                { data: 'total_records', name: 'total_records'},
                { data: 'listXLSX', name: 'listXLSX'},
                { data: 'listPDF', name: 'listPDF'},
                { data: 'ver', name: 'ver'},
                { data: 'SummaryXLSX', name: 'SummaryXLSX'},
                { data: 'SummaryPDF', name: 'SummaryPDF'},
                { data: 'run', name: 'run'},
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
                    "targets": 5,
                    "className": "text-center"
                },
                {
                    "targets": 6,
                    "className": "text-right pr-3",
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
                    "className": "text-right pr-3",
                },
                {
                    "targets": 12,
                    "className": "text-center",
                },
                {
                    "targets": 13,
                    "className": "text-center",
                },
                {
                    "targets": 14,
                    "className": "text-center",
                },
                {
                    "targets": 15,
                    "className": "text-center",
                },
                {
                    "targets": 16,
                    "className": "text-center",
                },
                {
                    "targets": 17,
                    "className": "text-center",
                }
            ]);

    });
</script>

