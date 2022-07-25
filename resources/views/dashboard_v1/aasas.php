<?php
if(!isset($sm))
    $sm = '';
?>
@extends('layouts.docker-horizontal')
@section('content')
<?php
\App\Library\AssetLib::library('sparkline');
?>
<!--<link rel="stylesheet" type="text/css" href="js/Chart.js-2.7.2/docs/style.css?ver={{ time() }}">-->
<style>
    canvas {
        background-color: #ffffff;
        width: 100% !important;
        max-width: 800px;
        padding: 5px !important;
        /*height: 250px !important;*/
    }

    .round {
        line-height: 37px;
        color: #fff;
        width: 35px;
        height: 35px;
        display: inline-block;
        text-align: center;
    }

    .table th, .table thead th {
        font-size: 13px;
    }
    .table td, .table th {
        padding: 8.4px 8px;
    }

    .c-border{
        border: 3px solid #e9ecef !important;
    }

    .border-bottom-0 {
        border-bottom: 0 !important;
    }

</style>
<div class="container-fluid">

    <!-- ============================================================== -->
    <!-- Sales Chart and browser state-->
    <!-- ============================================================== -->
    <div class="row">

        <!-- Column
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="card-title m-b-40">SALES IN 2018</h5>
                            <p>Lorem ipsum dolor sit amet, ectetur adipiscing elit. viverra tellus. ipsumdolorsitda amet, ectetur adipiscing elit.</p>
                            <p>Ectetur adipiscing elit. viverra tellus.ipsum dolor sit amet, dag adg ecteturadipiscingda elitdglj. vadghiverra tellus.</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div id="morris-area-chart" style="height:250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         Column -->
    </div>
    <!-- ============================================================== -->
    <!-- End Sales Chart -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Review -->
    <!-- ============================================================== -->
    <div class="row">

        <div class="col-lg-12">
            <div class="card mb-1">
                <div class="card-body pt-2 pb-0">
                    <form class="ajax-Form" id="dashboard_form" method="post" action="{{ URL::to('/') }}/getdashboardinfo">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1">Filter Type</label>
                                        <div class="col-md-9">
                                            <select class="form-control form-control-sm ajax-Select" id="filtertype" name="filtertype">
                                                <option value="Donor">Donor</option>
                                                <option <?= ($sm == 'pf') ? 'selected' : ''; ?> value="Performance">Performance</option>
                                                <option <?= ($sm == 'ac') ? 'selected' : ''; ?> value="All Campaigns">All Campaigns</option>
                                                <option <?= ($sm == 'ec') ? 'selected' : ''; ?> value="Email Campaigns">Email Campaigns</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1">Filter 1</label>
                                        <div class="col-md-9">
                                            <select name="filter1" class="form-control form-control-sm ajax-Select" id="filter1" data-placeholder="Select Values">
                                                {!! $f1Options !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1">Filter 2</label>
                                        <div class="col-md-9">
                                            <select name="filter2" class="form-control form-control-sm ajax-Select" id="filter2" data-placeholder="Select Values">
                                                {!! $f2Options !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1">Filter 3</label>
                                        <div class="col-md-9">
                                            <select name="filter3" class="form-control form-control-sm ajax-Select" id="filter3" data-placeholder="Select Values">
                                                {!! $f3Options !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1">Filter 4</label>
                                        <div class="col-md-9">
                                            <select name="filter4" class="form-control form-control-sm ajax-Select" id="filter4" data-placeholder="Select Values">
                                                {!! $f4Options !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 pt-1"></label>
                                        <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                            <div class="btn-group border" role="group" aria-label="First group">
                                                <button type="button" class="btn btn-secondary"><i class="fas fa-print"></i></button>
                                                <button type="button" class="btn btn-secondary"><i class="fas fa-file-pdf" style="color: #e92639;"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--/span-->
                            </div>

                        </div>

                        <input type="hidden" name="page" value="Donor">
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div class="dash">

    </div>
    <!-- ============================================================== -->
    <!-- End Review -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Comment - chats -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Comment - chats -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    @include('layouts.docker-rightsidebar')

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js?ver={{ time() }}"></script>
    <script src="js/Chart.js-2.7.2/dist/Chart.bundle.js?ver={{ time() }}"></script>
    <script src="js/Chart.js-2.7.2/samples/utils.js?ver={{ time() }}"></script>--}}
    <script src="js/Chart.js-master/dist/chart.js?ver={{ time() }}"></script>

    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->

    <script type="application/javascript">
        $(document).ready(function(){
            //$('.dashboard').trigger('click');
            setTimeout(function () {
                $('body').find('#dashboard_form').submit();
            },1500)

            $('body').find('.ajax-Select').on('change',function(){
                /*var filtertype = $('#filtertype').val();
                var filter1 = $('#filter1').val();
                var filter2 = $('#filter2').val();
                var filter3 = $('#filter3').val();
                var filter4 = $('#filter4').val();
                ACFn.sendAjax("{{ URL::to('/') }}/dashboard_ajax.php?pgaction=standard&filtertype=" + filtertype + "&filter1=" + filter1 + "&filter2=" + filter2 + "&filter3=" + filter3 + "&filter4=" + filter4 + "&page=Donor", "GET", "");*/

                $('body').find('#dashboard_form').submit();

            });

            ACFn.loadstickypopup = function (F, R) {

                $('.dash').html(R.html);

                if (R.type && R.type == 'standard') {


                    $.each(R.linechart_data, function (i) {
                        var position = R.linechart_data[i].chart_position;
                        var title = R.linechart_data[i].chart_title;
                        var legend1 = R.linechart_data[i].chart_legend1;
                        var legend2 = R.linechart_data[i].chart_legend2;
                        var legend3 = R.linechart_data[i].chart_legend3;
                        var legend4 = R.linechart_data[i].chart_legend4;
                        var label = [];
                        var value1 = [];
                        var value2 = [];
                        var value3 = [];
                        var value4 = [];
                        $.each(R.linechart_data[i].chart_detail, function (j) {
                            label[j] = R.linechart_data[i].chart_detail[j].chart_label;
                            value1[j] = R.linechart_data[i].chart_detail[j].chart_value1;
                            value2[j] = R.linechart_data[i].chart_detail[j].chart_value2;
                            value3[j] = R.linechart_data[i].chart_detail[j].chart_value3;
                            value4[j] = R.linechart_data[i].chart_detail[j].chart_value4;
                        });
                        if (R.linechart_data[i].chart_type == 'pie') {
                            addpiechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                        }
                        if (R.linechart_data[i].chart_type == 'doughnut') {
                            addDoughnutPiechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);

                        }
                        if (R.linechart_data[i].chart_type == 'line') {
                            addlinechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }
                        if (R.linechart_data[i].chart_type == 'combochart') {
                            addcombochart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'combochart1') {
                            addcombochart1(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'combochart2') {
                            addcombochart2(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }


                        if (R.linechart_data[i].chart_type == 'bar') {
                            addbarchart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'stackedbar') {
                            addStackedBarChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'verticalbar') {
                            addVerticalBarChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'progressiveline') {
                            addProgressiveLineChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'morriesareachart') {
                            morriesareachart(position, title);
                            $('#can-title-' + position).text(title);
                        }
                        if (R.linechart_data[i].chart_type == 'arealine') {
                            addarealinechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                            $('#can-title-' + position).text(title);
                        }

                        if (R.linechart_data[i].chart_type == 'top_new_customers') {
                            //topnewcustomers(position, title);
                            $('#can-title-' + position).text(title);
                        }

                    });
                    morries();

                    var sparklineLogin = function() {
                        $("#sparkline8").sparkline([2,4,4,6,8,5,6,4,8,6,6,2 ], {
                            type: 'line',
                            width: '100%',
                            height: '177',
                            lineColor: '#00c292',
                            fillColor: 'rgba(0, 194, 146, 0.2)',
                            maxSpotColor: '#00c292',
                            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
                            highlightSpotColor: '#00c292'
                        });
                        $("#sparkline9").sparkline([0,2,8,6,8,5,6,4,8,6,6,2 ], {
                            type: 'line',
                            width: '100%',
                            height: '177',
                            lineColor: '#03a9f3',
                            fillColor: 'rgba(3, 169, 243, 0.2)',
                            minSpotColor:'#03a9f3',
                            maxSpotColor: '#03a9f3',
                            highlightLineColor: 'rgba(0, 0, 0, 0.2)',
                            highlightSpotColor: '#03a9f3'
                        });

                    };
                    var sparkResize;

                    $(window).resize(function(e) {
                        clearTimeout(sparkResize);
                        sparkResize = setTimeout(sparklineLogin, 500);
                    });
                    sparklineLogin();

                }
            }
        });

        function topnewcustomers(pos,title) {

        }

        function morriesareachart(pos, title) {
            var area =Morris.Area({
                element: 'can-' + pos
                , data: [{
                    period: '2010'
                    , SiteA: 0
                    , SiteB: 0
                    , }, {
                    period: '2011'
                    , SiteA: 130
                    , SiteB: 100
                    , }, {
                    period: '2012'
                    , SiteA: 80
                    , SiteB: 60
                    , }, {
                    period: '2013'
                    , SiteA: 70
                    , SiteB: 200
                    , }, {
                    period: '2014'
                    , SiteA: 180
                    , SiteB: 150
                    , }, {
                    period: '2015'
                    , SiteA: 105
                    , SiteB: 90
                    , }
                    , {
                        period: '2016'
                        , SiteA: 250
                        , SiteB: 150
                        , }]
                , xkey: 'period'
                , ykeys: ['SiteA', 'SiteB']
                , labels: ['Site A', 'Site B']
                , pointSize: 0
                , fillOpacity: 0.4
                , pointStrokeColors: ['#b4becb', '#01c0c8']
                , behaveLikeLine: true
                , gridLineColor: '#e0e0e0'
                , lineWidth: 0
                , smooth: true
                , hideHover: 'auto'
                , lineColors: ['#b4becb', '#01c0c8']
                , resize: false
            });
        }

        function morries(){
            Morris.Donut({
                element: 'morris-donut-chart',
                data: [{
                    label: "Adv",
                    value: 8500,

                }, {
                    label: "Tredshow",
                    value: 3630,
                }, {
                    label: "Web",
                    value: 4870
                }],
                resize: true,
                colors:['#fb9678', '#01c0c8', '#03a9f3']
            });



        }
        var fontsize = 10;
        const MONTHS = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        function months(config) {
            var cfg = config || {};
            var count = cfg.count || 12;
            var section = cfg.section;
            var values = [];
            var i, value;

            for (i = 0; i < count; ++i) {
                value = MONTHS[Math.ceil(i) % 12];
                values.push(value.substring(0, section));
            }

            return values;
        }

        const COLORS = [
            '#4dc9f6',
            '#f67019',
            '#f53794',
            '#537bc4',
            '#acc236',
            '#166a8f',
            '#00a950',
            '#58595b',
            '#8549ba'
        ];

        const CHART_BORDER_COLORS = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        const CHART_COLORS = {
            red: 'rgba(255, 99, 132, 0.2)',
            orange: 'rgb(255, 159, 64, 0.2)',
            yellow: 'rgb(255, 205, 86, 0.2)',
            green: 'rgb(75, 192, 192, 0.2)',
            blue: 'rgb(54, 162, 235, 0.2)',
            purple: 'rgb(153, 102, 255, 0.2)',
            grey: 'rgb(201, 203, 207, 0.2)'
        };

        const NAMED_COLORS = [
            CHART_COLORS.red,
            CHART_COLORS.orange,
            CHART_COLORS.yellow,
            CHART_COLORS.green,
            CHART_COLORS.blue,
            CHART_COLORS.purple,
            CHART_COLORS.grey,
        ];

        function namedColor(index) {
            return NAMED_COLORS[index % NAMED_COLORS.length];
        }

        const labels = months({count: 7});

        function arrayIsEmpty(array) {
            //If it's not an array, return FALSE.
            if (!Array.isArray(array)) {
                return false;
            }
            //If it is an array, check its length property
            if (array.length == 0) {
                //Return TRUE if the array is empty
                return true;
            }
            var is_null = true;
            $.each(array,function (i,v) {
                if(v != null) is_null = false;
            });

            if(is_null == true){
                return true;
            }
            var is_blank = true;
            $.each(array,function (i,v) {
                if($.trim(v) != "") is_blank = false;
            });

            if(is_blank == true){
                return true;
            }
            //Otherwise, return FALSE.
            return false;
        }

        function totalfromVal(arrval) {
            var total = 0;
            $.each(arrval,function (i,v) {
                total = parseFloat(total) + parseFloat(v);
            });

            return total;
        }

        function addlinechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            function createConfig(gridlines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
                var values = [val1,val2,val3,val4];
                var dataset = [];
                $.each(values,function (i,val) {
                    if(arrayIsEmpty(val) != true){
                        var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                        if($.trim(l) != ""){
                            dataset.push({
                                type: 'line',
                                label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                                backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                                borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                                borderWidth : 1,
                                data: val,
                            })
                        }

                    }
                });

                return {
                    type: 'line',

                    data: {
                        labels: label,

                        datasets: dataset
                    },
                    options: {
                        elements: {
                            point:{
                                radius: 1
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display : true,
                                align : 'end',
                                position : 'top',
                                labels : {
                                    display : true,
                                    boxWidth : 5,
                                    boxHeight : 5,
                                    font: {
                                        size: 8
                                    }
                                },
                            },
                            title: {
                                display: false,
                                text: title,
                                fontFamily : 'sans-serif',
                                align :'start'
                            }
                        },
                        title: {
                            display: true,
                            text: title
                        },
                        scales: {
                            xAxes: [{
                                gridLines: gridlines,
                                ticks: {
                                    fontSize: 12
                                }
                            }],
                            yAxes: [{
                                gridLines: gridlines,
                                ticks: {
                                    min: 0,
                                    max: 100,
                                    stepSize: 10,
                                    fontSize: 12
                                }
                            }]
                        }
                    }
                };
            }

            var container = document.querySelector('.chart-area');

            [{
                title: title,
                gridLines: {
                    display: true
                }
            }].forEach(function (details) {
                var ctx = document.getElementById('can-' + pos).getContext('2d');
                var config = createConfig(details.gridLines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4);

                var chartmy = new Chart(ctx, config);
                //chartmy.reDraw()
            });


        }

        function addarealinechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            function createConfig(gridlines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
                var values = [val1,val2,val3,val4];
                var dataset = [];
                $.each(values,function (i,val) {
                    if(arrayIsEmpty(val) != true){
                        var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                        if($.trim(l) != ""){
                            dataset.push({
                                type: 'line',
                                label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                                backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                                borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                                borderWidth : 1,
                                data: val,
                                fill: true
                            })
                        }

                    }
                });

                return {
                    type: 'line',

                    data: {
                        labels: label,

                        datasets: dataset
                    },
                    options: {
                        elements: {
                            point: {
                                radius: 1
                            },
                            line: {
                                tension : 0.4
                            }
                        },
                        responsive: true,
                        plugins: {
                            legend: {
                                display : true,
                                align : 'end',
                                position : 'top',
                                labels : {
                                    display : true,
                                    boxWidth : 5,
                                    boxHeight : 5,
                                    font: {
                                        size: 8
                                    }
                                },
                            },
                            title: {
                                display: false,
                                text: title,
                                fontFamily : 'sans-serif',
                                align :'start'
                            }
                        },
                        pointBackgroundColor: '#fff',
                        radius: 10,
                        title: {
                            display: true,
                            text: title
                        },
                        scales: {
                            xAxes: [{
                                gridLines: gridlines,
                                ticks: {
                                    fontSize: 12
                                }
                            }],
                            yAxes: [{
                                gridLines: gridlines,
                                ticks: {
                                    min: 0,
                                    max: 100,
                                    stepSize: 10,
                                    fontSize: 12
                                }
                            }]
                        }
                    }
                };
            }

            var container = document.querySelector('.chart-area');

            [{
                title: title,
                gridLines: {
                    display: true
                }
            }].forEach(function (details) {
                var ctx = document.getElementById('can-' + pos).getContext('2d');
                var config = createConfig(details.gridLines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4);

                var chartmy = new Chart(ctx, config);
                //chartmy.reDraw()
            });


        }

        function addpiechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            var randomScalingFactor = function () {
                return Math.round(Math.random() * 100);
            };

            $('#can-title-' + pos).html('<small class="pull-right">\n' +
                '                                    <i class="fa fa-sort-desc"></i> Total: ' + totalfromVal(val1) + '\n' +
                '                                    </small>' + title);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: val1,
                        backgroundColor: [
                            CHART_BORDER_COLORS.red,
                            CHART_BORDER_COLORS.orange,
                            CHART_BORDER_COLORS.yellow,
                            CHART_BORDER_COLORS.blue,
                            CHART_BORDER_COLORS.green,
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: label

                },
                options: {
                    responsive: true,
                    maintainAspectRatio : true,
                    aspectRatio : 2,
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'right',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            fontFamily : 'sans-serif',
                            fontStyle : 'normal',
                            align :'start'
                        }
                    },
                    title: {
                        display: true,
                        text: title
                    }
                }

            };


            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myPie = new Chart(ctx, config);

            var colorNames = Object.keys(CHART_COLORS);
        }

        function addDoughnutPiechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {

            var randomScalingFactor = function () {
                return Math.round(Math.random() * 100);
            };

            $('#can-title-' + pos).html('<small class="pull-right">\n' +
                '                                    <i class="fa fa-sort-desc"></i> Total: ' + totalfromVal(val1) + '\n' +
                '                                    </small>' + title);
            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: val1,
                        backgroundColor: [
                            CHART_BORDER_COLORS.red,
                            CHART_BORDER_COLORS.orange,
                            CHART_BORDER_COLORS.yellow,
                            CHART_BORDER_COLORS.blue,
                            CHART_BORDER_COLORS.green,
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: label

                },
                options: {
                    responsive: true,
                    maintainAspectRatio : true,
                    aspectRatio : 2,
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'right',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            fontFamily : 'sans-serif',
                            fontStyle : 'normal',
                            align :'start'
                        }
                    },
                    title: {
                        display: true,
                        text: title
                    }
                }

            };


            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myPie = new Chart(ctx, config);

            var colorNames = Object.keys(CHART_COLORS);
        }

        function addcombochart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            var timeFormat = 'MM/DD/YYYY HH:mm';

            function newDateString(days) {
                return moment().add(days, 'd').format(timeFormat);
            }
            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            type: 'bar',
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                        })
                    }
                }
            });
            var config = {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: dataset
                },
                options: {
                    title: {
                        text: title,
                        display: true
                    },
                    /*color: function (context) {
                        console.log('context---' ,context.chart.data)
                        var index = context.dataIndex;
                        var value = context.dataset.data[index];
                        return value > 20 ? 'red' :  // draw negative values in red
                            index % 2 ? 'blue' :    // else, alternate values in blue and green
                                'green';
                    },*/
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            display: true,
                            time: {
                                format: timeFormat,
                                // round: 'day'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 100,
                                stepSize: 10
                            }
                        }]
                    },
                }
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myLine = new Chart(ctx, config);

            var colorNames = Object.keys(CHART_COLORS);
        }

        function addcombochart1(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            var timeFormat = 'MM/DD/YYYY HH:mm';

            function newDateString(days) {
                return moment().add(days, 'd').format(timeFormat);
            }

            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            type: 'bar',
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                        });
                    }
                }
            });

            var config = {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: dataset
                },
                options: {
                    title: {
                        text: title,
                        display: true
                    },
                    /*color: function (context) {
                        console.log('context---' + context)
                        //alert('gh');
                        var index = context.dataIndex;
                        var value = context.dataset.data[index];
                        return value > 20 ? 'red' :  // draw negative values in red
                            index % 2 ? 'blue' :    // else, alternate values in blue and green
                                'green';
                    },*/
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            display: true,
                            time: {
                                format: timeFormat,
                                // round: 'day'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 50,
                                stepSize: fontsize
                            }
                        }]
                    },
                }
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myLine = new Chart(ctx, config);

            var colorNames = Object.keys(CHART_COLORS);
        }

        function addcombochart2(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            var timeFormat = 'MM/DD/YYYY HH:mm';

            function newDateString(days) {
                return moment().add(days, 'd').format(timeFormat);
            }

            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            type: 'bar',
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                        });
                    }
                }
            });
            var config = {
                type: 'bar',
                data: {
                    labels: label,
                    datasets: dataset
                },
                options: {
                    title: {
                        text: title,
                        display: true
                    },
                    /*color: function (context) {

                        var index = context.dataIndex;
                        var value = context.dataset.data[index];
                        return value > 20 ? 'red' :  // draw negative values in red
                            index % 2 ? 'blue' :    // else, alternate values in blue and green
                                'green';
                    },*/
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            display: true,
                            time: {
                                format: timeFormat,
                                // round: 'day'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                min: 0,
                                max: 500,
                                stepSize: 100
                            }
                        }]
                    },
                }
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myLine = new Chart(ctx, config);

            var colorNames = Object.keys(CHART_COLORS);
        }

        function addbarchart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            var color = Chart.helpers.color;
            var barChartData = {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Dataset 1',
                    backgroundColor: color(CHART_COLORS.red).alpha(0.5).rgbString(),
                    borderColor: CHART_BORDER_COLORS.red,
                    borderWidth: 1,
                    data: [
                        65, 59, 80, 81, 56, 55, 40
                    ]
                }]

            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    title: {
                        display: true,
                        text: title
                    }
                }
            });
        }

        function addStackedBarChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            const DATA_COUNT = 7;
            const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                        });
                    }
                }
            });

            const data = {
                labels: label,
                datasets: dataset
            };
            var delayed;
            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    layout: {
                        padding: 5
                    },
                    animation: {
                        onComplete: () => {
                            delayed = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed) {
                                delay = context.dataIndex * 300 + context.datasetIndex * 100;
                            }
                            return delay;
                        },
                    },
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            var myChart = new Chart(ctx, config);
        }

        function addProgressiveLineChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            const data = [];
            const data2 = [];
            let prev = 100;
            let prev2 = 80;
            for (let i = 0; i < 1000; i++) {
                prev += 5 - Math.random() * 10;
                data.push({x: i, y: prev});
                prev2 += 5 - Math.random() * 10;
                data2.push({x: i, y: prev2});
            }

            const totalDuration = 10000;
            const delayBetweenPoints = totalDuration / data.length;
            const previousY = (ctx) => ctx.index === 0 ? ctx.chart.scales.y.getPixelForValue(100) : ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.index - 1].getProps(['y'], true).y;
            const animation = {
                x: {
                    type: 'number',
                    easing: 'linear',
                    duration: delayBetweenPoints,
                    from: NaN, // the point is initially skipped
                    delay(ctx) {
                        if (ctx.type !== 'data' || ctx.xStarted) {
                            return 0;
                        }
                        ctx.xStarted = true;
                        return ctx.index * delayBetweenPoints;
                    }
                },
                y: {
                    type: 'number',
                    easing: 'linear',
                    duration: delayBetweenPoints,
                    from: previousY,
                    delay(ctx) {
                        if (ctx.type !== 'data' || ctx.yStarted) {
                            return 0;
                        }
                        ctx.yStarted = true;
                        return ctx.index * delayBetweenPoints;
                    }
                }
            };

            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                            radius: 0,
                        });
                    }
                }
            });

            const config = {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Dataset 1',
                        borderColor: CHART_BORDER_COLORS.red,
                        borderWidth: 1,
                        radius: 0,
                        data: data,
                    },
                        {
                            label: 'Dataset 2',
                            borderColor: CHART_BORDER_COLORS.blue,
                            borderWidth: 1,
                            radius: 0,
                            data: data2,
                        }]
                },
                options: {
                    animation,
                    interaction: {
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    },
                    scales: {
                        x: {
                            type: 'linear'
                        }
                    }
                }
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            var myChart = new Chart(ctx, config);
        }

        function addVerticalBarChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
            const DATA_COUNT = 7;
            const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

            const labels = months({count: 7});

            var values = [val1,val2,val3,val4];
            var dataset = [];
            $.each(values,function (i,val) {
                if(arrayIsEmpty(val) != true){
                    var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                    if($.trim(l) != "") {
                        dataset.push({
                            label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                            backgroundColor: (i == 0) ? CHART_COLORS.red : ((i == 1) ? CHART_COLORS.blue : (i == 2) ? CHART_COLORS.yellow : CHART_COLORS.green),
                            borderColor: (i == 0) ? CHART_BORDER_COLORS.red : ((i == 1) ? CHART_BORDER_COLORS.blue : (i == 2) ? CHART_BORDER_COLORS.yellow : CHART_BORDER_COLORS.green),
                            borderWidth: 1,
                            data: val,
                            radius: 0,
                        });
                    }
                }
            });
            const data = {
                labels: label,
                datasets: dataset
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display : true,
                            align : 'end',
                            position : 'top',
                            labels : {
                                display : true,
                                boxWidth : 8,
                                boxHeight : 8,
                                font: {
                                    size: fontsize
                                }
                            },
                        },
                        title: {
                            display: false,
                            text: title,
                            align :'start'
                        }
                    }
                },
            };

            var ctx = document.getElementById('can-' + pos).getContext('2d');
            var myChart = new Chart(ctx, config);
        }

    </script>
</div>
@stop
