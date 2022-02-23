$(document).ready(function () {

    ACFn.ajax_touch = function (F,R) {
        if(R.success){
            $('#row_' + R.tocuh_data.DS_MKC_ContactID + ' td')
                .find('span')
                .attr('class',R.call_class);

            /*$('#DS_MKC_ContactID_' + R.tocuh_data.DS_MKC_ContactID)
                .find('span')
                .attr('class',R.call_class);*/
        }
    }


    ACFn.ajax_run_report_result_outer = function (F,R) {
        if (R.success) {
            localStorage.removeItem('xlsxData');
            var xlsxData = [];
            var entry = {
                "xHeader" : R.result.xHeaders,
                "bHeader" : R.result.bHeaders,
                "val" : R.result.val,
                "postfix" : R.result.postfix,
            };
            xlsxData.push(entry);
            localStorage.setItem("xlsxData", JSON.stringify(xlsxData));
            console.log(JSON.parse(localStorage.getItem("xlsxData")))
            if(R.chart_variable != ""){
                chart_change_outer(R.row_variable,R.column_variable,R.sum_variable,R.function_variable,R.show_as,R.chart_variable,'column_variable_input',R.chart_axis_scale,R.chart_label_value);
            }
        }
    }


});

function fillComment(obj,e) {
    if(e.which == 13 && obj.val() != "") {
        touchCall(obj.data('ds_mkc_contactid'),'TouchNotes',obj.val());
    }
}

function changeStatus(obj) {
    if(obj.val() != ""){
        touchCall(obj.data('ds_mkc_contactid'),'TouchStatus',obj.val());
    }
}

function touchCall(contactid,column,value) {
    ACFn.sendAjax('phone/touch','POST',{
        ds_mkc_contactid : contactid,
        column_name : column,
        column_value : value
    });
}

function run_report_inPhone(dataouter){
    if(dataouter.Report_Row != ""){

        var Report_Row = dataouter.Report_Row;
        var Report_Column = dataouter.Report_Column;
        var Report_Function = dataouter.Report_Function;
        var Report_Sum = dataouter.Report_Sum;
        var Report_Show = dataouter.Report_Show;

        var Chart_Type = $.trim(dataouter.Chart_Type);
        var Chart_Image = dataouter.Chart_Image;
        var Axis_Scale = dataouter.Axis_Scale;
        var Label_Value = dataouter.Label_Value;
        var List_Level = dataouter.list_level;
        var sql = dataouter.sql;
        var url = 'run';

        if( Report_Row.indexOf(',') != -1 ){
            Report_Row = Report_Row.split(',');
        }else{
            Report_Row = [Report_Row];
        }

        ACFn.sendAjax(url,'get',{
            list_level : List_Level,
            sql : sql,
            row_variable : Report_Row,
            column_variable : Report_Column,
            function_variable : Report_Function,
            sum_variable : Report_Sum,
            show_as : Report_Show,
            chart_variable : Chart_Type,
            chart_axis_scale : Axis_Scale,
            chart_label_value : Label_Value,
            inner_call : 2,
        },'',{},false)
    }
}

function implementReportWithPhone(request_type,report_rowid) {
    var interval = null;
    if(request_type == 'report'){
        $.ajax({
            type: 'GET',
            url: 'report/recd',
            data: {
                _token : $('[name="_token"]').val(),
                tempid : report_rowid
            },
            async: false,
            success: function (datao) {
                if(!datao.success){
                    ACFn.display_message(datao.messageTitle,'','success');
                }
                var dataouter = datao.aData;

                //localStorage.removeItem('record');

                var sSQL = dataouter.sql;
                /*var metaData = dataouter.meta_data;
                var metaStr = metaData.split('^');*/
                var params = JSON.parse(localStorage.getItem('params'));
                var currentTime = new Date();
                // returns the month (from 0 to 11)
                var r9 = currentTime.getMonth() + 1;
                // returns the day of the month (from 1 to 31)
                var r10 = currentTime.getDate();
                // returns the year (four digits)
                var r8 = currentTime.getFullYear();
                params = {
                    row_id : dataouter.row_id,
                    CID : dataouter.t_id,
                    CName : dataouter.t_name,
                    listShortName : dataouter.list_short_name,
                    meta_description : dataouter.rpmeta.Category,
                    sSQL : dataouter.sql,
                    list_level : dataouter.list_level,
                    selected_fields : dataouter.selected_fields,
                    CampaignID : dataouter.t_id,
                    Type : 'A',
                    Objective : dataouter.rpmeta.Objective,
                    Brand : dataouter.rpmeta.Brand,
                    Channel : dataouter.rpmeta.Channel,
                    Category : dataouter.rpmeta.Category,
                    ListDes : dataouter.rpmeta.ListDes,
                    Wave : dataouter.rpmeta.Wave,
                    Start_Date : r8 + '/' + r9 + '/' + r10,
                    Interval : dataouter.rpmeta.Interval,
                    ProductCat1 : dataouter.rpmeta.ProductCat1,
                    ProductCat2 : dataouter.rpmeta.ProductCat2,
                    SKU : dataouter.rpmeta.SKU,
                    Coupon : dataouter.rpmeta.Coupon,
                    Sort_Column : dataouter.rpmeta.Sort_Column,
                    Sort_Order : dataouter.rpmeta.Sort_Order,
                    schedule_action : 'download_report_inPhone',
                    _token : $('[name="_token"]').val(),
                    request_type : request_type
                };

                localStorage.setItem('params',JSON.stringify(params));
                if (dataouter.Report_Row != "" && request_type == 'report') {
                    run_report_inPhone(dataouter);
                    interval = setInterval(checkChartParam,1500);
                    function checkChartParam(){
                        var params = JSON.parse(localStorage.getItem('params'));
                        if(params.cI){
                            var requestParams = {
                                rowID : params.row_id,
                                cI : params.cI,
                                request_type : request_type,
                                _token : $('[name="_token"]').val(),
                            };
                            callFileGenerator(requestParams);
                            clearInterval(interval);
                        }
                    }
                }
            }
        });
    }else{
        var requestParams = {
            rowID : report_rowid,
            request_type : request_type,
            _token : $('[name="_token"]').val(),
        };
        callFileGenerator(requestParams);
    }
}

function callFileGenerator(requestParams) {
    $.ajax({
        url: 'phone/downloadphonereport',
        type: 'POST',
        data: requestParams,
        async: false,
        beforeSend: function () {
            NProgress.start();
        },
        success: function (R) {
            NProgress.done(true);
            if(R.success && R.redirect){
                a = document.createElement('a');
                a.href = R.redirectURL;
                // Give filename you wish to download
                a.download = R.file_name;
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                a.remove();
            }else{
                NProgress.done(true);
                ACFn.display_message(R.messageTitle,'','error',5000);
            }
        }
    });
}


