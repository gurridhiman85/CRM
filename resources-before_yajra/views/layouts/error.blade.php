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
    @section('head_css')
        @include('assetlib.head_css')
    @show
    <?php
    \App\Library\AssetLib::library('error');
    ?>

    @section('css')
        @include('assetlib.css')
    @show


    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>


    @section('head_js')
        @include('assetlib.head_js')
    @show
</head>
<body class="skin-default card-no-border">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">{!! config('constant.title') !!}</p>
    </div>
</div>

<section id="wrapper" class="error-page">

    @section('content')
        @yield('content')
    @show

</section>


<?php
\App\Library\AssetLib::library('popper','bootstrap','sweetalert','sweet-alert.init');
?>
@section('footer_js')
    @include('assetlib.js')
@show

<script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    });
    // ==============================================================
    // Login and Recover Password
    // ==============================================================
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
</script>
</body>
</html>