function profileJS($outer) {

    if ($outer.find('.ajax-DS-Link').length) {
        $outer.find('.ajax-DS-Link').on('click', function () {
            get_filter_summary();
            get_customer_excl_incl_summary(2);
            get_customer_excl_incl_summary(3);
            var url = $(this).data('href');
            var params = {
                row_variable: $('.row_variable_input').val(),
                column_variable: $('.column_variable_input').val(),
                function_variable: $('.function_input').val(),
                sum_variable: $('.sum_variable_input').val(),
                show_as: $('.show_as_input').val(),
                chart_variable: $('.chart_variable_input').val(),
                chart_axis_scale: $('.chart_axis_scale_input').val(),
                chart_label_value: $('.chart_label_value_input').val(),
            };

            localStorage.removeItem('filters');
            var filters = [];
            filters.push({
                'cont_filters': $.trim($('#filterSummmery').val()),
                'incl_filters': $.trim($('#customerInclusionSummmery').val()),
                'excl_filters': $.trim($('#customerExclusionSummmery').val())
            });
            localStorage.setItem('filters', JSON.stringify(filters));

            ACFn.sendAjax(url, 'get', {
                list_level: $('#list_level').val(),
                params: params,
                sql: $('#sqlQuery').val()
            })
        });
    }

    if ($outer.find('#sortcolumn').length) {
        $('body').on('change', '.dvd,#sortcolumn,#sortorder', function () {
            get_filter_summary();
            get_customer_excl_incl_summary(2);
            get_customer_excl_incl_summary(3);
        })
    }
    $('#showSqlBtn').hide();


}



var camp_id;
// Add Sub Group
var promoexportchk = 'N';
var previewchk = 'N';
var addsubgroupchk = 'N';
var metadatachk = 'N';
var define_Flag = 1;
/*** changed 26-06-2017 ****/             //Show the Dialog box once.
var CGOf = new Array();
var CC = new Array();
var CGD = new Array();

var oldcampclk = 'N';
var Camp_Name = "";
var deflag = 0;
var update_flag = 0;
var hide_flag = 0;

var campidArray = new Array();
var seq_num = 0;
var seg_clear_flag = 0;
var proExp_clrear_flag = 0;
var sch_val_flag = 0;

//For Execute Page
var seg_openFlag = 'N';
var promoExpo_openFlag = 'N';
var mArr = [];
//var dDate = '';//'<?php echo date('Ymd_Hi');?>';
function IntervalTimer(callback, interval) {
    var timerId, startTime, remaining = 0;
    var state = 0; //  0 = idle, 1 = running, 2 = paused, 3= resumed

    this.pause = function () {
        if (state != 1) return;

        remaining = interval - (new Date() - startTime);
        window.clearInterval(timerId);
        state = 2;
    };

    this.resume = function () {
        if (state != 2) return;

        state = 3;
        window.setTimeout(this.timeoutCallback, remaining);
    };

    this.timeoutCallback = function () {
        if (state != 3) return;

        triggerCompletedTab();

        startTime = new Date();
        timerId = window.setInterval(triggerCompletedTab, interval);
        state = 1;
    };

    startTime = new Date();
    timerId = window.setInterval(triggerCompletedTab, interval);
    state = 1;
}

/*var timer = new IntervalTimer(function () {
}, 40000);*/ // Timer for auto refresh level1 tab.

function emreport(){
    if($(".em_report:checked").length > 0){
        //$('.schreport').show();
        $('.emreport').show();
    }
    else{
        //$('.schreport').hide();
        $('.emreport').hide();
    }
}

