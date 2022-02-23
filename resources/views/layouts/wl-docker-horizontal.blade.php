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
    @section('wl-head_css')
        @include('assetlib.head_css')
    @show

    @section('wl-head_js')
        @include('assetlib.head_js')
    @show
    <?php
    \App\Library\AssetLib::library('popper','bootstrap','perfect-scrollbar','waves','sidebarmenu','custom','raphael','morris','datepicker','moment','fileinput','switchery','inputmask','sweetalert','sweet-alert.init','dropify','datetimepicker','clockpicker','bootstrap-datepicker','bootstrap-timepicker','tabs', 'dataTables.bootstrap4', 'dataTables', 'responsive.dataTables','dataTables-fixed-columns','dataTables-buttons','dataTables-colVis','chosen','ui-bootstrap','multiselect','style_multiselect','multiselect-jquery-ui','multiselect-filter','typeahead');
    ?>
    @section('wl-css')
        @include('assetlib.css')
    @show
    <style>
        .complete{
            display:none;
        }

        .more{
            /*background:lightblue;*/
            color:navy;
            padding:3px;
            cursor:pointer;
        }

		::-webkit-scrollbar {
			width: 0.7em;
			height: 0.7em;
		}

		::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		}

		::-webkit-scrollbar-thumb {
		  background-color: lightgrey;
		  outline: 1px solid #ddd;
		}

        label.ui-corner-all input {
            margin-right: 5px;
        }

        .swal2-popup .swal2-styled {
            margin: 0.3125em;
            padding: 0.825em 2em !important;
            box-shadow: none;
            font-weight: 500;
        }

        .swal2-popup .swal2-styled:focus {
            box-shadow: none !important;
        }
    </style>
    <link href="css/example.css?ver={{time()}}" rel="stylesheet" type="text/css">
</head>
<body class="horizontal-nav boxed fixed-layout skin-blue ">


<div id="main-wrapper">
    @section('wl-docker-topnav')
        @include('layouts.wl-docker-topnav')
    @show


    @section('wl-docker-leftsidebar')
        @include('layouts.wl-docker-leftsidebar')
    @show

    <div class="page-wrapper pt-2">
        @section('content')
            @yield('content')
        @show
    </div>

    @section('wl-docker-footer')
        @include('layouts.wl-docker-footer')
    @show

</div>
@section('wl_footer_js')
    @include('assetlib.js')
@show
<script>

    var browser = (function (agent) {
        switch (true) {
            case agent.indexOf("edge") > -1: return "MS Edge (EdgeHtml)";
            case agent.indexOf("edg") > -1: return "MS Edge Chromium";
            case agent.indexOf("opr") > -1 && !!window.opr: return "opera";
            case agent.indexOf("chrome") > -1 && !!window.chrome: return "chrome";
            case agent.indexOf("trident") > -1: return "Internet Explorer";
            case agent.indexOf("firefox") > -1: return "firefox";
            case agent.indexOf("safari") > -1: return "safari";
            default: return "other";
        }
    })(window.navigator.userAgent.toLowerCase());
    //alert(browser);
    function readmore(obj) {
        if(obj.siblings(".complete").is(":hidden")){
            obj.text("-")
            obj.siblings(".complete").show();
        }else{
            obj.text("+")
            obj.siblings(".complete").hide();
        }
    }
</script>
</body>
</html>
