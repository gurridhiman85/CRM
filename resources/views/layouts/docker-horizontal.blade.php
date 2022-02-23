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
    \App\Library\AssetLib::library('popper','bootstrap','perfect-scrollbar','waves','sidebarmenu','custom','raphael','morris','datepicker','moment','fileinput','switchery','inputmask','sweetalert','sweet-alert.init','dropify','datetimepicker','clockpicker','bootstrap-datepicker','bootstrap-timepicker','tabs', 'dataTables.bootstrap4', 'dataTables', 'responsive.dataTables','dataTables-fixed-columns','dataTables-buttons','dataTables-colVis','chosen','ui-bootstrap','multiselect','style_multiselect','multiselect-jquery-ui','multiselect-filter','typeahead');
    ?>
    @section('css')
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
<body
        class="horizontal-nav boxed fixed-layout skin-blue "
>
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{!! config('constant.title') !!}</p>
    </div>
</div>
@php
    if(Auth::check()){
        $User_Type = Auth::user()->authenticate->User_Type;
        $Visibilities = Auth::user()->authenticate->Visibilities;
        $visiblities = !empty($Visibilities) ? explode(',',$Visibilities) : [];
    }
@endphp

<div id="main-wrapper">
    @section('docker-topnav')
        @include('layouts.docker-topnav')
    @show


    @section('docker-leftsidebar')
        @include('layouts.docker-leftsidebar')
    @show

    <div class="page-wrapper pt-2">
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
            obj.siblings(".teaser").hide();
        }else{
            obj.text("+")
            obj.siblings(".complete").hide();
            obj.siblings(".teaser").show();
        }
    }
</script>
</body>
</html>