$(document).ready(function () {


    setTimeout(function () {

        var emailMS = [
            'txtTo1'
        ];
        $.each(emailMS, function (index, value) {
                if ($("#" + value).length){
                    $("#" + value).multiselect({
                        appendTo: '#emailBox',
                        close: function () {
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values',
                        enableFiltering: true,
                        filterBehavior: 'text',
                    }).multiselectfilter({label: 'Search'});

                    $("#" + value + "_ms").attr('style', 'width:100% !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 38px;');
                    $("#" + value).multiselect('refresh');
                }
            });
    },3000)


    $('.cn-report-btn').on('click',function () {
        $(this).hide();
        $('.cl-report-btn').show();
        $('.view-report').hide();
        $('.create-new').show();
        //$('.list-report').hide();
        $('.emreport').hide();

        $('.c-btn').html('');
        //timer.pause();

        $('#showSqlBtn').hide();
        $('.search-btn').hide();
        setTimeout(function () {
            $('.csql').attr('data-chance','1');
        },4000)
        sch_val_flag = 0;
        $('a[href="#tab_23"]').trigger('click');
    });

    $('.clr-btn').on('click',function () {
        $('.cl-report-btn').hide();
        $('.cn-report-btn').show();
        $('.create-new').hide();
        $('.view-report').hide();
        $('.emreport').show();
        //$('.list-report').show();
        $('.search-btn').show();
        $('#libflag_name').val('');
        $('#libcamp_name').val('');
        $('#libcamp_id').val('');
        $('#libcId').val('');
        $('.chart').html('');
        //timer.resume();
        $('a[href="#tab_23"]').text('Create');
        $('a[href="#tab_22"]').trigger('click');
        $('#showSqlBtn').hide();
        $('#profile_pdf').hide();
        $('#profile_xlsx').hide();

    });

    var autosearch_field = $('.ajax-search');
    autosearch_field.on('keyup change paste',function(e) {
        if((e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
            var obj = $(this);
            var oldVal = obj.val();
            delay(function(){
                var fvalue = $.trim(obj.val());
                $('[name="searchterm"]').val(fvalue);
                $('.tab-ajax li a.active').trigger('show.bs.tab');
            }, 1000 );
        }
    });
})
function blankMergeData() {}

function fillprofiledesc() {
    var target_group = $.trim($('#target_group').val());
    var comparision_group = $.trim($('#comparision_group').val());

    if(target_group != "" && comparision_group != ""){
        $('#profile_description').val('Profile of ' + target_group + ' vs. ' + comparision_group);
    }else if(target_group == "" || comparision_group == ""){
        $('#profile_description').val('');
    }
}

function save() {
    NProgress.start();
    var libview = document.getElementById('libview');
    var libupdate = document.getElementById('libupdate');
    var libnew = document.getElementById('libnew');

    var checkval = 'Y';
    if (libview.checked == false && libupdate.checked == false && libnew.checked == false) {

        checkval = 'Y';
        define_Flag = 1;
        if ($.trim($('#list_name').val()) == '') {
            var data = {
                'title': 'Please enter new Profile Name ',
                'text' : '',
                'butttontext' : 'Ok',
                'cbutttonflag' : false
            };
            ACFn.display_confirm_message(data);
            checkval = 'N';
        } else if($('#list_level').val() == ''){
            var data = {
                'title': 'Please select list level ',
                'text' : '',
                'butttontext' : 'Ok',
                'cbutttonflag' : false
            };
            ACFn.display_confirm_message(data);

            checkval = 'N';
        } else {

            $.ajax({
                url: 'profile/getlist',
                type: 'GET',
                async: false,
                success: function (data) {
                    var flag = 0;
                    var CampNameStr = data;
                    var nameArray = CampNameStr.list;
                    for (var i = 0; i < nameArray.length; i++) {
                        if (nameArray[i].t_name.toLowerCase() == (document.getElementById('list_name').value).toLowerCase())
                            flag = 1;
                    }
                    if (flag == 1) {

                        var data = {
                            'title': 'Profile Name already exists....Please enter new Profile Name',
                            'text' : '',
                            'butttontext' : 'Ok',
                            'cbutttonflag' : false
                        };
                        ACFn.display_confirm_message(data);

                        //$('#list_level').val('');
                        //$('#list_name').val('');
                        checkval = 'N';
                        document.getElementById('is_name_exist').value = 'exist';
                        document.getElementById('camp_id').value = '';
                        document.getElementById('is_name_exist').value = '';
                        Camp_Name = document.getElementById('list_name').value;
                        deflag = 1;
                        if (addsubgroupchk == 'N'){
                            $.ajax({
                                url: 'profile/seq',
                                type: 'GET',
                                async: false,
                                success: function (data) {
                                    camp_id = data.cid;
                                    document.getElementById('camp_id').value = $.trim(camp_id);
                                }
                            });
                        }
                    }
                }
            });
        }
    }
    else {
        var tempid = $('#libcamp_id').val();
        setTimeout(function () {
            $.ajax({
                type: 'GET',
                url: 'profile/recd',
                data: {
                    _token : $('[name="_token"]').val(),
                    tempid : tempid
                },
                async: false,
                success: function (responseStr) {
                    NProgress.done(true)
                    if (libview.checked == true){
                        $('#save','#savenow').hide();
                    }

                    if ((checkval == 'Y')) {
                        /********* 2018-03-23 - changes for hide buttons when view selected -- start ********/
                        setTimeout(function () {
                            if ($('#row_variable_input').val() != "") {
                                $('.run-report').show();
                                $('.summery-report').remove();
                            }
                        }, 2000)

                        /********* 2018-03-23 - changes for hide buttons when view selected -- end ********/

                        loadList(responseStr, up_flag);  // Load list according to saved values in table 2017-07-05
                        var response = responseStr.aData;
                        var rstr = response.Report_Row + "^" + response.Report_Column + "^" + response.Report_Function + "^" + response.Report_Sum + "^" + response.Report_Show + "^" + response.Chart_Type + "^" + response.Axis_Scale + "^" + response.Label_Value;
                        var currentTime = new Date();
                        // returns the month (from 0 to 11)
                        var r9 = currentTime.getMonth() + 1;
                        // returns the day of the month (from 1 to 31)
                        var r10 = currentTime.getDate();
                        // returns the year (four digits)
                        var r8 = currentTime.getFullYear();
                        var params = {
                            'row_id' : response.row_id,
                            'CID' : response.t_id,
                            'CName' : response.t_name,
                            'sSQL' : response.sql,
                            'listShortName' : response.list_short_name,
                            'list_level' : response.list_level,
                            'list_fields' : JSON.parse(response.list_fields),
                            'rStr' : rstr,
                            'filter_condition' : [],//response.filter_condition,
                            'Customer_Exclusion_Condition' : [],//response.Customer_Exclusion_Condition,
                            'Customer_Inclusion_Condition' : [],//response.Customer_Inclusion_Condition,
                            'selected_fields' : response.selected_fields,
                            'CampaignID' : response.t_id,
                            'Type' : 'C',
                            'Objective' : response.rpmeta.Objective,
                            'Brand' : response.rpmeta.Brand,
                            'Channel' : response.rpmeta.Channel,
                            'Category' : response.rpmeta.Category,
                            'ListDes' : response.rpmeta.ListDes,
                            'Wave' : response.rpmeta.Wave,
                            'Start_Date' : r8 + "/" + r9 + "/" + r10,
                            'Interval' : response.rpmeta.Interval,
                            'ProductCat1' : response.rpmeta.ProductCat1,
                            'ProductCat2' : response.rpmeta.ProductCat2,
                            'SKU' : response.rpmeta.SKU,
                            'Coupon' : response.rpmeta.Coupon,
                            'Sort_Column' : response.rpmeta.Sort_Column,
                            'Sort_Order' : response.rpmeta.Sort_Order,
                            //'is_public' : response.is_public,
                            'custom_sql' : response.Custom_SQL,
                            'cI' : response.Chart_Image,
                        };
                        localStorage.setItem('params',JSON.stringify(params));
                        update_flag = 1;
                        camp_id = responseStr.aData.t_id;
                        Camp_Name = document.getElementById('cmbsavedcampNew').options[document.getElementById('cmbsavedcampNew').selectedIndex].text;
                        oldcampclk = 'Y';
                    }
                }
            });
        },1500)

    }
}

function navMenu(obj,className){
    $('.navigation__item a.is-active').removeClass('is-active');

    $(".navigation__item").each(function() {
        var hideHref = $(this).find('a').attr('data-href');
        $(hideHref).hide();
    });

    $('.'+className).find('a').addClass('is-active');
    var showHref = $('.'+className).find('a').attr('data-href')
    $(showHref).show();
}

function ucwords(str, force) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function loadList(resStr, up_flag) {
    var responseStr = resStr.aData;
    var list_name = responseStr.t_name;
    var listShortName = responseStr.list_short_name;
    var list_level = responseStr.list_level;
    $('#list_level').val(list_level);
    ///////////////////////////////////////////////////////////////////////////////
    //$('#list_level').trigger('change');
    $("#accordion").html('');
    $("#accordion").html(resStr.fields_Html);


    $('#row_variable_input').html('');
    $('#column_variable_input').html('<option value="">Select</option>');
    $('#sum_variable_input').html('<option value="">Select</option>');
    $.each(resStr.lkpOptions, function(val, text) {
        $("#row_variable_input, #column_variable_input").append(

            $('<option></option>').val(text).html(text)
        );
    });
    $.each(resStr.numOptions, function(val, text) {
        $("#sum_variable_input").append(
            $('<option></option>').val(text).html(text)
        );
    });

    initJS($('#accordion'));

    var emailMS = [
        'row_variable_input'
    ];

    $.each(emailMS, function (index, value) {
        if ($("#" + value).length){
            if (up_flag == 'view') {
                $("#" + value).attr('disabled', true);
            }
            $("#" + value).multiselect({
                //appendTo: '#emailBox',
                close: function () {
                },
                header: true, //"Region",
                selectedList: 1, // 0-based index
                nonSelectedText: 'Select Values',
                enableFiltering: true,
                filterBehavior: 'text',
            }).multiselectfilter({label: 'Search'});
        }
    });
    $("#customFieldRow").show();
    $('#fieldSummery').hide();

    filterReset();
    create_query();
    //$('#sortorder').trigger('change');
    $('#sqlQueryRow').hide();
    $('#filterSection').show();

    $('#showSqlBtn').attr('disabled', false);
    $('#btnPreviewList').attr('disabled', false);
    $('#btncheckcnt').attr('disabled', false);
    $('#btnPreview').attr('disabled', false);
    $('#divDistributionBoxBtn').attr('disabled', false);
    $('#btnDownloadList50k').attr('disabled', false);
    $('#btnDownloadList200k').attr('disabled', false);
    /////////////////////////////////////////////////////////////////////////////////////


    var filterVal = responseStr.filter_criteria;
    var exclusionVal = responseStr.Customer_Exclusion_Criteria;
    var inclusionVal = responseStr.Customer_Inclusion_Criteria;
    var filterSummmery = responseStr.filter_condition;
    var customerExclusionSummmery = responseStr.Customer_Exclusion_Condition;
    var customerInclusionSummmery = responseStr.Customer_Inclusion_Condition;
    var selected_fields = responseStr.selected_fields;
    sql1 = responseStr.sql;
    sql = sql1.replace(/\\\"/g, "\"");

    var list_format = responseStr.List_Format;
    var report_orientation = responseStr.Report_Orientation;
    var is_public = responseStr.is_public;
    var custom_sql = responseStr.Custom_SQL;

    /*if (selected_fields.indexOf(",") >= 0){
        $("#sortcolumn").html('');
        var sfArr = selected_fields.split(',');
        for (var h = 0; h < sfArr.length; h++){
            $("#sortcolumn").append('<option value='+sfArr[h]+'>'+sfArr[h]+'</option>');
        }
    }*/

    //$('#sortcolumn').val(responseStr.rpmeta.Sort_Column);
    //$('#sortorder').val(responseStr.rpmeta.Sort_Order);

    $('#profile_description').val(responseStr.rpmeta.Category);
    $('#list_format').val(list_format);
    $('#campaign_id').val(responseStr.DCampaignID);
    $('#output_format').val(responseStr.SR_Attachment);
    if(responseStr.list_fields != ""){
        var fields = JSON.parse(responseStr.list_fields);
        $('#fields option').attr('selected',false);
        $("#fields").multiselect('refresh');

        $.each(fields,function (index,field) {
            $('#fields option[value="' + field + '"]').attr("selected", true);
        })
        $("#fields").multiselect('refresh');

        var id = 'fields_ms';
        $('[id="'+id+'"]').attr('style',
            'width:100% !important;' +
            'height: 28px; ' +
            'background-color: white !important;' +
            'height: calc(1.5em + .5rem + 2px);' +
            'padding: .25rem .5rem;' +
            'border-radius: .2rem;' +
            'background-clip: padding-box;' +
            'border: 1px solid #e9ecef;' +
            'font-size: .76563rem;' +
            'min-height: 30px;' /*+
                            'color: #cdcdcd;'*/
        );
    }

    if(responseStr.Chart_Image != ""){
        $('.chart').html('');
        var Chart_Images = JSON.parse(responseStr.Chart_Image);
        var idx = 0;
        $.each(Chart_Images,function (index,val) {
            var img_part = val.split('\\');
            $('#tab_23').find('.chart').append('<div id="chartPI' + idx + '" class="border mb-4 p-2 " style="display: block; width: 100%; max-width:800px; height: 530px; "></div>');

            $('#chartPI' + idx).append('<h4 class="card-title mt-3 mb-0" style="display: block; width: 100%; max-width:800px;text-align: center;">'+index.replace(/[\[\]']+/g,'').replace(/_+/g, ' ')+'</h4><img style="display: block;  width: 100%; height: 435px;" src="' + img_part[parseInt(img_part.length)-2] +'/'+img_part[parseInt(img_part.length)-1] + '" class="img-responsive">');
            idx++;
        })
    }

    if(is_public == 'Y') {
        $('#is_public').prop('checked',true);
    }

    if(custom_sql == 'Y') {
        $('#is_custom_sql').attr('checked',true);
        $('.csql').show();
    }else{
        $('.csql').attr('data-chance','1');
    }

    $('#listShortName').val(listShortName);
    $('#filterSummmery').val(filterSummmery);
    $('#customerExclusionSummmery').val(customerExclusionSummmery);
    $('#customerInclusionSummmery').val(customerInclusionSummmery);

    /***************** Multiselect section start **********************/
    setTimeout(function () {
        sql = sql1.replace(/\\\"/g, "\"");
        $('#list_name').val(list_name);
        $('#filterSummmery').show();

        if (up_flag == 'view') {
            $('#tab_24').find('input[type="text"], select').attr('disabled', 'disabled');
            //$("select").not("[id=customFieldList]").attr("disabled", true);
            $('#showSqlBtn').attr('disabled', false);
            $('#indicationFieldSummery').attr('disabled', false);
            $('#btnPreview').attr('disabled', false);
            $('#divDistributionBoxBtn').attr('disabled', false);
            $('#btncheckcnt').attr('disabled', false);
            $('#save','#savenow').attr('disabled', true);
        }
        var customClass = '';
        var color = '';
        for (var section = 1; section <= 3; section++) {
            if (section == 1) {
                title = '<div style=font-size:14px; class=tooltip><i class=^fa fa-question-circle-o fa-2^ aria-hidden=true></i><span class=tooltiptext>Set filter at the contact or customer level.</span></div> &nbsp;&nbsp;List-Level ';
                if (filterVal == "null" || filterVal == "")
                    continue;

                filterVal = JSON.parse(filterVal);
                var p = 1;
                var sectiontype = 'F';
                renderFilters(p, section, title, customClass, color, filterVal);
            } else if (section == 2) {
                title = '<div style=font-size:14px; class=tooltip><i class=^fa fa-question-circle-o fa-2^ aria-hidden=true></i><span class=tooltiptext>Set inclusion filters at the detail level. Only customers satisfying the filter conditions are selected.</span></div> &nbsp;&nbsp;Detail-Level Exclusions';
                if (exclusionVal == "null" || exclusionVal == "")
                    continue;
                else
                    $('#customerExclusionSection').show();

                exclusionVal = JSON.parse(exclusionVal);
                var p = 11;

                var sectiontype = 'CE';
                customClass = 'red-elements';
                color = 'color:red !important;';

                renderFilters(p, section, title, customClass, color, exclusionVal);

            } else if (section == 3) {
                $('#filterLoading').remove();
                title = '<div style=font-size:14px; class=tooltip><i class=^fa fa-question-circle-o fa-2^ aria-hidden=true></i><span class=tooltiptext>Set exclusion filters at the detail level. Customers satisfying the filter conditions are excluded. </span></div> &nbsp;&nbsp;Detail-Level Inclusions';
                if (inclusionVal == "null" || inclusionVal == "")
                    continue;
                else
                    $('#customerInclusionSection').show();
                color = '';
                customClass = '';
                inclusionVal = JSON.parse(inclusionVal);
                var p = 21;
                var sectiontype = 'CI';
                renderFilters(p, section, title, customClass, color, inclusionVal);

            }
        }

        setTimeout(function () {
            var Report_Row = responseStr.Report_Row;
            var Report_Column = responseStr.Report_Column;
            var Report_Function = responseStr.Report_Function;
            var Report_Sum = responseStr.Report_Sum;
            var Report_Show = responseStr.Report_Show;

            var Chart_Type = $.trim(responseStr.Chart_Type);
            var Chart_Image = responseStr.Chart_Image;
            var Axis_Scale = responseStr.Axis_Scale;
            var Label_Value = responseStr.Label_Value;

            if (Report_Row != "") {
                $('#tabular_report').show();
                if( Report_Row.indexOf(',') != -1 ){
                    var Report_RowArr = Report_Row.split(',');
                    $.each(Report_RowArr,function (index,val) {
                        $('#row_variable_input option[value="'+val+'"]').attr('selected',true);
                    })
                }else{
                    $('#row_variable_input option[value="'+Report_Row+'"]').attr('selected',true);
                }

                $("#row_variable_input").multiselect('refresh');

                if (up_flag == 'view') {
                    $("#row_variable_input_ms").attr('style', 'width:245.156px !important;height: 28px; background-color: #d6dce2 !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 27px;');
                }else{
                    $("#row_variable_input_ms").attr('style', 'width:245.156px !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 27px;');
                }

                $('#column_variable_input').val(Report_Column);
                if(Report_Column == ""){
                    if($('#show_as_input').length > 0){
                        $('#show_as_input option').hide();
                        $('#show_as_input option[value=np]').show();
                        $('#show_as_input option[value=pn]').show();
                        //$('#show_as_input').val('np')
                    }
                }else{
                    if($('#show_as_input').length > 0){
                        $('#show_as_input option').show();
                        $('#show_as_input option[value=np]').hide();
                        $('#show_as_input option[value=pn]').hide();
                        $('#show_as_input').val('number')
                    }
                }


                if ($.inArray(Report_Function,['Count','count']) == -1 && Report_Function != "") {
                    $('.sf').show();
                    $('#sum_variable_input').val(Report_Sum);
                } else {
                    $('.sf').hide();
                }
                $('#function_input').val(Report_Function != "" ? Report_Function.toLowerCase() : 'count');
                $('#show_as_input').val(Report_Show != "" ? Report_Show : 'number');
                $('#chart_variable_input').val(Chart_Type != "" ? Chart_Type : '');
                $('#chartImage').val(Chart_Image != "" ? Chart_Image : '');
                $('#chart_axis_scale_input').val(Axis_Scale != "" ? Axis_Scale : '');
                $('#chart_label_value_input').val(Label_Value != "" ? Label_Value : '');

            }

            /*var sFds = '';
            if (selected_fields.indexOf(",") >= 0){
                sFds = selected_fields.split(",")
            }else{
                sFds = selected_fields;
            }
            for (var m = 0; m <= 15; m++){
                if($('#s_' + m).length > 0){
                    $.each(sFds, function(i,e){
                        $("#s_" + m + " option[value='" + e + "']").prop("selected", true);
                    });
                    $('#s_' + m).trigger("chosen:updated");
                }
            }*/

            $('#fieldSummaryVal').text(selected_fields);

            var fromKeywords = ['FROM','from','From'];
            var sqlPart;
            $.each(fromKeywords, function (i,item) {
                if (sql.indexOf(item) > -1) {
                    sqlPart = sql.split(item);
                }
            });

            $('#sqlQueryPart').val(sqlPart[0] + " FROM " + list_level);
            $('#sqlQuery').val(sql);
        },1000);
    },1500)

    /***************** Filter section start *************************/
}

function renderFilters(start, section, title, clsname, color, filters) {
    var ntitle = title.replace('^', '"');
    ntitle = ntitle.replace('^', '"');
    $.each(filters, function (key, data) {
        $('#numRows_' + section).val(data.noLS);
        $.each(data.rows, function (ikey, idata) {
            var ids = (start * 10) + 1;
            var newRowIds = parseInt(ids) + 10;
            var newSecIds1 = parseInt(ids) + 10;
            var newSecIds2 = parseInt(ids) + 11;
            var newSecIds3 = parseInt(ids) + 12;

            $('#row_' + ids).after('<div class="divTableRow" id="row_' + newRowIds + '"><div class="divTableCell ff"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="' + color + 'vertical-align: middle; visibility: hidden;" class="divTableCell" id="info_' + newRowIds + '">' + ntitle + '<input type="hidden" id="tablename_' + newRowIds + '" value="" /><input type="hidden" id="typebox_' + newRowIds + '" value="" /><input type="hidden" id="countSec_' + newRowIds + '" value="0" /></div><div style="width:1%; text-align:right !important;" id="preCross_' + newRowIds + '" class="divTableCell"></div><div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_' + newSecIds1 + '"><a onclick="addSectionNew(' + newRowIds + ',' + newRowIds + ',0,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a></div></div></div></div></div><div class="divTableCell"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds1 + '"></div><div style="width:3%" class="divTableCell"  id="opCell_' + newSecIds1 + '"></div><div style="width:5%" class="divTableCell"  id="valCell_' + newSecIds1 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds2 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds2 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds2 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds2 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds3 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds3 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds3 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds3 + '"></div></div></div></div></div></div>');
            var colids = ids;
            var opids = ids;
            var valds = ids;
            var logids = ids;
            $('#tablename_' + colids).val(idata.table[ikey]);
            $('#typebox_' + colids).val(idata.type[ikey]);

            $.each(idata.col, function (ckey, cdata) {
                $('#ccolCell_' + colids).html('<select class="form-control form-control-sm ' + clsname + '" style="width:100%;" onchange="getCol(this,\'op' + colids + '\',\'' + colids + '\',\'' + section + '\');" class="t1" name="ccol' + colids + '" id="ccol' + colids + '"></select>');

                $.each(cdata.options, function (key, val) {
                    $('#ccol' + colids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.value));
                });


                var nextIds = parseInt(colids) + 1;

                if (ckey == 0) {
                    var rowIds = colids;
                    $('#plusDiv_' + rowIds).html('');
                    $('#preCross_' + rowIds).html('<a class="crosss" onclick="removeSection(' + rowIds + ',' + colids + ',' + section + ');" href="javascript:void(0);"><i class="fas fa-trash font-14"></i> </a>');
                } else {
                    $('#plusCell_' + colids).html('');
                    //$('#plusCell_' + colids).text('OR');
                }
                var secIds = ids;

                $('#plusCell_' + nextIds).html('<a onclick="addSectionNew(' + rowIds + ',' + nextIds + ',1,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a>');

                colids++;
            });
            $.each(idata.op, function (okey, odata) {

                $('#opCell_' + opids).html('<select class="form-control form-control-sm ' + clsname + '" onchange="changeVal(this.value,' + opids + ',1);" style="width:100%;" id="op' + opids + '" name="op' + opids + '"></select>')
                $.each(odata.options, function (key, val) {
                    $('#op' + opids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.text));
                });
                opids++;
            });
            $.each(idata.val, function (vkey, vdata) {
                if (vdata.options != "") {
                    $('#valCell_' + valds).html('<select class="form-control form-control-sm ' + clsname + '" style="width: 100%;" id="val' + valds + '"  onkeypress="GetTextInfo(this,event);" multiple="multiple"></select>');
                    $.each(vdata.options, function (key, val) {
                        $('#val' + valds).append($("<option></option>")
                            .attr("value", val.value).attr("selected", val.selected)
                            .text(val.value));
                    });
                    $("#val" + valds).multiselect({
                        close: function () {
                            var txtT = [];
                            $('#val' + valds + ' :selected').each(function (i, selected) {
                                txtT[i] = $(selected).text();
                            });
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Fields'
                    }).multiselectfilter({label: 'Search'});


                    $("#val" + valds).multiselect('refresh');

                    $("#val" + valds + "_ms").attr('style', 'width:100% !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;');

                } else {
                    $('#valCell_' + valds).html('<input class="form-control form-control-sm ' + clsname + '" style="width:100%;" id="val' + valds + '" name="val' + valds + '" type="text" value="' + vdata.value + '">');
                }
                valds++;
            });

            $.each(idata.log, function (okey, odata) {
                console.log("logids------" + logids)
                $('#plusCell_' + parseInt(logids+1)).html('<select class="form-control form-control-sm dvd ' + clsname + '" style="width:100%;" name="log' + logids + '" id="log' + logids + '"></select>')

                $.each(odata.options, function (key, val) {
                    $('#log' + logids).append($("<option></option>")
                        .attr("value", val.value).attr("selected", val.selected)
                        .text(val.text));
                });
                logids++;
            });
            ids++;
            start++;
        })
    });
}

function active_select(arg) {

    //var aT = new Array('list_Library','list_add','list_Meta','list_Execute','list_Define','list_Export');
    var aT = new Array('list_Library', 'list_add', 'list_Running','list_Schedule');
    for (i = 0; i < aT.length; i++) {
        //jQuery('#' + aT[i]).removeClass('active');
    }
    //document.getElementById(arg).className = 'active';
}

function active_preview() {
    var icon = new Array('icon_add', 'icon_Meta', 'icon_Execute', 'icon_Define', 'icon_Export', 'icon_Preview');
    for (i = 0; i < icon.length; i++) {
        document.getElementById(icon[i]).style.display = 'none';
    }
    document.getElementById('icon_Previous').style.display = 'block';

}

function session(runoption = true,obj) {
    $('#save','#savenow').attr('disabled', true);
    var campid = document.getElementById('camp_id').value;
    var campaign_id = document.getElementById('campaign_id').value;
    var list_name = document.getElementById('list_name').value;
    var listShortName = document.getElementById('listShortName').value;
    var list_level = document.getElementById('list_level').value;
    var output_format = document.getElementById('output_format').value;
    var fields = $('#fields').val();

    /*var list_fields = document.getElementById('list_fields').value;
    var element = Array('', 'ccol', 'op', 'val', 'log');
    if (typeof localStorage !== 'undefined') {
        localStorage.clear();
        var contactfilters = []
        var inclusionsfilters = []
        var exclusionsfilters = []
    }
    var filterVal = '';
    var customerExclusionVal = '';
    var customerInclusionVal = '';

    for (var k = 1; k <= 3; k++) {
        var tType = new Array();
        var tTable = new Array();
        var ccol = new Array();
        var op = new Array();
        var val = new Array();
        var log = new Array();
        var rows = new Array();
        noLS = $('#numRows_' + k).val();
        index = 1;
        if (k == 2) {  // for customer exclusion
            var p = 11;
            var c = parseInt(noLS) + parseInt(p);

        } else if (k == 3) {   // for customer inclusion
            var p = 21;
            var c = parseInt(noLS) + parseInt(p);
        } else {    // for regular filter
            var p = 1;
            var c = noLS;
        }
        m = 0;
        for (var i = p; i <= c; i++) {
            var ccol = new Array();
            var op = new Array();
            var val = new Array();
            var log = new Array();
            for (var j = 1; j <= 3; j++) {

                var id1 = (element[1] + i) + j.toString();
                var id2 = (element[2] + i) + j.toString();
                var id3 = (element[3] + i) + j.toString();
                var id4 = (element[4] + i) + j.toString();
                if ($('[id=' + id1 + ']').val() != undefined || $('[id=' + id1 + ']').val() != null) {
                    if ((j == 1) && k == 2 || k == 3) {
                        tType[m] = $('#typebox_' + i + j).val();
                        tTable[m] = $('#tablename_' + i + j).val();
                        m++;
                    }

                    var coloptions = $('[id=' + id1 + '] option');
                    var opoptions = $('[id=' + id2 + '] option');
                    var valoptions = $('[id=' + id3 + ']').prop("tagName") == 'SELECT' ? $('[id=' + id3 + '] option') : [];
                    var logoptions = $('[id=' + id4 + '] option');
                    var colvalues = $.map(coloptions, function (option) {
                        return {
                            'value': option.value,
                            'selected': option.selected
                        };
                    });

                    var opvalues = $.map(opoptions, function (option) {
                        return {
                            'value': option.value,
                            'text': option.text,
                            'selected': option.selected
                        };
                    });
                    var valvalues = [];
                    if (valoptions.length > 0) {
                        var valvalues = $.map(valoptions, function (option) {
                            return {
                                'value': option.value,
                                'selected': option.selected
                            };
                        });
                    }

                    var logoptions = $.map(logoptions, function (option) {
                        return {
                            'value': option.value,
                            'text': option.text,
                            'selected': option.selected
                        };
                    });


                    ccol.push({
                        'index': j,
                        'value': $('[id=' + id1 + ']').val(),
                        'options': colvalues
                    });

                    op.push({
                        'index': j,
                        'value': $('[id=' + id2 + ']').val(),
                        'options': opvalues
                    });

                    val.push({
                        'index': j,
                        'value': $('[id=' + id3 + ']').val(),
                        'options': valvalues.length > 0 ? valvalues : ''
                    });

                    if($('[id=' + id4 + ']').length > 0){
                        log.push({
                            'index': j,
                            'value': $('[id=' + id4 + ']').val(),
                            'options': logoptions.length > 0 ? logoptions : ''
                        });
                    }
                }
                index++;
            }
            if (ccol.length > 0) {
                rows.push({
                    'table': tTable,
                    'type': tType,
                    'col': ccol,
                    'op': op,
                    'val': val,
                    'log' : log
                })
            }
        }

        if (noLS > 0) {
            logStr = log;
            if (k == 1) {
                var filterVal = []; //$('#filterSection').html().replace(/'/g, '@');
                filterVal.push({
                    'noLS': noLS,
                    'rows': rows,
                    'logStr': logStr
                });
                //noLS + '^' + ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;
                localStorage.setItem('contactfilters', JSON.stringify(filterVal))
                console.log(JSON.parse(localStorage.getItem('contactfilters')));
            }

            if (k == 2) {
                var customerExclusionVal = []; //$('#filterSection').html().replace(/'/g, '@');
                customerExclusionVal.push({
                    'noLS': noLS,
                    'rows': rows,
                    'logStr': logStr
                });
                //noLS + '^' + ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;
                localStorage.setItem('exclusionsfilters', JSON.stringify(customerExclusionVal))
                console.log(JSON.parse(localStorage.getItem('exclusionsfilters')));

                //customerExclusionVal = //noLS + '^' + tTableStr + '^' + tTypeStr + '^' + ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;
            }
            if (k == 3) {
                var customerInclusionVal = []; //$('#filterSection').html().replace(/'/g, '@');
                customerInclusionVal.push({
                    'noLS': noLS,
                    'rows': rows,
                    'logStr': logStr
                });
                //noLS + '^' + ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;
                localStorage.setItem('inclusionsfilters', JSON.stringify(customerInclusionVal))
                console.log(JSON.parse(localStorage.getItem('inclusionsfilters')));

                //customerInclusionVal = //noLS + '^' + tTableStr + '^' + tTypeStr + '^' + ccolStr + '^' + opStr + '^' + valStr + '^' + logStr;
            }
        }
    }*/


    var meta_desc = $('#profile_description').val()
    //var is_public = $('#is_public').is(':checked') == true ? 'Y' : 'N';
    //var custom_sql = $('#is_custom_sql').is(':checked') ? 'Y' : 'N';

    var currentTime = new Date();
    var r9 = currentTime.getMonth() + 1;
    var r10 = currentTime.getDate();
    var r8 = currentTime.getFullYear();

    /*var filter_condition = $('#filterSummmery').val();
    var Customer_Exclusion_Condition = $('#customerExclusionSummmery').val();
    var Customer_Inclusion_Condition = $('#customerInclusionSummmery').val();
    var selected_fields = $.trim($('#fieldSummaryVal').text());
    var sqlQuery = $('#sqlQuery').val();

    var rv = $('#row_variable_input').val();
    var cv = $('#column_variable_input').val();
    var fu = $('#function_input').val();
    var sv = $('#sum_variable_input').val();
    var sa = $('#show_as_input').val();
    var ct = $('#chart_variable_input').val();
    var cI = $('#chartImage').val();
    var as = $('#chart_axis_scale_input').val();
    var lv = $('#chart_label_value_input').val();
    var rstr = rv + "^" + cv + "^" + fu + "^" + sv + "^" + sa + "^" + ct + "^" + as + "^" + lv;

    var list_format = $('#list_format').val();
    var report_orientation = $('#report_orientation').val();*/

    var params = {
        'CID' : campid,
        'CName' : list_name,
        'sSQL' : '',
        'listShortName' : listShortName,
        'list_level' : list_level,
        'list_fields' : fields,
        'rStr' : '',
        'list_format' : '',
        'report_orientation' : '',
        'filter_condition' : '',
        'Customer_Exclusion_Condition' : '',
        'Customer_Inclusion_Condition' : '',
        'selected_fields' : '',
        //'metaStr' : metaStr,
        'CampaignID' : campaign_id,
        'Type' : 'P',
        'Objective' : '',
        'Brand' : '',
        'Channel' : '',
        'Category' : meta_desc,
        'ListDes' : '',
        'Wave' : '',
        'Start_Date' : r8 + "/" + r9 + "/" + r10,
        'Interval' : '',
        'ProductCat1' : '',
        'ProductCat2' : '',
        'SKU' : '',
        'Coupon' : '',
        'Sort_Column' : '',
        'Sort_Order' : '',
        'is_public' : 'Y',
        'custom_sql' : 'N',
        'cI' : '',
        SR_Attachment : output_format,
        schedule_action : 'Sch_campaign1',
        pdfdata : [],
        profileXLSXdata : []
    };
    localStorage.setItem('params',JSON.stringify(params));

   /* localStorage.getItem('contactfilters');
    localStorage.getItem('exclusionsfilters');
    localStorage.getItem('inclusionsfilters');*/

    var sSQL = '';

    parent.camp_id = campid;
    parent.Camp_Name =listShortName;
   /* parent.document.getElementById('list_level').value = list_level;
    parent.document.getElementById('meta_description').value = meta_desc;
    parent.document.getElementById('fieldSummaryVal').innerText = selected_fields;

    localStorage.removeItem('record');
    var record = {
        camp_id : campid,
        Camp_Name : list_name,
        list_short_name : listShortName,
        meta_description : meta_desc,
        sSQL : sSQL,
        list_level : list_level,
        selected_fields : selected_fields,
        schedule_action : 'Sch_campaign1'
    };
    localStorage.setItem('record',JSON.stringify(record));*/

    addsubgroupchk = 'N';
    oldcampclk = 'Y';
    promoexportchk = 'N';
    $('#save','#savenow').attr('disabled', false);

    if(runoption == true){
        $('[href="#tab_26"]').trigger('click');
        obj.html('').text('...');
    }else{
        var postdata = {
            pgaction : 'Sch_campaign1',
            CID : $.trim(campid),
            CName : listShortName,
            //metaStr : metaStr,
            CampaignID : campaign_id,
            Type : 'P',
            Objective : '',
            Brand : '',
            Channel : '',
            Category : meta_desc,
            ListDes : '',
            Wave : '',
            Start_Date : r8 + "-" + r9 + "-" + r10,
            Interval : '',
            ProductCat1 : '',
            ProductCat2 : '',
            SKU : '',
            Coupon : '',
            Sort_Column : '',
            Sort_Order : '',
            SMTPStr : 'N',
            ftp_data : {enable : 'N'},
            SFTP_Attachment : '',
            SR_Attachment : output_format,
            SREmailStr : {enable : 'N'},
            ShareStr : {enable : 'N'},
            rtype : 'RI',
            params : JSON.stringify(params),
            filterVal : JSON.stringify([]),
            customerExclusionVal : JSON.stringify([]),
            customerInclusionVal : JSON.stringify([]),
            _token : $('[name="_token"]').val()
        };

        $.ajax({
            url : 'profile/ar_sch_data',
            type : 'POST',
            data : postdata,
            beforeSend : function(){
                NProgress.done(true);
                NProgress.start()
            },
            success : function (data) {

                setTimeout(function () {
                    NProgress.done(true);
                    window.parent.$('.clr-btn').trigger('click');


                },1500)

                setTimeout(function () {
                    runprofileafter(2,obj.attr('id'));
                    setTimeout(function () {
                        updateprofile({
                            t_id : data.t_id,
                            pdfdata : localStorage.getItem('pdfdata'),
                            profileXLSXdata : localStorage.getItem('profileXLSXdata'),
                            _token : $('[name="_token"]').val()
                        });
                    },2000)

                },5000)
                //localStorage.removeItem('record');
                //localStorage.removeItem('params');
                //localStorage.removeItem('contactfilters');
               // localStorage.removeItem('exclusionsfilters');
                //localStorage.removeItem('inclusionsfilters');

               /* var params = {
                    t_id : data.t_id,
                    pdfdata : JSON.parse(localStorage.getItem("pdfdata")),
                    profileXLSXdata : JSON.parse(localStorage.getItem("profileXLSXdata"))
                };
                localStorage.setItem('params',JSON.stringify(params));*/

            },
            complete : function () {
                obj.html('').text('Run Now');
                setTimeout(function () {
                    window.parent.$('.clr-btn').trigger('click');
                },1500);


            }
        })
    }
}

function updateprofile(dataarr = {}) {
    $.ajax({
        url : 'profile/updateprofile',
        type : 'POST',
        data : dataarr,
        async: false,
        beforeSend : function(){
            NProgress.start();
            NProgress.start()
        },
        success : function (data) {
            NProgress.done(true);
            localStorage.removeItem('params');
            localStorage.removeItem('pdfdata');
            localStorage.removeItem('profileXLSXdata');
            setTimeout(function () {
                $('.chart').hide().html('')
            },2000);
        },
        complete : function () {}
    })
}

function triggerCompletedTab() {
    if(!$('a[href="#tab_23"]').hasClass('active') && !$('a[href="#tab_24"]').hasClass('active')){
        $('a[href="#tab_22"]').trigger('click');
        $('a[href="#tab_22"]').trigger('show.bs.tab');
    }
}

function showOldReport(row_id) {
    $('.clr-btn').show();
    $('.emreport').hide();
    $('.cn-report-btn').hide();
    $('.create-new').hide();
    $('.older-version a').attr('data-row_id',row_id);
    $('.older-version').show();
    $('.c-btn').html('');
    /*$('#libflag_name').val(libflag_name);
    $('#libcamp_name').val(libcamp_name);
    $('#libcamp_id').val(libcamp_id);*/
    $('a[href="#tab_25"]').trigger('click');
}

function librarySQL_sel(obj, col) {

    document.getElementById('list_Preview').style.display = 'none';
    document.getElementById('btncheckcnt').style.display = 'none';
    YAHOO.namespace("csr.container");
    YAHOO.csr.container.wait = new YAHOO.widget.Panel("wait", {
            width: "240px",
            fixedcenter: true,
            close: false,
            draggable: false,
            zindex: 4,
            modal: true,
            visible: false
        }
    );

    YAHOO.csr.container.wait.setHeader("Displaying Details, please wait...");
    YAHOO.csr.container.wait.setBody("<img src=\"http://l.yimg.com/a/i/us/per/gr/gp/rel_interstitial_loading.gif\"/>");

    YAHOO.csr.container.wait.render(document.body);
    YAHOO.csr.container.wait.show();
    showdiv('divLibrary');
    seg_openFlag = 'Y';
    var handleSuccess = function (o) {
        if (o.responseText !== undefined) {
            $("#divLibrary").html(o.responseText);
            YAHOO.csr.container.wait.hide();
            initTable('the-table');
        }
    }
    var callback = {success: handleSuccess};
    var postData = "col=" + col + "&obj=" + obj + "&pgaction=getschedule&gschtype=level1&rand=" + Math.random();
    var request = YAHOO.util.Connect.asyncRequest('POST', 'cc_lib_data.php', callback, postData);
}

function show_Create_library(obj) { // Change Campaign - 2014-03  begin
    $('.older-version').hide();
    $('.search-btn').hide();
    var val = obj.val();


    var libnameArray = val.split(",");
    var libflag_name = libnameArray[0];
    var libcamp_id = libnameArray[1];
    var libcamp_name = libnameArray[2];
    var libcId = libnameArray[3];

    if (libflag_name == 'run') {
        obj.val(0);
        get_distribution_show(libcamp_id);

        return false;
    }

    if (libflag_name == 'replica') {
        obj.val(0);
        NProgress.start();
        $.ajax({
            type: 'GET',
            url: 'profile/recd',
            data: {
                _token : $('[name="_token"]').val(),
                tempid : libcamp_id
            },
            async: true,
            success: function (datao) { //alert(data);
                NProgress.done(true)
                var dataArr = datao.aData;
                var sql1 = dataArr.sql;
                var sSQL = sql1.replace(/\\\"/g, "\"");
                /*var metaData = dataArr.meta_data;
                var metaStr = metaData.split('^');*/
                $('#previewSql').val(sSQL);
                $('#previewDownloadFileName').val(dataArr.t_name);
                $('#previewDownloadFileType').val('xlsx');
                list_report_run(0,dataArr.t_name,dataArr.List_Format,dataArr.rpmeta.Category);
            }
        });
        return false;
    }

    if (libflag_name == 'email') {
        NProgress.start();
        $('.eCampid').val(libcamp_id);
        $('#set_type').val('P');
        $('#sendemail-title').text('Send Profile via Email');
        $('#txtTo1_ms').css({width : '100%'})

        $.ajax({
            type: 'GET',
            url: 'profile/recd',
            data: {
                _token : $('[name="_token"]').val(),
                tempid : libcamp_id
            },
            async: true,
            success: function (datao) {
                NProgress.done(true);
                var dataArr = datao.aData;
                $('#txtSub1').val($('#clientname').val() + ' - Profile ' + dataArr.list_short_name);
                $('#emailBox').modal('show');
            }
        });
        obj.val(0);
        return false;
    }

    if (libflag_name == 'share') {
        $('.eCampid').val(libcamp_id);
        $('#ppt_type').val('P');
        $('#share-title').text('Share Report');
        var postData = {
            eCampid : libcamp_id,
            t_type : 'A',
            _token : $('[name="_token"]').val()
        };
        obj.val(0);


        $.ajax({
            type: 'GET',
            url: 'getshare',
            data: postData,
            async: false,
            success: function (dataouter) { //alert(data);

                if(dataouter.success){
                    console.log('Attach_Phone--',dataouter.Attach_Phone);
                    dataouter.Attach_Phone == 1 ? $('#chkAddToPhone').prop('checked',true) : $('#chkAddToPhone').prop('checked',false);
                    $.each(dataouter.shared_with_user_id, function(i, item) {
                        console.log(item);
                        $('#userFieldList option[value="' + item + '"]').prop('selected', true);
                    });
                    //setTimeout(function(){
                    $("#userFieldList").multiselect({
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Users'
                    }).multiselectfilter({label: 'Search'});

                    if(dataouter.shared_with_user_id.length == 0){
                        $("#userFieldList").multiselect('uncheckAll');
                    }

                    $("#userFieldList").multiselect('refresh');
                    //},1000)
                    $('#userFieldList_ms').css('width','100%');
                }
            }
        });
        //YAHOO.listpull.container.divShareBox.show();
        $('#sharePopup').modal('show');

        return false;
    }

    if (libflag_name == 'delete') {
        delete_row_comp(libcamp_id);
        obj.val(0);
        return false;
    }

    if (libflag_name == 'deleteolderversion') {
        delete_older_version_row_comp(libcamp_id);
        obj.val(0);
        return false;
    }

    if(libflag_name == 'schedule'){
        obj.val(0);
        $('#schedulePopup').modal('show');
        sch_val_flag = 1;
        $.ajax({
            type: 'GET',
            url: 'profile/recd',
            data: {
                _token : $('[name="_token"]').val(),
                tempid : libcamp_id
            },
            async: false,
            success: function (datao) {
                NProgress.done(true)
                var dataouter = datao.aData;

                localStorage.removeItem('params');
                var sSQL = dataouter.sql;
                /*var metaData = dataouter.meta_data;
                var metaStr = metaData.split('^');*/
                var params = {
                    CID : dataouter.t_id,
                    CName : dataouter.t_name,
                    list_short_name : dataouter.list_short_name,
                    meta_description : dataouter.rpmeta.Category,
                    sSQL : '',
                    list_level : dataouter.list_level,
                    selected_fields : dataouter.selected_fields,
                    'CampaignID' : dataouter.t_id,
                    'Type' : 'P',
                    'Objective' : '',
                    'Brand' : '',
                    'Channel' : '',
                    'Category' : dataouter.rpmeta.Category,
                    'ListDes' : '',
                    'Wave' : '',
                    'Start_Date' : dataouter.rpmeta.Start_Date,
                    'Interval' : '',
                    'ProductCat1' : '',
                    'ProductCat2' : '',
                    'SKU' : '',
                    'Coupon' : '',
                    'Sort_Column' : dataouter.rpmeta.Sort_Column,
                    'Sort_Order' : dataouter.rpmeta.Sort_Order,
                    schedule_action : 'ReSch_campaign'
                };

                localStorage.setItem('params',JSON.stringify(params));

                if (dataouter.Report_Row != "") {
                    run_report_outer(dataouter);
                }


                addsubgroupchk = 'N';
                oldcampclk = 'Y';
                promoexportchk = 'N';

                document.frmExec.action = "profile/reschedule";
                document.frmExec.sSQL.value = sSQL;
                document.frmExec.target = "iframeSchedule";
                document.frmExec.submit();
            }
        });
        return false;
    }

    //timer.pause();
    sch_val_flag = 1;
    if (define_Flag == 0) {
        if ((libflag_name == 'view') || (libflag_name == 'update')) {

            //sDialog.hide();
        } else {
            //sDialog.show();
        }
    } else {
        //YAHOO.util.Event.removeListener("btncreatecc", "click");
    }
    if (libflag_name == 'view'){
        $('.cl-report-btn').show();
        $('.cn-report-btn').hide();
        $('.create-new').hide();
        $('.view-report').show();
        //$('.list-report, .emreport').hide();
        $('.c-btn').html('');
        $('.emreport').hide();
        $('#libflag_name').val(libflag_name);
        $('#libcamp_name').val(libcamp_name);
        $('#libcamp_id').val(libcamp_id);
        $('#libcId').val(libcId);
        $('a[href="#tab_23"]').text('View');
        $('a[href="#tab_24"]').trigger('click');
        $('#showSqlBtn').hide();
        $('.search-btn').hide();
        return false;
    }

    if (libflag_name == 'new'){
        $('.cl-report-btn').show();
        $('.cn-report-btn').hide();
        $('.create-new').hide();
        $('.view-report').show();
        //$('.list-report, .emreport').hide();
        $('.emreport').hide();
        $('.c-btn').html('');
        $('#libflag_name').val(libflag_name);
        $('#libcamp_name').val(libcamp_name);
        $('#libcamp_id').val(libcamp_id);
        $('#libcId').val(libcId);
        $('a[href="#tab_23"]').text('Save As');
        $('a[href="#tab_24"]').trigger('click');
        return false;
    }

    //CheckSavedCampaignTemplatelib(libflag_name, libcamp_name, libcamp_id);
    //updateOptlib(libflag_name, libcamp_name, libcamp_id);
}// Change Campaign - 2014-03  End

ACFn.report_sent = function (F , R){
    ACFn.display_message(R.messageTitle,'','success');
    $('#sendreportviaemail')[0].reset();
    $('#emailBox').modal('hide');

}

ACFn.ajax_success_share = function (F , R){
    ACFn.display_message(R.messageTitle,'','success');
    $('#sharePopup').modal('hide');
    $("#userFieldList").multiselect('uncheckAll');
    $('#sharereport')[0].reset();
    $('#userFieldList_ms').css({width : '100%'});
    $('.s-cmessage').hide();

}

function list_report_run(is_download,reportName = '',list_format = '',repdes = '') {
    var sSQL = $('#sqlQuery').length == 0 ? $('#previewSql').val() : $('#sqlQuery').val();
    if (is_download == 0) {
        ACFn.sendAjax('preview','GET',{
            sql : sSQL,
            reportName : reportName,
            list_format : list_format,
            repdes : repdes
        });
        return false;
    }
    //if (is_download == 1) {

        var fFileName = $('#list_name').val() != undefined && $('#list_name').val() != "" ?  $('#list_name').val() : (reportName != "") ? reportName : 'Report' ;
        var fileType = (is_download == 1) ? 'xlsx' : 'pdf';
        list_format = list_format != "" ? (list_format == 'default' ? 'portrait' : list_format) : 'portrait' ;
        repdes = repdes != '' ? repdes : ($('#meta_description').length ? $('#meta_description').val() : '');
        ACFn.sendAjax('download10K','GET',{
            ftype : fileType,
            filename :fFileName,
            list_format : list_format,
            repdes : repdes,
            sSQL : sSQL
        });
    //}
}

function get_distribution_show(recid) {
    $('#distributionResultHtml').html('');
    $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px;');
    $('#indicationCFMsgForDistribution').text('');

    $.ajax({
        type: 'GET',
        url: 'profile/recd',
        data: {
            _token : $('[name="_token"]').val(),
            tempid : recid
        },
        async: false,
        success: function (datao) {
            NProgress.done(true)
            var dataouter = datao.aData;
            //var dataArr = dataouter.split('<|>');

            localStorage.removeItem('srData');
            var listShortName = dataouter.list_short_name;
            /*var metaData = dataouter.meta_data;
            var metaStr = metaData.split('^');*/
            var sql = (dataouter.sql).replace(/\\\"/g, "\"");

            var srData = {
                'list_level' :  dataouter.list_level,
                't_name' : dataouter.t_name,
                'list_short_name' : dataouter.list_short_name,
                'metaDesc' : dataouter.rpmeta.Category,
                'cont_filters': dataouter.filter_condition,
                'incl_filters': dataouter.Customer_Inclusion_Condition,
                'excl_filters': dataouter.Customer_Exclusion_Condition,
                'sql' : sql,
                'list_format' : dataouter.List_Format,
                'report_orientation' : dataouter.Report_Orientation
            };
            localStorage.setItem('srData',JSON.stringify(srData));
            var list_level = dataouter.list_level;
            $('#list_name').val(dataouter.t_name);
            $('#meta_description').length ? $('#meta_description').val(metaStr[3]) : '';

            var Report_Row = dataouter.Report_Row;
            var Report_Column = dataouter.Report_Column;
            var Report_Function = dataouter.Report_Function;
            var Report_Sum = dataouter.Report_Sum;
            var Report_Show = dataouter.Report_Show;
            var Chart_Type = $.trim(dataouter.Chart_Type);
            var Axis_Scale = $.trim(dataouter.Axis_Scale);

            if( Report_Row.indexOf(',') != -1 ){
                Report_Row = Report_Row.split(',');
            }else{
                Report_Row = [Report_Row];
            }

            var params = {
                row_variable : Report_Row,
                column_variable : Report_Column,
                function_variable : Report_Function,
                sum_variable : Report_Sum,
                show_as : Report_Show,
                chart_variable : Chart_Type,
                chart_axis_scale : '',
                chart_label_value : Axis_Scale,
            };

            ACFn.sendAjax('getdistributionpu','GET',{
                list_level: list_level,
                params : params,
                sql : sql
            });
        }
    });

}

function executeSQL() {
    var sSQL = window.document.Form1.sql.value;
    var handleSuccess = function (o) {
        if (o.responseText !== undefined) {
            document.getElementById('divdbtcnt').innerHTML = o.responseText;
        }
    }
    var callback = {success: handleSuccess};
    var postData = "pgaction=countsql&sSQL=" + sSQL + "&rand=" + Math.random();
    document.getElementById('divdbtcnt').innerHTML = "Loading ...";
    var request = YAHOO.util.Connect.asyncRequest('POST', 'ajax_data.php', callback, postData);
}

function CheckSavedCampaignTemplate(o) {

    var disp = document.getElementById('inserttext');
    var displabel = document.getElementById('insertlabel');

    if (o.value == "create") {
        var x = document.getElementById("cmbsavedcamp");
        x.disabled = true;
        document.frmdefine.txtCampname.disabled = false;
        disp.style.display = "none";
        displabel.style.display = "none";
        document.getElementById('campchk').value = '';
        document.getElementById('trUpdate').style.display = 'none';
        up_flag = 'new';
        document.getElementById('rUpdate').checked = true;
        disp.style.display = "none";
        displabel.style.display = "none";
        oldcampclk = 'N';
    } else {
        var x = document.getElementById("cmbsavedcamp");
        x.disabled = false;
        document.frmdefine.txtCampname.disabled = true;
        document.getElementById('trUpdate').style.display = 'block';
        document.getElementById('campchk').value = 'Y';
    }
}

function CheckSavedCampaignTemplatelib(o, libcamp_name, libcamp_id) {

    var disp = document.getElementById('inserttext');
    var displabel = document.getElementById('insertlabel');

    var x = document.getElementById("cmbsavedcamp");
    var xy = document.getElementById("cmbsavedcampNew");
    x.disabled = false;
    xy.disabled = false;
    document.frmdefine.txtCampname.disabled = true;
    document.getElementById('trUpdate').style.display = 'block';
    document.getElementById('campchk').value = 'Y';
    var obj = o;
    var libcamp_id = libcamp_id;
    var libcamp_name = libcamp_name;
    updateOptlib(obj, libcamp_name, libcamp_id);
}

function updateOptlib(obj, libcamp_name, libcamp_id) {

    var libcampid = libcamp_id;
    //var disp = document.getElementById('inserttext');
    //var displabel = document.getElementById('insertlabel');
    if (obj == 'new') {
        //disp.style.display = "block";
        //displabel.style.display = "block";
        //document.getElementById('txtnewCampname').value = libcamp_name;
        //var selist = document.getElementById("cmbsavedcamp");
        var selistnew = document.getElementById("cmbsavedcampNew");
        var lLength = $('#cmbsavedcampNew > option').length;

        for (var i = 0; i < parseInt(lLength); i++) {
            var elvalue = selistnew.options[i].value;
            if (elvalue == libcamp_id) {
                //selist.selectedIndex = i;
                selistnew.selectedIndex = i;
            }
        }

        //cmbsavedcamp
        document.getElementById("libnew").checked = "checked";
        up_flag = 'new';
        update_flag = 0;
        save();
    } else {
        //document.getElementById("libview").checked = "checked";
        $('#save, #savenow').hide();
        up_flag = 'view';
        //disp.style.display = "none";
        //displabel.style.display = "none";
        //disp.value = "";
        var selistnew = document.getElementById("cmbsavedcampNew");
        var lLength = $('#cmbsavedcampNew > option').length;
        for (var i = 0; i < parseInt(lLength); i++) {
            var elvalue = selistnew.options[i].value;
            if (elvalue == libcamp_id) {
                selistnew.selectedIndex = i;
            }
        }
        save();
    }
}  //  Change Campaign - 2014-03  End

function updateOpt(obj) {
    var disp = document.getElementById('inserttext');
    var displabel = document.getElementById('insertlabel');
    if (obj.value == 'new') {
        disp.style.display = "block";
        displabel.style.display = "block";
        up_flag = 'new';
        update_flag = 0;
    } else if (obj.value == 'update') {
        disp.style.display = "none";
        displabel.style.display = "none";
        disp.value = "";
        up_flag = 'update';
    } else if (obj.value == 'copy') {
        disp.style.display = "block";
        displabel.style.display = "block";
        up_flag = 'new';
        update_flag = 0;
    } else {
        disp.style.display = "none";
        displabel.style.display = "none";
        disp.value = "";
        up_flag = 'view';
    }
}

function shareReport() {
    /*
		var method = $('#sharereport').attr('method');
		var action = $('#sharereport').attr('action');
		var eCampid = $('#divShareBox .eCampid').val();
		var t_type = $('#divShareBox .t_type').val();

		var users = [];
        $('#userFieldList option:selected').each(function (i, selected) {
            users[i] = $(selected).text();
        });

		$.ajax({
			url : action,
			type : method,
			data : {
				'eCampid' : eCampid,
				't_type'  : t_type,
				'users'   : users
			},
			success : function(responseTxt){

			}
		});
		*/
    $('#sharereport').submit();
}

function sendReport() {
    $('#sendreportviaemail').submit();
}

function show_Create() {
    navMenu($(this),'list_Define');
    $('.list_Define').show();
    $('.list_Library').hide();
    $('.list_Running').hide();
    $('.list_Schedule').hide();
    $('.list_Define_img').hide();
    $('#cmbsavedcampNew').hide();
    $('#viewSelection').hide();

    $('.ft a[title="Run"]').parents('.yui-button').show()
    $('#meta_description').val('')

    active_select('list_Define');            // Change Campaign - 2014-03  begin
    showdiv('designview');
    hidediv('divmetadata');
    hidediv('divpreview');
    hidediv('divExecute');
    hidediv('divPromoExport');
    hidediv('divaddsub');
    hidediv('divLibrary');         // Change Campaign - 2014-03  begin
    hidediv('divRunningList');         // Change Campaign - 2019-09-26  begin
    hidediv('divScheduleList');
    up_flag = 'new';
    update_flag = 0;
    if (define_Flag == 0) {
        var sDialog = YAHOO.listpull.container.createcc;
        sDialog.show();

    } else {
        YAHOO.util.Event.removeListener("btncreatecc", "click");
    }

}

function listShortname() {
    var string = document.getElementById("listShortName").value;
    if (string.length > 20) {
        $('#indicationMsg').fadeIn('slow');
        $('#indicationMsg').text('Report Name should be less than 20 characters');
        $('#indicationMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationMsg').fadeOut(2000);
            $('#indicationMsg').text('');
        }, 3000);
        return false;
    } else if (/[^a-zA-Z0-9_\-\/]/.test(string)) {

        $("#list_name").val($.trim(string.replace(/[`~!@#$%^&*()|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '')));
        $("#listShortName").val($.trim(string.replace(/[`~!@#$%^&*()|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '')));
        $('#indicationMsg').fadeIn('slow');
        $('#indicationMsg').text('Special Characters are not Valid for this field');
        $('#indicationMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationMsg').fadeOut(2000);
            $('#indicationMsg').text('');
        }, 3000);
        return false;
    }else{
        changeListShortName();
        delay(function(){
            run_profile();
        }, 1000 );
    }
}

function changeListShortName() {

    var listShortName = $('#listShortName').val();
    var lListLevel = $('#list_level').val();
    if (lListLevel == "") {
        $('#indicationLLMsg').fadeIn('slow');
        $('#indicationLLMsg').text('Please select level');
        $('#indicationLLMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationLLMsg').fadeOut(2000);
            $('#indicationLLMsg').text('');
        }, 3000);
        $('#customFieldRow').hide();
        return false;
    }


    var lListFields = $('#list_fields').val();
    if (lListFields == "") {
        $('#indicationLfMsg').fadeIn('slow');
        $('#indicationLfMsg').text('Please select fields');
        $('#indicationLfMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationLfMsg').fadeOut(2000);
            $('#indicationLfMsg').text('');
        }, 3000);
        $('#customFieldRow').hide();
        return false;
    }
    //var lListName = pPreFix + "_" + listShortName + "_" + dDate;
    var lListName = listShortName + "_" + dDate;

    $('#list_name').val($.trim(lListName));
    /***** Check list name is already exist or not *****/

    if ($.trim(up_flag) != 'new') {
        //save();
    } else {
        document.getElementById('is_name_exist').value = '';
    }

    if (document.getElementById('is_name_exist').value == 'exist') {
        return false;
    }
    /***** Check list name is already exist or not *****/
}

function isInArray(value, array) {
    return $.inArray(value, array) != -1;
}

/****************** Add fields from new custom field summary 2017-10-20 Start *************/
$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});

