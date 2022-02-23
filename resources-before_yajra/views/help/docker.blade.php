<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>{!! config('constant.title') !!}</title>

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    @section('head_css')
        @include('assetlib.head_css')
    @show

    @section('head_js')
        @include('assetlib.head_js')
    @show
    <?php
    \App\Library\AssetLib::library('popper','bootstrap','perfect-scrollbar','waves','sidebarmenu','custom','raphael','morris','sparkline','toast','file-upload','jasny-bootstrap','datepicker','select2','switchery','bootstrap-select','tagsinput','touchspin','dff','inputmask','sweetalert','sweet-alert.init','dropify','moment','datetimepicker','clockpicker','bootstrap-datepicker','bootstrap-timepicker','daterangepicker','jstree','contextMenu','Split','dropzone','fileinput','tabs', 'sticky', 'dataTables.bootstrap4', 'dataTables', 'responsive.dataTables','dataTables-fixed-columns','dataTables-buttons','dataTables-colVis','user-card','chosen','tooltip','ui-bootstrap','ribbon','multiselect','multiselect-filter','style_multiselect','multiselect-jquery-ui');
    ?>
    @section('css')
        @include('assetlib.css')
    @show
</head>
<body class="fixed-layout mini-sidebar {!! !empty(Auth::user()->utheme) ? Auth::user()->utheme->meta_value : 'skin-blue' !!} ">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{!! config('constant.title') !!}</p>
    </div>
</div>

<div id="main-wrapper">
    @section('docker-topnav')
        @include('layouts.docker-topnav')
    @show


    @section('docker-leftsidebar')
        @include('layouts.docker-leftsidebar')
    @show

    <div class="page-wrapper">
        @section('content')
            @yield('content')
        @show
    </div>

    @section('docker-footer')
        @include('layouts.docker-footer')
    @show
</div>
@section('footer_js')
    @include('assetlib.js')
@show
<style>
    .sttabs nav {
        text-align: center;
    }
    .top-doc .sttabs nav ul {
        justify-content: flex-start;
    }
    .sttabs nav ul {
        position: relative;
        display: flex;
        margin: 0 auto;
        padding: 0;
        font-family: 'open sans', sans-serif;
        list-style: none;
        flex-flow: row wrap;
    }

    .top-doc .sttabs nav ul li {
        flex: none;
        margin-left: -1px;
    }

    .sttabs nav ul li {
        position: relative;
        z-index: 1;
        display: block;
        margin: 0;
        text-align: center;
    }

    nav.js-class-change-nav ul li a.active {
        background: #e4e7ea;
    }

    .top-doc .sttabs nav a {
        font-weight: 500;
        color: #54667a;
        line-height: 22px;
    }

    .sttabs nav a {
        position: relative;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<script type="text/javascript">
    var jstree_my_files_div = null;
    (function() {
        jstree_my_files_div = $('#jstree_my_files_div').jstree({
            'core' : {
                'data' : [
                    {
                        'text' : 'User Guide',
                        'state' : {
                            'opened' : true,
                            'selected' : true
                        },
                        'children' : [
                            {'text' : 'CRM Square User Guide' },
                        ]
                    }
                ]
            }
            /*'core' : {
                'data' : [
                    { "id" : "ajson1", "parent" : "#", "text" : "User Guide" },
                    { "id" : "ajson1", "parent" : "ajson1", "text" : "CRM Square User Guide" },

                    { "id" : "ajson2", "parent" : "#", "text" : "GMD Metadata" },
                    { "id" : "ajson3", "parent" : "ajson2", "text" : "Source Feeds" },
                    { "id" : "ajson4", "parent" : "ajson2", "text" : "Architecture" },
                    { "id" : "ajson5", "parent" : "ajson2", "text" : "Hierarchy" },
                    { "id" : "ajson6", "parent" : "ajson2", "text" : "ERD" },
                    { "id" : "ajson7", "parent" : "ajson2", "text" : "Tables" },
                    { "id" : "ajson8", "parent" : "ajson2", "text" : "Data Dictionary" },

                    { "id" : "ajson9", "parent" : "#", "text" : "GMD Source to Target Map" },
                    { "id" : "ajson10", "parent" : "ajson9", "text" : "Table Level Map" },
                    { "id" : "ajson11", "parent" : "ajson9", "text" : "Column Level Map" },
                    { "id" : "ajson12", "parent" : "ajson9", "text" : "Input Source Prioritization" },

                    { "id" : "ajson13", "parent" : "#", "text" : "GMD Report Library" },
                    { "id" : "ajson14", "parent" : "ajson13", "text" : "Report Lib 2015Q4 AMER" },
                    { "id" : "ajson15", "parent" : "ajson13", "text" : "Report Lib 2015Q4 EMEA" },
                    { "id" : "ajson16", "parent" : "ajson13", "text" : "Report Lib 2015Q4 APAC" },

                    { "id" : "ajson17", "parent" : "#", "text" : "CRM Square Extract Module Videos" },
                    { "id" : "ajson18", "parent" : "ajson17", "text" : "Create List from Template" },
                    { "id" : "ajson19", "parent" : "ajson17", "text" : "Create Template via Copy" },
                    { "id" : "ajson20", "parent" : "ajson17", "text" : "Create Template from Scratch" },
                    { "id" : "ajson21", "parent" : "ajson17", "text" : "Update Template Metadata" },

                    { "id" : "ajson22", "parent" : "#", "text" : "CRM Square Report Module Videos" },
                    { "id" : "ajson23", "parent" : "ajson22", "text" : "Run Pre-existing Report" },
                ]
            }*/
        });


    })();





</script>
</body>
</html>