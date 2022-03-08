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
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="container-fluid">
        @include('lookup.common.index',['part' => 'top-header','option' => 'phone'])
		<div class="col-md-12">
            <div class="card">
                <div class="card-body pt-2">

                    @include('lookup.common.index',[
                        'part' => 'filters-heading-wd-buttons',
                        'option' => 'phone'
                    ])

					<div class="row">
						<div class="col-lg-2 lookup-filters border-right">
							<div class="card-body p-1" style=" overflow-x:hidden; overflow-y:auto;height: 618px;">
                                <div class="row" >
                                    <form id="filter_form" class="filter-scroll-js filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">
                                        @php
                                            $segment = Request::segment(1);
                                            $segment2 = Request::segment(2);
                                        @endphp
                                        <div class="form-body">
                                            <div class="card-body pt-0">
                                                @if($segment == "phone")
                                                    @include('lookup.filters.phone')
                                                @endif
                                                @php

                                                    $filter = \App\Helpers\Helper::generateLeftSideFilterHtml($lLookupFirstScreenFilters,'s');
                                                    echo $filter['html'];
                                                    $multiselect_filters_fields = $filter['multiselect_filters_fields'];
                                                    $multiselect_filters_fields[] = 'status-filter';
                                                    $multiselect_filters_fields[] = 'campaign-filter';
                                                @endphp

                                                <div class="form-group">
                                                    <label class="control-label">Notes</label>
                                                    <input type="text" name="Notes" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

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
						<div class="col-lg-10 lookup-content">
							   <div class="vtabs customvtab" style="width: 100%">
									{!! csrf_field() !!}
									<ul class="nav nav-tabs tab-style tab-hash tab-ajax" style="display: none;"
										role="tablist"
										data-href="phone/getfirstscreen"
										data-method="post"
										data-default-tab="dummy">

										<li class="nav-item">
											<a href="#tab_20" class="nav-link" data-tabid="20" data-toggle="tab" aria-expanded="true"><span class="hidden-sm-up"><i class="fas fa-users"></i></span> <span class="hidden-xs-down">First</span></a>
										</li>

									</ul>
									<!-- Tab panes -->
									<div class="tab-content br-n pn" style="width: 86% !important;padding: 0px !important;">
										<div class="tab-pane active" id="tab_20" role="tabpanel"></div>
										<div class="tab-pane" id="tab_21" role="tabpanel"></div>
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
            localStorage.removeItem('MergeKeys');

            var filtersMS = {!! json_encode($multiselect_filters_fields) !!};
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
                            'min-height: 30px;'
                        );
                    },1000);
                }
            });

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
    </script>
    <script src="elite/js/custom.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/lookup.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/phone.js?ver={!! time() !!}" type="text/javascript"></script>
    <script src="js/report.js?ver={!! time() !!}" type="text/javascript"></script>
@stop