function add_field(fieldname,action) { console.log(fieldname,action);

    var fieldSummaryVal = $.trim($('#fieldSummaryVal').text());
    if (action == 'selected') {

        if (fieldSummaryVal == "") {
            $('#fieldSummaryVal').text(fieldname);
        } else {
            if($("#fieldSummaryVal:contains('"+ fieldname +"')").length == 0){
                $('#fieldSummaryVal').text(fieldSummaryVal + "," + fieldname);
            }

            /*var n = fieldSummaryVal.indexOf(fieldname);
            if (n == -1) {
                $('#fieldSummaryVal').text(fieldSummaryVal + "," + fieldname);
            }*/
        }

        $("#sortcolumn").append('<option value='+fieldname+'>'+fieldname+'</option>');
    } else {
        $("#sortcolumn option[value="+fieldname+"]").remove();
        if($("#fieldSummaryVal:contains('"+ fieldname +"')").length > 0){
            var res = fieldSummaryVal.replace("," + fieldname, "");
        }else if($("#fieldSummaryVal:contains('"+ fieldname +"')").length == 0){
            if($("#fieldSummaryVal:contains(',')").length > 0){
                var res = fieldSummaryVal.replace(fieldname + ",", "");
            }else{
                var res = fieldSummaryVal.replace(fieldname, "");
            }
        }
        $('#fieldSummaryVal').text($.trim(res));
        return false;
        var n = fieldSummaryVal.indexOf(fieldname);
        if (n > 0) { // If column exist on after first position
            var res = fieldSummaryVal.replace("," + fieldname, "");
        } else if (n == 0) { // If column exist on first position
            var mn = fieldSummaryVal.indexOf(",");
            if (mn >= 0) {   //if not single column exist in field summary
                var res = fieldSummaryVal.replace(fieldname + ",", "");
            } else {        //if only single column exist in field summary
                var res = fieldSummaryVal.replace(fieldname, "");
            }
        }
        $('#fieldSummaryVal').text($.trim(res));
    }

    if(!$('#is_custom_sql').is(':checked')){
        setTimeout(function () {
            create_query();
            get_customer_excl_incl_summary(2);
            get_customer_excl_incl_summary(3);
        }, 500);
    }
}

