
<html>
<head>
    <style>

        @page {
           /* size: letter portrait;*/
           /* size: landscape;*/
            margin:0.9;
            padding:0.9; // you can set margin and padding 0
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
            color: #5e6060;/*#808282;*/ /*#3ea6d0;*/
            font-family : Arial !important;
            font-size : 18px;
            font-weight : 600;


            text-align: center;
            line-height: 1.5cm; ;
        }

        header img {
            position: relative;
        }


        table{
            position: inherit;
            /*line-height: 8cm;	*/
            top:2cm;
            text-align: center;
            left: 0.5cm;
            right: 0.5cm;
            float : none !important;
            width : 100% !important;
            margin-bottom: 0px !important;
            font-family : Arial !important;
        }

        .contentDiv{
            /*line-height: 8cm;*/
        }

        .cimg{
            text-align: center;
            left: 0.5cm;
            right: 0.5cm;
        }

        /*     Custom c&P                */
        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #e9ecef;
        }

        .table {
            width: 100%;
            color: #000000;
            margin-bottom: 3cm;
            margin-top: 0.8cm;
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
            font-weight:400;
            word-wrap:break-word;
        }

        .table thead th {
            vertical-align: middle;
            word-wrap:break-word;
        }

        .color-table.sr-table td {
            border: 1px solid #e9ecef;
            padding: 5px 8px;
            font-size: 8pt;
            word-wrap:break-word;
        }

        .color-table.sr-table td.left-side-cell {
            border: 1px solid #e9ecef;
            padding: 5px 8px;
            font-size: 12px !important;
            font-weight:400 !important;
            color: #010101 !important;
            word-wrap:break-word;
        }

        /* for pdf page numbers
        footer .page-number:after {  content: counter(page); }
        footer .page-number {
            bottom: 0cm;
            left: 0.5cm;
            right: 0.5cm;
            height: 2cm;
            color: #a3a5a5;
            line-height: 1.5cm;
            float: left;
            padding-left: 0.5cm;
        }*/

        footer .page-date {
            position: relative;
            font-family : Arial !important;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0.5cm;
            height: 2cm;

            /** Extra personal styles **/
            /*color: #3ea6d0;*/
            color: #a3a5a5;
            font-size: 11px;
            text-align: center;
            line-height: 1.5cm;
            font-family : Arial !important;
        }

        footer a {
            bottom: 0cm;
            top: 0.5cm;
            margin-top: 0.6cm;
            left: 0.5cm;
            right: 0.5cm;

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

    </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<header>
    <!--<img style="height: 1.8cm;line-height: 1.5cm;margin-left:0.5cm;margin-top:0.1cm;margin-bottom:0.1cm;float:left;" src="{!! url('/').'/img/logoReportHeader.jpg' !!}"></img>{!! $header !!}<img style="height: 0.6cm;line-height: 1.5cm;margin-right:0.5cm;margin-top:0.7cm;margin-bottom:0.25cm;float:right;width: 135px;" src="{!! url('/').'/img/crmlogo.png' !!}"></img> -->

    <img style="height: 0.7cm;line-height: 2.4cm;margin-left:0.5cm;margin-top:0.6cm;margin-bottom:0.5cm;text-align:center;" src="{!! config('constant.BaseUrl').'/img/logoReportHeader.jpg' !!}"></img><br>
    <span style="line-height: 0.4cm;text-align:center;margin-left:0.5cm;margin-bottom:0.5cm;font-weight:400;color:black;">{!! $header !!}</span><!--<hr class="class-1">-->
</header>


<footer>
    <!--<span class="page-number">Page </span>-->
    <!--
    <span class="page-date">{!! date('Y-m-d') !!}</span>
    &copy;{!! date("Y") !!} Data Square. All Rights Reserved | <a style="text-decoration: none;color: #9ea0a0;" target="_blank" href="http://www.datasquare.com">www.datasquare.com</a> <span style="bottom: 0cm; left: 0cm; right: 0.5cm;height: 2cm;color: #9ea0a0;line-height: 1.5cm;float:right;">{!! $footer !!}</span> -->
    <a style="text-decoration: none;color: #9ea0a0;" target="_blank" href="http://www.datasquare.com"><img style="height: 0.4cm;line-height: 1.2cm;margin-top:0.7cm;margin-bottom:0.25cm;text-align:left;" src="{!! config('constant.BaseUrl').'/img/crmlogo.png' !!}"></img></a>
    <span class="page-date">{!! $footer !!}</span>
     <span style="bottom: 0cm; left: 0cm; right: 0.5cm;height: 2cm;color: #9ea0a0;line-height: 1.5cm;float:right;">{!! date('Y-m-d') !!}</span>

</footer>

<!-- Wrap the content of your PDF inside a main tag -->
<main>

    <div class="contentDiv">
         {!! $tablehtml !!}

    </div>
    <div class="page-text" style="font-size: 12px">
        @if(!empty($selections)) <b>Selection :</b> {!! $selections !!} @endif
    </div>
    <div class="sel-inter" style="font-size: 12px">
        @if(!empty($interpretation)) <span class="page-inter"><b>Interpretation :</b> 63% of the 4,983 records are categorized as Tier9_Prospect.</span> @endif
    </div>
    <div class="cimg">
        {!! $charthtml !!}
    </div>
</main>
</body>
</html>
