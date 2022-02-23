@extends('layouts.docker-horizontal')
@section('content')
    <style>
        /*.text-box1 {
            padding-left: 3px;
            width: 162px;
            height: 21px;
            font-size: 12px;
        }*/

        .title-label {
            font-size: 12px;
            display: inline-block;
            padding-top: 4px;
        }

        .pull-right {
            float: right;
        }

        .lock {
            color: #6eb6d0;
        }

        .control-height {
            height: 25px !important;
            width: 125px !important;
        }

        .btn-active {
            background: #dbeef4 !important;
            border-color: #31859C !important;

        }

        .btn-active button {
            color: #31859C !important;
        }

        .yui-push-button {
            border-width: 1px 1px !important;
        }

        .yui-skin-sam .yui-button .first-child {
            border-width: 0px 0px;
        }

        .navbar h3 {
            color: #f5f5f5;
            margin-top: 14px;
        }

        .hljs-pre {
            background: #f8f8f8;
            padding: 3px;
        }

        .footer {
            border-top: 1px solid #eee;
            margin-top: 40px;
            padding: 40px 0;
        }

        .input-group {
            width: 110px;
            margin-bottom: 10px;
        }

        .pull-center {
            margin-left: auto;
            margin-right: auto;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 730px;
            }
        }

        @media (max-width: 767px) {
            .pull-center {
                float: right;
            }
        }
    </style>
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body pt-2">

                    <div class="bd">

                        <div class="filter-open-close">
                            <div class="filter collapse" id="collapseFilters" aria-expanded="false">
                                <form id="filter_form" class="filter-scroll-js filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">
                                    <div class="form-body">
                                        <div class="card-body pt-0">
                                            <div class="form-actions pull-right d-none" >
                                                <input type="hidden" name="searchterm" class="form-control form-control-sm" placeholder="" data-placeholder="">
                                                <button type="submit" class="btn btn-info">Apply</button>
                                                <button type="button" class="btn border-secondary waves-effect waves-light btn-outline-secondary " onclick="clearFilters();" style="border-color: #dee2e6;">Clear</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="after-filter"></div>
                            </div>
                        </div>

                        <div class="row" style="border-bottom: 1px solid #dee2e6;">

                            <div class="col-md-7">
                                <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax" role="tablist" data-href="email/get" data-method="get" data-default-tab="tab_20">
                                    {{--<li class="nav-item new-email" style="display:none; border-bottom: 1px solid #dee2e6;">
                                        <a class="nav-link" data-toggle="tab" data-tabid="21" href="#tab_21" role="tab" aria-selected="true">
                                            <span class="hidden-sm-up"></span>
                                            <span class="hidden-xs-down">New</span>
                                        </a>
                                    </li>--}}

                                    <li class="nav-item new-email">
                                        <a class="nav-link active"
                                           data-toggle="tab"
                                           data-tabid="ECinsert"
                                           href="#Setup"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">Setup</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="Proofs"
                                           href="#Proofs"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">Proofs</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="deploy"
                                           href="#Deploy"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">Deploy</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="TFD"
                                           href="#DeployAfterTest"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">DeployAfterTest</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="ReDeploy"
                                           href="#ReDeploy"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">ReDeploy</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="Re-ReDeploy"
                                           href="#Re-ReDeploy"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">Re-ReDeploy</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="PR"
                                           href="#ProcessReport"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">ProcessReport</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link"
                                           data-toggle="tab"
                                           data-tabid="CR"
                                           href="#CampaignReport"
                                           role="tab">
                                            <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                            <span class="hidden-xs-down">Campaign Report</span>
                                        </a>
                                    </li>

                                    <li class="nav-item email-list" style="border-bottom: 1px solid #dee2e6;">
                                        <a class="nav-link" data-toggle="tab" data-tabid="20" href="#tab_20" role="tab" aria-selected="true">
                                            <span class="hidden-sm-up"></span>
                                            <span class="hidden-xs-down">Listing</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>

                            <div class="col-md-5">
                                <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="input-group mb-1">
                                        <div class="all-pagination" style="vertical-align: middle;margin: 10px;"></div>

                                        <input type="text" id="filtersearch" class="form-control ajax-search search-btn" placeholder="Search" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                        <div class="input-group-append search-btn">
                                            <div class="input-group-text border-right-0 border-left-0" title="Search" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');">
                                                <i class="fas fa-search ds-c"></i>
                                            </div>
                                        </div>

                                        <div class="c-btn" style="display: none;"></div>
                                        <button type="button"
                                                style="display: none;"
                                                class="btn btn-light border-right-0 font-16 s-f cl-report-btn ds-c3"
                                                title="Download 10K"
                                                onclick="downloadFile50k('xlsx');">
                                            <i class="fas fa-download"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 s-f border-left-0 border-right-0 ds-c3"
                                                title="Count_Toprocess"
                                                id="count_toprocess_btn">
                                            <i class="fas fa-calculator ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 s-f border-right-0 ds-c3"
                                                title="Today's Campaigns"
                                                id="today_campaign_btn">
                                            <i class="fas fa-clipboard ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16  border-right-0 s-f ds-c3"
                                                title="DB Running Queries"
                                                id="db_queries_btn">
                                            <i class="fas fa-database ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 border-right-0 s-f ds-c3"
                                                title="Shrink"
                                                id="shrink_btn">
                                            <i class="fas fa-compress ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 border-right-0 s-f ds-c3"
                                                title="Temp File Size"
                                                id="filesize_btn">
                                            <i class="fas fa-file ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 border-right-0 s-f ds-c3"
                                                title="Delete ToProcess"
                                                id="Delete_toprocess_btn">
                                            <i class="fas fa-trash ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 border-right-0 s-f ds-c3"
                                                title="Check_Dupes"
                                                id="btncheckcnt">
                                            <i class="fas fa-cubes ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 s-f ds-c3"
                                                id="refreshbtn"
                                                title="Refresh">
                                            <i class="fas fa-sync ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light font-16 s-f ds-c3 close-email-btn"
                                                style="display: none;"
                                                title="Close"
                                                onclick="addNewEmail('close');">
                                            <i class="fas fa-times-circle ds-c"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-light border-left-0 font-16 s-f ds-c3 open-email-btn"
                                                title="New Email"
                                                onclick="addNewEmail('open');">
                                            <i class="fas fa-plus ds-c"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12" id="responseHtml" style="padding-left: 0px; display:none;">
                                    {{--<div class="tab">
                                        <button class="tablinks active" id="resulttab" onclick="changeTab(event, 'Result')">
                                            Results
                                        </button>
                                        <button class="tablinks" id="messagetab" onclick="changeTab(event, 'Message')">Messages
                                        </button>
                                        <button class="tablinks" id="querytab" onclick="changeTab(event, 'Queries')">SQL Query
                                        </button>
                                    </div>
                                    <div id="Result" class="tabcontent" style="display: block !important;padding-left: 0px;"></div>
                                    <div id="Message" class="tabcontent"></div>
                                    <div id="Queries" class="tabcontent"></div>--}}

                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#Resulttab" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Results</span></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Messagetab" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Messages</span></a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#Queriestab" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">SQL Query</span></a>
                                        </li>
                                    </ul>

                                    <div class="tab-content tabcontent-border">
                                        <div class="tab-pane active" id="Resulttab" role="tabpanel">
                                            <div class="overflow-auto" id="Result"></div>
                                        </div>
                                        <div class="tab-pane" id="Messagetab" role="tabpanel">
                                            <div class="overflow-auto" id="Message"></div>
                                        </div>
                                        <div class="tab-pane" id="Queriestab" role="tabpanel">
                                            <div class="overflow-auto" id="Queries"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content br-n pn">
                            <div class="tab-pane customtab active" id="tab_20" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="Setup" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="Proofs" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="Deploy" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="DeployAfterTest" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="ReDeploy" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="Re-ReDeploy" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="ProcessReport" role="tabpanel"></div>
                            <div class="tab-pane customtab" id="CampaignReport" role="tabpanel"></div>
                        </div>

                        <!--
                        <div class="form-body pt-2">

                            <form name='frmedit' style="display:none;">
                                <input type='hidden' id='dt_customerid' value=''>
                                <table>
                                    <tr>
                                        <td>Phone:</td>
                                        <td style='width:200px;'>Email</td>
                                        <td>Last Name</td>
                                        <td>First Name:</td>
                                        <td>Street:</td>
                                        <td>Zip Code:</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_phone"><input id="dt_phone" type="text" value="">
                                                <div id="dt_ac_container"></div>
                                            </div>
                                        </td>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_email"><input id="dt_email" type="text" value="">
                                                <div id="dt_ac_container"></div>
                                            </div>
                                        </td>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_lname"><input id="dt_lname" type="text" value="">
                                                <div id="dt_ac_container"></div>
                                            </div>
                                        </td>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_fname"><input id="dt_fname" type="text" value="">
                                                <div id="dt_ac_container"></div>
                                            </div>
                                        </td>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_city"><input id="dt_city" type="text" value="">
                                                <div id="dt_ac_container"></div>
                                            </div>
                                        </td>
                                        <td style='padding-right:10px;'>
                                            <div id="autocomplete_zip"><input id="dt_input_zip" type="text" value="">
                                                <div id="dt_ac_zip_container"></div>
                                            </div>
                                        </td>

                                    </tr>
                                </table>
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12 pl-0" id="responseHtml" style="display: none;">
                                            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14" role="tablist">

                                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                                    <a class="nav-link" data-toggle="tab" id="resulttab" href="#Result" role="tab" aria-selected="false">
                                                        <span class="hidden-sm-up"></span>
                                                        <span class="hidden-xs-down">Result</span>
                                                    </a>
                                                </li>

                                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                                    <a class="nav-link" data-toggle="tab" id="messagetab" href="#Message" role="tab" aria-selected="false">
                                                        <span class="hidden-sm-up"></span>
                                                        <span class="hidden-xs-down">Message</span>
                                                    </a>
                                                </li>

                                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                                    <a class="nav-link" data-toggle="tab" id="querytab" href="#Queries" role="tab" aria-selected="true">
                                                        <span class="hidden-sm-up"></span>
                                                        <span class="hidden-xs-down">SQL Query</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content br-n pn">
                                                <div class="tab-pane customtab active" id="Result" role="tabpanel"></div>
                                                <div class="tab-pane customtab" id="Message" role="tabpanel"></div>
                                                <div class="tab-pane customtab" id="Queries" role="tabpanel"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> -->
                    </div>
                </div>
            </div>
        </div>



        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
    @include('layouts.docker-rightsidebar')
    <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>

    <style>
        /* Style the tab */
        .tab {
            height: 26px;
            /*width: 228px;*/
            overflow: hidden;
            border: 1px solid #6eb6d0;
            background-color: #6eb6d0;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: #6eb6d0;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 2px 9px;
            transition: 0.3s;
            font-size: 14px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #dbeef4;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #dbeef4;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #dbeef4;
            border-top: none;
            overflow: scroll;
            height: 500px;
        }

        a.edit-field {
            color: #212529;
        }
        a.edit-field:hover {
            color: #212529;
        }

        .popover {
            z-index: 99999 !important;
        }

        .cursor-pointer{
            cursor: pointer;
        }

    </style>
    <script src="elite/js/custom.js?ver={{time()}}" type="text/javascript"></script>
    <script>

       /* function init() {
            var handleYes = function () {
                this.hide();
            };

            YAHOO.listpull.container.checkcnt = new YAHOO.widget.SimpleDialog("divcheckcnt",
                {
                    width: "400px",
                    fixedcenter: true,
                    visible: false,
                    draggable: true,
                    modal: false,
                    iframe: true,
                    close: true,
                    constraintoviewport: true,
                    buttons: [{text: "Close", handler: handleYes}]
                });

            YAHOO.listpull.container.checkcnt.render();
            YAHOO.util.Event.addListener("btncheckcnt", "click", YAHOO.listpull.container.checkcnt.show, YAHOO.listpull.container.checkcnt, true);
        }

        YAHOO.namespace("listpull.container");
        YAHOO.util.Event.addListener(window, "load", init);*/

       function addNewEmail(action) {
           if(action == 'open'){
               $('.new-email').show();
               $('.new-email a').trigger('click');
               $('.close-email-btn').show();
               $('.open-email-btn').hide();
               //$('#filtersearch').hide()
               //$('.search-btn').hide()
               //$('.c-btn').hide()
               //$('.new-email a').show().addClass('active');
           }else{
               //$('.new-email').hide();
               $('.close-email-btn').hide();
               $('.open-email-btn').show();
               $('.email-list a').trigger('click');
               //$('#filtersearch').show()
               //$('.search-btn').show()
           }
       }

        function changeTab(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

       function blankMergeData() {}

       function editfield(obj) {
           var field_name = obj.data('field-name');
           var campaignid = obj.data('campaignid');
           ACFn.sendAjax('email/showeditpopup', 'get', { campaignid : campaignid, field_name : field_name });
       }

        $('#email_configBtn').on('click', function () {
            $('.email-config').toggle();
        })

        $(document).ready(function () {
            $('[href="#tab_20"]').trigger('click');
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



            $('#filesize_btn').on('click', function () {
                ACFn.sendAjax('email/getfilesize', 'get', {});
            })

            $('#shrink_btn').on('click', function () {
                ACFn.sendAjax('email/getshrink', 'get', {});
            })

            $('#count_toprocess_btn').on('click', function () {
                ACFn.sendAjax('email/count_toprocess', 'get', {});
            })

            $('#today_campaign_btn').on('click', function () {
                ACFn.sendAjax('email/today_campaigns', 'get', {});
            })

            $('#Delete_toprocess_btn').on('click', function () {
                var data = {
                    'title': 'Are you sure ?',
                    'text' : '',
                    'butttontext' : 'Ok',
                    'cbutttonflag' : true
                };
                ACFn.display_confirm_message(data,deleteToProcess);
            });

            $('#btncheckcnt').on('click', function () {
                ACFn.sendAjax('email/count_dupes_popup', 'get', {});
            });

            /*$('body').find('#count_dupes_btn').on('click', function () { alert('enter')
                ACFn.sendAjax('email/count_dupes', 'get', {campagin_id: $('#campids').val()});
            });*/

            ACFn.ajax_count_toprocess_result = function (F, R) {
               // $('.loading-div').text('');
                if (R.success == true) {
                    $('#responseHtml').show();
                    $('#divdatatable').hide();
                    $('#Message').html(R.html);

                    if (R.resultHtml && R.resultHtml != "") {
                        $('#Result').html(R.resultHtml);
                    } else {
                        /*$('.tab').css('width', '165px');*/
                        $('#resulttab').removeClass('active');
                        $('#messagetab').addClass('active');
                        $('#querytab').removeClass('active');
                        $('#resulttab').hide();
                        $('#Result').hide();
                        $('#Queries').hide();
                        $('#Message').show();
                    }
                    $('#Queries').html(R.Sqls);

                } else {
                    ACFn.display_message(
                        "Error!",
                        data.message,
                        "error");
                }
            }

            $('#db_queries_btn').on('click', function () {
                ACFn.sendAjax('email/db_queries', 'get', {});
            })

            ACFn.ajax_db_queries_result = function (F, R) {
                if (R.success == true) {
                    if (R.response.flag && R.response.flag == 'Delete_toprocess') {

                        ACFn.display_message(
                            R.response.message,
                            R.response.message,
                            "success");
                        return false;
                    }

                    if (R.response.flag && R.response.flag == 'count_dupes') {
                        $('#dupes_count_field').val(R.response.count)
                        $('#dupes_count_distinct_field').val(R.response.count_distinct)
                        return false;
                    }

                    $('#responseHtml').show();
                    $('#Message').html(R.response.html);
                    if (R.response.resultHtml && R.response.resultHtml != "") {
                        $('#Result').html(R.response.resultHtml);
                    }
                    $('#Queries').html(R.response.Sqls);
                } else {
                    ACFn.display_message(
                        "Error!",
                        R.response.message,
                        "error");
                }
            }

            $('#input_html_id').on('change', function () {
                $('#responseHtml').hide();
                //if($('.header-btn span[class!="btn-active"] span button').length > 0){ $('.header-btn span[class!="btn-active"] span button').trigger('click'); }
                $('.header-btn').children().each(function (i, obj) {
                    if (!$(obj).hasClass('btn-active')) {
                        $(obj).children().children().trigger('click')
                    }
                });
                if (!$('#ScheduleBtn').hasClass('btn-active')) {
                    if ($(this).val() != "") {
                        ACFn.sendAjax('email_data.php?pgaction=getCampagin', 'get', {campagin_id: $(this).val()});
                        ACFn.ajax_get_camp = function (F, R) {
                            if (R.StartDate) {
                                $('#StartDate_input').prop('value', R.StartDate);
                            }

                            if (R.Time1) {
                                $('#Time1_input').val(R.Time1);
                            }
                        }
                    }
                }
            });

            ACFn.ajax_modify_camp = function (F, R) {
                $('#modal-popup').modal('hide');
                $('.tab-ajax li a.active').trigger('show.bs.tab');
            }

            $("#StartDate_input").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            $('.clockpicker').clockpicker({
                placement: 'bottom',
                align: 'left',
                donetext: 'Done'
            });

            $('#refreshbtn').on('click', function () {
                $('#responseHtml').hide();
                $('[href="#Setup"]').trigger('click');
            });
        });

        function decideSection(activeClass, ingnoreList, obj) {

            //$('.loading-div').text('');
            if (ingnoreList.length > 0) {
                $.each(ingnoreList, function (key, val) {
                    $('.' + val).hide();
                });
            }
            $('.' + activeClass).show();
            $('.other').hide();
            $('.is_j').hide();
            $('#responseHtml').hide();
            if (activeClass == 'ECinsert' || activeClass == 'Schedule') {
                $('#divdatatable').show();
            } else {
                $('#divdatatable').hide();
            }
            if (activeClass == 'Proofs') {
                $('.listid').show()
                $('.listid').css('visibility', 'visible');
            } else {
                $('.listid').hide()
                $('.listid').css('visibility', 'hidden');
            }

            if(activeClass == 'deploy'){
                $('#deploy_campaign_auto_deploy button').removeAttr('disabled');
                $('#deploy_campaign_deploy button').removeAttr('disabled');
            }

            $('.header-btn .yui-push-button').addClass('btn-active');
            obj.parent().parent().removeClass('btn-active')
        }

        function addEmailReport(btnPos, currentBtn, nextBtn, BtnText, prevBtn,is_alert = 0) {

            /******** Disable buttons if it's deployed once, Enable only when deploy review button press - Start ******/
            if(btnPos == 5){
                $('#deploy_campaign_auto_deploy button').attr('disabled',true);
            }else if(btnPos == 5.1){
                $('#deploy_campaign_deploy button').attr('disabled',true);
            }
            if(btnPos > 4 && (btnPos < 10 || btnPos == 12 || btnPos == 13 || btnPos == 14)) {
                if (typeof (Storage) !== "undefined") {

                    var pressedButtons = JSON.parse(localStorage.getItem("pressedButtons"));
                    if (pressedButtons == null) pressedButtons = [];

                    if ($.inArray(btnPos, pressedButtons) == -1) {

                        if(is_alert == 1){
                            var x = confirm('Are you sure ?');
                            if(x == false){
                                return false;
                            }
                            pressedButtons.push(btnPos);
                            localStorage.setItem("pressedButtons", JSON.stringify(pressedButtons));
                        }
                    } else {
                        //$('.loading-div').text('Already done this process');
                        //alert('Campaign has already been submitted');
                        //return false;
                    }
                }
            }else if(btnPos == 4){

                localStorage.removeItem("pressedButtons");
            }
            /******** Disable buttons if it's deployed once, Enable only when deploy review button press - End ******/

            $('.is_j').hide();
            var is_valid = true;
            $('#' + currentBtn).addClass('btn-active');
            if (prevBtn != '') {
                $('#' + prevBtn).removeClass('btn-active');
            }

            if (btnPos != 0) {
                if ($('#input_html_id').val() == "") {
                    //$('#input_html_id').css({"border": "1px solid #fb5454"});
                    is_valid = false;
                    if (btnPos == 2) {
                        $('#' + currentBtn).val('');
                    }
                } else if (!$('#input_html_id').val().match(/^\d+$/)) {
                    //$('#input_html_id').css({"border": "1px solid #fb5454"});
                    $('#input_html_id').val('');
                    //$('#input_html_id').attr('placeholder', 'Plase enter vaild id');
                    is_valid = false;
                    if (btnPos == 2) {
                        $('#' + currentBtn).val('');
                    }
                } else {
                    //$('#input_html_id').css({"border": "1px solid #93CDDD"});
                }
            } else {
                if ($('#campaign_name').val() == "") {
                    $('#campaign_name').css({"border": "1px solid #fb5454"});
                    $('#campaign_name').val('');
                    is_valid = false;
                }
            }

            if (is_valid == false) {
                return false;
            }

            var F = $('#emailForm');
            var btnTxt = $('#' + currentBtn).find('button').text();
            var formdata = F.serialize()
            $.ajax({
                url: "email/sendemail?pgaction=emailReport_v2&btnPos=" + btnPos,
                data: formdata,
                type: "POST",
                method: "POST",
                dataType: "json",
                beforeSend: function () {
                    ACFn.clear_errors(F);
                    //$('.loading-div').text('Loading...');
                },
                success: function (data) {
                    console.log('enter--',data)
                    //$('.loading-div').text('');
                    if (data.success == true) {
                        var R = ACFn.json_parse(data.response);
                        if (btnPos == 0 && R.input_html_id_options) {
                            $('#input_html_id').html(R.input_html_id_options);
                            $('[href="#Setup"]').trigger('show.bs.tab');
                        }
                        if (btnPos == 2 || btnPos == 3) {
                            $('.is_j').show();
                        }
                        if (nextBtn != "") {

                            $('#' + nextBtn).show();
                            $('#' + nextBtn).find('button').attr('disabled', false);
                        }
                        if (btnPos == 4) {
                            $('.Schedulebox').show();
                            $('#deploy_campaign_auto_deploy').hide();
                        } else {
                            $('#schedule_stage_deploy').removeClass('btn-active');
                            $('.Schedulebox').hide();
                            //$('#deploy_campaign_auto_deploy').hide();
                        }

                        $('#responseHtml').show();
                        $('#Message').html(data.response.html);

                        if (data.response.resultHtml && data.response.resultHtml != "") {
                            $('#Result').html(data.response.resultHtml);

                            if (btnPos == 0) {
                                $('#responseHtml').hide();
                                $('#campaign_name').val('');
                                $('#template').val('');
                                $('#campaignID_data').val('');
                                $('#test_subject').val(0);
                                $('#subject1').val('');
                                $('#subject2').val('');
                                $('#subject3').val('');
                                $('#TestSubjectPct').val('');
                                $('#email_configBtn').trigger('click');
                            }

                        }
                        $('#Queries').html(data.response.Sqls);
                        if (btnPos == 20) {   //For wait Sp's
                            $.ajax({
                                url: "email/sendemail?pgaction=emailReport_v2&btnPos=15",
                                data: formdata,
                                type: "POST",
                                method: "POST",
                                dataType: "json",
                                beforeSend: function () {
                                    ACFn.clear_errors(F);
                                    //$('.loading-div').text('Loading...');
                                },
                                success: function (data) {
                                    $('#responseHtml').show();
                                    $('#Message').html(data.response.html);

                                    if (data.response.resultHtml && data.response.resultHtml != "") {
                                        $('#Result').html(data.response.resultHtml);
                                    }
                                    $('#Queries').html(data.response.Sqls);
                                }
                            });
                        }
                    } else {
                        ACFn.display_message(
                            data.messageTitle,
                            '',
                            "error");
                    }
                },
                error: function () {
                    ACFn.display_message(
                        "Server Error!",
                        "Try Again Later",
                        "error");
                },
                complete: function () {
                    $('#' + currentBtn).find('button').text(btnTxt);
                    $("body").removeClass(
                        "ajax-loading");
                }
            });
        }

        function UpdateEmailReport(obj) {
            var is_valid = true;
            if ($('#input_html_id').val() == "") {
                //$('#input_html_id').css({"border": "1px solid #fb5454"});
                is_valid = false;
            } else if ($('#StartDate_input').val() == "") {
                $('#StartDate_input').css({"border": "1px solid #fb5454"});
                is_valid = false;
            } else if ($('#Time1_input').val() == "") {
                $('#Time1_input').css({"border": "1px solid #fb5454"});
                is_valid = false;
            }

            if (is_valid == false) {
                return false;
            }
            $('#create_stage1_deploy').removeClass('btn-active');
            $('#deploy_campaign_deploy').removeClass('btn-active');
            obj.parent().parent().addClass('btn-active')
            var F = $('#emailForm');
            var formdata = F.serialize()
            $.ajax({
                url: "email_data.php?pgaction=modifyCamp",
                data: formdata,
                type: "POST",
                method: "POST",
                dataType: "json",
                beforeSend: function () {
                    ACFn.clear_errors(F);

                    //$('.loading-div').text('Loading...');
                },
                success: function (data) {
                    //$('.loading-div').text('');
                    if (data.success == true) {
                        var R = ACFn.json_parse(data);

                        $('#responseHtml').show();
                        $('#Message').html(data.html);

                        if (data.resultHtml && data.resultHtml != "") {
                            $('#Result').html(data.resultHtml);
                            $('#resulttab').show();
                            $('#Result').show();
                            $('#Message').hide();
                            $('#Queries').hide();
                            $('#resulttab').addClass('active');
                            $('#messagetab').removeClass('active');
                            $('#querytab').removeClass('active');
                            /*$('.tab').css('width', '228px');*/


                            $('#resulttab').show();
                            $('#messagetab').show();
                            $('#querytab').show();


                        } else {
                            /*$('.tab').css('width', '165px');*/
                            $('#resulttab').removeClass('active');
                            $('#messagetab').addClass('active');
                            $('#querytab').removeClass('active');
                            $('#resulttab').hide();
                            $('#Result').hide();
                            $('#Queries').hide();
                            $('#Message').show();
                        }
                        $('#Queries').html(data.Sqls);
                        //load_table();
                    } else {
                        ACFn.display_message(
                            "Error!",
                            data.message,
                            "error");
                    }
                },
                error: function () {
                    ACFn.display_message(
                        "Server Error!",
                        "Try Again Later",
                        "error");
                },
                complete: function () {

                    $("body").removeClass(
                        "ajax-loading");
                }
            });
        }

        function deleteToProcess() {
            ACFn.sendAjax('email/Delete_toprocess', 'get', {});
        }

    </script>
    <script type="text/javascript">
        load_table();

        function load_table() {
            var zChar = new Array(' ', '(', ')', '-', '.');
            var maxphonelength = 13;
            var phonevalue1;
            var phonevalue2;
            var cursorposition;

            function ParseForNumber1(object) {
                phonevalue1 = ParseChar(object.value, zChar);
            }

            function ParseForNumber2(object) {
                phonevalue2 = ParseChar(object.value, zChar);
            }

            function backspacerUP(object, e) {
                if (e) {
                    e = e;
                } else {
                    e = window.event;
                }
                if (e.which) {
                    var keycode = e.which;
                } else {
                    var keycode = e.keyCode;
                }
                ParseForNumber1(object);
                if (keycode >= 48) {
                    ValidatePhone(object);
                }
            }

            function backspacerDOWN(object, e) {
                if (e) {
                    e = e
                } else {
                    e = window.event
                }
                if (e.which) {
                    var keycode = e.which;
                } else {
                    var keycode = e.keyCode;
                }
                ParseForNumber2(object);
            }

            function GetCursorPosition() {
                var t1 = phonevalue1;
                var t2 = phonevalue2;
                var bool = false;
                for (i = 0; i < t1.length; i++) {
                    if (t1.substring(i, 1) != t2.substring(i, 1)) {
                        if (!bool) {
                            cursorposition = i;
                            bool = true;
                        }
                    }
                }
            }

            function ValidatePhone(object) {
                var p = phonevalue1;
                p = p.replace(/[^\d]*/gi, "");
                if (p.length < 3) {
                    object.value = p;
                } else if (p.length == 3) {
                    pp = p;
                    d4 = p.indexOf('(');
                    d5 = p.indexOf(')');
                    if (d4 == -1) {
                        pp = "(" + pp;
                    }
                    if (d5 == -1) {
                        pp = pp + ")";
                    }
                    object.value = pp;
                } else if (p.length > 3 && p.length < 7) {
                    p = "(" + p;
                    l30 = p.length;
                    p30 = p.substring(0, 4);
                    p30 = p30 + ")";
                    p31 = p.substring(4, l30);
                    pp = p30 + p31;
                    object.value = pp;
                } else if (p.length >= 7) {
                    p = "(" + p;
                    l30 = p.length;
                    p30 = p.substring(0, 4);
                    p30 = p30 + ")";
                    p31 = p.substring(4, l30);
                    pp = p30 + p31;
                    l40 = pp.length;
                    p40 = pp.substring(0, 8);
                    p40 = p40 + "-";
                    p41 = pp.substring(8, l40);
                    ppp = p40 + p41;
                    object.value = ppp.substring(0, maxphonelength);
                }
                GetCursorPosition();
                if (cursorposition >= 0) {
                    if (cursorposition == 0) {
                        cursorposition = 2;
                    } else if (cursorposition <= 2) {
                        cursorposition = cursorposition + 1;
                    } else if (cursorposition <= 5) {
                        cursorposition = cursorposition + 2;
                    } else if (cursorposition == 6) {
                        cursorposition = cursorposition + 2;
                    } else if (cursorposition == 7) {
                        cursorposition = cursorposition + 4;
                        e1 = object.value.indexOf(')');
                        e2 = object.value.indexOf('-');
                        if (e1 > -1 && e2 > -1) {
                            if (e2 - e1 == 4) {
                                cursorposition = cursorposition - 1;
                            }
                        }
                    } else if (cursorposition < 11) {
                        cursorposition = cursorposition + 3;
                    } else if (cursorposition == 11) {
                        cursorposition = cursorposition + 1;
                    } else if (cursorposition >= 12) {
                        cursorposition = cursorposition;
                    }
                    var txtRange = object.createTextRange();
                    txtRange.moveStart("character", cursorposition);
                    txtRange.moveEnd("character", cursorposition - object.value.length);
                    txtRange.select();
                }
            }

            function ParseChar(sStr, sChar) {
                if (sChar.length == null) {
                    zChar = new Array(sChar);
                } else zChar = sChar;
                for (i = 0; i < zChar.length; i++) {
                    sNewStr = "";
                    var iStart = 0;
                    var iEnd = sStr.indexOf(sChar[i]);
                    while (iEnd != -1) {
                        sNewStr += sStr.substring(iStart, iEnd);
                        iStart = iEnd + 1;
                        iEnd = sStr.indexOf(sChar[i], iStart);
                    }
                    sNewStr += sStr.substring(sStr.lastIndexOf(sChar[i]) + 1, sStr.length);
                    sStr = sNewStr;
                }
                return sNewStr;
            }

            function validPH(nvl, cvl) {
                var phoneNumberPattern = /^\(?(\d{3})\)\d{3}[- ]?\d{4}$/;
                if (!phoneNumberPattern.test(nvl)) {
                    alert("The phone number you entered is not valid.\r\nPlease enter a phone number with the format (xxx)xxx-xxxx.");
                    return cvl;
                } else {
                    return nvl;
                }
            }

            function addcustomer() {
                var handleSuccess = function (o) {
                    if (o.responseText !== undefined) {
                        var obj = document.getElementById('divstatus')
                        obj.innerHTML = o.responseText + " Successfully added customer data";
                        document.frmaddcust.reset();

                    }
                }
                var callback = {success: handleSuccess};
                if (document.frmcustadd.firstname.value == "") {
                    document.frmcustadd.firstname.focus();
                    return false;
                }
                YAHOO.util.Connect.setForm('frmaddcust');
                var request = YAHOO.util.Connect.asyncRequest('POST', 'email_data.php?pgaction=emailcsr', callback);
                var obj = document.getElementById('divstatus')
                obj.style.display = "block";
                obj.innerHTML = "Please wait. Adding customer data";

            }


            /*(function () {
                var Dom = YAHOO.util.Dom,
                    Event = YAHOO.util.Event,
                    queryString = '&rand=' + Math.random(),
                    zip = null,
                    myDataSource = null,
                    myDataTable = null;


                Event.onDOMReady(function () {
                    var myColumnDefs = [
                        {key: "CampaignId", label: "Campaign ID", sortable: true, resizeable: true, editor: false},
                        {
                            key: "CampaignName",
                            label: "Campaign Name",
                            sortable: true,
                            resizeable: true,
                            editor: new YAHOO.widget.TextboxCellEditor({
                                disableBtns: false, _sId: "CampaignName", validator: true, save: saveTextarea
                            })
                        },
                        {
                            key: "template",
                            label: "Template",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextboxCellEditor({
                                disableBtns: false,
                                _sId: "template",
                                validator: true,
                                save: saveTextarea
                            })
                        },
                        {key: "Time1", label: "Time 1", sortable: false, resizeable: true, editor: false},
                        {key: "StartDate", label: "StartDate", sortable: false, resizeable: true, formatter:YAHOO.widget.DataTable.formatDate, editor: new YAHOO.widget.DateCellEditor({
                                disableBtns: false,
                                _sId: "StartDate",
                                validator: true,
                                save: saveTextarea
                            })},
                        {key: "EndDate", label: "EndDate", formatter:YAHOO.widget.DataTable.formatDate, editor: new YAHOO.widget.DateCellEditor({
                                disableBtns: false,
                                _sId: "EndDate",
                                validator: true,
                                save: saveTextarea
                            })},

                        {
                            key: "subject1",
                            label: "Subject 1",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "subject1",
                                validator: true,
                                save: saveTextarea
                            })
                        },
                        {
                            key: "subject2",
                            label: "Subject 2",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "subject2",
                                validator: true,
                                save: saveTextarea
                            })
                        },
                        {
                            key: "subject3",
                            label: "Subject 3",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "subject3",
                                validator: true,
                                save: saveTextarea
                            })
                        },
                        {
                            key: "TestSubject",
                            label: "Test Subject",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "TestSubject",
                                validator: true,
                                save: saveTextarea
                            })
                        },

                        {
                            key: "CampaignID_Data",
                            label: "CampaignID_Data",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "CampaignID_Data",
                                validator: true,
                                save: saveTextarea
                            })
                        },

                        {
                            key: "TestSubjectPct",
                            label: "TestSubjectPct",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "TestSubjectPct",
                                validator: true,
                                save: saveTextarea
                            })
                        },
                        {
                            key: "SubjectWin",
                            label: "SubjectWin",
                            sortable: false,
                            resizeable: true,
                            editor: new YAHOO.widget.TextareaCellEditor({
                                disableBtns: false,
                                _sId: "SubjectWin",
                                validator: true,
                                save: saveTextarea
                            })
                        },

                    ];

                    myDataSource = new YAHOO.util.DataSource("email_data.php?pgaction=emailcsr&");
                    myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
                    myDataSource.connXhrMode = "queueRequests";
                    myDataSource.responseSchema = {
                        resultsList: "records",
                        fields: ["CampaignId", "CampaignName", "template", "Time1", {key:"StartDate",parser:"date"}, {key:"EndDate",parser:"date"}, "subject1", "subject2", "subject3", "TestSubject", "CampaignID_Data", "TestSubjectPct", "SubjectWin"],
                        metaFields: {totalRecords: "totalRecords"}

                    };

                    var getCustomDate = function (date) {
                        var dt=new Date();
                        var da=date.split('-',3);
                        if (da.length!=3) return ''; // gave us bad data
                        dt.setFullYear(da[0]);
                        dt.setMonth(da[1]-1);
                        var dtime = da[2].split(' ');
                        dt.setDate(dtime[0]);
                        if (dtime.length==2) {
                            var time_vals = dtime[1].split(':');
                            dt.setHours(time_vals[0],time_vals[1]);
                        }
                        dt.setSeconds(0,0);
                        return dt;
                    }


                    var getemail = function (query) {
                        myDataSource.sendRequest('email=' + Dom.get('dt_email').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };
                    var getlname = function (query) {
                        myDataSource.sendRequest('lname=' + Dom.get('dt_lname').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };
                    var getfname = function (query) {
                        myDataSource.sendRequest('fname=' + Dom.get('dt_fname').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };
                    var getcity = function (query) {
                        myDataSource.sendRequest('city=' + Dom.get('dt_city').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };

                    var getphone = function (query) {
                        myDataSource.sendRequest('phone=' + Dom.get('dt_phone').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };
                    var getZip = function (query) {
                        myDataSource.sendRequest('zip=' + Dom.get('dt_input_zip').value + queryString, myDataTable.onDataReturnInitializeTable, myDataTable);
                    };


                    var oACDSemail = new YAHOO.util.FunctionDataSource(getemail);
                    oACDSemail.queryMatchContains = true;
                    var oAutoemail = new YAHOO.widget.AutoComplete("dt_email", "dt_ac_container", oACDSemail);

                    var oACDSlname = new YAHOO.util.FunctionDataSource(getlname);
                    oACDSlname.queryMatchContains = true;
                    var oAutolname = new YAHOO.widget.AutoComplete("dt_lname", "dt_ac_container", oACDSlname);

                    var oACDSfname = new YAHOO.util.FunctionDataSource(getfname);
                    oACDSfname.queryMatchContains = true;
                    var oAutofname = new YAHOO.widget.AutoComplete("dt_fname", "dt_ac_container", oACDSfname);

                    var oACDScity = new YAHOO.util.FunctionDataSource(getcity);
                    oACDScity.queryMatchContains = true;
                    var oAutocity = new YAHOO.widget.AutoComplete("dt_city", "dt_ac_container", oACDScity);


                    var oACDSphone = new YAHOO.util.FunctionDataSource(getphone);
                    oACDSphone.queryMatchContains = true;
                    var oAutophone = new YAHOO.widget.AutoComplete("dt_phone", "dt_ac_container", oACDSphone);

                    var oACDSZip = new YAHOO.util.FunctionDataSource(getZip);
                    oACDSZip.queryMatchContains = true;
                    var oAutoCompZip = new YAHOO.widget.AutoComplete("dt_input_zip", "dt_ac_zip_container", oACDSZip);


                    myPaginator = new YAHOO.widget.Paginator({rowsPerPage: 25})
                    myDataTable = new YAHOO.widget.DataTable("divdatatable", myColumnDefs, myDataSource, {
                            dynamicData: true,
                            paginated: true,
                            paginator: myPaginator,
                            initialRequest: 'results=20&datatable=yes&lname=' + Dom.get('dt_lname').value + '&fname=' + Dom.get('dt_fname').value + '&city=' + Dom.get('dt_city').value + '&zip=' + Dom.get('dt_input_zip').value + queryString
                        }
                    );

                    myDataTable.handleDataReturnPayload = function (oRequest, oResponse, oPayload) {
                        oPayload = oPayload || {};
                        oPayload.totalRecords = oResponse.meta.totalRecords;
                        return oPayload;
                    }

                    myDataTable.subscribe("rowClickEvent", function (oArgs) {


                    });

                    function convert(str) {
                        var date = new Date(str),
                            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                            day = ("0" + date.getDate()).slice(-2);
                        return [date.getFullYear(), mnth, day].join("-");
                    }

                    function saveTextarea() {
                        var inputValue = this.getInputValue();
                        var id = this.getId();


                        // Validate new value
                        if (this.validator) {
                            //validValue = this.validator.call(this.getDataTable(), inputValue, this.value, this);

                            if (inputValue === "") {
                                if (this.resetInvalidData) {
                                    this.resetForm();
                                }
                                this.fireEvent("invalidDataEvent",
                                    {editor: this, oldData: this.value, newData: inputValue});
                                YAHOO.log("Could not save Cell Editor input due to invalid data " +
                                    inputValue, "warn", this.toString());
                                return;
                            }
                        }

                        var oSelf = this;
                        var finishSave = function (bSuccess, oNewValue) {
                            //console.log(oSelf); return false;
                            var oOrigValue = oSelf.value;
                            if (bSuccess) {
                                // Update new value
                                oSelf.value = oNewValue;
                                var records = oSelf.getRecord();
                                console.log(oOrigValue);
                                console.log(oNewValue);
                                console.log(oSelf);
                                //oNewValue = oSelf.getId() == "EndDate" ?  convert(oNewValue) : oNewValue;
                                var CoNewValue = oSelf.getId() == "EndDate" || oSelf.getId() == "StartDate" ?  convert(oNewValue) : oNewValue;
                                ACFn.sendAjax('email_data.php?pgaction=updateCampaign',
                                    'get',
                                    {
                                        camp_id: records._oData.CampaignId,
                                        new_value: CoNewValue,
                                        column_name: oSelf.getId()
                                    },
                                    '');

                                ACFn.update_cell = function (F, R) {

                                    if (R.is_already_exist == false) {
                                        oSelf.getDataTable().updateCell(oSelf.getRecord(), oSelf.getColumn(), oNewValue);
                                        // Hide CellEditor
                                        oSelf.getContainerEl().style.display = "none";
                                        oSelf.isActive = false;
                                        oSelf.getDataTable()._oCellEditor = null;
                                    } else {
                                        alert(R.message);
                                        oSelf.getDataTable().updateCell(oSelf.getRecord(), oSelf.getColumn(), oNewValue);
                                    }
                                }

                                /!*
                                oSelf.fireEvent("saveEvent",
                                        {editor:oSelf, oldData:oOrigValue, newData:oSelf.value});
                                YAHOO.log("Cell Editor input saved", "info", this.toString());*!/
                            } else {
                                oSelf.resetForm();
                                oSelf.fireEvent("revertEvent",
                                    {editor: oSelf, oldData: oOrigValue, newData: oNewValue});
                                YAHOO.log("Could not save Cell Editor input " +
                                    lang.dump(oNewValue), "warn", oSelf.toString());
                            }
                            oSelf.unblock();
                        };

                        this.block();
                        finishSave(true, inputValue);
                    }

                    myDataTable.subscribe("rowMouseoverEvent", myDataTable.onEventHighlightRow);
                    myDataTable.subscribe("rowMouseoutEvent", myDataTable.onEventUnhighlightRow);
                    myDataTable.subscribe("cellClickEvent", myDataTable.onEventShowCellEditor);

                });
            })();*/
        }
    </script>
    <style>
        body {
           /* //background: #dbeef4;*/
        }
    </style>

    <!--
    <div id='divcheckcnt' class='yui-panel'>
        <div class='hd'>Check Dupes</div>
        <div class='bd'>
            <table>
                <tr>
                    <td></td>
                    <td colspan="2"><input type="text" id="campids" style="width: 187px !important;"></td>
                    <td><span class="yui-button yui-push-button" style="margin: 0px !important;"><span class="first-child"><button
                                        type="button" title="Count_Toprocess" id="count_dupes_btn"><img style="float:left;"
                                                                                                        width="15"
                                                                                                        height="15"
                                                                                                        src="images/arrow_right-512.png"></button></span></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><span id="loadingcounts" style="display: none">Loading...</span></td>
                </tr>
                <tr>
                    <td>Counts:</td>
                    <td><input type="text" id='divdbtcnt'></td>
                    <td>Counts Distinct:</td>
                    <td><input type="text" id='divdbtcntdistinct'></td>
                </tr>
            </table>
        </div>
    </div>-->

@stop