function getDistributionFields() {
    var list_level = $('#list_level').val();
    $.ajax({
        type: 'POST',
        url: 'ajax_data.php?pgaction=getDistributionFields&rand=' + Math.random(),
        data: {'list_level': list_level},
        async: false,
        success: function (data) {
            var responseOptions = data.split('::');

            $('#row_variable_input').html('<option value=>Select</option>' + responseOptions[0].split('^'));
            $('#column_variable_input').html('<option value=>Select</option>' + responseOptions[0].split('^'));
            $('#sum_variable_input').html('<option value=>Select</option>' + responseOptions[1].split('^'));
            $('#function_input').html('<option selected value=count>Count</option><option value=sum>Sum</option>');

            $('#chart_variable').val('');
            $('#chart_variable').show();
            //create_distribution(0)
        }
    });
}

/****************** Add fields from new custom field summary 2017-10-20 End *************/

function lListTemplateName(id, aAction, selectedFields) {
    var listShortName = $('#listShortName').val();
    var lListFields = $('#list_fields').val();
    var lListLevel = $('#list_level').val();

    $('#list_fields option[value="custom_fields"]').attr('selected', 'selected');


    if ((id == "list_level" || lListLevel == "") && lListFields == "") {
        return false;
    }

    if (lListLevel == "") {
        $('#indicationLLMsg').fadeIn('slow');
        $('#indicationLLMsg').text('Please select level');
        $('#indicationLLMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationLLMsg').fadeOut(2000);
            $('#indicationLLMsg').text('');
        }, 3000);
        $('#customFieldRow').hide();
        return false;
    }

    if (lListFields == "") {
        $('#indicationLfMsg').fadeIn('slow');
        $('#indicationLfMsg').text('Please select fields');
        $('#indicationLfMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationLfMsg').fadeOut(2000);
            $('#indicationLfMsg').text('');
        }, 3000);
        $('#customFieldRow').hide();
        return false;
    }

    var lListName = listShortName + "_" + dDate;
    $('#list_name').val($.trim(lListName));

    /***** Check list name is already exist or not *****/
    if (aAction != 'change') {
        save();
    }

    if ($('#is_name_exist').val() == 'exist') {
        return false;
    }

    /********** 2018-01-19 Start Change ***************/
    $('#customerExclusionSection').show();
    $('#customerExclusionSummmeryRow').hide();

    $('#customerInclusionSection').show();
    $('#customerInclusionSummmeryRow').hide();
    /********** 2018-01-19 End Change ***************/

    if (lListFields == "custom_fields") {
        $.ajax({
            type: 'GET',
            url: 'getfieldtypes',
            data : {
                list_level : lListLevel,
                checked_fields : ['DS_MKC_ContactID']
            },
            async: true,
            beforeSend: function () {
                NProgress.start();
                $('#customFieldType').text('Loading...');
            },
            success: function (data) {
                NProgress.done();
                $('#fieldSummaryVal').text('DS_MKC_ContactID');
                $("#accordion").html('');
                $("#accordion").html(data.fieldsHtml);


                $('#row_variable_input').html('');
                $('#column_variable_input').html('<option value="">Select</option>');
                $('#sum_variable_input').html('<option value="">Select</option>');
                $.each(data.lkpOptions, function(val, text) {
                    $("#row_variable_input, #column_variable_input").append(

                        $('<option></option>').val(text).html(text)
                    );
                });

                $.each(data.numOptions, function(val, text) {
                    $("#sum_variable_input").append(
                        $('<option></option>').val(text).html(text)
                    );
                });

                initJS($('#accordion'));

                var emailMS = [
                    'row_variable_input'
                ];

                $.each(emailMS, function (index, value) {
                    if ($("#" + value).length){
                        $("#" + value).multiselect({
                            //appendTo: '#emailBox',
                            close: function () {
                            },
                            header: true, //"Region",
                            selectedList: 1, // 0-based index
                            nonSelectedText: 'Select Values',
                            enableFiltering: true,
                            filterBehavior: 'text',
                        }).multiselectfilter({label: 'Search'});

                        $("#" + value).multiselect('refresh');
                        $("#" + value + "_ms").attr('style', 'width:245.156px !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;min-height: 27px;');

                    }
                });
                $("#customFieldRow").show();
                $('#fieldSummery').hide();

                filterReset();
                create_query();
                $('#sortorder').trigger('change');
                $('#sqlQueryRow').hide();
                $('#filterSection').show();

                $('#showSqlBtn').attr('disabled', false);
                $('#btnPreviewList').attr('disabled', false);
                $('#btncheckcnt').attr('disabled', false);
                $('#btnPreview').attr('disabled', false);
                $('#divDistributionBoxBtn').attr('disabled', false);
                $('#btnDownloadList50k').attr('disabled', false);
                $('#btnDownloadList200k').attr('disabled', false);

            }
        });
    }
}

function filterReset() {
    $('#filterSection').html('<input type="hidden" id="numRows_1" value="0">\n' +
        '                <input type="hidden" id="titlelevel_1" value="11">\n' +
        '                <input type="hidden" id="next_target_1" value="plusDiv_11">\n' +
        '                <div class="divTableBody">\n' +
        '\n' +
        '                    <div class="divTableRow" id="row_11">\n' +
        '                        <div class="divTableCell ff">\n' +
        '                            <div class="divTable blueTable">\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div class="divTableCell">\n' +
        '                                            &nbsp;&nbsp;List-Level\n' +
        '                                            <span id="filterLoading" style="padding-left: 145px;display:none;">Loading....</span>\n' +
        '                                            <input type="hidden" id="tablename_11" value="">\n' +
        '                                            <input type="hidden" id="typebox_11" value="">\n' +
        '                                            <input type="hidden" id="countSec_11" value="0">\n' +
        '                                        </div>\n' +
        '\n' +
        '                                        <div id="preCross_11" class="divTableCell" style="width:1%;text-align:right !important;"></div>\n' +
        '                                        <div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_11">\n' +
        '                                            <a onclick="addSectionNew(11,11,0,1,\' &nbsp;&nbsp;List-Level\');" href="javascript:void(0);">\n' +
        '                                                <input type="hidden" id="countSec_11" value="0">\n' +
        '                                                <i class="fa fa-plus-circle font-14 ds-c"></i>\n' +
        '                                            </a>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                        <div class="divTableCell">\n' +
        '                            <div class="divTable blueTable">\n' +
        '\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_11"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_11"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_11"></div>\n' +
        '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_12"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_12"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_12"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_12"></div>\n' +
        '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_13"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_13"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_13"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_13"></div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </div>');

    $('#customerInclusionSection').html('<input type="hidden" id="numRows_3" value="0">\n' +
        '                <input type="hidden" id="titlelevel_3" value="211">\n' +
        '                <div class="divTableBody">\n' +
        '                    <div class="divTableRow" id="row">\n' +
        '                        <div class="divTableCell ff">\n' +
        '                            <div class="divTable blueTable">\n' +
        '\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div id="fieldName" style="padding-right: 21px;padding-left: 41px;" class="divTableCell"><!-- Filter  --></div>\n' +
        '                                        <div style="width:1%" class="divTableCell"></div>\n' +
        '                                        <div style="width:1%" class="divTableCell" id="plusDiv"></div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                        <div class="divTableCell">\n' +
        '                            <div class="divTable blueTable">\n' +
        '\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
        '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
        '                                        <div style="width:2%;text-align: center;" class="divTableCell" id="plusCell"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
        '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
        '                                        <div style="width:2%;text-align: center;" class="divTableCell" id="plusCell"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
        '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                    <div class="divTableRow" id="row_211">\n' +
        '                        <div class="divTableCell ff">\n' +
        '                            <div class="divTable blueTable">\n' +
        '\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div class="divTableCell">\n' +
        '                                            &nbsp;&nbsp;Detail-Level Inclusions\n' +
        '                                            <input type="hidden" id="tablename_211" value="">\n' +
        '                                            <input type="hidden" id="typebox_211" value="">\n' +
        '                                            <input type="hidden" id="countSec_211" value="0">\n' +
        '                                        </div>\n' +
        '\n' +
        '                                        <div id="preCross_211" class="divTableCell" style="width:1%;text-align:right !important;"></div>\n' +
        '                                        <div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_211">\n' +
        '                                            <a onclick="addSectionNew(211,211,0,3,\'&nbsp;&nbsp;Detail-Level Inclusions\');" href="javascript:void(0);">\n' +
        '                                                <i class="fa fa-plus-circle font-14 ds-c"></i>\n' +
        '                                            </a>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                        <div class="divTableCell">\n' +
        '                            <div class="divTable blueTable">\n' +
        '\n' +
        '                                <div class="divTableBody">\n' +
        '                                    <div class="divTableRow">\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_211"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_211"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_211"></div>\n' +
        '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_212"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_212"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_212"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_212"></div>\n' +
        '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_213"></div>\n' +
        '                                        <div style="width:7%" class="divTableCell" id="ccolCell_213"></div>\n' +
        '                                        <div style="width:3%" class="divTableCell" id="opCell_213"></div>\n' +
        '                                        <div style="width:5%" class="divTableCell" id="valCell_213"></div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>\n' +
        '                </div>');

        $('#customerExclusionSection').html('<input type="hidden" id="numRows_2" value="0">\n' +
            '                <input type="hidden" id="titlelevel_2" value="111">\n' +
            '                <div class="divTableBody">\n' +
            '                    <div class="divTableRow" id="row">\n' +
            '                        <div class="divTableCell ff">\n' +
            '                            <div class="divTable blueTable">\n' +
            '\n' +
            '                                <div class="divTableBody">\n' +
            '                                    <div class="divTableRow">\n' +
            '                                        <div id="fieldName" style="padding-right: 21px;padding-left: 41px;" class="divTableCell"><!-- Filter  --></div>\n' +
            '                                        <div style="width:1%" class="divTableCell"></div>\n' +
            '                                        <div style="width:1%" class="divTableCell" id="plusDiv"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '\n' +
            '                        </div>\n' +
            '                        <div class="divTableCell">\n' +
            '                            <div class="divTable blueTable">\n' +
            '\n' +
            '                                <div class="divTableBody">\n' +
            '                                    <div class="divTableRow">\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
            '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
            '                                        <div style="width:2%;text-align: center;" class="divTableCell" id="plusCell"></div>\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
            '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
            '                                        <div style="width:2%;text-align: center;" class="divTableCell" id="plusCell"></div>\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell"></div>\n' +
            '                                        <div style="width:4%" class="divTableCell" id="opCell"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                    <div class="divTableRow" id="row_111">\n' +
            '                        <div class="divTableCell ff">\n' +
            '                            <div class="divTable blueTable">\n' +
            '\n' +
            '                                <div class="divTableBody">\n' +
            '                                    <div class="divTableRow">\n' +
            '                                        <div class="divTableCell">\n' +
            '                                            &nbsp;&nbsp;Detail-Level Exclusions\n' +
            '\n' +
            '                                            <input type="hidden" id="tablename_111" value="">\n' +
            '                                            <input type="hidden" id="typebox_111" value="">\n' +
            '                                            <input type="hidden" id="countSec_111" value="0">\n' +
            '                                        </div>\n' +
            '\n' +
            '                                        <div id="preCross_111" class="divTableCell" style="width:1%;text-align:right !important;"></div>\n' +
            '                                        <div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_111">\n' +
            '                                            <a onclick="addSectionNew(111,111,0,2,\'&nbsp;&nbsp;Detail-Level Exclusions\');" href="javascript:void(0);">\n' +
            '                                                <i class="fa fa-plus-circle font-14 ds-c"></i>\n' +
            '                                            </a>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="divTableCell">\n' +
            '                            <div class="divTable blueTable">\n' +
            '\n' +
            '                                <div class="divTableBody">\n' +
            '                                    <div class="divTableRow">\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell_111"></div>\n' +
            '                                        <div style="width:3%" class="divTableCell" id="opCell_111"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell_111"></div>\n' +
            '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_112"></div>\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell_112"></div>\n' +
            '                                        <div style="width:3%" class="divTableCell" id="opCell_112"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell_112"></div>\n' +
            '                                        <div style="width:3%;text-align: center;font-size: 10px;" class="divTableCell" id="plusCell_113"></div>\n' +
            '                                        <div style="width:7%" class="divTableCell" id="ccolCell_113"></div>\n' +
            '                                        <div style="width:3%" class="divTableCell" id="opCell_113"></div>\n' +
            '                                        <div style="width:5%" class="divTableCell" id="valCell_113"></div>\n' +
            '                                    </div>\n' +
            '                                </div>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </div>\n' +
            '                </div>')
}

function getMultiselectFields(id, val) {
    $('#showSqlBtn').attr('disabled', false);
    $('#btnPreviewList').attr('disabled', false);
    $('#btncheckcnt').attr('disabled', false);
    $('#btnPreview').attr('disabled', false);
    $('#divDistributionBoxBtn').attr('disabled', false);
    $('#btnDownloadList50k').attr('disabled', false);
    $('#btnDownloadList200k').attr('disabled', false);
    $('#filterSummmeryRow').attr('style', 'height:70px;');
    var lListLevel = $('#list_level').val();
    var handleSuccess = function (o) {
        if (o.responseText !== undefined) {  //alert(o.responseText);
            $('#customFieldList').html('');
            $('#customFieldList').html(o.responseText);

            document.getElementById('loadingTD').style.display = "none";
            $('#loadingTD').text("");
            setTimeout(function () {  //alert(o.responseText);

                $("#customFieldList").multiselect({
                    close: function () {
                        var txtT = [];
                        $('#customFieldList :selected').each(function (i, selected) {
                            txtT[i] = $(selected).text();
                        });

                        $('#fieldSummaryVal').text(txtT);
                        $('#fieldSummery').hide();
                        get_filter_summary();
                    },
                    header: true, //"Region",
                    selectedList: 1, // 0-based index
                    nonSelectedText: 'Select Fields'
                }).multiselectfilter({label: 'Search'});


                $("#customFieldList").multiselect('refresh');

                $("#customFieldList_ms").attr('style', ' width: 210px !important; height: 20px; background-color: white !important; border-bottom-color: #1c94c4; border-top-color: #1c94c4; border-right-color: #1c94c4; border-left-color: #1c94c4; border-bottom-right-radius : 0px; border-bottom-left-radius:0px; border-top-right-radius:0px; border-top-left-radius: 0px; font-size: 0.8em;');


                $('#customFieldRow').show();
                $('#btncheckcnt').show();

                $('#LsSQL').hide();

                if (id == "customFieldType" || $('#filterSection').is(':empty') == false) {
                    $('#filterSection').show();
                } else {
                    changeSqlFields();
                }
            }, 1000);
        }
    }
    var callback = {success: handleSuccess};
    var postData = "pgaction=getCustomOptions&level=" + lListLevel + "&ftype=" + val + "&rand=" + Math.random();
    document.getElementById('loadingTD').style.display = "block";
    $('#loadingTD').text("Preparing custom fields, Please wait...");
    var request = YAHOO.util.Connect.asyncRequest('POST', 'ajax_data.php', callback, postData);
}

function create_query() {

    var fieldSummaryVal = $.trim($('#fieldSummaryVal').text());
    if ($('#list_level').val() == "sa") {
        var tTableName = "Sales_Achieve_View";
    } else {

        var tTableName = $('#list_level').val();
    }
    if (fieldSummaryVal == "") {
        var str = "SELECT  DS_MKC_ContactID  FROM  " + tTableName;
    } else {
        var str = "SELECT  " + fieldSummaryVal + "  FROM  " + tTableName;
    }

    $('#sqlQuery').val(str);
    $('#sqlQueryPart').val(str);
}

function create_query_onclick() {
    if($('.csql').is(':visible') && $('.csql').attr('data-chance') == '0') { $('.csql').hide(); $('.csql').attr('data-chance','1');  }
    $("#sqlQueryRow").toggle();
    $(".csql").toggle();
}

function uncheckCSQL(obj) { console.log(obj.is(':checked'));
    if(!obj.is(':checked')){ console.log('enter');
        get_filter_summary();
        get_customer_excl_incl_summary(2);
        get_customer_excl_incl_summary(3);
        run_report_inner();
    }
}

function OnSQLBlur() {
    if (window.document.Form1.qb.getAttribute("Connected") == true) {
        window.document.Form1.qb.setAttribute("SQL", window.document.Form1.sql.value);
    }
}



function changeSqlFields() {
    /********************* Create Query section 2016-09-24 Start ***********************/
    var dDisplayFeature = "";
    if ($('#list_fields').val() == "email_template") {
        dDisplayFeature = ' AND Display_For_ET = 1';
    } else if ($('#list_fields').val() == "call_template") {
        dDisplayFeature = ' AND Display_For_CT = 1';
    } else if ($('#list_fields').val() == "analysis_template") {
        dDisplayFeature = ' AND Display_For_AT = 1';
    } else if ($('#list_fields').val() == "custom_fields") {
        dDisplayFeature = ' AND Display_For_Filter = 1';
    }

    if ($('#list_level').val() == "sa") {
        var fieldValue = "Sales_Achieve";
        dDisplayFeature = ' AND Display_For_Select = 1';
    } else {
        var fieldValue = $('#list_level').val();
    }
    var sql = "SELECT Field_Display FROM Lookup_Fields WHERE List_Level = '" + fieldValue + "' " + dDisplayFeature;

    $.ajax({
        type: 'POST',
        url: 'ajax_data.php?pgaction=getcol&sSQL=' + sql + '&rand=' + Math.random(),
        async: false,
        success: function (data) {
            var colstr = data;
            var colarray = colstr.split(",");
            $('#filterSummmeryRow').show();
            $('#filterBtn').show();

            var fieldSummary = $.trim($('#fieldSummaryVal').text());
            if ($('#list_level').val() == "sa") {
                var tTableName = "Sales_Achieve_View";
            } else {
                var tTableName = $('#list_level').val();
            }

            if ($('#list_fields').val() != "custom_fields") {
                var str = "SELECT  " + $.trim(colarray) + "  FROM  " + tTableName;
                var listdisHTML = $.trim(colarray);
            } else if (fieldSummary != "") {
                var str = "SELECT  " + fieldSummary + "  FROM  " + tTableName;
                var listdisHTML = fieldSummary;
            } else {
                var str = "SELECT  DS_MKC_ContactID  FROM  " + tTableName;
                var listdisHTML = '';
            }

            $('#fieldSummeryVal').html(listdisHTML);
            $('#sqlQuery').val(str);

            $('#sqlQueryPart').val(str);
            $("#sqlQueryRow").hide();
        }
    });

    /********************** Create Query section 2016-09-24 End ***********************/
}

