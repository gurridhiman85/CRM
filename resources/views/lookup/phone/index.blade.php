@extends('layouts.docker-horizontal')
@section('content')
    <?php
   // \App\Library\AssetLib::library('jq-dt-editable','tiny-editable-mindmup','tiny-editable-numeric');
    ?>
    <style>
        .ui-multiselect-menu{
            width: 257px !important;
        }
        .ui-multiselect .ui-widget .ui-state-default .ui-corner-all{
            color : #0f0f0f !important
        }
        select.form-control-sm{
            color : #0f0f0f !important
        }
        input.form-control.form-control-sm{
            color : #0f0f0f !important
        }

        option.badge {
            text-align: left;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="container-fluid">
        {{--@include('lookup.common.index',['part' => 'top-header','option' => 'phone'])--}}
		<div class="col-md-12">
            <div class="card">
                <div class="card-body pt-2">
                    <div class="row">
                        <div class="after-filter ml-4 mt-1"></div>
                    </div>
                    <div class="row firstscreen" style="border-bottom: 1px solid #dee2e6;">
                        <div class="col-lg-2 pl-4 pt-3 filters">
                            <h5>Filters</h5>
                        </div>
                        <div class="col-lg-5 tabs">

                            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax"
                                role="tablist"
                                data-href="phone/getfirstscreen"
                                data-method="post"
                                data-default-tab="dummy">

                                {{--<li class="nav-item">
                                    <a href="#tab_20" class="nav-link" data-tabid="20" data-toggle="tab" aria-expanded="true"><span class="hidden-sm-up"><i class="fas fa-users"></i></span> <span class="hidden-xs-down">Listing</span></a>
                                </li>--}}

                                @foreach($alllevels as $key => $level)
                                    <?php
                                    $levelWS = str_replace('-','_h_',str_replace(' ','_',$level));
                                    ?>
                                    <li class="nav-item" style="border-bottom: 1px solid #dee2e6;">
                                        <a class="nav-link" data-toggle="tab" data-tabid="{!! $levelWS !!}" href="#tab_{!! $levelWS !!}" role="tab" aria-selected="false" onclick="filtertoggle(1,'{!! $levelWS !!}'); setlevel('{!! $levelWS !!}',{{ json_encode($jslevel) }})">
                                            <span class="hidden-sm-up"></span>
                                            <span class="hidden-xs-down">{!! ucfirst($level) !!}</span>
                                        </a>
                                    </li>
                                @endforeach
                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="single_campaign" href="#tab_single_campaign" role="tab" aria-selected="true" onclick="filtertoggle(0,'SingleCamp')">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Single Campaign</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-5 icons">

                            <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="all-pagination pt-3 pr-3"></div>
                                <div class="input-group">
                                    <input type="text" id="filtersearch" class="form-control ajax-search" placeholder="Search" aria-label="Input group example" aria-describedby="btnGroupAddon">
                                    <div class="input-group-append">
                                        <div class="input-group-text border-right-0 border-left-0" title="Search" id="btnGroupAddon" onclick="$('.ajax-search').trigger('keyup');">
                                            <i class="fas fa-search ds-c"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-group" role="group" aria-label="First group">
                                    <button type="button" title="Clear Filters" class="btn btn-light font-16 clear-btn border-right-0 no-border p-r-2" onclick="clearFilters()"><i class="mdi mdi-filter-remove ds-c"></i></button>
                                    <button type="button" class="btn btn-light no-border border-right-0 font-12" id="refreshBtn" onclick="refreshMergeList()" title="Refresh" style="float: right;box-shadow: none;display: none;"><i class="fas fa-sync-alt ds-c" ></i></button>
                                    <div class="c-btn" style="display: none;"></div>

                                    <div class="btn-group">

                                        <button type="button" title="Report download" class="btn btn-light no-border dropdown-toggle font-12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right;box-shadow: none;"><i class="ti-bar-chart font-weight-bold font-16 ds-c"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="implementReportWithPhone('report','{!! $report_row_id !!}');"><i class="fas fa-file-pdf" style="color: #e92639;"></i> PDF Report</a>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="implementReportWithPhone('list','{!! $report_row_id !!}');"><i class="fas fa-file-excel" style="color: #06b489;"></i> Xlsx Report</a>
                                        </div>
                                    </div>
                                    <button type="button" title="Download" class="btn btn-light no-border font-12" onclick="downloadCMLink($(this))" data-href="lookup/downloadreport" data-prefix="phone" data-screen="first" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-download ds-c"></i></button>
                                    <button type="button" title="Add to Phone" class="btn btn-light font-12 ajax-Link" data-href="phone/add" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-plus ds-c"></i></button>
                                </div>

                            </div>
                        </div>
                    </div>

					<div class="row">
						<div class="col-lg-2 filters border-right">
							<div class="card-body p-1" style=" overflow-x:hidden; overflow-y:auto;height: 618px;">
                                <div class="row" >
                                    <form id="filter_form" class="filter-scroll-js filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">
                                        @php
                                            $segment = Request::segment(1);
                                            $segment2 = Request::segment(2);
                                        @endphp
                                        <div class="form-body">
                                            <div class="card-body pt-0">
                                                {{--@if($segment == "phone")
                                                    @include('lookup.filters.phone')
                                                @endif--}}
                                                <?php $multiselect_filters_fields = []; ?>
                                                @foreach($lLevelFilters as $level => $lLevelFilter)

                                                @php
                                                    $levels[] = str_replace('-','_h_',str_replace(' ','_',$level));
                                                    $levelWS = str_replace('-','_h_',str_replace(' ','_',$level));
                                                    $filter = \App\Helpers\Helper::generateLeftSideFilterHtml($lLevelFilter,str_replace('-','_h_',str_replace(' ','_',$level)));
                                                    echo $filter['html'];
                                                    $multiselect_filters_fields[] = $filter['multiselect_filters_fields'];
                                                    //$multiselect_filters_fields[] = 'status-filter';
                                                    //$multiselect_filters_fields[] = 'campaign-filter';
                                                @endphp
                                                @endforeach

                                                {{--<div class="form-group">
                                                    <label class="control-label">Notes</label>
                                                    <input type="text" name="Notes" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>--}}

                                                <div class="form-actions pull-right d-none" >
                                                    <input type="hidden" name="contactids" id="contactid" onemptied="blankMergeData();" onchange="blankMergeData();" class="form-control form-control-sm" placeholder="" data-placeholder="">
                                                    <input type="hidden" name="searchterm" class="form-control form-control-sm" placeholder="" data-placeholder="">
                                                    <button type="submit" class="btn btn-info">Apply</button>
                                                    <button type="button" class="btn border-secondary waves-effect waves-light btn-outline-secondary " onclick="clearFilters();" style="border-color: #dee2e6;">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
						</div>
						<div class="col-lg-10 content">
                           <div class="vtabs customvtab" style="width: 100%">
                                <!-- Tab panes -->
                                <div class="tab-content br-n pn" style="width: 86% !important;padding: 0px !important;">
                                    {{--<div class="tab-pane active" id="tab_20" role="tabpanel"></div>--}}
                                    @foreach($alllevels as $key => $level)
                                        <?php
                                        $levelWS = str_replace('-','_h_',str_replace(' ','_',$level));;
                                        ?>
                                        <div class="tab-pane customtab {{ $levelWS == 'Listing' ? 'active' : '' }}" id="tab_{!! $levelWS !!}" role="tabpanel"></div>
                                    @endforeach
                                    <div class="tab-pane" id="tab_21" role="tabpanel"></div>
                                    <div class="tab-pane" id="tab_single_campaign" role="tabpanel"></div>
                                </div>
                            </div>
						</div>
					</div>
                    <!-- Nav tabs -->

                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div id="chartPO" style="display:none;width: 100%; max-width:800px; height: 530px; "></div>
                        </div>
                        <div class="col-md-1"></div>
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

    @include('layouts.modal.modals')
</div>
    <script type="application/javascript">
        var username = '{!! Auth::user()->User_FName.' '.Auth::user()->User_LName !!}';
        $(document).ready(function () {
            /*setTimeout(function () {
                $('[data-tabid="Listing"]').trigger('click');
                NProgress.start();
            },2000)*/

            localStorage.removeItem('MergeKeys');

            var filtersMSS = {!! json_encode($multiselect_filters_fields) !!};
            $.each( filtersMSS, function( indexMS, filtersMS ){
                $.each( filtersMS, function( index, value ){

                    if($('[id="'+value+'"]').length){
                        $('[id="'+value+'"]').multiselect({
                            //appendTo: '#filtersModel',
                            close: function () {
                                delay(function(){
                                    $('#filter_form').submit();
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
                                'min-height: 30px;' /*+
                                'color: #cdcdcd;'*/
                            );
                        },1000);
                    }
                });
            });

            filtertoggle(1,'');
            var params = parseHashUrl();
            if(params.tab){
                var tab = params.tab.split('_');
                setlevel(tab[1],{!! json_encode($levels) !!});
            }else{
                setlevel('Catalog',{!! json_encode($levels) !!});
            }

            @if(request()->get('req'))
                setTimeout(function () {
                    $("#sidebarnav li").removeClass("active");
                    $("#sidebarnav li a").removeClass("active");
                    $("#sidebarnav li:first").addClass("active");
                    $("#sidebarnav li:first a").addClass("active");
                    var uri = window.location.href.toString();
                    if (uri.indexOf("?") > 0) {
                        var clean_uri = uri.substring(0, uri.indexOf("?"));
                        window.history.replaceState({}, document.title, clean_uri);
                    }
                }, 2000)

                ACFn.sendAjax('lookup/add', 'GET', {});
            @endif


        });

        function setlevel(level,alllevels) {
            $('[title="Download"]').attr('data-screen',level);

            //clearFilters();
            $.each(alllevels,function (index,value) {

                var value = value.replace(" ", "_");
                if($.trim(level) == value){
                    $('.' + value).removeAttr('style');
                }else{
                    $('.' + value).hide();
                }
            })
        }

        function filtertoggle(flag = 0,section = '') {
            if(flag === 1){
                $('[title=Filters]').show();
                $('.filters').show();
                $('.tabs').addClass('col-lg-5').removeClass('col-lg-7');
                $('.content').addClass('col-lg-10').removeClass('col-lg-12');
            }else{
                $('.filters').hide();
                $('.tabs').addClass('col-lg-7').removeClass('col-lg-5');
                $('.content').addClass('col-lg-12').removeClass('col-lg-10');
                $('[title="Campaign"]').show();
                $('[title="Create New"]').show();
                $('[title=Filters]').hide()
                $('[title=Download]').hide()
            }
        }
    </script>
    <script src="elite/js/custom.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/lookup.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/phone.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/report.js?ver={!! time() !!}" type="text/javascript"></script>
@stop
