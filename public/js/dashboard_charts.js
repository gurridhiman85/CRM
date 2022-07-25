
$(document).ready(function(){
    var pagename = $('.dashboard-page li a.active').data('page');
    if(pagename !== undefined){
        $('.dashboard-nav').text(pagename + ' Dashboard');
        $('#filtertype').val(pagename);
        setTimeout(function () {
            $('body').find('#dashboard_form').submit();
        },1500)
    }


    $('body').find('#dashboardfilter1').on('change',function(){
        $('body').find('#filter1').val($(this).val());
        $('body').find('#dashboard_form').submit();
    });
    $('body').find('#dashboardfilter2').on('change',function(){
        $('body').find('#filter2').val($(this).val());
        $('body').find('#dashboard_form').submit();
    });

    /*$('body').find('.ajax-Select').on('change',function(){
        $('body').find('#dashboard_form').submit();
    });*/

    ACFn.loadstickypopup = function (F, R) {

        $('.dash').html(R.html);

        if (R.type && R.type == 'standard') {
            $('#dashboardfilter1').html('');
            $('#dashboardfilter2').html('');
            $.each(R.chartfilters1, function (i,col) {


                $('#dashboardfilter1')
                    .append($('<option>', {value: $.trim(col.filter1)})
                        .text($.trim(col.filter1)));

                $('#dashboardfilter1 option[value="' + R.filter1 + '"]').attr('selected', true);
            });

            $.each(R.chartfilters2, function (i,col) {


                $('#dashboardfilter2')
                    .append($('<option>', {value: $.trim(col.filter2)})
                        .text($.trim(col.filter2)));

                $('#dashboardfilter2 option[value="' + R.filter2 + '"]').attr('selected', true);
            });

            $.each(R.chartLabels, function (i,col) {

                if(col.Notes1 != "") {
                    $('.Notes1').show();
                    $('#Notes1_Value').val(col.Notes1);
                }
                else
                    $('.Notes1').hide();

                if(col.Notes2 != "") {
                    $('.Notes2').show();
                    $('#Notes2_Value').text(col.Notes2);
                }
                else
                    $('.Notes2').hide();

                if(col.Notes3 != "") {
                    $('.Notes3').show();
                    $('#Notes3_Value').text(col.Notes3);
                }
                else
                    $('.Notes3').hide();

                if(col.Notes4 != "") {
                    $('.Notes4').show();
                    $('#Notes4_Value').text(col.Notes4);
                }
                else
                    $('.Notes4').hide();

            });

            $.each(R.linechart_data, function (i) {
                var legendColors = [];
                var position = R.linechart_data[i].chart_position;
                var title = R.linechart_data[i].chart_title;
                var legend1 = R.linechart_data[i].chart_legend1;
                var legend2 = R.linechart_data[i].chart_legend2;
                var legend3 = R.linechart_data[i].chart_legend3;
                var legend4 = R.linechart_data[i].chart_legend4;
                legendColors.push({
                    'legend1_Background_Color' : R.linechart_data[i].Legend1_Background_Color,
                    'legend2_Background_Color' : R.linechart_data[i].Legend2_Background_Color,
                    'legend3_Background_Color' : R.linechart_data[i].Legend3_Background_Color,
                    'legend4_Background_Color' : R.linechart_data[i].Legend4_Background_Color,
                    'legend5_Background_Color' : R.linechart_data[i].Legend5_Background_Color,
                    'legend6_Background_Color' : R.linechart_data[i].Legend6_Background_Color,

                    'legend1_Border_Color' : R.linechart_data[i].Legend1_Border_Color,
                    'legend2_Border_Color' : R.linechart_data[i].Legend2_Border_Color,
                    'legend3_Border_Color' : R.linechart_data[i].Legend3_Border_Color,
                    'legend4_Border_Color' : R.linechart_data[i].Legend4_Border_Color,
                    'legend5_Border_Color' : R.linechart_data[i].Legend5_Border_Color,
                    'legend6_Border_Color' : R.linechart_data[i].Legend6_Border_Color,
                    'Chart_Scale' : R.linechart_data[i].Chart_Scale,
                    'Format' : R.linechart_data[i].Format,

                });

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
                if($('#can-' + position).length > 0){
                    var tagName = $('#can-' + position).get(0).tagName
                    if (R.linechart_data[i].chart_type == 'pie') {
                        addpiechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                    }
                    if (R.linechart_data[i].chart_type == 'doughnut') {
                        addDoughnutPiechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);

                    }
                    if (R.linechart_data[i].chart_type == 'line') {
                        addlinechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }
                    if (R.linechart_data[i].chart_type == 'combochart') {
                        addcombochart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'combochart1') {
                        addcombochart1(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'combochart2') {
                        addcombochart2(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }


                    if (R.linechart_data[i].chart_type == 'bar') {
                        addbarchart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'stackedbar') {
                        addStackedBarChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'verticalbar') {
                        addVerticalBarChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'progressiveline') {
                        addProgressiveLineChart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'morriesareachart') {
                        morriesareachart(position, title);
                        $('#can-title-' + position).text(title);
                    }
                    if (R.linechart_data[i].chart_type == 'arealine') {
                        addarealinechart(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4,legendColors);
                        $('#can-title-' + position).text(title);
                    }

                    if (R.linechart_data[i].chart_type == 'top_new_customers') {
                        if(tagName != 'DIV'){
                            $('#can-' + position).replaceWith(function(){
                                return $("<div />", {id: 'can-' + position, html: ''});
                            });
                        }
                        $('#can-' + position).addClass('table-responsive')
                            .addClass('mb-1');
                        topnewcustomers(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                        $('#can-title-' + position).text(title);
                    }
                    if (R.linechart_data[i].chart_type == 'analytics') {
                        analyticsBox(position, title, legend1, legend2, legend3, legend4, label, value1, value2, value3, value4);
                        $('#can-title-' + position).text(title);
                    }
                }
            });
            /* morries();

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
             sparklineLogin();*/

        }
    }


});

function syncDashboard(val,link) {
    var new_url= base_url+'/'+link;
    window.history.pushState("data","CRM",new_url);
    //document.title=link;

    $('#filtertype').val(val);
    $('.dashboard-nav').text(val + ' Dashboard');
    setTimeout(function () {
        $('body').find('#dashboard_form').submit();
    },1500)
}

function printDashboard() {
    var dash = document.getElementById("dash").innerHTML;
    var win = window.open();
    //var myStyle = '<link rel="stylesheet" type="text/css" href="https://crmsquare.com/DB_CRMLV_v16D_AB/css/style-horizontal.min.css?ver=1627474391" media="print"/> ';
    //win.document.write(myStyle+ dash);

    win.document.write('<html><head><title></title>');
    win.document.write('<link rel="stylesheet" href="https://crmsquare.com/DB_CRMLV_v16D_AB/css/style-horizontal.min.css?ver=1627474391" type="text/css" />');
    win.document.write('<style type="text/css">.test { color:red; } </style></head><body>');
    win.document.write(dash);
    win.document.write('</body></html>');
    win.print();
    win.close();
}

function topnewcustomers(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
    var tableHtml = '';
    tableHtml = '<table class="table mb-1">\n' +
        '                            <thead>\n' +
        '                            <tr>\n' +
        '                                <th>' + leg1 + '</th>\n' +
        '                                <th>' + leg2 + '</th>\n' +
        '                                <th>' + leg3 + '</th>\n' +
        '                                <th>' + leg4 + '</th>\n' +
        '                            </tr>\n' +
        '                            </thead>\n' +
        '                            <tbody>\n' ;
    $.each(label,function (i,lb) {
        val1[i] = $.trim(val1[i]);
        val2[i] = $.trim(val2[i]);
        val3[i] = $.trim(val3[i]);
        val4[i] = $.trim(val4[i]);
        tableHtml += '                            <tr>\n' +
            '                                <td>' + val1[i] + '</td>\n' +
            '                                <td>' + val2[i] + '</td>\n' +
            '                                <td>' + val3[i] + '</td>\n' +
            '                                <td><span class="label ' + lb + '">' + val4[i] + '</span> </td>\n' +
            '                            </tr>\n';
    });
    tableHtml += '                            </tbody>\n' +
        '                        </table>';

    $('#can-' + pos).html(tableHtml);
}

function analyticsBox(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {

    var boxhtml = '';
    $.each(label,function (i,lb) {
        val1[i] = $.trim(val1[i]);
        val2[i] = $.trim(val2[i]);
        var cls = 'pr-1';
        var colorCls = 'round-danger';
        if((parseInt(i)+1) % 2 == 0){
            cls = 'pl-1';
        }
        if(val2[i] == 'fa-user'){
            colorCls = 'round-success';
        }
        if(val2[i] == 'fa-calendar-alt'){
            colorCls = 'btn-info';
        }
        /*boxhtml += '<div class="col-md-6 ' + cls + '">\n' +
            '                <div class="card mb-1">\n' +
            '                    <div class="card-body p-3 pl-4">\n' +
            '                        <div class="d-flex flex-row">\n' +
            '                            <div class="round align-self-center ' + colorCls + '"><i class="fas ' +  val2[i] + '"></i></div>\n' +
            '                            <div class="m-l-10 align-self-center">\n' +
            '                                <h6 class="m-b-0">'+ val1[i] +'</h6>\n' +
            '                                <h7 class="text-muted m-b-0">' + lb + '</h7>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </div>';*/

        boxhtml += '<div class="col-md-6 ' + cls + '">\n' +
            '                <div class="card mb-1">\n' +
            '                    <div class="card-body p-3 pl-4 cp-3">\n' +
            '                        <div class="d-flex flex-row">\n' +
            '                            <div class="round align-self-center ' + colorCls + '"><i class="fas ' +  val2[i] + '"></i></div>\n' +
            '                            <div class="m-l-10 align-self-center">\n' +
            '                                <h5 class="m-b-0">'+ val1[i] +'</h5>\n' +
            '                                <h6 class="text-muted m-b-0">' + lb + '</h6>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>\n' +
            '            </div>';
    });
    $('#can-' + pos).html(boxhtml);
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
var fontsize = 12;
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
    orange: '#00c292',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)',
    light_blue : '#03a9f3',
    light_purple1 : '#8bb5ff',
    light_yellow : 'rgb(255, 205, 86)',
    light_orange : '#fb9678',
    light_purple : '#b7b0d9',
    light_green : '#00c292',
};

const CHART_COLORS = {
    red: 'rgba(255, 99, 132, 0.2)',
    orange: 'rgb(255, 159, 64, 0.2)',
    yellow: 'rgb(255, 205, 86, 0.2)',
    green: 'rgb(75, 192, 192, 0.2)',
    blue: 'rgb(54, 162, 235, 0.2)',
    purple: 'rgb(153, 102, 255, 0.2)',
    grey: 'rgb(201, 203, 207, 0.2)',
    light_blue : 'rgba(54, 162, 235, 1)',
    light_purple1 : '#b9cfff',
    light_yellow : 'rgba(255, 205, 86, 0.2)',
    light_orange : 'rgba(251, 150, 120, 0.2)',
    light_purple : '#f1edff',
    light_green : 'rgba(204, 243, 233, 1)',
};

const NAMED_COLORS = [
    CHART_COLORS.red,
    CHART_COLORS.orange,
    CHART_COLORS.yellow,
    CHART_COLORS.green,
    CHART_COLORS.blue,
    CHART_COLORS.purple,
    CHART_COLORS.grey,
    CHART_COLORS.light_purple1,
    CHART_COLORS.light_yellow,
    CHART_COLORS.light_orange
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
    total = total.toLocaleString();

    return total;
}

function addlinechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    function createConfig(gridlines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
        var values = [val1,val2,val3,val4];
        var dataset = [];
        $.each(values,function (i,val) {
            if(arrayIsEmpty(val) != true){
                var brcolors = [];
                var bgcolors = [];
                for(var k = 0; k < val.length; k++){
                    var brcolor;
                    var bgcolor;
                    if(val[k] > 0){
                        if(i == 0){
                            brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                            bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                        }else if(i == 1){
                            brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                            bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                        }else if(i == 2){
                            brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                            bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                        }else{
                            brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                            bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                        }
                    }else{
                        brcolor = CHART_BORDER_COLORS.red;;
                        bgcolor = CHART_COLORS.red;;
                    }
                    bgcolors[k] = bgcolor;
                    brcolors[k] = brcolor;
                }
                var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                if($.trim(l) != ""){
                    dataset.push({
                        type: 'line',
                        label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                        backgroundColor: bgcolors,
                        borderColor: brcolors,
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
            //plugins: [ChartDataLabels],
            options: {
                elements: {
                    point:{
                        radius: 1
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        display : checklegend(leg1, leg2, leg3, leg4),
                        align : 'end',
                        position : 'top',
                        labels : {
                            display : true,
                            boxWidth : 5,
                            boxHeight : 5,
                            font: {
                                size: 12
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
                    x: {
                        type : 'category',
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                            },
                            color: '#212529',
                        }
                    },
                    y: {
                        type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                        grid: {
                            drawBorder: true,
                            color: function(context) {
                                if (context.tick.value > 0) {
                                    return false;
                                } else if (context.tick.value < 0) {
                                    return false;
                                }

                                return '#d2d2d2';
                            },
                        },
                    }
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

function addarealinechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    function createConfig(gridlines, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4) {
        var values = [val1,val2,val3,val4];
        var dataset = [];
        $.each(values,function (i,val) {
            if(arrayIsEmpty(val) != true){
                var brcolors = [];
                var bgcolors = [];
                for(var k = 0; k < val.length; k++){
                    var brcolor;
                    var bgcolor;
                    if(val[k] > 0){
                        if(i == 0){
                            brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                            bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                        }else if(i == 1){
                            brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                            bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                        }else if(i == 2){
                            brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                            bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                        }else{
                            brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                            bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                        }
                    }else{
                        brcolor = CHART_BORDER_COLORS.red;;
                        bgcolor = CHART_COLORS.red;;
                    }
                    bgcolors[k] = bgcolor;
                    brcolors[k] = brcolor;
                }
                var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
                if($.trim(l) != ""){
                    dataset.push({
                        type: 'line',
                        label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                        backgroundColor: bgcolors,
                        borderColor: brcolors,
                        borderWidth : 1,
                        data: val,
                        fill: true,

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
            //plugins: [ChartDataLabels],
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
                        display : checklegend(leg1, leg2, leg3, leg4),
                        align : 'end',
                        position : 'top',
                        labels : {
                            display : true,
                            boxWidth : 5,
                            boxHeight : 5,
                            font: {
                                size: 12
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
                    x: {
                        type : 'category',
                        grid: {
                            display: false,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 300,
                                fontFamily: "Arial"
                            },
                            color: '#212529',
                        },
                    },
                    y: {
                        type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                        grid: {
                            drawBorder: true,
                            color: function(context) {
                                if (context.tick.value > 0) {
                                    return false;
                                } else if (context.tick.value < 0) {
                                    return false;
                                }

                                return '#d2d2d2';
                            },
                        },
                        gridLines: gridlines,
                        ticks: {
                            min: 0,
                            max: 100,
                            stepSize: 10,
                            fontSize: 12,
                            font: {
                                size: 12,
                                weight: 300,
                                fontFamily: "Arial"
                            },
                            color: '#212529',

                        }
                    }


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

function addpiechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100);
    };

    $('#can-title-' + pos).html('<small class="pull-right">\n' +
        '                                    <i class="fa fa-sort-desc"></i> Total: ' + legendColors[0]['Format'] + totalfromVal(val1) + '\n' +
        '                                    </small>' + title);


    var config = {
        type: 'pie',
        data: {
            datasets: [{
                data: val1,
                backgroundColor: [
                    legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_BORDER_COLORS.red,
                    legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_BORDER_COLORS.orange,
                    legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_BORDER_COLORS.yellow,
                    legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_BORDER_COLORS.blue,
                    legendColors[0]['legend5_Background_Color'] != "" ? legendColors[0]['legend5_Background_Color'] : CHART_BORDER_COLORS.green,
                    legendColors[0]['legend6_Background_Color'] != "" ? legendColors[0]['legend6_Background_Color'] : CHART_BORDER_COLORS.light_green,
                    CHART_BORDER_COLORS.grey,
                    CHART_BORDER_COLORS.purple

                ],

                label: 'Dataset 1',
                datalabels : {
                    formatter: (value, ctx) => {
                        let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                        if (ctx.datasetIndex === datasets.length - 1) {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data != null ? parseInt(data) : parseInt(0);
                            });
                            let percentage = (value*100 / sum).toFixed(0)+"%";
                            return percentage;

                        }
                        else {
                            return '';
                        }

                    },
                    anchor: 'center',
                    align: 'top',
                    labels: {
                        title: {
                            font: {
                                size: 11
                            }
                        },
                    }
                }
            }],
            labels: label

        },
        plugins: [ChartDataLabels],
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

function addDoughnutPiechart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {

    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100);
    };

    $('#can-title-' + pos).html('<small class="pull-right">\n' +
        '                                    <i class="fa fa-sort-desc"></i> Total: ' + legendColors[0]['Format'] + totalfromVal(val1) + '\n' +
        '                                    </small>' + title);
    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: val1,
                backgroundColor: [
                    (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_BORDER_COLORS.red),
                    (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_BORDER_COLORS.orange),
                    (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_BORDER_COLORS.yellow),
                    (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_BORDER_COLORS.blue),
                    (legendColors[0]['legend5_Background_Color'] != "" ? legendColors[0]['legend5_Background_Color'] : CHART_BORDER_COLORS.green),
                    (legendColors[0]['legend6_Background_Color'] != "" ? legendColors[0]['legend6_Background_Color'] : CHART_BORDER_COLORS.light_green),
                    CHART_BORDER_COLORS.grey,
                    CHART_BORDER_COLORS.purple
                ],
                label: 'Dataset 1',
                datalabels : {
                    formatter: (value, ctx) => {
                        let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                        if (ctx.datasetIndex === datasets.length - 1) {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data != null ? parseInt(data) : parseInt(0);
                            });
                            let percentage = (value*100 / sum).toFixed(0)+"%";
                            return percentage;

                        }
                        else {
                            return '';
                        }

                    },
                    anchor: 'center',
                    align: 'top',
                    labels: {
                        title: {
                            font: {
                                size: 11
                            }
                        },
                    }
                }
            }],
            labels: label

        },
        plugins: [ChartDataLabels],
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

function addcombochart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    var timeFormat = 'MM/DD/YYYY HH:mm';

    function newDateString(days) {
        return moment().add(days, 'd').format(timeFormat);
    }
    var values = [val1,val2,val3,val4];
    var dataset = [];
    $.each(values,function (i,val) {

        if(arrayIsEmpty(val) != true){
            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }
            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    type: 'bar',
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolors,
                    hoverBackgroundColor: bgcolors,
                    borderColor: brcolors,
                    borderWidth: 1,
                    data: val,
                    datalabels : {
                        /*formatter: (value, ctx) => {
                            let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                            if (ctx.datasetIndex === datasets.length - 1) {
                                let sum = 0;
                                datasets.map(dataset => {
                                    sum += parseInt(dataset.data[ctx.dataIndex]);
                                });
                                return sum.toLocaleString(/!* ... *!/);
                            }
                            else {
                                return '';
                            }

                        },*/
                        anchor: (context) => {
                            const anchor = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                anchor.push('end');
                            }else{
                                anchor.push('start');
                            }

                            return anchor;
                        },
                        align: (context) => {
                            const align = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                align.push('top');
                            }else{
                                align.push('bottom');
                            }

                            return align;
                        },
                        labels: {
                            title: {
                                font: {
                                    size: 11
                                }
                            },
                        }
                    }
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
        plugins: [ChartDataLabels],
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
                    display : checklegend(leg1, leg2, leg3, leg4),
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
                    grid: {
                        display: false,
                    },
                    display: true,
                    time: {
                        format: timeFormat,
                        // round: 'day'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 100,
                            fontFamily: "Poppins, sans-serif"
                        },
                        color: '#212529',
                    }
                },
                y: {
                    type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    grid: {
                        drawBorder: true,
                        color: function(context) {
                            if (context.tick.value > 0) {
                                return false;
                            } else if (context.tick.value < 0) {
                                return false;
                            }

                            return '#d2d2d2';
                        },
                    }
                }
            },
        }
    };

    var ctx = document.getElementById('can-' + pos).getContext('2d');
    window.myLine = new Chart(ctx, config);

    var colorNames = Object.keys(CHART_COLORS);
}

function addcombochart1(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    var timeFormat = 'MM/DD/YYYY HH:mm';

    function newDateString(days) {
        return moment().add(days, 'd').format(timeFormat);
    }

    var values = [val1,val2,val3,val4];
    var dataset = [];
    $.each(values,function (i,val) {
        if(arrayIsEmpty(val) != true){

            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }

            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    type: 'bar',
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolors,
                    borderColor: brcolors,
                    borderWidth: 1,
                    data: val,
                    datalabels : {
                        formatter: (value, ctx) => {
                            let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                            if (ctx.datasetIndex === datasets.length - 1) {
                                let sum = 0;
                                datasets.map(dataset => {
                                    sum += dataset.data[ctx.dataIndex] != null ? parseInt(dataset.data[ctx.dataIndex]) : parseInt(0);
                                });
                                return sum.toLocaleString(/* ... */);
                            }
                            else {
                                return '';
                            }

                        },
                        anchor: (context) => {
                            const anchor = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                anchor.push('end');
                            }else{
                                anchor.push('start');
                            }

                            return anchor;
                        },
                        align: (context) => {
                            const align = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                align.push('top');
                            }else{
                                align.push('bottom');
                            }

                            return align;
                        },
                        labels: {
                            title: {
                                font: {
                                    size: 11
                                }
                            },
                        }
                    }
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
        plugins: [ChartDataLabels],
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
                    display : checklegend(leg1, leg2, leg3, leg4),
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
                    grid: {
                        display: false,
                    },
                    type: 'category',
                    display: true,
                    time: {
                        format: timeFormat,
                        // round: 'day'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }
                },
                y: {
                    type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    grid: {
                        drawBorder: true,
                        color: function(context) {
                            if (context.tick.value > 0) {
                                return false;
                            } else if (context.tick.value < 0) {
                                return false;
                            }

                            return '#d2d2d2';
                        },
                    },
                    ticks: {
                        min: 0,
                        max: 50,
                        stepSize: fontsize,
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }
                }
            },
        }
    };

    var ctx = document.getElementById('can-' + pos).getContext('2d');
    window.myLine = new Chart(ctx, config);

    var colorNames = Object.keys(CHART_COLORS);
}

function addcombochart2(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    var timeFormat = 'MM/DD/YYYY HH:mm';

    function newDateString(days) {
        return moment().add(days, 'd').format(timeFormat);
    }

    var values = [val1,val2,val3,val4];
    var dataset = [];
    $.each(values,function (i,val) {
        if(arrayIsEmpty(val) != true){
            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }

            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    type: 'bar',
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolor,
                    borderColor: brcolors,
                    borderWidth: 1,
                    data: val,
                    datalabels : {
                        formatter: (value, ctx) => {
                            let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                            if (ctx.datasetIndex === datasets.length - 1) {
                                let sum = 0;
                                datasets.map(dataset => {
                                    sum += dataset.data[ctx.dataIndex] != null ? parseInt(dataset.data[ctx.dataIndex]) : parseInt(0);
                                });
                                return sum.toLocaleString(/* ... */);
                            }
                            else {
                                return '';
                            }

                        },
                        anchor: (context) => {
                            const anchor = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                anchor.push('end');
                            }else{
                                anchor.push('start');
                            }

                            return anchor;
                        },
                        align: (context) => {
                            const align = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                align.push('top');
                            }else{
                                align.push('bottom');
                            }

                            return align;
                        },
                        labels: {
                            title: {
                                font: {
                                    size: 11
                                }
                            },
                        }
                    }
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
        plugins: [ChartDataLabels],
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
                    display : checklegend(leg1, leg2, leg3, leg4),
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
                    grid: {
                        display: false,
                    },
                    type: 'category',
                    display: true,
                    time: {
                        format: timeFormat,
                        // round: 'day'
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }
                },
                y: {
                    type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    grid: {
                        drawBorder: true,
                        color: function(context) {
                            if (context.tick.value > 0) {
                                return false;
                            } else if (context.tick.value < 0) {
                                return false;
                            }

                            return '#d2d2d2';
                        },
                    },
                    ticks: {
                        min: 0,
                        max: 500,
                        stepSize: 100,
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }

                }
            },
        }
    };

    var ctx = document.getElementById('can-' + pos).getContext('2d');
    window.myLine = new Chart(ctx, config);

    var colorNames = Object.keys(CHART_COLORS);
}