function get_filter_summary() {

    /*var waitHTML = '<table class="c1" style="width:1005px" ><tr><td style="">Filter Result</td><td style="width:\'18px\';font-weight: bold;"><td style="font-weight: bold;">Please Wait...</td></td><td style="width:\'20px\';"></td><td></td><td style="visibility: hidden;font-weight: bold">Filter Description</td><td style="visibility: hidden; font-weight: bold; width:60px">Sample %</td><td style="visibility: hidden; font-weight: bold;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
    $('#LsSQL').show();
    $('#LsSQL').html(waitHTML);*/

    var ListSegCri = new Array();
    var ListSegDes = new Array();
    var ZeroRec = 0, index = 1;
    var ccol = new Array();
    var op = new Array();
    var val = new Array();
    var log = new Array();

    var k = 0;
    var element = [' ', 'ccol', 'op', 'val', 'log'];
    var WhereArray = new Array();
    var aAditionalFilter = "";
    var importSelect = $('#importSelect').val();

    var k = 0;

    noRows = document.getElementById("numRows_1").value;
    var ccol = new Array();
    var op = new Array();
    var is_nm = new Array();//check is numeric
    var log = new Array();
    var k = 0;
    var element = [' ', 'ccol', 'op', 'val', 'log'];

    var p = 1;
    var c = noRows;

    for (var i = p; i <= c; i++) {
        for (j = 1; j <= 3; j++) {

            var id1 = (element[1] + i) + j.toString();
            var id2 = (element[2] + i) + j.toString();
            var id3 = (element[3] + i) + j.toString();
            var id4 = (element[4] + i) + j.toString();

            if ($('[id=' + id1 + ']').val() != "") {
                ccol[k] = $('[id=' + id1 + ']').val();
                op[k] = $('[id=' + id2 + ']').val();
                is_nm[k] = $('[id=' + id2 + ']').attr('rel');
                val[k] = $('[id=' + id3 + ']').val();
                if (j != 3) {
                    //log[k] = ' OR '; //$('[id='+id4+']').val();
                    if($('[id='+id4+']').length > 0){
                        log[k] = $('[id='+id4+']').val();
                    }
                }
                k++;
            } else {
                continue;
            }
        }
    }

    var where = new Array(), op1, flag;

    for (var j = 0; j < noRows; j++) {

        flag = 0;
        where[j] = "";
        for (var i = (j * 3); i < (j * 3) + 3; i++) {
            switch (op[i]) {

                case '0':
                    op1 = " > ";
                    break;
                case '1':
                    op1 = " < ";
                    break;
                case '2':
                    op1 = " >= ";
                    break;
                case '3':
                    op1 = " <= ";
                    break;
                case '4':
                    op1 = " = ";
                    break;
                case '5':
                    op1 = " != ";
                    break;
                case '6':
                    op1 = " in ";
                    break;
                case '7':
                    op1 = " not in ";
                    break;
                case '8':
                    op1 = " like ";
                    break;
                case '8.1':
                    op1 = " like ";
                    break;
                case '8.2':
                    op1 = " like ";
                    break;
                case '9':
                    op1 = " not like ";
                    break;
                case '9.1':
                    op1 = " not like ";
                    break;
                case '9.2':
                    op1 = " not like ";
                    break;
                default:
                    op1 = "";
                    break;
            }
            //if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {
            if ((ccol[i] != "") && (op1 != "")) {
                var reg = /^\d+$/;
                if (flag == 0) {
                    if (op[i] > '5' && op[i] < '8') {

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        if (ccol[i + 1] != " " && ccol[i + 1] != null) {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + nNewValVar;
                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + nNewValVar + ' )';
                        }
                        flag = 1;
                    } else if (op[i] > '7') {

                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1 || Array.isArray(val[i])) {
                            var vValArray = val[i].indexOf(",") > -1 ? val[i].split(",") :
                                Array.isArray(val[i]) ? val[i] : [];
                            console.log('vValArray---',vValArray)
                            var nNewValVar = '(';

                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }


                        if (ccol[i + 1] != " ") // For two columns
                            where[j] += '' + collog;
                        else
                            where[j] += collog;

                        flag = 1;
                    } else {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                //if (reg.test(vValArray[kk]) == true) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if (ccol[i + 1] != " ") {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';

                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' )';
                        }
                        flag = 1;
                    }
                } else if (log[i - 1] != " ") {
                    if (op[i] > '5' && op[i] < '8') {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';
                        else
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';


                    } else if (op[i] > '7') {
                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1 || Array.isArray(val[i])) {
                            var vValArray = val[i].indexOf(",") > -1 ? val[i].split(",") :
                                Array.isArray(val[i]) ? val[i] : [];
                            var nNewValVar = '(';
                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }
                        var vl = (j * 3) + 2;

                        if ((i == vl) && (ccol[vl] != "")) {
                            where[j] += log[i - 1] + collog + '';
                        } else {
                            where[j] += log[i - 1] + collog + '';
                        }

                    } else {
                        var vl = (j * 3) + 2;

                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        else {
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        }
                    }
                }  // Else if
            }  // If

        }  // Inner For
    }// Outer For

    var k = 0;
    for (var i = 0; i < where.length; i++) {
        if (where[i] != "") {

            var n = where[i].indexOf("OR");
            if (n > 0) {
                WhereArray[k] = " (" + where[i] + ") ";
            } else {
                WhereArray[k] = "" + where[i];
            }
            k++;
        }
    }
    var str = WhereArray.join(" AND ");
    if (str != "") {
        str = str;
    } else {
        str = "";
    }
    var sSQL;

    /***************** Change 2017-03-07 Start ***********************/
    str = str.replace(/::/g, ",");
    /***************** Change 2017-03-07 End ***********************/


    /************************ Without run query get filter summery Start **********************************/
    $('#filterSummmery').val(str);
    var cCECondition1 = "";
    var cCECondition2 = "";
    var cCICondition = "";

    if ($('#customerExclusionSummmery').val() != "") {
        var eExclArr = $('#customerExclusionSummmery').val().split('::^');
        cCECondition1 = eExclArr[0];
        cCECondition2 = eExclArr[1];
    }
    if ($('#customerInclusionSummmery').val() != "") {
        cCICondition = $('#customerInclusionSummmery').val();
    }

    if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCECondition1 + " WHERE " + cCECondition2;
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCECondition1 + " WHERE " + $('#filterSummmery').val() + " AND " + cCECondition2;
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCICondition + " " + cCECondition1 + " WHERE " + $('#filterSummmery').val() + " AND " + cCECondition2;

    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + " WHERE " + $('#filterSummmery').val();
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCICondition + " WHERE " + $('#filterSummmery').val();
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCICondition + " " + cCECondition1 + " WHERE " + cCECondition2;
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + "  " + cCICondition;
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val();

    sSQL = sSQL+" Order By "+$('#sortcolumn').val()+" "+$('#sortorder').val();

    if($('#is_custom_sql').is(':checked') == false){
        $('#sqlQuery').val(sSQL);
    }

    $('#LsSQL').show();
    iniFiltersFun();
    /************************ Without run query get filter summery End **********************************/
}

function get_customer_excl_incl_summary(section) {

    /*var waitHTML = '<table class="c1" style="width:1005px" ><tr><td style="">Filter Result</td><td style="width:\'18px\';font-weight: bold;"><td style="font-weight: bold;">Please Wait...</td></td><td style="width:\'20px\';"></td><td></td><td style="visibility: hidden;font-weight: bold">Filter Description</td><td style="visibility: hidden; font-weight: bold; width:60px">Sample %</td><td style="visibility: hidden; font-weight: bold;">Sample Size</td></tr><tr><td height="10px"></td></tr>';
    $('#LsSQL').show();
    $('#LsSQL').html(waitHTML);*/

    var ListSegCri = new Array();
    var ListSegDes = new Array();
    var ZeroRec = 0, index = 1;
    var ccol = new Array();
    var op = new Array();
    var is_nm = new Array();//check is numeric
    var val = new Array();
    var log = new Array();

    var k = 0;
    var element = [' ', 'ccol', 'op', 'val', 'log'];
    var WhereArray = new Array();
    var aAditionalFilter = "";
    var importSelect = $('#importSelect').val();

    var k = 0;

    noRows = document.getElementById("numRows_" + section).value;
    var ccol = new Array();
    var op = new Array();
    var log = new Array();
    var k = 0;
    var element = [' ', 'ccol', 'op', 'val', 'log'];

    if (section == 2) {
        var p = 11;
        var c = parseInt(noRows) + parseInt(p);

    } else if (section == 3) {
        var p = 21;
        var c = parseInt(noRows) + parseInt(p);
    }


    var table_name = new Array();
    var n = 0;
    for (var i = p; i < c; i++) {
        for (j = 1; j <= 3; j++) {
            if (j == 1) {
                table_name[n] = $('#tablename_' + i + j).val();
            }
            var id1 = (element[1] + i) + j.toString();
            var id2 = (element[2] + i) + j.toString();
            var id3 = (element[3] + i) + j.toString();
            var id4 = (element[4] + i) + j.toString();

            if ($('[id=' + id1 + ']').val() != "") {
                ccol[k] = $('[id=' + id1 + ']').val();
                op[k] = $('[id=' + id2 + ']').val();
                is_nm[k] = $('[id=' + id2 + ']').attr('rel');
                val[k] = $('[id=' + id3 + ']').val();
                if (j != 3) {
                    //log[k] = ' OR '; //$('[id='+id4+']').val();
                    if($('[id='+id4+']').length > 0){
                        log[k] = $('[id='+id4+']').val();
                    }
                }
                k++;
            } else {
                continue;
            }
        }
        n++;
    }

    var where = new Array(), op1, flag;
    var eExclusionCondition = "";
    for (var j = 0; j < noRows; j++) {

        flag = 0;
        where[j] = "";
        for (var i = (j * 3); i < (j * 3) + 3; i++) {
            switch (op[i]) {

                case '0':
                    op1 = " > ";
                    break;
                case '1':
                    op1 = " < ";
                    break;
                case '2':
                    op1 = " >= ";
                    break;
                case '3':
                    op1 = " <= ";
                    break;
                case '4':
                    op1 = " = ";
                    break;
                case '5':
                    op1 = " != ";
                    break;
                case '6':
                    op1 = " in ";
                    break;
                case '7':
                    op1 = " not in ";
                    break;
                case '8':
                    op1 = " like ";
                    break;
                case '8.1':
                    op1 = " like ";
                    break;
                case '8.2':
                    op1 = " like ";
                    break;
                case '9':
                    op1 = " not like ";
                    break;
                case '9.1':
                    op1 = " not like ";
                    break;
                case '9.2':
                    op1 = " not like ";
                    break;
                default:
                    op1 = "";
                    break;
            }
            if ((ccol[i] != "") && (op1 != "") && (val[i] != "")) {
                var reg = /^\d+$/;
                if (flag == 0) {
                    if (op[i] > '5' && op[i] < '8') {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }

                        if (ccol[i + 1] != " " && ccol[i + 1] != null) {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + nNewValVar;
                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + nNewValVar + ' )';
                        }
                        flag = 1;
                    } else if (op[i] > '7') {
                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1 || Array.isArray(val[i])) {
                            var vValArray = val[i].indexOf(",") > -1 ? val[i].split(",") :
                                Array.isArray(val[i]) ? val[i] : [];
                            var nNewValVar = '(';

                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }
                        if (ccol[i + 1] != " ") // For two columns
                            where[j] += '' + collog;
                        else
                            where[j] += collog;

                        flag = 1;
                    } else {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if (ccol[i + 1] != " ") {
                            where[j] += ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';

                        } else {
                            where[j] += '( ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' )';
                        }
                        flag = 1;
                    }
                } else if (log[i - 1] != " ") {
                    if (op[i] > '5' && op[i] < '8') {
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(' + vValArray[kk] + ')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + ')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '(\'' + vValArray[kk] + '\')';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\')';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '(\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '(' + val[i] + ')';
                            } else {
                                nNewValVar = '(\'' + val[i] + '\')';
                            }
                        }
                        var vl = (j * 3) + 2;
                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';
                        else
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + nNewValVar + '';


                    } else if (op[i] > '7') {
                        if (op[i] == '8') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '8.1') {
                            var st = '', ed = '%';
                        } else if (op[i] === '8.2') {
                            var st = '%', ed = '';
                        } else if (op[i] == '9') {
                            var st = '%', ed = '%';
                        } else if (op[i] == '9.1') {
                            var st = '', ed = '%';
                        } else if (op[i] == '9.2') {
                            var st = '%', ed = '';
                        }

                        if (val[i].indexOf(",") > -1 || Array.isArray(val[i])) {
                            var vValArray = val[i].indexOf(",") > -1 ? val[i].split(",") :
                                Array.isArray(val[i]) ? val[i] : [];
                            var nNewValVar = '(';
                            var collog = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (vValArray.length == 1)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (kk + 1 == vValArray.length)
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\')';
                                else if (vValArray.length > 1 && kk == 0)
                                    collog = collog + '( ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';
                                else
                                    collog = collog + ' ' + ccol[i] + ' ' + op1 + ' \'' + st + vValArray[kk] + ed + '\' OR ';   //After Start if like have more then one options
                            }
                        } else {
                            collog = '(' + ccol[i] + ' ' + op1 + ' \'' + st + val[i] + ed + '\')';
                        }
                        var vl = (j * 3) + 2;
                        if ((i == vl) && (ccol[vl] != "")) {
                            where[j] += log[i - 1] + collog + '';
                        } else {
                            where[j] += log[i - 1] + collog + '';
                        }

                    } else {
                        var vl = (j * 3) + 2;
                        if (val[i].toString().indexOf(",") > -1) {
                            var vValArray = val[i].toString().split(",");
                            var nNewValVar = '';
                            for (kk = 0; kk < vValArray.length; kk++) {
                                if (is_nm[i] == 1) {
                                    if (vValArray.length == 1)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '' + vValArray[kk] + '';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '' + vValArray[kk] + ',';
                                    else
                                        nNewValVar += '' + vValArray[kk] + ',';
                                } else {
                                    if (vValArray.length == 1)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (kk + 1 == vValArray.length)
                                        nNewValVar += '\'' + vValArray[kk] + '\'';
                                    else if (vValArray.length > 1 && kk == 0)
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                    else
                                        nNewValVar += '\'' + vValArray[kk] + '\',';
                                }
                            }
                        } else {
                            if (is_nm[i] == 1) {
                                nNewValVar = '' + val[i] + '';
                            } else {
                                nNewValVar = '\'' + val[i] + '\'';
                            }
                        }
                        if ((i == vl) && (ccol[vl] != ""))
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        else {
                            where[j] += log[i - 1] + ' ' + ccol[i] + ' ' + op1 + ' ' + nNewValVar + ' ';
                        }
                    }
                }  // Else if
            }  // If

        }  // Inner For
        if (section == 2 && where[j] != "") {
            eExclusionCondition += " LEFT JOIN  (select distinct DS_MKC_ContactID as ex_customerid from " + table_name[j] + " where " + where[j] + " )  excl_" + j + " on excl_" + j + ".ex_customerid=s.DS_MKC_ContactID";
            if (j == 0) {
                cCECondition2 = " excl_" + j + ".ex_customerid is null";
            } else {
                cCECondition2 += " AND excl_" + j + ".ex_customerid is null";
            }
        }
        if (section == 3 && where[j] != "") {
            eExclusionCondition += " INNER JOIN  (select distinct DS_MKC_ContactID as in_customerid from " + table_name[j] + " where " + where[j] + " )  incl_" + j + " on incl_" + j + ".in_customerid=s.DS_MKC_ContactID";
        }
    }// Outer For


    /***************** Change 2017-03-07 Start ***********************/
    eExclusionCondition = eExclusionCondition.replace(/::/g, ",");
    /***************** Change 2017-03-07 End ***********************/


    /************************ Without run query get filter summery Start **********************************/

    if (section == 2) {
        if (cCECondition2 != undefined)
            $('#customerExclusionSummmery').val(eExclusionCondition + '::^' + cCECondition2);
        else
            $('#customerExclusionSummmery').val('');
    } else if (section == 3)
        $('#customerInclusionSummmery').val(eExclusionCondition);


    var cCECondition1 = "";
    var cCECondition2 = "";
    var cCICondition = "";

    if ($('#customerExclusionSummmery').val() != "") {
        var eExclArr = $('#customerExclusionSummmery').val().split('::^');
        cCECondition1 = eExclArr[0];
        cCECondition2 = eExclArr[1];
    }
    if ($('#customerInclusionSummmery').val() != "") {
        cCICondition = $('#customerInclusionSummmery').val();
    }
    var sSQL = '';
    if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCECondition1 + " WHERE " + cCECondition2;
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCECondition1 + " WHERE " + $('#filterSummmery').val() + " AND " + cCECondition2;
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCICondition + " " + cCECondition1 + " WHERE " + $('#filterSummmery').val() + " AND " + cCECondition2;

    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val() + " WHERE " + $('#filterSummmery').val();
    else if ($('#filterSummmery').val() != "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCICondition + " WHERE " + $('#filterSummmery').val();
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() != "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCICondition + " " + cCECondition1 + " WHERE " + cCECondition2;
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() != "")
        sSQL = $('#sqlQueryPart').val() + " s " + cCICondition;
    else if ($('#filterSummmery').val() == "" && $('#customerExclusionSummmery').val() == "" && $('#customerInclusionSummmery').val() == "")
        sSQL = $('#sqlQueryPart').val();

    sSQL = sSQL+" Order By "+$('#sortcolumn').val()+" "+$('#sortorder').val();

    if($('#is_custom_sql').is(':checked') == false){
        $('#sqlQuery').val(sSQL);
    }
    iniFiltersFun();
    /************************ Without run query get filter summery End **********************************/
}

function iniFiltersFun() {
    var filterSection = $('#filterSection');
    filterSection.on('onblur keyup change paste',function(e) {

        if((e.type == 'onblur' && e.target.tagName == 'INPUT') ||(e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
            delay(function(){
                var str1 = e.target.id;
                var str2 = "val";
                if(str1.indexOf(str2) != -1){
                    run_report_inner();
                }
            }, 1000 );
        }
    });


    var customerInclusionSection = $('#customerInclusionSection');
    customerInclusionSection.on('onblur keyup change paste',function(e) {

        if((e.type == 'onblur' && e.target.tagName == 'INPUT') || (e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
            delay(function(){
                var str1 = e.target.id;
                var str2 = "val";
                if(str1.indexOf(str2) != -1){
                    run_report_inner()
                }
            }, 1000 );
        }
    });

    var customerExclusionSection = $('#customerExclusionSection');
    customerExclusionSection.on('onblur keyup change paste',function(e) {

        if((e.type == 'onblur' && e.target.tagName == 'INPUT') || (e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
            delay(function(){
                var str1 = e.target.id;
                var str2 = "val";
                if(str1.indexOf(str2) != -1){
                    run_report_inner()
                }
            }, 1000 );
        }
    });

    var sqlQuery = $('#sqlQuery');
    sqlQuery.on('onblur keyup change paste',function(e) {

        if((e.type == 'onblur' && e.target.tagName == 'TEXTAREA') || (e.type == 'keyup' && e.target.tagName == 'TEXTAREA') || (e.type == 'change' && e.target.tagName == 'TEXTAREA')){
            delay(function(){
                run_report_inner()
            }, 1000 );
        }
    });
}

function check_sql_count() {
    var fieldSummery = $.trim($('#fieldSummaryVal').text());

    if ($('#is_custom_sql').is(':checked') == false) {
        get_filter_summary();
        get_customer_excl_incl_summary(2);
        get_customer_excl_incl_summary(3);

        var sSQL = $('#sqlQuery').val();
    } else {
        var sSQL = $('#sqlQuery').val();
    }
    var nNewSQL = sSQL;//escape(sSQL);

    ACFn.sendAjax('countsql','GET',{
        sSQL : nNewSQL
    });
}

function loadpreview() {
    var fieldSummery = $.trim($('#fieldSummaryVal').text());

    /********************* Changed 2017-03-07 start*********************/
    if ($('#list_level').val() == "sa") {
        var tTableName = "Sales_Achieve_View";
    } else {
        var tTableName = $('#list_level').val();
    }
    var filterSummmery = $('#filterSummmery').val();
    /********************* Changed 2017-03-07 end *********************/

    if ($('#is_custom_sql').is(':checked') == false) {
        get_filter_summary();
        get_customer_excl_incl_summary(2);
        get_customer_excl_incl_summary(3);
        var sSQL = $('#sqlQuery').val();
    } else {
        var sSQL = $('#sqlQuery').val();
    }

    ACFn.sendAjax('preview','GET',{
        sql : sSQL,
        fieldSummery :fieldSummery,
        tTableName : tTableName,
        filterSummmery : filterSummmery
    });
}

function is_query_changed(v) {
    //$('#is_query_changed').val(v);
}

function toggleFieldSummery() {
    $('#fieldSummery').toggle();
}

function clearFilterSummary() {
    $('#filterSummmery').val('');
    $('#fieldSummaryVal').text('');
    $(':input', '#accordion').not(':button, :submit, :reset, :hidden').removeAttr('checked').removeAttr('selected');
    var element = ['ccol', 'op', 'val'];
    for (var section = 1; section <= 3; section++) {
        if (section == 1) {
            var p = 1;
        } else if (section == 2) {
            var p = 11;
        } else if (section == 3) {
            var p = 21;
        }

        for (var i = p; i <= parseInt(p) + 10; i++) {      // Row loop
            for (var j = 1; j <= 3; j++) {
                var id1 = (element[0] + i) + j.toString();
                var id2 = (element[1] + i) + j.toString();
                var id3 = (element[2] + i) + j.toString();
                $('#' + id1).val('');
                $('#' + id2).val('');
                $('#' + id3).val('');
            }
        }
    }

    create_query();
}

function updateSqlEditFilterSummery() { //alert('here');
    if ($('#list_level').val() == "sa") {
        var tTableName = "Sales_Achieve_View";
    } else {
        var tTableName = $('#list_level').val();
    }

    var filterSummmery = $('#filterSummmery').val();
    var fieldSummery = $.trim($('#fieldSummaryVal').text());

    if (filterSummmery != "")
        var str = "SELECT  " + fieldSummery + "  FROM  " + tTableName + " WHERE " + filterSummmery;
    else
        var str = "SELECT  " + fieldSummery + "  FROM  " + tTableName;

    $('#sqlQuery').val(str);
    //is_query_changed(1);
}


function runprofile(pos,elementid = '') {
    if ($.trim($('#campaign_id').val()) == "") {
        $('#indicationCMsg').fadeIn('slow');
        $('#indicationCMsg').text('Please select campaign');
        $('#indicationCMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationCMsg').fadeOut(2000);
            $('#indicationCMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#listShortName').val()) == "") {
        $('#indicationMsg').fadeIn('slow');
        $('#indicationMsg').text('Please enter profile name');
        $('#indicationMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationMsg').fadeOut(2000);
            $('#indicationMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#target_group').val()) == "") {
        $('#indicationtgMsg').fadeIn('slow');
        $('#indicationtgMsg').text('Please enter target group');
        $('#indicationtgMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationtgMsg').fadeOut(2000);
            $('#indicationtgMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#comparision_group').val()) == "") {
        $('#indicationcgMsg').fadeIn('slow');
        $('#indicationcgMsg').text('Please enter comparision group');
        $('#indicationcgMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationcgMsg').fadeOut(2000);
            $('#indicationcgMsg').text('');
        }, 3000);
        return false;
    }
    NProgress.start();
    if(elementid != ''){
        $('#' + elementid).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
    }

    var campaign_id = $('[name="campaign"]').val();
    var campaign_name = $('[name="campaign"] :selected').text();
    var fields = $('#fields').val();
    var output_format = $('#output_format').val();
    var profile_name = $('#listShortName').val();
    var profile_description = $('#profile_description').val();
    /*console.log('profile----',campaign_id,campaign_name,fields,output_format,profile_name,profile_description);*/

    setTimeout(function () {
        ACFn.sendAjax('profile/generatesummaryreport', 'post', {
            campaign_id: campaign_id,
            fields: JSON.stringify(fields),
            output_format: output_format,
            profile_name: profile_name,
            profile_description: profile_description
        }, $('#' + elementid), {}, false);
    },1000);


}

function runprofileafter(pos,elementid = '') {
    var params = JSON.parse(localStorage.getItem('params'));
    var campaign_id = params.CampaignID;
    var fields = params.list_fields;
    var output_format = params.SR_Attachment;
    var profile_name = params.listShortName;
    var profile_description = params.Category;
    /*console.log('profile----',campaign_id,campaign_name,fields,output_format,profile_name,profile_description);*/

    ACFn.sendAjax('profile/generatesummaryreport','post',{
        campaign_id : campaign_id,
        fields : JSON.stringify(fields),
        output_format : output_format,
        profile_name : profile_name,
        profile_description : profile_description
    },$('#' + elementid),{},false);


}

