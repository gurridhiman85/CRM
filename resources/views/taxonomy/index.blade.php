<?php
if(!Auth::check())
    $layout = 'wl-docker-horizontal';
else
    $layout = 'docker-horizontal';
?>
@extends('layouts.'.$layout)
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="after-filter"></div>
                            </div>
                        </div>

                        <div class="row" style="border-bottom: 1px solid #dee2e6;">

                            <div class="col-md-7">
                                <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax" role="tablist" data-href="taxonomy/get" data-method="get" data-default-tab="tab_2">
                                    @foreach($lLevelFilters as $key => $lLevelFilter)
                                        <li class="nav-item" style="border-bottom: 1px solid #dee2e6;">
                                            <a class="nav-link" data-toggle="tab" data-tabid="{!! $key !!}" href="#tab_{!! $key !!}" role="tab" aria-selected="true" onclick="setlevel('{!! $key !!}',{{ json_encode($alllevels) }})">
                                                <span class="hidden-sm-up"></span>
                                                <span class="hidden-xs-down">{!! ucfirst($key) !!}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="col-md-5">
                                <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                    <div class="input-group mb-1">
                                        <div class="all-pagination" style="vertical-align: middle;margin: 10px;"></div>

                                        <input type="text" id="filtersearch" class="form-control ajax-search search-btn" placeholder="Search" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                        <div class="input-group-append search-btn">
                                            <div class="input-group-text border-left-0" title="Search" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');">
                                                <i class="fas fa-search ds-c"></i>
                                            </div>
                                        </div>
                                        <button type="button" title="Filters" class="btn btn-light border-left-0 border-right-0 no-border p-r-2" data-toggle="modal" data-target="#filtersModel"><i class="fas fa-filter ds-c"></i></button>
                                        <div class="c-btn" style="display: none;"></div>
                                        <button type="button" id="DownloadBtn" title="Download" class="btn btn-light border-left-0 font-12" onclick="downloadTNLink($(this))" data-href="taxonomy/download" data-tab="level2" aria-expanded="false"><i class="fas fa-download ds-c"></i></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-content br-n pn">
                            <?php $i=1; ?>
                            @foreach($lLevelFilters as $key => $lLevelFilter)
                                <div class="tab-pane customtab {!! $i == 2 ? 'active' : '' !!}" id="tab_{!! $key !!}" role="tabpanel"></div>
                                @php $i++; @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal bs-example-modal-sm" id="filtersModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title pl-1" id="myModalLabel">Filters</h6>
                        <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
                    </div>
                    <div class="modal-body p-1">
                        <div class="card mb-1">
                            <div class="card-body p-1">
                                <form id="filter_form" class=" filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">
                                    <div class="form-body">
                                        <div class="row">
                                            @php
                                                $multiselect_filters_fields = [];
                                                $levels = [];
                                                $i = 0;
                                            @endphp
                                            @foreach($lLevelFilters as $level => $lLevelFilter)
                                                <?php $levels[] = $level; ?>
                                                @foreach($lLevelFilter as $Field_Name => $lLevelF)
                                                    @php
                                                        $Field_ID = $Field_Name.'-sum-filter';
                                                        $Field_Name = $level.'-'.$Field_Name;
                                                        $multiselect_filters_fields[] = $Field_ID;
                                                    @endphp
                                                    @if($i == 3)
                                                        {{--</div>
                                                        <div class="row">--}}
                                                        @php $i = 0; @endphp
                                                    @endif
                                                    <div class="col-md-4 mt-1 {!! $level !!}">
                                                        <label class="control-label">{!! $lLevelF['Field_Display_Name'] !!}</label>
                                                        <select name="{!! $Field_Name !!}" id="{!! $Field_ID !!}" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                            @foreach($lLevelF as $LF)
                                                                @if($LF == $lLevelF['Field_Display_Name']) @continue @endif
                                                                @php $LF = !is_numeric($LF) ? trim($LF) : $LF; @endphp
                                                                <option value="{!! trim($LF) !!}">{!! trim($LF) !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @php $i++; @endphp
                                                @endforeach
                                            @endforeach

                                        </div>
                                    </div>
                                    <div class="form-actions pull-right" >
                                        <input type="hidden" name="searchterm" class="form-control form-control-sm" placeholder="" data-placeholder="">
                                        <button type="submit" class="btn btn-info">Apply</button>
                                        <button type="button" class="btn border-secondary waves-effect waves-light btn-outline-secondary " onclick="clearFilters();" style="border-color: #dee2e6;">Clear</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
        $(document).ready(function () {
            var filtersMS = {!! json_encode($multiselect_filters_fields) !!};
            $.each( filtersMS, function( index, value ){

                if($('[id="'+value+'"]').length){
                    $('[id="'+value+'"]').multiselect({
                        //appendTo: '#filtersModel',
                        close: function () {
                            delay(function(){
                                //$('#filter_form').submit();
                            }, 1000 );
                        },
                        header: true, //"Region",
                        selectedList: 0, // 0-based index
                        nonSelectedText: 'Select Values',
                        enableFiltering: true,
                        filterBehavior: 'text',
                    }).multiselectfilter({label: 'Search'});

                    setTimeout(function () {
                        var id = value + '_ms';
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
                            'min-height: 30px;'
                        );
                    },1000);
                }
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

            ACFn.ajax_field_update = function (F , R) {
                if(R.success){
                    //$('#s7dflname_' + R.rowid).text(R.aData.DFLName);
                    //$('#checkbox_' + R.rowid).attr('checked',true);
                }
            }

            ACFn.ajax_download_file = function(F,R){
                if(R.success){
                    window.location.href = R.download_url;
                }
            };

            var params = parseHashUrl();
            if(params.tab){
                var tab = params.tab.split('_');
                setlevel(tab[1],{!! json_encode($levels) !!});
            }else{
                setlevel('Level2',{!! json_encode($levels) !!});
            }

        });

        function getFilters(F) {
            if (typeof F == 'undefined') {
                if($("#filter_milestone_form").length > 0){
                    F = $("#filter_milestone_form");
                }else{
                    F = $("#filter_form");
                }

            }
            var filters = [];
            var filtersFlag = false;
            if (F.length) {
                $.each(F.serializeArray(), function (index, element) {
                    console.log(element);
                    if (typeof filters[element.name] == 'undefined') {
                        filters[element.name] = [];
                    }
                    if (element.value) {
                        filters[element.name].push(element.value);
                        filtersFlag = true;
                    }
                });
            }
            var obj = $.extend({}, filters);
            if(filtersFlag == true){
                filtersApplied(obj, F);

            }else{
                if($("#filtersApplied").length > 0){
                    $('#filtersApplied').remove();
                    $('.clear-btn').remove();
                }
            }
            console.log('Form elements');
            console.log(obj);
            console.log('Form elements end');
            return obj;
        }

        function filtersApplied(filters, $form) {
            if (typeof $form == 'undefined') {
                $form = $("#filter_form");
            }
            var key = null;
            for (var prop in filters) {
                if (filters.hasOwnProperty(prop)) {
                    key++;
                }
            }
            if (key > 0 && $("#filtersApplied").length == 0) {
                //$("#collapseFilters").after('<ul id="filtersApplied" class="selected-filters" ></ul>');
                $(".after-filter").html('<ul id="filtersApplied" class="selected-filters" ></ul>'); //<button type="button" class="btn clear-btn" onclick="clearFilters()"><i class="fa fa-refresh" aria-hidden="true"></i> Clear Filter</button>
            }
            var fouter = $("#filtersApplied");
            fouter.empty();
            $.each(filters, function (name, element) {
                var elselect = $form.find("select[name='" + name + "']");
                var elinput = $form.find("input[name='" + name + "']");
                $.each(element, function (key, value) {
                    if (value == '') {
                        return;
                    }
                    var long_name = value;
                    var elcheckbox = $form.find("[name='" + name + "'][value='" + value + "'][type='checkbox']");
                    var elradio = $form.find("[name='" + name + "'][value='" + value + "'][type='radio']");
                    if (elcheckbox.length && elcheckbox.next('label').length) {
                        long_name = elcheckbox.next('label').html();
                    } else if (elradio.length && elradio.next('label').length) {
                        long_name = elradio.next('label').html();
                    } else if (elselect.length) {
                        var opt = elselect.find('option[value="' + value + '"]');
                        if (opt.length) {
                            long_name = opt.html();
                        }
                    } else if (elinput.length) {
                        var opr = $form.find("select[name='" + name + "_op']").length ?  $form.find("select[name='" + name + "_op']").val() : '';
                        long_name = elinput.attr('data-placeholder') + ' '+ opr + ' ' + elinput.val();
                    }
                    //console.log("not allowed----",elselect.data('notallowed'));
                    if(elselect.data('notallowed') == false || elselect.data('notallowed') == undefined){
                        fouter.append('<li class="selected-filter mr-1"><span>' + long_name + '</span><a href="#" class="removeFilter" data-name="' + name + '" data-value="' + value + '" ><i class="fas fa-times-circle"></i></a></li>');
                    }

                });

            });
        }

        function downloadTNLink(obj){
            var url = obj.data('href');
            var tab = obj.attr('data-tab');
            var filters = getFilters($('#filter_form'));
            var table = $('#basic_table_without_dynamic_pagination');
            var downloadableColumns = table.attr('data-columns-visible') ? table.attr('data-columns-visible') : '';
            ACFn.sendAjax(url,'GET',{
                tab : tab,
                filters : filters,
                downloadableColumns : downloadableColumns
            },obj);
        }

        function setlevel(level,alllevels) {
            $('#DownloadBtn').attr('data-tab',level);

            //clearFilters();
            $.each(alllevels,function (index,value) {
                if($.trim(level) == value){
                    $('.' + value).removeAttr('style');
                }else{
                    $('.' + value).hide();
                }
            })
        }

        function blankMergeData(){}

        function ajax_field_update(obj) {
            var primary_column = obj.data('primary_column');
            var primary_column_value = obj.data('primary_column_value');
            var fieldname = obj.data('field');
            var fieldvalue = obj.val();
            delay(function(){
                ACFn.sendAjax('taxonomy/quickupdate','GET',{
                    primary_colum : primary_column,
                    primary_column_value : primary_column_value,
                    fieldname : fieldname,
                    fieldvalue : fieldvalue,
                })
            }, 1000 );
        }


    </script>

@stop
