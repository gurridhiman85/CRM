<?php
$js_files = [
    'popper' => [
        'path' => '/assets/node_modules/popper/popper.min.js',
        'required' => 0
    ],
    'bootstrap' => [
        'path' => '/assets/node_modules/bootstrap/dist/js/bootstrap.min.js',
        'required' => 0
    ],
    'perfect-scrollbar' => [
        'path' => '/js/perfect-scrollbar.jquery.min.js',
        'required' => 0
    ],
    'waves' => [
        'path' => '/js/waves.js',
        'required' => 0
    ],
    'sidebarmenu' => [
        'path' => '/js/sidebarmenu.js',
        'required' => 0
    ],
    'custom' => [
        'path' => '/js/custom-horizontal.min.js',
        'required' => 0
    ],
    'skycons' => [
        'path' => '/assets/node_modules/skycons/skycons.js',
        'required' => 0
    ],
    'raphael' => [
        'path' => '/assets/node_modules/raphael/raphael-min.js',
        'required' => 0
    ],
    'morris' => [
        'path' => '/assets/node_modules/morrisjs/morris.min.js',
        'required' => 0
    ],
    'sparkline' => [
        'path' => '/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js',
        'required' => 0
    ],
    'toast' => [
        'path' => '/assets/node_modules/toast-master/js/jquery.toast.js',
        'required' => 0
    ],
    'dashboard1' => [
        'path' => '/js/dashboard1.js',
        'required' => 0
    ],
    'dashboard4' => [
        'path' => '/js/dashboard4.js',
        'required' => 0
    ],
    'nprogress' => [
        'path' => '/assets/bower_components/nprogress/nprogress.js',
        'required' => 1
    ],
    'customjs' => [
        'path' => '/elite/js/custom.js',
        'required' => 0
    ],
    'sticky' => [
        'path' => '/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js',
        'required' => 0
    ],
    'dataTables' => [
        'path' => '/assets/node_modules/datatables.net/js/jquery.dataTables.min.js',
        'required' => 0
    ],
    'dataTables-fixed-columns' => [
        'path' => '/assets/node_modules/datatables.net-bs4/js/dataTables.fixedColumns.min.js',
        'required' => 0
    ],
    'dataTables-buttons' => [
        'path' => '/assets/node_modules/datatables.net-bs4/js/dataTables.buttons.min.js',
        'required' => 0
    ],
    'dataTables-colVis' => [
        'path' => '/assets/node_modules/datatables.net-bs4/js/buttons.colVis.min.js',
        'required' => 0
    ],

    'responsive.dataTables' => [
        'path' => '/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js',
        'required' => 0
    ],
    'jasny-bootstrap' => [
        'path' => '/js/pages/jasny-bootstrap.js',
        'required' => 0
    ],


    'switchery' => [
        'path' => '/assets/node_modules/switchery/dist/switchery.min.js',
        'required' => 0
    ],
    'select2' => [
        'path' => '/assets/node_modules/select2/dist/js/select2.full.min.js',
        'required' => 0
    ],
    'bootstrap-select' => [
        'path' => '/assets/node_modules/bootstrap-select/bootstrap-select.min.js',
        'required' => 0
    ],
    'tagsinput' => [
        'path' => '/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js',
        'required' => 0
    ],
    'touchspin' => [
        'path' => '/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js',
        'required' => 0
    ],
    'dff' => [
        'path' => '/assets/node_modules/dff/dff.js',
        'required' => 0
    ],
    'multiselect-jquery-ui' => [
        'path' => '/assets/bower_components/multiselect/js/jquery-ui.min.js',
        'required' => 0,
        'depends' => [
            '/assets/bower_components/multiselect/js/jquery.multiselect.js',
            '/assets/bower_components/multiselect/js/jquery.multiselect.filter.js'
        ]
    ],
    'multiselect' => [
        'path' => '/assets/bower_components/multiselect/js/jquery.multiselect.js',
        'required' => 0,
        'depends' => [
            '/assets/bower_components/multiselect/js/jquery.multiselect.js',
            '/assets/bower_components/multiselect/js/jquery.multiselect.filter.js'
        ]
    ],
    'multiselect-filter' => [
        'path' => '/assets/bower_components/multiselect/js/jquery.multiselect.filter.js',
        'required' => 0,
        'depends' => [
            '/assets/bower_components/multiselect/js/jquery.multiselect.js',
            '/assets/bower_components/multiselect/js/jquery.multiselect.filter.js'
        ]
    ],

    'sweetalert' => [
        'path' => '/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js',
        'required' => 0,
    ],
    'sweet-alert.init' => [
        'path' => '/assets/node_modules/sweetalert2/sweet-alert.init.js',
        'required' => 0,
        'depends' => ['sweetalert']
    ],
    'inputmask' => [
        'path' => '/assets/node_modules/inputmask/dist/min/jquery.inputmask.bundle.min.js',
        'required' => 0,
        'depends' => ['mask']
    ],
    'mask' => [
        'path' => '/js/pages/mask.init.js',
        'required' => 0
    ],
    'dropify' => [
        'path' => '/assets/node_modules/dropify/dist/js/dropify.min.js',
        'required' => 0
    ],
    'moment' => [
        'path' => '/assets/node_modules/moment/moment.js',
        'required' => 0
    ],
    'datetimepicker' => [
        'path' => '/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
        'required' => 0
    ],
    'clockpicker' => [
        'path' => '/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js',
        'required' => 0
    ],
    'bootstrap-datepicker' => [
        'path' => '/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js',
        'required' => 0
    ],
    'bootstrap-timepicker' => [
        'path' => '/assets/node_modules/timepicker/bootstrap-timepicker.min.js',
        'required' => 0
    ],
    'daterangepicker' => [
        'path' => '/assets/node_modules/bootstrap-daterangepicker/daterangepicker.js',
        'required' => 0
    ],
    'register-steps' => [
        'path' => '/assets/node_modules/register-steps/jquery.easing.min.js',
        'required' => 0,
        'depends' => ['register-init']
    ],
    'register-init' => [
        'path' => '/assets/node_modules/register-steps/register-init.js',
        'required' => 0
    ],
    'wizard' => [
        'path' => '/assets/node_modules/wizard/jquery.steps.min.js',
        'required' => 0,
        'depends' => ['validate']
    ],
    'validate' => [
        'path' => '/assets/node_modules/wizard/jquery.validate.min.js',
        'required' => 0
    ],
    'init' => [
        'path' => '/js/init.js',
        'required' => 1
    ],
    'ajax' => [
        'path' => '/js/ajax.js',
        'required' => 1
    ],
    'callbackFN' => [
        'path' => '/js/callback_fn.js',
        'required' => 1
    ],
    'AReport' => [
        'path' => '/js/report.js',
        'required' => 0
    ],
    'campaign' => [
        'path' => '/js/campaign.js',
        'required' => 0
    ],
    'model' => [
        'path' => '/js/model.js',
        'required' => 0
    ],
    'profile' => [
        'path' => '/js/profile.js',
        'required' => 0
    ],
    'segment' => [
        'path' => '/js/segment.js',
        'required' => 0
    ],
    'jstree' => [
        'path' => '/assets/bower_components/jstree/dist/jstree.min.js',
        'required' => 0
    ],
    'contextMenu' => [
        'path' => '/assets/bower_components/jQuery-contextMenu/dist/jquery.contextMenu.min.js',
        'required' => 0
    ],
    'Split' => [
        'path' => '/elite/plugins/bower_components/Split.js/split.min.js',
        'required' => 0
    ],
    'dropzone' => [
        'path' => '/assets/node_modules/dropzone-master/dist/dropzone.js',
        'required' => 0
    ],
    'fileinput' => [
        'path' => '/assets/bower_components/bootstrap-fileinput/js/fileinput.min.js',
        'required' => 0
    ],
    'fancybox' => [
        'path' => '/assets/bower_components/fancybox/dist/jquery.fancybox.min.js',
        'required' => 0
    ],
    'chosen' => [
        'path' => '/assets/bower_components/chosen/chosen.jquery.js',
        'required' => 0
    ],
    'jq-dt-editable' => [
        'path' => '/assets/node_modules/jquery-datatables-editable/jquery.dataTables.js',
        'required' => 0
    ],
    'tiny-editable-mindmup' => [
        'path' => '/assets/node_modules/tiny-editable/mindmup-editabletable.js',
        'required' => 0
    ],
    'tiny-editable-numeric' => [
        'path' => '/assets/node_modules/tiny-editable/numeric-input-example.js',
        'required' => 0
    ],
    'importcontact' => [
        'path' => '/js/importcontact.js',
        'required' => 0
    ],
    'importbulkcc' => [
        'path' => '/js/importbulkcc.js',
        'required' => 0
    ],
    'importzoom' => [
        'path' => '/js/importzoom.js',
        'required' => 0
    ],
    'zoomcleanse' => [
        'path' => '/js/zoomcleanse.js',
        'required' => 0
    ],

    'typeahead' => [
        'path' => '/assets/node_modules/typeahead.js-master/dist/typeahead.bundle.min.js',
        'required' => 0,

    ],
    /*'typeahead' => [
        'path' => '/assets/node_modules/typeahead.js-master/typeahead.init.js',
        'required' => 0,
        'depends' => [
            'typeahead.bundle'
        ]
    ],*/
];

\App\Library\AssetLib::echoJsFiles($js_files);