function addsubSQL(obj,runoption = true){
    if ($.trim($('#campaign_id').val()) == "") {
        $('#indicationCMsg').fadeIn('slow');
        $('#indicationCMsg').text('Please select campaign');
        $('#indicationCMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationCMsg').fadeOut(2000);
            $('#indicationCMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#listShortName').val()) == "") {
        $('#indicationMsg').fadeIn('slow');
        $('#indicationMsg').text('Please enter profile name');
        $('#indicationMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationMsg').fadeOut(2000);
            $('#indicationMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#target_group').val()) == "") {
        $('#indicationtgMsg').fadeIn('slow');
        $('#indicationtgMsg').text('Please enter target group');
        $('#indicationtgMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationtgMsg').fadeOut(2000);
            $('#indicationtgMsg').text('');
        }, 3000);
        return false;
    }

    if ($.trim($('#comparision_group').val()) == "") {
        $('#indicationcgMsg').fadeIn('slow');
        $('#indicationcgMsg').text('Please enter comparision group');
        $('#indicationcgMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationcgMsg').fadeOut(2000);
            $('#indicationcgMsg').text('');
        }, 3000);
        return false;
    }

    obj.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
    var pos = runoption == false ? 2 : 3;
    //runprofile(pos,obj);
    /*if ($.trim($('#listShortName').val()) == "") {
        $('#indicationMsg').fadeIn('slow');
        $('#indicationMsg').text('Please enter profile name');
        $('#indicationMsg').attr('style', 'color:red;');
        setTimeout(function () {
            $('#indicationMsg').fadeOut(2000);
            $('#indicationMsg').text('');
        }, 3000);
        return false;
    }

    var campaign_id = $('[name="campaign"]').val();
    var campaign_name = $('[name="campaign"] :selected').text();
    var fields = $('#fields').val();
    var output_format = $('#output_format').val();
    var profile_name = $('#listShortName').val();
    var profile_description = $('#profile_description').val();
    /!*console.log('profile----',campaign_id,campaign_name,fields,output_format,profile_name,profile_description);*!/
    ACFn.sendAjax('profile/generatesummaryreport','post',{
        campaign_id : campaign_id,
        fields : JSON.stringify(fields),
        output_format : output_format,
        profile_name : profile_name,
        profile_description : profile_description
    });*/


    $('#save','#savenow').attr('disabled', true);

    if (up_flag == 'new') {

        $.ajax({
            url: 'profile/getlist',
            type: 'GET',
            async: false,
            success: function (data) {
                var flag = 0;
                var CampNameStr = data;
                var nameArray = CampNameStr.list;
                for (var i = 0; i < nameArray.length; i++) {
                    if (nameArray[i].t_name.toLowerCase() == (document.getElementById('list_name').value).toLowerCase())
                        flag = 1;
                }
                if (flag == 1) {
                    var data = {
                        'title': 'Profile Name Exists.... Please Enter New Profile Name ',
                        'text' : '',
                        'butttontext' : 'Ok',
                        'cbutttonflag' : false
                    };
                    ACFn.display_confirm_message(data);

                    checkval = 'N';
                    document.getElementById('is_name_exist').value = 'exist';
                    $('#save','#savenow').attr('disabled', false);
                    return false;
                } else {
                    document.getElementById('is_name_exist').value = '';
                    Camp_Name = document.getElementById('list_name').value;
                    deflag = 1;
                    if (addsubgroupchk == 'N')
                        $.ajax({
                            url: 'profile/seq',
                            type: 'GET',
                            async: false,
                            success: function (data) {
                                camp_id = data.cid;
                                document.getElementById('camp_id').value = $.trim(camp_id);
                                update_flag = 0;
                            }
                        });
                }
            }
        });

        if ($('#is_name_exist').val() == 'exist') {
            return false;
        }

    }

    $('.list_Define_img').hide();
    $('.list_Library').hide();
    seg_openFlag = 'Y';
    if (addsubgroupchk == 'N') {
        $('#save','#savenow').attr('disabled', true);
        setTimeout(function () {
            session(runoption,obj);
        },1500)

    }
}

function changeImportTemplate(vall) {
    var newVal = vall.replace("y_M", "y");
    if (newVal == 'Imported_Company::^') {
        newVal = 'Imported_Companies_Parents_Masters';
    }
    $("#importTempLink").attr("href", "Public/" + newVal + ".xlsx");
}

function downloadFile50k(fileType) {
    var fieldSummery = $.trim($('#fieldSummeryColumns').text());

    $('#downloadBtn50k').css('background', 'green');
    if ($('#is_custom_sql').is(':checked') == false) {
        get_filter_summary();
        get_customer_excl_incl_summary(2);
        get_customer_excl_incl_summary(3);
        var sSQL = $('#sqlQuery').val();
    } else {
        var sSQL = $('#sqlQuery').val();
    }
    var fFileName = $.trim($('#listShortName').val()) != "" ? $.trim($('#list_name').val()) : 'ReportWith10K' ;

    var nNewSQL = sSQL; //escape(sSQL);
    var List_Level = $('#list_level').val();
    var List_Fields = $('#list_fields').val();

    ACFn.sendAjax('download10K','GET',{
        ftype : fileType,
        filename :fFileName,
        sSQL : nNewSQL,
        List_Level : List_Level,
        List_Fields : List_Fields
    });

}

function create_distribution_option() {
    $('#distributionResultHtml').html('');
    $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px;');
    $('#indicationCFMsgForDistribution').text('');

    var list_level = $('#list_level').val();
    $.ajax({
        type: 'POST',
        url: 'ajax_data.php?pgaction=getDistributionFields&rand=' + Math.random(),
        data: {'list_level': list_level},
        async: true,
        success: function (data) {
            var responseOptions = data.split('::');

            $('#row_variable').html('<option value=>Select</option>' + responseOptions[0].split('^'));
            $('#column_variable').html('<option value=>Select</option>' + responseOptions[0].split('^'));
            $('#sum_variable').html('<option value=>Select</option>' + responseOptions[1].split('^'));
            $('#function_variable').html('<option value=count>Count</option><option value=sum>Sum</option>');

            $('#row_variable').val($('#row_variable_input').val())
            $('#column_variable').val($('#column_variable_input').val())
            $('#function_variable').val(($('#function_input').val() != "") ? $('#function_input').val().toLowerCase() : 'count')
            /* $('#show_as option:contains(' + $('#show_as_input').val() != "" ? $('#show_as_input').val().toLowerCase() : 'number' + ')').attr('selected', true);*/

            var showas = $('#show_as_input').val() != "" ? $('#show_as_input option:selected').attr('rel') : 'number';
            $('#show_as').find('option[rel="' + showas + '"]').attr('selected', true);

            //$('#show_as').val(($('#show_as_input').val() != "") ? $('#show_as_input').val() : 'number')
            $('#sum_variable_input').val() != "" ? $('#sum_variable_box').show() : $('#sum_variable').hide()
            $('#sum_variable').val($('#sum_variable_input').val())

            $('#chart_variable').val($('#chart_variable_input').val());
            $('#chart_variable').show();
            create_distribution(0)
        }
    });
}

function create_distribution(is_download,$outer = []) {
    console.log($outer)
    if ($('#row_variable').val() == "") {
        $('#row_variable').attr('style', 'width:105px;margin-right:25px;border-color:red;');
    } else if ($('#function_variable').val() == "") {
        $('#function_variable').attr('style', 'width:105px;margin-right:25px;border-color:red;');
    } else if ($('#function_variable').val() == "sum" && $('#sum_variable').val() == "") {
        $('#sum_variable').attr('style', 'width:105px;margin-right:25px;border-color:red;');
    } else {
        var formData = $('#getDistributionFields').serialize();
        var list_level = $('#list_level').val();
        var list_name = $('#list_name').val();
        var list_description = $('#meta_description').val();
        var sSql = $('#sqlQuery').val();
        if($outer.length > 0){
            list_level = $outer[0]['list_level'];
            sSql = $outer[0]['sql'];
        }
        $('#distributionResultHtml').html('');

        $.ajax({
            type: 'POST',
            url: 'ajax_data.php?pgaction=getDistribution&rand=' + Math.random(),
            data: formData + '&list_name=' + list_name +'&list_description=' + list_description + '&list_level=' + list_level + '&filterval=' + $('#filterSummmery').val() + '&sql=' + escape(sSql),
            async: true,
            dataType: "json",
            beforeSend: function () {
                $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px');
                $('#indicationCFMsgForDistribution').text('Summary report running. Please wait???');
                $('#chartP').html('');
            },
            success: function (data) {
                $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px');
                $('#indicationCFMsgForDistribution').text('');
                if ($.trim(data) != "") {
                    $('#distributionResultHtml').html(data.html);
                    $('#distributionResultHtml').find('table').attr('style','width:100% !important;float:none !important');
                    localStorage.removeItem('xlsxData');
                    var xlsxData = [];
                    var entry = {
                        "xHeader" : data.xHeaders,
                        "bHeader" : data.bHeaders,
                        "val" : data.val,
                        "postfix" : data.postfix,

                    };
                    xlsxData.push(entry);
                    localStorage.setItem("xlsxData", JSON.stringify(xlsxData));
                    console.log(JSON.parse(localStorage.getItem("xlsxData")))
                    setTimeout(function () {
                        d_pdf(is_download);
                    }, 1500)

                    $('#row_variable').val() ? $('#tabular_report').show() : $('#tabular_report').hide()
                    $('#row_variable_input').val(ucwords($('#row_variable').val(), true))
                    $('#column_variable_input').val(ucwords($('#column_variable').val()))
                    $('#function_input').val($('#function_variable').val() != "" ? $('#function_variable').val().toLowerCase() : 'count')

                    $('#chart_variable_input').val($('#chart_variable').val() != "" ? $('#chart_variable').val().toLowerCase() : '')

                    $('#show_as_input').val($('#show_as option:selected').val())
                    if (ucwords($('#function_variable').val()) != "Count") {
                        $('.sf').show();
                        $('#sum_variable_input').val(ucwords($('#sum_variable').val()))
                    } else {
                        $('.sf').hide();
                        $('#sum_variable_input').val('')
                    }


                    $('#distributionResultHtml').append('<div id="distributionChartResult"></div>');
                    //$('#chart_variable').val('');
                    $('#chart_variable_box').show();  // If want to hide chart functionality
                    if($('#chart_variable').val() != ""){
                        $('#chart_variable').trigger('change');
                    }

                } else {
                    $('#chart_variable_box').hide();
                    $('#indicationCFMsgForDistribution').attr('style', 'color:red;font-size:13px');
                    $('#indicationCFMsgForDistribution').text('Result not found');
                }

                if ($('#column_variable').val() == "") {
                    $('#show_as').find('option').not(':first').hide();
                    $('#chart_variable option[value=pie]').show();
                    $('#chart_variable option[value=line]').hide();
                } else {
                    $('#show_as').find('option').show();
                    $('#chart_variable option[value=pie]').hide();
                    $('#chart_variable option[value=line]').show();
                }

            },
            error: function (jqXHR, exception) {

                $('#chart_variable_box').hide();
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }

                $('#indicationCFMsgForDistribution').text(msg);
                $('#indicationCFMsgForDistribution').attr('style', 'color:red;font-size:13px');
            }
        });
    }
}

function delete_row_comp(libcamp_id) {
    var ans = confirm("Confirm Deletion?");
    if (ans) {
        $.ajax({
            type: 'POST',
            url: 'delete',
            data: {del_row : libcamp_id , type : 'P', _token : $('[name="_token"]').val()},
            async: false,
            success: function (dataouter) {
                ACFn.display_message('Successfully Deleted','','success')
                $('.tab-ajax li a.active').trigger('show.bs.tab');
            }
        })
    }
}

function delete_older_version_row_comp(libcamp_id) {
    var ans = confirm("Confirm Deletion?");
    if (ans) {
        $.ajax({
            type: 'POST',
            url: 'delete_older_version',
            data: {del_row : libcamp_id , type : 'A', _token : $('[name="_token"]').val()},
            async: false,
            dataType : 'JSON',
            success: function (dataouter) {
                if(dataouter.success){
                    ACFn.display_message('Successfully Deleted','','success')
                    $('.tab-ajax li a.active').trigger('show.bs.tab');
                    $('.older-version').show();
                }else{
                    ACFn.display_message(dataouter.messageTitle,'','success')
                }

            }
        })
    }
}

function refreshPage() {
    //timer.resume();
    navMenu($(this),'list_Define');
    location.reload();
}

function updatePrivacy(val) {
    YAHOO.csr.container.wait.setHeader("Updating, please wait...");
    YAHOO.csr.container.wait.setBody("<img src=\"http://l.yimg.com/a/i/us/per/gr/gp/rel_interstitial_loading.gif\"/>");

    YAHOO.csr.container.wait.render(document.body);
    YAHOO.csr.container.wait.show();

    var libnameArray = val.split(",");
    var is_pulic = libnameArray[0];
    var libcamp_id = libnameArray[1];

    var postData = "pgaction=udprivacy&row_id=" + libcamp_id + "&is_public=" + is_pulic;

    $.ajax({
        type: 'POST',
        url: 'ar_sch_data.php',
        data: postData,
        async: true,
        success: function (response) { //alert(data);
            YAHOO.csr.container.wait.hide();
        },
        complete:function () {
            YAHOO.csr.container.wait.hide();
        }
    });
}

function limitText(limitField, limitCount, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    } else {
        limitCount.value = limitNum - limitField.value.length;
    }
}

function addSectionNew(rowIds, secIds, flag, section, title) {
    var ntitle = title.replace('^', '"');
    ntitle = ntitle.replace('^', '"');

    var cColOptions = '<option value=""></option>';
    var List_Level = $('#list_level').val();
    var customClass = '';
    var sectionType;
    var color = '';
    if (section == 1)
        sectionType = 'F';
    else if (section == 2) {
        sectionType = 'CE';
        customClass = 'red-elements';
        color = 'color:red !important;';
    } else if (section == 3) {
        sectionType = 'CI';
    } else
        sectionType = 'F';

    $.ajax({
        type: 'GET',
        url: 'getfieldtypesforfilter',
        data : {
            _token : $('[name="_token"]').val(),
            List_Level : List_Level,
            sectiontype : sectionType
        },
        async: true,
        beforeSend: function () {
            $('#ccolCell_' + secIds).text('Loading...');
        },
        success: function (data) {
            cColOptions = data.colOptions;

            /*for (var i = 0; i < cColArray.length; i++) {
                cColOptions += '<option value="' + $.trim(cColArray[i]) + '">' + $.trim(cColArray[i]) + '</option>';
            }*/
            if (flag == 1) {
                if (section == 2 || section == 3) {
                    var getType = $('#typebox_' + rowIds).val();
                    getSectionMaterial(rowIds, secIds, flag, getType, section, '\'' + title + '\'');
                } else {
                    $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:100%;" onchange="getSectionMaterial(' + rowIds + ',' + secIds + ',' + flag + ',this.value,' + section + ',\'' + title + '\');" class="t1" style="width:100%;" id="ttype' + secIds + '" name="ttype11" value=">">' + cColOptions + '</select>');
                }
            } else {
                $('#plusDiv_' + secIds).children('a').attr('onclick', '')
                $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:100%;" onchange="getSectionMaterial(' + rowIds + ',' + secIds + ',' + flag + ',this.value,' + section + ',\'' + title + '\');" class="t1" id="ttype' + secIds + '" name="ttype11" value=">">' + cColOptions + '</select>');
                var newRowIds = parseInt(rowIds) + 10;
                var newSecIds1 = parseInt(secIds) + 10;
                var newSecIds2 = parseInt(secIds) + 11;
                var newSecIds3 = parseInt(secIds) + 12;
                $('#row_' + rowIds).after('<div class="divTableRow" id="row_' + newRowIds + '"><div class="divTableCell ff"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="' + color + 'vertical-align: middle; visibility: hidden;" class="divTableCell" id="info_' + newRowIds + '">' + ntitle + '<input type="hidden" id="tablename_' + newRowIds + '" value="" /><input type="hidden" id="typebox_' + newRowIds + '" value="" /><input type="hidden" id="countSec_' + newRowIds + '" value="0" /></div><div style="width:1%; text-align:right !important;" id="preCross_' + newRowIds + '" class="divTableCell"></div><div style="width:1%;font-size: 10px;text-align:center;" class="divTableCell" id="plusDiv_' + newSecIds1 + '"><a onclick="addSectionNew(' + newRowIds + ',' + newRowIds + ',0,' + section + ',\'' + title + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a></div></div></div></div></div><div class="divTableCell"><div class="divTable blueTable"><div class="divTableBody"><div class="divTableRow"><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds1 + '"></div><div style="width:3%" class="divTableCell"  id="opCell_' + newSecIds1 + '"></div><div style="width:5%" class="divTableCell"  id="valCell_' + newSecIds1 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds2 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds2 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds2 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds2 + '"></div><div style="width:3%;text-align: center;font-size: 8px;" class="divTableCell" id="plusCell_' + newSecIds3 + '"></div><div style="width:7%" class="divTableCell" id="ccolCell_' + newSecIds3 + '"></div><div style="width:3%" class="divTableCell" id="opCell_' + newSecIds3 + '"></div><div style="width:5%" class="divTableCell" id="valCell_' + newSecIds3 + '"></div></div></div></div></div></div>');
            }
        }
    });

}

function getSectionMaterial(rowIds, secIds, flag, tType, section, title) {
    var customClass = '';
    var cColOptions = '<option value=""></option>';
    var List_Level = $('#list_level').val();
    var sectionType;
    if (section == 1)
        sectionType = 'F';
    else if (section == 2) {
        sectionType = 'CE';
        $('#typebox_' + rowIds).val(tType);
        customClass = 'red-elements'
    } else if (section == 3) {
        $('#typebox_' + rowIds).val(tType);
        sectionType = 'CI';
    } else
        sectionType = 'F';

    $.ajax({
        type: 'GET',
        url: 'getfields',
        data : {
            _token : $('[name="_token"]').val(),
            field_type : tType,
            sectiontype : sectionType,
            List_Level : List_Level,
        },
        async: true,
        beforeSend: function () {
            $('#ccolCell_' + secIds).text('Loading...');
        },
        success: function (data) {
            //var responseData = data.split('::^');
            $('#tablename_' + rowIds).val(data.Tb_Name);
            $('#ccolCell_' + secIds).text('');
            //var cColArray = responseData[1].split(',');

            cColOptions = data.fields;

            /*for (var i = 0; i < cColArray.length; i++) {
                cColOptions += '<option value="' + $.trim(cColArray[i]) + '">' + $.trim(cColArray[i]) + '</option>';
            }*/
            var opId = 'op' + secIds;
            $('#ccolCell_' + secIds).html('<select class="form-control form-control-sm fst-f ' + customClass + '" style="width:100%;" onchange="getCol(this,\'' + opId + '\',\'' + secIds + '\',\'' + section + '\');" class="t1" style="width: 112px; !important;" name="ccol' + secIds + '" id="ccol' + secIds + '" value=">">' + cColOptions + '</select>');
        }
    });

    if (flag == 0) {
        var preRowIds = parseInt(rowIds) - 10;
        if (section != 2 && section != 3) {
            $('#plusDiv_' + rowIds).text('AND');
        } else {
            $('#plusDiv_' + rowIds).text('');
        }
        $('#plusDiv_' + rowIds).text('');
        var numRows = $('#numRows_' + section).val();
        numRows = parseInt(numRows) + 1;
        $('#numRows_' + section).val(numRows);
        $('#plusDiv_' + rowIds).html('<a style="display:block;" class="crosss" onclick="removeSection(' + rowIds + ',' + secIds + ',' + section + ');" href="javascript:void(0);"><i class="fas fa-trash font-14"></i> </a>');
    }
    if (flag == 1) {
        $('#logCell_' + secIds).hide();
        $('#plusCell_' + secIds).html('');
        //$('#plusCell_' + secIds).text('OR');
        $('#plusCell_' + secIds).html('<select class="dvd form-control form-control-sm" style="width:100%;" id="log'+parseInt(secIds - 1)+'" name="log'+parseInt(secIds - 1)+'"><option value=" OR ">OR</option><option value=" AND ">AND</option></select>');
        var countSec = $('#countSec_' + rowIds).val();
        countSec = parseInt(countSec) + 1;
        $('#countSec_' + rowIds).val(countSec);
    }

    $('#opCell_' + secIds).html('<select class="form-control form-control-sm ' + customClass + '" style="width:100%;" id="op' + secIds + '" name="op' + secIds + '"><option value=" "></option><option value="0">&gt;</option><option value="1">&lt;</option><option value="2">&gt;=</option><option value="3">&lt;=</option><option selected="" value="4">=</option><option value="5">!=</option><option value="6">in</option><option value="7">not in</option><option value="8">like</option><option value="9">not like</option></select>');
    $('#valCell_' + secIds).html('<div class="form-group"><input class="form-control form-control form-control-sm  ' + customClass + '" style="width:100%;" id="val' + secIds + '" onkeyup="get_filter_summary();" name="val' + secIds + '" type="text"></div>');

    var nextIds = parseInt(secIds) + 1;

    $('#plusCell_' + nextIds).html('<a onclick ="addSectionNew(' + rowIds + ',' + nextIds + ',1,' + section + ',\'' + title.toString() + '\');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i></a>');

    if (countSec != undefined && countSec == 1 && section != 1) {
        $('#plusCell_' + nextIds).html('<a onclick ="addSectionNew(' + rowIds + ',' + nextIds + ',1,' + section + ',' + title + ');" href="javascript:void(0);"><i class="fas fa-plus-circle font-14 ds-c"></i> </a>');
    }
}

function removeSection(rowIds, secIds, section) {

    var nxtRowIds = parseInt(rowIds) + 10;
    var titlelevel = $('#titlelevel_' + section).val();
    if (rowIds == $('#titlelevel_' + section).val()) {

        for (var i = 1; i <= $('#numRows_' + section).val(); i++) {
            titlelevel = parseInt(titlelevel) + 10;
            if ($('#info_' + titlelevel).length > 0) {
                $('#titlelevel_' + section).val(titlelevel);
                $('#info_' + titlelevel).css('visibility', 'visible');
                break;
            }

        }

    }
    $('#row_' + rowIds).remove();
    get_filter_summary();
    get_customer_excl_incl_summary(2);
    get_customer_excl_incl_summary(3);
    run_report_inner();
}

function showCross(ids) {
    $('#preCross_' + ids + ' .cross').show();
}

function hideCross(ids) {
    $('#preCross_' + ids + ' .cross').hide();
}

