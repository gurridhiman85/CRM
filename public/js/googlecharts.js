$(document).ready(function () {
    google.charts.load('current', {'packages': ['corechart']});

    google.load("visualization", "1", {
        packages: ["corechart"]
    });
});

function getdataset_v1(params) {
    var values = [];
    var dData = [];
    var headers = [];

    if(arrayIsEmpty(params.label) !== true){
        headers.push('Group');
        values.push(params.label);
    }
    if(arrayIsEmpty(params.value1) !== true && params.legend1 !== ""){
        headers.push(params.legend1);
        headers.push({role : 'style'})
        values.push(params.value1);
    }
    if(arrayIsEmpty(params.value2) !== true && params.legend2 !== ""){
        headers.push(params.legend2);
        headers.push({role : 'style'})
        values.push(params.value2);
    }
    if(arrayIsEmpty(params.value3) !== true && params.legend3 !== ""){
        headers.push(params.legend3);
        headers.push({role : 'style'})
        values.push(params.value3);
    }
    if(arrayIsEmpty(params.value4) !== true && params.legend4 !== ""){
        headers.push(params.legend4);
        headers.push({role : 'style'})
        values.push(params.value4);
    }
    dData.push(headers);

    for (var k = 0; k < values[0].length; k++){
        var entry = [];
        for(var i = 0; i < 5; i++){
            if(typeof values[i] != "undefined") {
                if(values[i][k] != ""){
                    if(i == 0){
                        entry.push(values[i][k]);
                    }else {
                        var val = values[i][k];
                        entry.push(parseFloat(values[i][k]));
                        //var color = val > 0 ? params.legendColors[0]['legend'+i+'_Background_Color'] : 'red';
                        var color = val < 0 && params.chart_type !== 'googleline' ? 'red' :  params.legendColors[0]['legend'+i+'_Background_Color'];

                        entry.push('color:' +color)
                    }
                }
            }
        }
        dData.push(entry)
    }
    console.log(dData);
    return dData;
}

function getdataset_v2(params) {
    var values = [];
    var dData = [];
    var headers = [];

    if(arrayIsEmpty(params.label) !== true){
        headers.push('Group');
        values.push(params.label);
    }
    if(arrayIsEmpty(params.value1) !== true && params.legend1 !== ""){
        headers.push(params.legend1);
        headers.push({role : 'style'})
        values.push(params.value1);
    }
    if(arrayIsEmpty(params.value2) !== true && params.legend2 !== ""){

        headers.push(params.legend2);
        headers.push({role : 'style'})
        values.push(params.value2);

    }
    dData.push(headers);

    for (var k = 0; k < values[0].length; k++){
        var entry = [];
        for(var i = 0; i < 3; i++){
            if(typeof values[i] != "undefined") {
                if(values[i][k] !== ""){
                    if(i === 0){
                        entry.push(values[i][k]);
                    }else {
                        var val = parseFloat(values[i][k]);
                        var color = val < 0 && params.chart_type !== 'googleline' ? 'red' :  params.legendColors[0]['legend'+i+'_Background_Color'];
                        if(i === 2){
                            entry.push(val);
                            entry.push('stroke-color: #96d7fd; stroke-width: 2; fill-color: #cdeefd;'+'color:' +color)
                        }else{
                            entry.push(val);
                            entry.push('color:' +color)
                        }

                    }
                }
            }
        }
        dData.push(entry)
    }
    return dData;
}

function getdataset(params) {

    var values = [params.label, params.value1,params.value2];
    var dData = [];
    dData.push(['Label', params.legend1, params.legend2, {role : 'style'}, params.legend2])
    for (var k = 0; k < values[0].length; k++){
        var entry = [];
        for(var i = 0; i < 4; i++){
            if(i == 3){
                entry.push('stroke-color: #96d7fd; stroke-width: 2; fill-color: #cdeefd')
                entry.push(values[2][k])
            }else{
                if(values[i][k] !== undefined && values[i][k] != "") {
                    if(i == 0){
                        console.log('values --- ',i,k,'----',values[i][k])
                        entry.push(values[i][k]);
                    }else {
                        entry.push(parseInt(values[i][k]));
                    }
                }
            }
        }

        dData.push(entry)
    }
    return dData;
}

function addEntry(chart_type, value, titleid) {
    //console.log(chart_type);
    // Parse any JSON previously stored in allEntries
    var existingEntries = JSON.parse(localStorage.getItem("allEntries"));
    if(existingEntries == null) existingEntries = [];
    var entry = {
        title : $('#' + titleid).text(),
        img : '<img style="display: block;  width: 100%; " src="' + value + '" class="img-responsive">',
    };
    // Save allEntries back to local storage
    existingEntries.push(entry);
    localStorage.setItem("allEntries", JSON.stringify(existingEntries));
    var existingEntries = JSON.parse(localStorage.getItem("allEntries"));

    //console.log(existingEntries)
}

