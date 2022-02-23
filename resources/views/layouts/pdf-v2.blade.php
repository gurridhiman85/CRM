<!DOCTYPE html>
<html lang="en">
    <head>
        <title>DOM-PDF</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    </head>
    <body>

        <header>
            <img style="height: 0.7cm;line-height: 2.4cm;margin-left:0.5cm;margin-top:0.6cm;margin-bottom:0.5cm;text-align:center;"
                 src="{!! config('constant.BaseUrl').'/img/logoReportHeader.jpg' !!}"></img><br>
            <span style="line-height: 0.4cm;text-align:center;margin-left:0.5cm;margin-bottom:0.5cm;font-weight:400;color:black;">{!! $header !!}</span>

        </header>

        <footer>
            <a style="text-decoration: none;color: #9ea0a0;" target="_blank" href="http://www.datasquare.com"><img
                        style="height: 0.4cm;line-height: 1.2cm;margin-top:0.7cm;margin-bottom:0.25cm;text-align:left;"
                        src="{!! config('constant.BaseUrl').'/img/crmlogo.png' !!}"></img></a>
            <span class="page-date">{!! $footer !!}</span>
            <span style="bottom: 0cm; left: 0cm; right: 0.5cm;height: 2cm;color: #9ea0a0;line-height: 1.5cm;float:right;">{!! date('Y-m-d') !!}</span>
        </footer>

        <div style="page-break-after:auto;">

            <div class="container my-content">
                <div class="row">
                    {!! $tablehtml !!}
                </div>
                <div class="row">
                    <div class="cimg">
                        {!! $charthtml !!}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
