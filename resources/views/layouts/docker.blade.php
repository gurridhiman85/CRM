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
		
		body::-webkit-scrollbar {
			width: 1em;
		}
		 
		body::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
		}
		 
		body::-webkit-scrollbar-thumb {
		  background-color: darkgrey;
		  outline: 1px solid #ddd;
		}
    </style>
</head>
<body class="fixed-layout mini-sidebar {!! !empty(Auth::user()->utheme) ? Auth::user()->utheme->meta_value : 'skin-blue' !!} ">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{!! config('constant.title') !!}</p>
    </div>
</div>
@php
    $User_Type = Session::get('User_Type');
    $Visibilities = Session::get('Visibilities');
    $visiblities = !empty($Visibilities) ? explode(',',$Visibilities) : [];
@endphp

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

<script>
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