function drawGoogleBarLineChart(params) {
    var data = google.visualization.arrayToDataTable(getdataset_v2(params));
    var ctitle = params.title.replace(/[\[\]']+/g,'').replace(/_+/g, ' ');
    var htitle = '';
    var legend = {
        position: 'top',
        alignment: 'center' ,
        orientation: 'vertical'
    };

    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,2,3,
        {
            calc: "stringify",
            sourceColumn: 3,
            type: "string",
            role: "annotation"
        },]);

    var options2 = {
        title: '',
        titleTextStyle: {
            bold: false,
            fontSize: 20
        },
        legend: legend,
        //curveType: 'function',
        colors : [
            params.legendColors[0]['legend1_Background_Color'],
            params.legendColors[0]['legend2_Background_Color'],
            params.legendColors[0]['legend3_Background_Color']
        ],
        pointSize: 4,
        series: {
            0: {
                axis: 'A',
                type: "bars",
                targetAxisIndex: 0,
                border : 4,
                color: "#cdeefd",
            },
            1: {
                axis: 'P',
                type: "line",
                pointShape: 'square',
                targetAxisIndex: 1,
                color: '#ccf3e9'
            }
        },
        hAxis: {
            title: htitle,
        },
        vAxes: [{
            gridlines: {
                color: '#e8e8e8',
            },
            title: ''
        }, {
            gridlines: {
                color: 'transparent'
            },
            textPosition: 'none',
            title: ''
        }],
        axes: {
            y: {
                A : {label: 'A'},
                P : {label: 'P'}
            }
        },
        'is3D': true,
        'backgroundColor': 'transparent',
        displayAnnotations : true,
        annotations: {
            alwaysOutside: true,
            textStyle: {
                fontName: 'Times-Roman',
                fontSize: 12,
                color: '#212529',
            }
        },
        chartArea: {
            width: '60%',
            height: '70%',
        }
    };

    var descobj = document.getElementById('can-' +params.position);
    var chart = new google.visualization.LineChart(descobj);
    chart.draw(view, options2);
    google.visualization.events.addListener(chart, 'ready', function () {
        setTimeout(function () {
            addEntry('drawGoogleBarLineChart', chart.getImageURI(), 'can-title-' +params.position)
        },500)
    });
}

function googleBarChart(params) {

    var legend = {
        position: 'top',
        alignment: 'center',
        orientation: 'vertical'
    };

    var data = google.visualization.arrayToDataTable(getdataset_v1(params));
    var view = new google.visualization.DataView(data);

    var options = {
        //title : 'Monthly Coffee Production by Country',
        //hAxis: { textPosition: 'none' },
        seriesType: 'bars',
        colors : [
            params.legendColors[0]['legend1_Background_Color'],
            params.legendColors[0]['legend2_Background_Color'],
            params.legendColors[0]['legend3_Background_Color']
        ],
        legend: legend,
        chartArea: {
            width: '60%',
            height: '70%',

        },
    };

    var chart = new google.visualization.ComboChart(document.getElementById('can-' + params.position));
    chart.draw(view, options);

    setTimeout(function () {
        addEntry('googleBarChart',chart.getImageURI(), 'can-title-' +params.position)
    },500)

}

function googleLineChart(params) {
    var legend = {
        position: 'top',
        alignment: 'center' ,
        orientation: 'vertical'
    };

    var data = google.visualization.arrayToDataTable(getdataset_v1(params));
    var view = new google.visualization.DataView(data);

    var options = {
        //title: 'Company Performance',
        //curveType: 'function',
        pointSize: 4,
        legend: legend,
        'is3D': true,
        'backgroundColor': 'transparent',
        displayAnnotations : true,
        colors : [
            params.legendColors[0]['legend1_Background_Color'],
            params.legendColors[0]['legend2_Background_Color'],
            params.legendColors[0]['legend3_Background_Color']
        ],
        annotations: {
            alwaysOutside: true,
            textStyle: {
                fontName: 'Times-Roman',
                fontSize: 12,
                //bold : true,
                color: '#212529',
            }
        },
        chartArea: {
            width: '60%',
            height: '70%',
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById('can-' + params.position));
    chart.draw(view, options);

    google.visualization.events.addListener(chart, 'ready', function () {
        setTimeout(function () {
            addEntry('googleLineChart',chart.getImageURI(), 'can-title-' +params.position)
        },500)
    });
}