function addbarchart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
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

function addStackedBarChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    const DATA_COUNT = 7;
    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

    var values = [val1,val2,val3,val4];
    var dataset = [];
    $.each(values,function (i,val) {
        if(arrayIsEmpty(val) != true){
            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }
            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolors,
                    borderColor: brcolors,
                    borderWidth: 1,
                    data: val,
                    datalabels : {
                        formatter: (value, ctx) => {
                            let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                            if (ctx.datasetIndex === datasets.length - 1) {

                                let sum = 0;
                                datasets.map(dataset => {
                                    sum += dataset.data[ctx.dataIndex] != null ?
                                        parseInt(dataset.data[ctx.dataIndex]) :
                                        parseInt(0);
                                });

                                return sum.toLocaleString(/* ... */);
                            }
                            else {
                                return '';
                            }

                        },
                        anchor: (context) => {
                            const anchor = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                anchor.push('end');
                            }else if(context.dataset.data[context.dataIndex] == null){
                                anchor.push('end');
                            }else{
                                anchor.push('start');
                            }
                            return anchor;
                        },
                        align: (context) => {
                            const align = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                align.push('top');
                            }else if(context.dataset.data[context.dataIndex] == null){
                                align.push('top');
                            }else{
                                align.push('bottom');
                            }
                            return align;
                        },
                        labels: {
                            title: {
                                font: {
                                    size: 11
                                }
                            },
                        }

                    }
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
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display : checklegend(leg1, leg2, leg3, leg4),
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
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    },
                },
                y: {
                    type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    stacked: true,
                    grid: {
                        drawBorder: true,
                        color: function(context) {
                            if (context.tick.value > 0) {
                                return false;
                            } else if (context.tick.value < 0) {
                                return false;
                            }

                            return '#e4e4e4';
                        },
                    },
                }
            }
        }
    };

    var ctx = document.getElementById('can-' + pos).getContext('2d');
    var myChart = new Chart(ctx, config);
}

function addProgressiveLineChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
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
            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }
            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolors,
                    borderColor: brcolors,
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
                    type : 'linear',
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    },
                },
                y: {
                    //type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: 300,
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }
                }
            }
        }
    };
    var ctx = document.getElementById('can-' + pos).getContext('2d');
    var myChart = new Chart(ctx, config);
}

function addVerticalBarChart(pos, title, leg1, leg2, leg3, leg4, label, val1, val2, val3, val4,legendColors) {
    const DATA_COUNT = 7;
    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

    const labels = months({count: 7});

    var values = [val1,val2,val3,val4];
    var dataset = [];

    $.each(values,function (i,val) {
        if(arrayIsEmpty(val) != true){
            var brcolors = [];
            var bgcolors = [];
            for(var k = 0; k < val.length; k++){
                var brcolor;
                var bgcolor;
                if(val[k] > 0){
                    if(i == 0){
                        brcolor = (legendColors[0]['legend1_Border_Color'] != "" ? legendColors[0]['legend1_Border_Color'] : CHART_BORDER_COLORS.light_green);

                        bgcolor = (legendColors[0]['legend1_Background_Color'] != "" ? legendColors[0]['legend1_Background_Color'] : CHART_COLORS.light_green);

                    }else if(i == 1){
                        brcolor = (legendColors[0]['legend2_Border_Color'] != "" ? legendColors[0]['legend2_Border_Color'] : CHART_BORDER_COLORS.light_purple);

                        bgcolor = (legendColors[0]['legend2_Background_Color'] != "" ? legendColors[0]['legend2_Background_Color'] : CHART_COLORS.light_purple);

                    }else if(i == 2){
                        brcolor = (legendColors[0]['legend3_Border_Color'] != "" ? legendColors[0]['legend3_Border_Color'] : CHART_BORDER_COLORS.yellow);

                        bgcolor = (legendColors[0]['legend3_Background_Color'] != "" ? legendColors[0]['legend3_Background_Color'] : CHART_COLORS.yellow);

                    }else{
                        brcolor = (legendColors[0]['legend4_Border_Color'] != "" ? legendColors[0]['legend4_Border_Color'] : CHART_BORDER_COLORS.green);

                        bgcolor = (legendColors[0]['legend4_Background_Color'] != "" ? legendColors[0]['legend4_Background_Color'] : CHART_COLORS.green);
                    }
                }else{
                    brcolor = CHART_BORDER_COLORS.red;;
                    bgcolor = CHART_COLORS.red;;
                }
                bgcolors[k] = bgcolor;
                brcolors[k] = brcolor;
            }
            var l = (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4);
            if($.trim(l) != "") {
                dataset.push({
                    label: (i == 0) ? leg1 : ((i == 1) ? leg2 : (i == 2) ? leg3 : leg4),
                    backgroundColor: bgcolors,
                    borderColor: brcolors,
                    borderWidth: 1,
                    data: val,
                    radius: 0,
                    datalabels : {
                        formatter: (value, ctx) => {
                            let datasets = ctx.chart.data.datasets; // Tried `.filter(ds => !ds._meta.hidden);` without success
                            if (ctx.datasetIndex === datasets.length - 1) {
                                let sum = 0;
                                datasets.map(dataset => {
                                    sum += dataset.data[ctx.dataIndex] != null ? parseInt(dataset.data[ctx.dataIndex]) : parseInt(0);
                                });
                                return sum.toLocaleString(/* ... */) + '%';
                            }


                            else {
                                return '';
                            }

                        },
                        anchor: (context) => {
                            const anchor = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                anchor.push('end');
                            }else{
                                anchor.push('start');
                            }

                            return anchor;
                        },
                        align: (context) => {
                            const align = [];
                            if(context.dataset.data[context.dataIndex] >= 0){
                                align.push('top');
                            }else{
                                align.push('bottom');
                            }

                            return align;
                        },
                        labels: {
                            title: {
                                font: {
                                    size: 11
                                }
                            },
                        }
                    }
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
        plugins: [ChartDataLabels],
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display : checklegend(leg1, leg2, leg3, leg4),
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

                    grid: {
                        display: false,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: "300",
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    },
                },
                /*y: {
                    grid: {
                        display: true,
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: "300",
                            fontFamily: "Arial"
                        },
                        color: '#212529',
                    }
                },*/

                y: {
                    type : legendColors[0]['Chart_Scale'] != "" ? legendColors[0]['Chart_Scale'] : 'linear',
                    grid: {
                        drawBorder: true,
                        color: function(context) {
                            if (context.tick.value > 0) {
                                return false;
                            } else if (context.tick.value < 0) {
                                return false;
                            }

                            return '#d2d2d2';
                        },
                    },
                }
            }
        },
    };

    var ctx = document.getElementById('can-' + pos).getContext('2d');
    var myChart = new Chart(ctx, config);
}

function checklegend(l1,l2,l3,l4) {
    var lc = 0;
    if(l1 != "")
        lc++;
    if(l2 != "")
        lc++;
    if(l3 != "")
        lc++;
    if(l4 != "")
        lc++;
    return lc > 1 ? true : false;
}