function changeVal(val, secIds, section) {

    var notAllowed = ['0', '1', '4', '8', '8.1', '8.2'];
    if ($('#is_custom_sql').is(':checked') == false) {
        get_filter_summary();
        get_customer_excl_incl_summary(2);
        get_customer_excl_incl_summary(3);
    }

    var classname = '';
    if (section == 2) {
        classname = 'red-elements';
    }
    if (isInArray(val, notAllowed)) {
        $('#op' + secIds + ' option[value="' + $('#op' + secIds).val() + '"]').attr('selected', true);
        $('#valCell_' + secIds).html('');
        $('#valCell_' + secIds).html('<div class="form-group"><input class="form-control form-control form-control-sm ' + classname + '" style="width:100%;" id="val' + secIds + '" onblur="get_filter_summary(' + section + ');" onkeyup="get_filter_summary(' + section + ');" name="val' + secIds + '" type="text" onkeyup="$(this).attr(\'value\',$(this).val())" autocomplete="off"></div>');
    } else {
        var colname = $('#ccol' + secIds).val();
        getCustomFieldMeta(colname, 'ccol' + secIds, 1, 1, 'op' + secIds, secIds, section);
        $('#op' + secIds).val(val);
        $('#op' + secIds + ' option[value="' + $('#op' + secIds).val() + '"]').attr('selected', true);
    }
}

function getCol(obj, optId, secIds, section) {

    var val = obj.value;
    var selId = obj.id;
    $('#' + selId).val() != "" ? $('#' + selId + ' option[value=' + $('#' + selId).val() + ']').attr('selected', true) : '';
    if (val != "") {
        getCustomFieldMeta(val, selId, 1, 1, optId, secIds, section);
        var sqlp = document.getElementById('sqlQueryPart').value;
        var sqlP = sqlp.split('FROM');
        if (sqlP[0].indexOf('*') < 0 && section != 2 && section != 3) {
            if (sqlP[0].indexOf(val) < 0) {
                document.getElementById('sqlQueryPart').value = '';
                document.getElementById('sqlQueryPart').value = sqlP[0] + ',' + val + ' FROM ' + sqlP[1];
            }
        }

        var sql = document.getElementById('sqlQuery').value;
        var sqlPart = sql.split('FROM');

        if (sqlPart[0].indexOf('*') < 0 && section != 2 && section != 3) {
            if (sqlPart[0].indexOf(val) < 0) {
                document.getElementById('sqlQuery').value = '';
                document.getElementById('sqlQuery').value = sqlPart[0] + ',' + val + ' FROM ' + sqlPart[1];

                var fieldSummaryVal = $.trim($('#fieldSummaryVal').text());
                if (fieldSummaryVal.indexOf(val) < 0) {
                    $('#fieldSummaryVal').text(fieldSummaryVal + ',' + val);
                }
                for (var i = 0; i < 1; i++) {
                    $('#customFieldList option[value=' + val + ']').attr('selected', true);
                }

                $("#customFieldList").multiselect({
                    close: function () {
                        var txtT = [];
                        $('#customFieldList :selected').each(function (i, selected) {
                            txtT[i] = $(selected).text();
                        });

                        $('#fieldSummaryVal').text(txtT);
                        $('#fieldSummery').hide();
                        get_filter_summary(section);
                    },
                    header: true, //"Region",
                    selectedList: 1, // 0-based index
                    nonSelectedText: 'Select Fields'
                }).multiselectfilter({label: 'Search'});
            }
        }
    }
}

function getCustomFieldMeta(val, select_id, numF, divNum, optId, secIds, section) {
    $.ajax({
        type: 'get',
        url: 'getcolbycustom',
        data : {
            _token : $('[name="_token"]').val(),
            colId : select_id,
            sectiontype : section,
            colName : val,
            secIds : secIds,
            list_level : $('#list_level').val()
        },
        async: false,
        success: function (data) {
            var responseData = data.split(':::^');
            console.log(responseData);
            //$('#is_query_changed').val(0);

            var classname = '';
            if (section == 2) {
                classname = 'red-elements'
            }
            if (responseData[1] != '0') {
                $('#valCell_' + secIds).html('');
                $('#valCell_' + secIds).html(responseData[1]);

                setTimeout(function () {
                    $("#val" + secIds).multiselect({
                        close: function () {
                            if (section == 2 || section == 3)
                                get_customer_excl_incl_summary(section);
                            else
                                get_filter_summary();
                        },
                        header: true, //"Region",
                        selectedList: 1, // 0-based index
                        nonSelectedText: 'Select Values'
                    }).multiselectfilter({label: 'Search'});

                    $("#val" + secIds + "_ms").attr('style', 'width:100% !important;height: 28px; background-color: white !important;height: calc(1.5em + .5rem + 2px);padding: .25rem .5rem;border-radius: .2rem;background-clip: padding-box;border: 1px solid #e9ecef;font-size: .76563rem;');
                    if (section == 2) {
                        $("#val" + secIds + "_ms").addClass('red-elements');
                    }

                    $(".ui-multiselect-menu").attr('id', 'sBox');
                }, 200);

            } else {
                if (section == 2) {
                    var funName = 'get_customer_excl_incl_summary(' + section + ');';
                } else if (section == 3) {
                    var funName = 'get_customer_excl_incl_summary(' + section + ');';
                } else {
                    var funName = 'get_filter_summary(' + section + ');';
                }
                $('#valCell_' + secIds).html('<div class="form-group"><input class="form-control form-control form-control-sm ' + classname + '" style="width:100%;" id="val' + secIds + '" onblur="' + funName + '" onkeyup="' + funName + '" name="val' + secIds + '" type="text" autocomplete="off" onkeyup="$(this).attr(\'value\',$(this).val())"></div>');
            }

            $('#opCell_' + secIds).html('');
            $('#opCell_' + secIds).html(responseData[2]);
            $('#opCell_' + secIds).attr('rel', responseData[3]);
            $('#' + optId).attr('style', 'width:100%');
        }
    });

}

/**************** Changed 2015-11-02 Start **********************************/
function GetTextInfo(thiss, events) {
    //is_query_changed(0);
    fnKeyPressHandler_A(thiss, events);
    myElement = thiss.id;
    var e = document.getElementById(thiss.id);
    if ((document.getElementById('LSSC_' + thiss.id).value == 0) && (thiss.innerText != "")) {
        e.options[0].value = thiss.innerText;
        e.options[0].innerText = thiss.innerText;
        var nNewArr = e.options[0].innerText.split('Text');

        if (nNewArr == "Type Tex") {
            e.options[0].value = "";
            e.options[0].innerText = "Type Text";
        } else {
            e.options[0].value = nNewArr[1];
            e.options[0].innerText = nNewArr[1];
            e.options[0].style.color = 'black';
            document.getElementById('LSSC_' + thiss.id).value = 1;
        }
    } else {
        if (thiss.innerText == "") {
            document.getElementById('LSSC_' + thiss.id).value = 0;
            e.options[0].value = "";
            e.options[0].innerText = "Type Text";
            e.options[0].style.color = 'gray';
        } else {
            e.options[0].style.color = 'black';
            e.options[0].value = thiss.innerText;
            e.options[0].innerText = thiss.innerText;
            document.getElementById('LSSC_' + thiss.id).value = 1
        }
    }
}

function run_report(){

    $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px');
    $('#indicationCFMsgForDistribution').text('Summary report running. Please wait???');

    var url = 'run';
    var sSql = $('#SR_sql').val();
    ACFn.sendAjax(url,'get',{
        list_level : $('#SR_list_level').val(),
        sql : sSql,
        row_variable : $('#row_variable').val(),
        column_variable : $('#column_variable').val(),
        function_variable : $('#function_variable').val(),
        sum_variable : $('#sum_variable').val(),
        show_as : $('#show_as').val(),
        chart_variable : $('#chart_variable').val(),
        chart_axis_scale : $('#chart_axis_scale').val(),
        chart_label_value : $('#chart_label_value').val(),
    })


}

function run_report_inner(){
    if($('#row_variable_input').val() != ""){
        var url = 'run';
        var sSql = $('#sqlQuery').val();
        ACFn.sendAjax(url,'get',{
            list_level : $('#list_level').val(),
            sql : sSql,
            row_variable : $('#row_variable_input').val(),
            column_variable : $('#column_variable_input').val(),
            function_variable : $('#function_input').val(),
            sum_variable : $('#sum_variable_input').val(),
            show_as : $('#show_as_input').val(),
            chart_variable : $('#chart_variable_input').val(),
            chart_axis_scale : $('#chart_axis_scale_input').val(),
            chart_label_value : $('#chart_label_value_input').val(),
            inner_call : 1,
        })
    }
}

google.charts.load('current', {'packages': ['corechart']});
ACFn.ajax_run_profile_result = function (F,R) {

    if (R.success) {
        F.html('<i class=" fas fa-eye font-12"></i>');
        //localStorage.removeItem('pdfdata');
        var pdfdata = [];
        google.load("visualization", "1", {
            packages: ["corechart"]
        });

       // google.charts.load('current', {'packages':['line', 'corechart']});
        google.setOnLoadCallback(drawChart);

        function drawChart() {

            $.each(R.response, function (index,item) {
                var dData = [];
                dData.push([item.row_variable.replace(/_+/g, ' '), 'Universe', {role : 'style'}, 'Response Rate'])
                $.each(item.data, function (idx, im) {
                    if (im.Value != null)
                        dData.push([im.Value, parseInt(im.TotalCount),'stroke-color: #96d7fd; stroke-width: 2; fill-color: #cdeefd', parseFloat(im.ResponseRate)])
                });
                console.log('----------start-----------');
                console.log(dData);
                console.log('----------end-----------');
                var data = google.visualization.arrayToDataTable(dData);

                var ctitle = item.row_variable.replace(/[\[\]']+/g,'').replace(/_+/g, ' ');
                var htitle = '';
                //var legend = 'none';
                var legend = {
                    position: 'top',
                    alignment: 'center' ,
                    orientation: 'vertical'
                };
                if(index === 0){
                    ctitle = item.row_variable.replace(/[\[\]']+/g,'').replace(/_+/g, ' '),//item.row_variable + ' by ' + item.column_variable;
                    htitle = '';
                    //legend = 'none';
                }

                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,2,
                    3,{
                        //calc: "stringify",
                        calc: function (dt, row) {
                            return dt.getValue(row, 3) + '%';
                        },
                        sourceColumn: 3,
                        type: "string",
                        role: "annotation"
                    },]);

                var options2 = {
                    title: '',
                    titleTextStyle: {
                        //color: '333333',
                        //fontName: 'Arial',
                        bold: false,
                        fontSize: 20
                    },
                    legend: legend,
                    //curveType: 'function',
                    pointSize: 4,
                    series: {
                        0: {
                            axis: 'Universe',
                            type: "bars",
                            targetAxisIndex: 0,
                            border : 4,
                            color: "#cdeefd", //"#5e92b1",//"#75aed0"
                        },
                        1: {
                            axis: 'Response Rate',
                            type: "line",
                            pointShape: 'square',
                            targetAxisIndex: 1,
                            color: "red"
                        }
                    },
                    hAxis: {
                        title: htitle,
                    },
                    vAxes: [{
                        gridlines: {
                            //color: 'transparent'
                            color: '#e8e8e8',
                            //minSpacing: 20
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
                            TotalCount : {label: 'Universe'},
                            ResponseRate : {label: 'Response Rate'}
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
                            //bold : true,
                            color: '#212529',
                        }
                    },
                    chartArea: {
                        width: '60%',
                        height: '70%',
                        /*backgroundColor: {
                            stroke: '#E8E8E8',
                            strokeWidth: 1
                        }*/
                    }
                };

                //index = parseInt(index) + 100;
                $('.chart').append('<div id="chartPI' + index + '" class="border  mb-4 p-2 " style="display: block; width: 100%; max-width:800px; height: 530px; "></div>');

                var descobj = document.getElementById('chartPI' +index);

                var chartElemId = 'chartPI' +index;
                //var chart = new google.charts.Line(descobj);
                var chart = new google.visualization.LineChart(descobj);
                chart.draw(view, options2);

                //setTimeout(function () {
                    $('#chartPI' + index).prepend('<h4 class="card-title mt-3 mb-0" id="can-title-2" style="display: block; width: 100%; max-width:800px;text-align: center;">'+ctitle+'</h4>');

                //},200);

                //setTimeout(function () {
                    google.visualization.events.addListener(chart, 'ready', function () {
                        setTimeout(function () {
                            //$('#' + chartElemId).after('<img id="chart' + index + '" src=' + chart.getImageURI() + '>');
                            //$('#' + chartElemId).hide();

                            //item.chart_img_png = chart.getImageURI();

                            var entry = {
                                row_variable : item.row_variable,
                                chart_title : item.row_variable.replace(/[\[\]']+/g,'').replace(/_+/g, ' '),
                                chart_img_png : chart.getImageURI(),
                                chart_img : '<img style="display: block;  width: 100%; height: 435px;" src="' + chart.getImageURI() + '" class="img-responsive">',
                                chart_table : item.result
                            };
                            pdfdata.push(entry);
                            localStorage.setItem("pdfdata", JSON.stringify(pdfdata));

                            //item.chart_img = $('#chart' + index).attr('src')
                        },1000)
                        //document.getElementById("download_link").setAttribute("href", chart.getImageURI())
                    });
                //},900)
            });
            if(isInArray($('#output_format').val(),['pdf','both'])){
                $('#profile_pdf').show();
            }


            //localStorage.removeItem('profileXLSXdata');
            var profileXLSXdata = [];

            profileXLSXdata.push(R.data);
            localStorage.setItem("profileXLSXdata", JSON.stringify(profileXLSXdata));
            if(isInArray($('#output_format').val(),['xlsx','both'])){
                $('#profile_xlsx').show();
            }
            //$('.chart').hide();
        }
    }
}


function singleRenderChartData(localStoreData,rv,cv){
    var pureData =  [];
    var pureData2 =  [];
    var head = [];
    var head2 = [];
    var body = [];
    var body2 = [];
    localStoreData = JSON.parse(localStoreData);
    $.each(localStoreData, function(i, item) {
        //console.log(item);

        $.each(item.xHeader, function(i, xitem) {
            if(i == 0){
                head.push(xitem.label)
                head2.push(xitem.label)
            }
        });

        $.each(item.bHeader, function(i, bitem) {
            if(bitem.label != 'Total'){
                if(cv == "" && bitem.label == "Distribution"){
                    head.push(rv.toString())
                    head.push({ role: 'annotation'})
                    head2.push(rv.toString())
                }else{
                    head.push(bitem.label.toString())
                    head.push({ role: 'annotation'})
                    head2.push(bitem.label.toString())
                }

            }
        });
        pureData.push(head);
        pureData2.push(head2);

        $.each(item.val, function(i, vitem) {

            if(i < parseInt(item.val.length) - 1){
                var iBody = [];
                var iBody2 = [];
                var j = 1;
                var k = 1;
                $.each(vitem, function(iV, iVitem) {
                    if(iV != 'Total'){
                        if(rv == iV){
                            if(iVitem != null){
                                iVitem = iVitem.replace('%','');
                            }else{
                                iVitem = 0;
                            }
                            iBody[0] = iVitem.toString();
                            iBody2[0] = iVitem.toString();
                        }else if(iV == 0 && rv != iV){
                            if(iVitem != null){
                                iVitem = iVitem.replace('%','');
                            }else{
                                iVitem = 0;
                            }
                            iBody[0] = iVitem.toString();
                            iBody2[0] = iVitem.toString();
                        }else{
                            iBody[j] = parseInt(iVitem);
                            iBody2[k] = parseInt(iVitem);
                            if(iVitem != null){
                                iVitem = iVitem.toString();
                                iVitem = iVitem.indexOf('%') != -1 ? iVitem.replace('%','') : iVitem;
                                iVitem = Math.round(iVitem);
                            }else{
                                iVitem = 0;
                            }
                            iBody[++j] = iVitem;
                            j++;
                            k++;
                        }
                    }
                });
                pureData.push(iBody)
                pureData2.push(iBody2)
            }
        });

    });
    return {'pureData' : pureData,'withoutLV' : pureData2};
}

function chart_change(rv,cv,sv,fn,sa,ct,ctID,cs,cval,desObj){
    rv = rv.join(' | ');
    $('#indicationCFMsgForDistribution').attr('style', 'color:#5eb5d7;font-size:13px');
    $('#indicationCFMsgForDistribution').text('');
    if ($('#chart_variable_input').val() != "") {
        var type = ct;
        var ctype = $('#' + ctID).find('option:selected').text() + ' Chart';

        var xlsxData = localStorage.getItem("xlsxData");

        //google.charts.load('current', {'packages':['corechart']});
        var colVariable = cv == "" ? 'Distribution' : cv;
        var rowVariable = rv == "" ? 'Summary' : rv;

        var ctitle = cv == "" ? colVariable + ' by ' + rowVariable : rowVariable + ' by ' + colVariable;

        var xLabel = '';
        var preLabel = '';

        var colV = cv != "" ? cv : '';

        if(isInArray(fn,['sum','sc'])){
            preLabel = ucwords('Sum') + ' of ';
            var colV = sv != "" ? sv : '';
        }

        var showAsArr = [
            {Key:"number",text:"Number",tag:"Number" },
            {Key:"np",text:"Number and Percent",tag:"Number" },
            {Key:"pn",text:"Percent and Number",tag:"Percent" },
            {Key:"prt",text:"Percent of Row Total",tag:"Percent"},
            {Key:"pct",text:"Percent of Column Total",tag:"Percent"},
            {Key:"pgt",text:"Percent of Grand Total",tag:"Percent"},
            {Key:"nprt",text:"Number and Percent of Row Total",tag:"Number"},
            {Key:"prtn",text:"Percent of Row Total and Number",tag:"Percent"},
            {Key:"npct",text:"Number and Percent of Column Total",tag:"Number"},
            {Key:"pctn",text:"Percent of Column Total and Number",tag:"Percent"},
        ];

        var entry = showAsArr.find(function(e) { return e.Key === sa; });
        if (entry) {
            var smV = isInArray(fn,['count','cs']) ? '' : sv;
            if(cv == "" && smV == "" && isInArray(fn,['count','cs'])){
                xLabel = ' ' + entry.tag;
            }else{
                xLabel = ' (' + entry.tag + ')'
            }
        }


        var cLegend = cv != "" ? 'bottom' : 'none';
        var xAxis = (preLabel + colV + xLabel).replace(/_/g, ' ');
        var yAxis =  rv.replace(/_/g, ' ');
        var cType = cs;

        var cdata =singleRenderChartData(xlsxData,rv,cv);
        console.log(cdata);

        google.setOnLoadCallback(function() {
            if(cval == '0'){
                drawChart(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,desObj);
            }else if(cval == '1'){
                drawChart(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,desObj);
            }else if(cval == '2'){
                drawChart(cdata.pureData,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,desObj);
            }else if(cval == '3'){
                drawChart(cdata.pureData,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,desObj);
            }else{
                drawChart(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,'0',desObj);
            }
        });

        function drawChart(cdata,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,desObj) {
            console.log('enter---',desObj);
            var data = google.visualization.arrayToDataTable(cdata);
            var colors = ['#DC143C','#07ae07',  '#FF8C00', '#4682B4',  '#93cddd', '#FF1493', '#696969', '#FA8072', '#8A2BE2', '#8B008B', '#4B0082', '#1E90FF',  '#2F4F4F','#228B22','#FF0000','#B22222','#CD5C5C'];
            if(cval == '0'){
                xAxis = '';
                yAxis = '';
            }else if(cval == '2'){
                xAxis = '';
                yAxis = '';
            }
            if(cType == 'log'){
                var vAxis = {
                    title: xAxis,
                    italic: false,
                    scaleType: 'log',
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#848484',
                        italic: false
                    }
                };
            }else{
                var vAxis = {
                    title: xAxis, //xAxis
                    italic: false,
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#848484',
                        italic: false
                    }
                };
            }

            var options = {
                title : '',
                pieHole : 0.4,
                legend : {position: cLegend, textStyle: {fontSize: 9},scrollArrows: 'none'},
                hAxis: {
                    title: yAxis,
                    italic: false,
                    minValue: 0,
                    format: 'short',
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#4d4d4d',
                        italic: false
                    }
                },
                vAxis: vAxis,
                'is3D':true,
                'backgroundColor': 'transparent',
                colors: colors,
                annotations: {
                    alwaysOutside: true,
                    textStyle: {
                        fontName: 'Whitney HTF Light',
                        fontSize: 9,
                        color: '#b1b1b1',
                    }
                },
                chartArea:{width:'60%',height:'70%'}
            };
            var chart_area = desObj;

            if(type == 'pie'){
                var chart = new google.visualization.PieChart(chart_area);

                google.visualization.events.addListener(chart, 'ready', function(){
                    console.log('chart.getImageURI()---' , chart.getImageURI());

                    chart_area.innerHTML = '<img src="' + chart.getImageURI() + '" class="img-responsive">';
                    if($('#chartPI').length){
                        $('#chartPI').hide();
                    }
                    addChart($('#chartPI'));
                    $('#chartImage').val(chart.getImageURI());
                    if($('#chart_variable').length){
                        $('#chart_variable_input').val($('#chart_variable').val());
                        $('#chart_axis_scale_input').val($('#chart_axis_scale').val());
                        $('#chart_label_value_input').prop('checked',$('#chart_label_value').is(':checked') ? true : false);
                    }

                });

                chart.draw(data, options);
            }
            else if(type == 'line'){
                if($('#chartPI').length){
                    $('#chartPI').show();
                }
                var chart = new google.visualization.LineChart(chart_area);

                google.visualization.events.addListener(chart, 'ready', function(){


                    chart_area.innerHTML = '<img src="' + chart.getImageURI() + '" class="img-responsive">';
                    if($('#chartPI').length){
                        $('#chartPI').hide();
                    }
                    addChart($('#chartPI'));
                    $('#chartImage').val(chart.getImageURI());
                    addChart($('#chartPI'));
                    if($('#chart_variable').length){
                        $('#chart_variable_input').val($('#chart_variable').val());
                        $('#chart_axis_scale_input').val($('#chart_axis_scale').val());
                        $('#chart_label_value_input').prop('checked',$('#chart_label_value').is(':checked') ? true : false);
                    }

                });
                chart.draw(data, options);
            }
            else if(type == 'bar'){
                var chart = new google.visualization.BarChart(chart_area);
                chart.draw(data, options);
            }
            else if(type == 'combobar'){
                var chart = new google.visualization.ComboChart(chart_area);
                var options = {
                    title : '',
                    //vAxis: {title: 'Cups'},
                    //hAxis: {title: 'Month'},
                    seriesType: 'bars',
                    series: {5: {type: 'line'}},
                    'backgroundColor': 'transparent',
                    colors: colors,
                    chartArea:{width:'60%',height:'70%'}
                };
                chart.draw(data, options);
            }else if(type == 'column'){
                if($('#chartPI').length){
                    $('#chartPI').show();
                }
                var chart = new google.visualization.ColumnChart(chart_area);
                google.visualization.events.addListener(chart, 'ready', function(){


                    chart_area.innerHTML = '<img src="' + chart.getImageURI() + '" class="img-responsive">';
                    if($('#chartPI').length){
                        $('#chartPI').hide();
                    }
                    addChart($('#chartPI'));
                    $('#chartImage').val(chart.getImageURI());
                    if($('#chart_variable').length){
                        $('#chart_variable_input').val($('#chart_variable').val());
                        $('#chart_axis_scale_input').val($('#chart_axis_scale').val());
                        $('#chart_label_value_input').prop('checked',$('#chart_label_value').is(':checked') ? true : false);
                    }

                });
                chart.draw(data, options);
            }
        }

    } else {
        $('#chartP').html('');
        $('#chartImage').val('');
    }
}

function chart_change_outer(rv,cv,sv,fn,sa,ct,ctID,cs,cval,inner_call){
    rv = rv.join(' | ');
    if (ct != "") {
        var type = ct;
        //var ctype = $('#' + ctID).find('option:selected').text() + ' Chart';
        var ctype = ' Chart';

        var xlsxData = localStorage.getItem("xlsxData");

        google.charts.load('current', {'packages':['corechart']});
        var colVariable = cv == "" ? 'Distribution' : cv;
        var rowVariable = rv == "" ? 'Summary' : rv;

        var ctitle = cv == "" ? colVariable + ' by ' + rowVariable : rowVariable + ' by ' + colVariable;

        var xLabel = '';
        var preLabel = '';

        var colV = cv != "" ? cv : '';

        if(isInArray(fn,['sum','sc'])){
            preLabel = ucwords('Sum') + ' of ';
            var colV = sv != "" ? sv : '';
        }

        var showAsArr = [
            {Key:"number",text:"Number",tag:"Number" },
            {Key:"np",text:"Number and Percent",tag:"Number" },
            {Key:"pn",text:"Percent and Number",tag:"Percent" },
            {Key:"prt",text:"Percent of Row Total",tag:"Percent"},
            {Key:"pct",text:"Percent of Column Total",tag:"Percent"},
            {Key:"pgt",text:"Percent of Grand Total",tag:"Percent"},
            {Key:"nprt",text:"Number and Percent of Row Total",tag:"Number"},
            {Key:"prtn",text:"Percent of Row Total and Number",tag:"Percent"},
            {Key:"npct",text:"Number and Percent of Column Total",tag:"Number"},
            {Key:"pctn",text:"Percent of Column Total and Number",tag:"Percent"},
        ];

        var entry = showAsArr.find(function(e) { return e.Key === sa; });
        if (entry) {
            var smV = isInArray(fn,['count','cs']) ? '' : sv;
            if(cv == "" && smV == "" && isInArray(fn,['count','cs'])){
                xLabel = ' ' + entry.tag;
            }else{
                xLabel = ' (' + entry.tag + ')'
            }
        }

        var cLegend = cv != "" ? 'bottom' : 'none';
        var xAxis = (preLabel + colV + xLabel).replace(/_/g, ' ');
        var yAxis =  rv.replace(/_/g, ' ');
        var cType = cs;

        var cdata =singleRenderChartData(xlsxData,rv,cv);
        $('#chartPO').show();
        var imgurl;
        google.setOnLoadCallback(function() {

            if(cval == '0'){
                drawChartOuter(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,inner_call);
            }else if(cval == '1'){
                drawChartOuter(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,inner_call);
            }else if(cval == '2'){
                drawChartOuter(cdata.pureData,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,inner_call);
            }else if(cval == '3'){
                drawChartOuter(cdata.pureData,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,inner_call);
            }else{
                drawChartOuter(cdata.withoutLV,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,'0',inner_call);
            }
        });

        function drawChartOuter(cdata,type,ctype,ctitle,xAxis,yAxis,cType,cLegend,cval,inner_call) {
            var data = google.visualization.arrayToDataTable(cdata);
            var colors = ['#DC143C','#07ae07',  '#FF8C00', '#4682B4',  '#93cddd', '#FF1493', '#696969', '#FA8072', '#8A2BE2', '#8B008B', '#4B0082', '#1E90FF',  '#2F4F4F','#228B22','#FF0000','#B22222','#CD5C5C'];
            if(cval == '0'){
                xAxis = '';
                yAxis = '';
            }else if(cval == '2'){
                xAxis = '';
                yAxis = '';
            }
            if(cType == 'log'){
                var vAxis = {
                    title: xAxis,
                    italic: false,
                    scaleType: 'log',
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#848484',
                        italic: false
                    }
                };
            }else{
                var vAxis = {
                    title: xAxis,
                    italic: false,
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#848484',
                        italic: false
                    }
                };
            }

            var options = {
                title : '',
                pieHole : 0.4,
                legend : {position: cLegend, textStyle: {fontSize: 9},scrollArrows: 'none'},
                hAxis: {
                    title: yAxis,
                    italic: false,
                    minValue: 0,
                    format: 'short',
                    textStyle : {
                        fontSize: 10,
                        color: '#4d4d4d',
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 12,
                        color: '#4d4d4d',
                        italic: false
                    }
                },
                vAxis: vAxis,
                'is3D':true,
                'backgroundColor': 'transparent',
                colors: colors,
                annotations: {
                    alwaysOutside: true,
                    textStyle: {
                        fontName: 'Whitney HTF Light',
                        fontSize: 9,
                        color: '#b1b1b1',
                    }
                },
                chartArea:{width:'60%',height:'70%'}
            };

            var chart_area = document.getElementById('chartPO');
            var chart_img_url = '';
            if(type == 'pie'){
                var chart = new google.visualization.PieChart(chart_area);
                chart.draw(data, options);
            }
            else if(type == 'line'){
                var chart = new google.visualization.LineChart(chart_area);
                google.visualization.events.addListener(chart, 'ready', function(){
                    chart_area.innerHTML = '<img style="display: none;" src="' + chart.getImageURI() + '" class="img-responsive">';
                    addChart($('#chartPO'),inner_call);
                });
                chart.draw(data, options);
            }
            else if(type == 'bar'){
                var chart = new google.visualization.BarChart(chart_area);
                chart.draw(data, options);
            }
            else if(type == 'combobar'){
                var chart = new google.visualization.ComboChart(chart_area);
                var options = {
                    title : '',
                    //vAxis: {title: 'Cups'},
                    //hAxis: {title: 'Month'},
                    seriesType: 'bars',
                    series: {5: {type: 'line'}},
                    'backgroundColor': 'transparent',
                    colors: colors,
                    chartArea:{width:'60%',height:'70%'}
                };
                chart.draw(data, options);
            }else if(type == 'column'){

                var chart = new google.visualization.ColumnChart(chart_area);
                google.visualization.events.addListener(chart, 'ready', function(){
                    chart_area.innerHTML = '<img style="display: none;" src="' + chart.getImageURI() + '" class="img-responsive">';
                    addChart($('#chartPO'),inner_call);
                });
                chart.draw(data, options);
            }
        }

    }
}

