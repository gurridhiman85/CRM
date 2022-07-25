<html>
<head>
    <style>

        @page {
            /* size: letter portrait;*/
            /* size: landscape;*/
            margin: 0.9;
            padding: 0.9; // you can set margin and padding 0
        }

        @font-face {
            font-family: 'Arial';
            src: url({{ storage_path('fonts\arial.ttf') }}) format("truetype");
            font-weight: 400; /*// use the matching font-weight here ( 100, 200, 300, 400, etc).*/
            font-style: normal; /*// use the matching font-style here*/
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 2cm;
            margin-left: 0.50cm;
            margin-right: 0.50cm;
            margin-bottom: 2cm;
            background: none;
        }


        /** Define the header rules **/
        header {
            position: fixed;
            top: 0.2cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;

            /** Extra personal styles **/
            color: #5e6060; /*#808282;*/ /*#3ea6d0;*/
            font-family: Arial !important;
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            line-height: 1.4cm;;
        }

        header img {
            position: relative;
        }


        table {
            position: relative;
            /*line-height: 8cm;	*/
            top: 0.7cm;
            text-align: center;
            /*left: 0.5cm;
            right: 0.5cm;*/

            float: none !important;
            width: 100% !important;
            margin-bottom: 6px !important;
            font-family: Arial !important;


        }

        .contentDiv {
            /*line-height: 8cm;*/
            margin-left: 1cm;
            margin-right: 1cm;
            margin-top: 0.8cm;
        }

        .cimg {
            text-align: center;
            /*border: 1px solid #e9ecef;*/
            margin-left: 1cm;
            margin-right: 1cm;
            /*margin-left: 0.2cm;
            margin-right: 0.2cm;*/
            /*left: 0.5cm;
            right: 0.5cm;*/
        }

        /*     Custom c&P                */
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #e9ecef;
        }

        .table {
            width: 100%;
            color: #000000;
            margin-bottom: 6cm;

        }

        table {
            border-collapse: collapse;

        }

        .color-table.sr-table thead th {
            background-color: #f9f9f9;
            color: #010101;
            border: 0.5px solid #e9ecef;
            padding: 5px 8px;
            font-size: 12px;
            font-weight: 400;
            word-wrap: break-word;
        }

        .table thead th {
            vertical-align: middle;
            word-wrap: break-word;
        }

        .color-table.sr-table tr.totalCL {
            background-color: #f9f9f9;
            color: #010101;
            border: 0.5px solid #e9ecef;
        }

        .color-table.sr-table td {
            border: 1px solid #e9ecef;
            padding: 5px 8px;
            font-size: 8pt;
            word-wrap: break-word;
        }

        .color-table.sr-table td.left-side-cell {
            border: 1px solid #e9ecef;
            padding: 5px 8px;
            font-size: 12px !important;
            font-weight: 400 !important;
            color: #010101 !important;
            word-wrap: break-word;
        }

        footer .page-date {
            position: relative;
            font-family: Arial !important;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0.5cm;
            height: 2cm;
            margin-left: 1cm;
            margin-right: 1cm;

            /** Extra personal styles **/
            /*color: #3ea6d0;*/
            color: #a3a5a5;
            font-size: 11px;
            text-align: center;
            line-height: 1.5cm;
            font-family: Arial !important;
        }

        footer a {
            bottom: 0cm;
            top: 0.5cm;
            margin-top: 0.6cm;
            left: 1cm;
            right: 1cm;

            color: #a3a5a5;
            line-height: 1.5cm;
            float: left;
            padding-left: 0.5cm;
        }

        footer a {
            margin-top: 0.3cm;
        }


        hr.class-1 {
            border-top: 0.5px solid #8c8b8b;
        }

        .text-left {
            text-align: left !important
        }

        .text-right {
            text-align: right !important
        }



    </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<header>
    <div style="margin-bottom:0.2cm;">
        <img style="height: 0.7cm;line-height:1cm;margin-left:0.5cm;margin-top:0.2cm;text-align:center;"
             src="{!! config('constant.BaseUrl').'/img/logoReportHeader.jpg' !!}"></img>
    </div>
    <div style="line-height: 0.8cm;text-align:center;margin-left:0.5cm;margin-bottom:0.4cm;font-weight:400;color:black;">
        {!! $header !!}
        <br/>
        <span style=" font-size: 15px; ">{!! $subheader !!}</span>
    </div>
</header>

<footer>

    <a style="text-decoration: none;color: #9ea0a0;" target="_blank" href="http://www.datasquare.com"><img
                style="height: 0.4cm;line-height: 1.2cm;margin-top:0.7cm;margin-bottom:0.25cm;text-align:left;"
                src="{!! config('constant.BaseUrl').'/img/crmlogo.png' !!}"></img></a>
    <span class="page-date">{!! $footer !!}</span>
    <span style="bottom: 0cm; left: 0cm; right: 0.5cm;height: 2cm;color: #9ea0a0;line-height: 1.5cm;float:right;">{!! date('Y-m-d') !!}</span>

</footer>

<!-- Wrap the content of your PDF inside a main tag -->
<main>



    <div class="contentDiv">
        {!! $tablehtml !!}
    </div>

    <div class="cimg" style="margin-top:2cm;">
        {!! $charthtml !!}
    </div>

</main>


</body>
</html>