function addChart(obj,inner_call = '') {
    if (typeof localStorage !== 'undefined') {
        if(obj.find('img').length > 0){
            if (localStorage.getItem("params") === null) {
                var params = {
                    cI : obj.find('img').attr('src')
                };
            }else{
                var params = JSON.parse(localStorage.getItem('params'));
                params.cI = obj.find('img').attr('src');
            }

            localStorage.setItem('params',JSON.stringify(params));
            if(inner_call == 3){
                var postdata = {
                    pgaction : 'ReSch_campaign',
                    CID : $.trim(params.camp_id),
                    CName : params.list_short_name,
                    // metaStr : metaData,
                    SMTPStr : 'N',
                    ftp_flag : 'N',
                    ftpData : '',
                    SFTP_Attachment : '',
                    SR_Attachment : 'onlyreport',
                    SREmailStr : 'N',
                    ShareStr : 'N',
                    rtype : 'RI',
                    params : JSON.stringify(params),
                    _token : $('[name="_token"]').val()
                };

                $.ajax({
                    url : 'profile/ar_sch_data',
                    type : 'POST',
                    data : postdata,
                    async : false,
                    beforeSend : function(){

                    },
                    success : function (data) {

                        localStorage.removeItem('record');
                        localStorage.removeItem('params');
                        localStorage.removeItem('contactfilters');
                        localStorage.removeItem('exclusionsfilters');
                        localStorage.removeItem('inclusionsfilters');
                    }
                })
            }
            setTimeout(function () {
                obj.html('').hide();
            },1500);
        }
    }
}

function ChangeColumn(obj) {
    console.log('change');
    if (obj.val() == "") {
        // $('#show_as').find('option').not(':first').hide();
        //$('#show_as').val('number');
        $('#chart_variable option[value=pie]').show();
        $('#chart_variable option[value=line]').hide();

        $('#show_as option').hide();
        $('#show_as option[value=np]').show();
        $('#show_as option[value=pn]').show();
        $('#show_as').val('np');

        $('#function_variable option[value=cs]').show();
        $('#function_variable option[value=sc]').show();
        $('#function_variable').val('count');
        $('#function_variable').trigger('onchange');

        if($('#show_as_input').length > 0){
            $('#show_as_input option').hide();
            $('#show_as_input option[value=np]').show();
            $('#show_as_input option[value=pn]').show();
            $('#show_as_input').val('np')
        }

        if($('#function_input').length > 0){
            $('#function_input option[value=cs]').show();
            $('#function_input option[value=sc]').show();
            $('#function_input').val('count');
            if($('#function_variable').length == 0){
                $('#function_input').trigger('onchange');
            }
        }
    } else {
        $('#show_as option').show();
        $('#show_as option[value=np]').hide();
        $('#show_as option[value=pn]').hide();
        $('#show_as').val('number')

        if($('#function_variable').length > 0) {
            $('#function_variable option[value=cs]').hide();
            $('#function_variable option[value=sc]').hide();
            $('#function_variable').val('count');
            $('#function_variable').trigger('change');
        }

        if($('#show_as_input').length > 0){
            $('#show_as_input option').show();
            $('#show_as_input option[value=np]').hide();
            $('#show_as_input option[value=pn]').hide();
            $('#show_as_input').val('number')
        }
        if($('#function_input').length > 0){
            $('#function_input option[value=cs]').hide();
            $('#function_input option[value=sc]').hide();
            $('#function_input').val('count');
            if($('#function_variable').length == 0) {
                $('#function_input').trigger('change');
            }
        }



        if ($('#chart_variable').val() == 'pie') {
            $('#chart_variable').val('');
            return false;
        }
        $('#chart_variable option[value=pie]').hide();
        $('#chart_variable option[value=line]').show();

    }
}

function stringSanitize(sTr,rmWords, sbStrLen = 0){
    if(sTr.length > sbStrLen){
        var sTrArr =  sTr.split(' ');
        $.each(rmWords , function(f,r) {
            $.each(sTrArr , function(i, v) {
                if(v.indexOf(f) != -1){
                    sTrArr[i] = v.replace(f,r)
                }
            });
        });
        if(sbStrLen > 0){
            var newStr = sTrArr.join('_');
            return newStr.substring(0,sbStrLen);

        }
        return sTrArr.join('_');
    }else{
        var sTrArr =  sTr.split(' ');
        return sTrArr.join('_');
    }

}

function d_pdf(is_download) {

    if (is_download == 1) {
        var filename = ($('#listShortName').length > 0) ? $('#listShortName').val() : 'test';

        ACFn.sendAjax('profile/generatePDF','POST', {
            pdfdata : localStorage.getItem("pdfdata"),
            rpheader : $('#profile_description').val(),
            rpfooter : filename,
            filename : filename,
            papersize : 'portrait'
        });
        /*$.each(pdfdata, function (key, val) {
            chartImg += val.chart_table + '<br/>' + val.chart_img + '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
        });*/

        /*ACFn.sendAjax('HTMLtoPDF','POST',{
            tablehtml : '',
            charthtml : chartImg,
            rpheader : $('#profile_description').val(),
            rpfooter : filename,
            filename : filename+ '.pdf',
            //cont_filters: srData.filter_condition,
            //incl_filters: srData.Customer_Inclusion_Condition,
            //excl_filters : srData.Customer_Exclusion_Condition,
            papersize : 'portrait',
            _token : $('[name="_token"]').val()
        });*/
    }
    else if(is_download == 2){
        var filename = ($('#listShortName').length > 0) ? $('#listShortName').val() : 'test';
        ACFn.sendAjax('profile/generateXLSX','POST',{
            filename : filename,
            profileXLSXdata : localStorage.getItem("profileXLSXdata"),
            _token : $('[name="_token"]').val()
        });
    }
}

ACFn.ajax_download_sr_file = function (F,R) {
    var dDownloadLink = $.trim(R.link);
    if (dDownloadLink.indexOf('http') > -1) {
        //window.location.href = $.trim(o.responseText);
        var blob = R.link;
        var link = document.createElement('a');
        link.href = blob;
        link.download = R.filename;
        // append the link to the document body
        document.body.appendChild(link);
        link.click();
        $('#modal-popup').modal('hide');
    } else {
        ACFn.display_message('There are some issues with this query. Please try another query.','success');
    }
}
/**************** Changed 2015-11-02 End **********************************/



function ExeMutlipleRep() {
    $('.emreport').html('<span class="spinner-border spinner-border-sm ds-c" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>');
    $('.emreport').attr('disabled',true);
    NProgress.start();

        $('input:checkbox.em_report:checked').each(function (index,elem) {
            setTimeout(function () {
            var dataouter = JSON.parse($(elem).val());
            dataouter.sql = atob(dataouter.sql)
            localStorage.removeItem('record');
            var sSQL = dataouter.sql;
           /* var metaData = dataouter.meta_data;
            var metaStr = metaData.split('^');*/
            var currentTime = new Date();
            // returns the month (from 0 to 11)
            var r9 = currentTime.getMonth() + 1;
            // returns the day of the month (from 1 to 31)
            var r10 = currentTime.getDate();
            // returns the year (four digits)
            var r8 = currentTime.getFullYear();
            var Category = dataouter.rpmeta.Category.replace("~", " ");
            var params = {
                camp_id : dataouter.t_id,
                CampaignID :  dataouter.t_id,
                Type : 'A',
                Objective : dataouter.rpmeta.Objective,
                Brand : dataouter.rpmeta.Brand,
                Channel : dataouter.rpmeta.Channel,
                Category : Category,
                ListDes : dataouter.rpmeta.ListDes,
                Wave : dataouter.rpmeta.Wave,
                Start_Date : r8 + "/" + r9 + "/" + r10,
                Interval : dataouter.rpmeta.Interval,
                ProductCat1 : dataouter.rpmeta.ProductCat1,
                ProductCat2 : dataouter.rpmeta.ProductCat2,
                SKU : dataouter.rpmeta.SKU,
                Coupon : dataouter.rpmeta.Coupon,
                Sort_Column : 'DS_MKC_ContactID',
                Sort_Order : 'ASC',
                Camp_Name : dataouter.t_name,
                list_short_name : dataouter.list_short_name,
                meta_description : Category,
                sSQL : dataouter.sql,
                list_level : dataouter.list_level,
                selected_fields : dataouter.selected_fields,
                schedule_action : 'ReSch_campaign'
            };
            localStorage.setItem('params',JSON.stringify(params));
            if (dataouter.Report_Row != "") {
                run_report_outer(dataouter,3);
            }

            //setTimeout(function () {
                /*var postdata = {
                    pgaction : 'ReSch_campaign',
                    CID : $.trim(dataouter.t_id),
                    CName : dataouter.list_short_name,
                   // metaStr : metaData,
                    SMTPStr : 'N',
                    ftp_flag : 'N',
                    ftpData : '',
                    SFTP_Attachment : '',
                    SR_Attachment : 'onlyreport',
                    SREmailStr : 'N',
                    ShareStr : 'N',
                    rtype : 'RI',
                    params : JSON.stringify(params),
                    _token : $('[name="_token"]').val()
                };

                $.ajax({
                    url : 'profile/ar_sch_dataaa',
                    type : 'POST',
                    data : postdata,
                    async : false,
                    beforeSend : function(){

                    },
                    success : function (data) {

                        localStorage.removeItem('record');
                        localStorage.removeItem('params');
                        localStorage.removeItem('contactfilters');
                        localStorage.removeItem('exclusionsfilters');
                        localStorage.removeItem('inclusionsfilters');
                    }
                })*/
            //},5000)
            },4000)
        });

    setTimeout(function () {
        $(".em_report").prop('checked',false);
        NProgress.done(true);
        $('.emreport').html('<span class="fas fa-forward ds-c" role="status" aria-hidden="true"></span>');
        $('.emreport').attr('disabled',false);

        //$('#mutlipleReports').val('');
        //$('.em_report').hide();
    },4000);
    /*if($(".em_report:checked").length == 0){
        ACFn.display_message('Please select atleast one checkbox ','','success');
        return false;
    }

    var data = {
        'title': 'Do you want to execute multiple report ?',
        'text' : '',
        'butttontext' : 'Ok',
        'cbutttonflag' : true
    };

    var title = data["title"] ? data["title"] : "Are you sure?";
    var text = data["text"] ? data["text"]
        : "You might not be able to revert this!";
    var butttontext = data["butttontext"] ? data["butttontext"] : "Yes";
    var cbutttonflag = !data["cbutttonflag"] ? data["cbutttonflag"] : true;
    if (typeof swal === 'function') {
        swal.fire({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: cbutttonflag,
            confirmButtonColor: '#3ea6d0',
            cancelButtonColor: '#00000033',
            //customClass: 'swal-wd',
            confirmButtonText: butttontext,
            allowOutsideClick: false,
            customClass: {
                popup: 'swal-wd',
                confirmButton: 'btn btn-info'
            },
            onBeforeOpen: function(ele) {
                $(ele).find('button.swal2-confirm.swal2-styled')
                    .toggleClass('swal2-styled')
            }
        }).then(function (result) {
            if (result.value){


                setTimeout(function () {
                    //$(".em_report").prop('checked',false);
                    //$('#mutlipleReports').val('');
                    //$('.em_report').hide();
                },2000);
            }
        });
    }*/
}

function mPdfChecked(obj,type){
    if($(".po_status:checked").length > 0)
        $('.dmpdf').show();
     else
        $('.dmpdf').hide();

    if($(".po_status:checked").length == 11){
        ACFn.display_message('Maximum 10 pdf can export at the same time','','success');
        obj.prop('checked',false)
        return false;
    }

    var mPdfIDs = $.trim($('#mutliplePDF_IDs').val());
    var rowID = $.trim(obj.val());


    if (obj.is(':checked')) {
        if (mPdfIDs == "") {
            $('#mutliplePDF_IDs').val(rowID + '_' + type);
        } else {
            var n = mPdfIDs.indexOf(rowID);
            if (n == -1) {
                $('#mutliplePDF_IDs').val(mPdfIDs + "," + rowID + '_' + type);
            }
        }
    } else {
        var n = mPdfIDs.indexOf(rowID + '_' + type);
        if (n > 0) { // If column exist on after first position
            var res = mPdfIDs.replace("," + rowID  + '_' + type, "");
        } else if (n == 0) { // If column exist on first position
            var mn = mPdfIDs.indexOf(",");
            if (mn >= 0) {   //if not single column exist in field summary
                var res = mPdfIDs.replace(rowID + '_' + type + ",", "");
            } else {        //if only single column exist in field summary
                var res = mPdfIDs.replace(rowID + '_' + type, "");
            }
        }
        $('#mutliplePDF_IDs').val($.trim(res));
    }
}

function downloadMutliplePDF() {
    if($(".po_status:checked").length == 0){
        ACFn.display_message('Please select atleast one checkbox ','','success');
        return false;
    }

    var data = {
        'title': 'Do you want to add more pdf files ?',
        'text' : '',
        'butttontext' : 'Yes',
        'cbutttonflag' : true
    };

    var title = data["title"] ? data["title"] : "Are you sure?";
    var text = data["text"] ? data["text"]
        : "You might not be able to revert this!";
    var butttontext = data["butttontext"] ? data["butttontext"] : "Yes";
    var cbutttonflag = !data["cbutttonflag"] ? data["cbutttonflag"] : true;
    if (typeof swal === 'function') {
        swal.fire({
            title: title,
            text: text,
            type: 'warning',
            showCancelButton: cbutttonflag,
            confirmButtonColor: '#3ea6d0',
            cancelButtonColor: '#00000033',
            //customClass: 'swal-wd',
            confirmButtonText: butttontext,
            cancelButtonText: "No",
            allowOutsideClick: false,
            customClass: {
                popup: 'swal-wd',
                confirmButton: 'btn btn-info'
            },
            onBeforeOpen: function(ele) {
                $(ele).find('button.swal2-confirm.swal2-styled')
                    .toggleClass('swal2-styled')
            }
        }).then(function (result) {
            var url = 'downloadmultiplepdf';
            if (result.value) url = 'showpdfupload';
            sendMergeReq(url);

        });
    } else if (confirm(title + '\n' + text)) {
        sendMergeReq('showpdfupload');
    }else{
        sendMergeReq('downloadmultiplepdf');
    }
}




