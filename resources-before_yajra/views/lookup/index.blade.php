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
            color : #898b72 !important;
        }
        select.form-control-sm{
            color : #898b72 !important;
        }
        input.form-control.form-control-sm{
            color : #898b72 !important;
        }
    </style>
    <div class="container-fluid">

		@include('lookup.common.index',['part' => 'top-header','option' => 'phone'])

        <div class="col-md-12">
            <div class="card">
                <div class="card-body pt-2">

                    @include('lookup.common.index',[
                        'part' => 'filters-heading-wd-buttons',
                        'option' => 'lookup'
                    ])

                    <!-- Nav tabs -->
                    <div class="row">
                        <div class="col-lg-2 lookup-filters border-right p-0">
                            <div class="card-body p-1" style="overflow-x:hidden; overflow-y:auto;height: 615px;">

                                <div class="row" >
                                    <form id="filter_form" class=" filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">

                                        @php
                                            $segment = Request::segment(1);
                                            $segment2 = Request::segment(2);
                                        @endphp
                                        <div class="form-body">
                                            <div class="card-body pt-0">
                                                @if($segment == "phone")
                                                    @include('lookup.filters.phone')
                                                @endif
                                                <div class="form-group">
                                                    <label class="control-label">ZSS Segment</label>

                                                    <select name="ZSS_Segment" class="form-control form-control-sm" id="zss_segment-filter" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($ZSS_Segments as $ZSS_Segment)
                                                            <option value="{!! $ZSS_Segment !!}">{!! $ZSS_Segment !!}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Member Segment</label>
                                                    <select name="MemberSegment" id="member_segment-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($MemberSegments as $MemberSegment)
                                                            <option value="{!! $MemberSegment !!}">{!! $MemberSegment !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Donor Segment</label>
                                                    <select name="DonorSegment" id="donor_segment-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($DonorSegments as $DonorSegment)
                                                            <option value="{!! $DonorSegment !!}">{!! $DonorSegment !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Event Segment</label>
                                                    <select name="EventSegment" id="event_segment-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($EventSegments as $EventSegment)
                                                            <option value="{!! $EventSegment !!}">{!! $EventSegment !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Lifecycle Segment</label>
                                                    <select name="LifecycleSegment" id="lifecycle_segment-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($LifecycleSegments as $LifecycleSegment)
                                                            <option value="{!! $LifecycleSegment !!}">{!! $LifecycleSegment !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Address Quality</label>
                                                    <select name="AddressQuality" id="address-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($AddressQualities as $AddressQuality)
                                                            <option value="{!! $AddressQuality !!}">{!! $AddressQuality !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Country</label>
                                                    <select name="country" id="country-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($countries as $country)
                                                            <option value="{!! $country !!}">{!! $country !!}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="control-label">Contact</label>
                                                    <input type="text" name="Extendedname" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Company</label>
                                                    <input type="text" name="Company" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Address</label>
                                                    <input type="text" name="Address" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Email</label>
                                                    <input type="text" name="Email" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Phone</label>
                                                    <input type="text" name="Phone" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Last 5yr Gift $</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Last_5Yrs_GiftsAmt_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Last_5Yrs_GiftsAmt" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Last 5yr Gift $ " data-placeholder="Last 5yr Gift $ ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">LifeTime Gift $</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Life2date_GiftsAmt_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Life2date_GiftsAmt" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Life Gift $ " data-placeholder="LifeTime Gift $ ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Last 5yr Spend $</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Last_5Yrs_SpendAmt_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Last_5Yrs_SpendAmt" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Last 5yr Spend $ " data-placeholder="Last 5yr Spend $ ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">LifeTime Spend $</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Life2date_SpendAmt_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Life2date_SpendAmt" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Life Spend $ " data-placeholder="LifeTime Spend $ ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Days Since Last Visit</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Dayssincelastvisit_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Dayssincelastvisit" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Days - Last Visit " data-placeholder="Days Since Last Visit ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Years Since 1st Visit</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="Yearssincefirstvisit_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="Yearssincefirstvisit" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Years - 1st Visit " data-placeholder="Years Since 1st Visit ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Days Since 1st Create</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="DaysSince1stCreate_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="DaysSince1stCreate" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Days - 1st Create " data-placeholder="Days Since 1st Create ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Days Since Last Update</label>
                                                    <div class="row">
                                                        <div class="col-md-4 p-r-0">
                                                            <select name="DaysSinceLastUpdate_op" onchange="applyFilters()" class="form-control form-control-sm" data-notallowed="true">
                                                                <option value=">">&gt;</option>
                                                                <option selected value="<">&lt;</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-8 p-l-0">
                                                            <input type="text" name="DaysSinceLastUpdate" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter Days - Last Update " data-placeholder="Days Since Last Update ">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Activity Cat 1</label>
                                                    <select name="ActivityCat1" id="ActivityCat1-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($ActivityCat1 as $AC1)
                                                            <option value="{!! $AC1 !!}">{!! $AC1 !!}</option>
                                                        @endforeach
                                                    </select>

                                                    <!--<input type="text" name="ActivityCat1" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">-->
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Activity Cat 2</label>
                                                    <select name="ActivityCat2" id="ActivityCat2-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($ActivityCat2 as $AC2)
                                                            <option value="{!! $AC2 !!}">{!! $AC2 !!}</option>
                                                        @endforeach
                                                    </select>
                                                    <!--<input type="text" name="ActivityCat2" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">-->
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label">Activity Type</label>
                                                    <select name="Activity" id="Activity-filter" class="form-control form-control-sm" multiple="multiple" data-placeholder="Select Values">
                                                        @foreach($Activity as $AT)
                                                            <option value="{!! $AT !!}">{!! $AT !!}</option>
                                                        @endforeach
                                                    </select>
                                                    <!--<input type="text" name="Activity" onkeyup="applyFilters();" class="form-control form-control-sm" placeholder="Enter search string" data-placeholder="">-->
                                                </div>

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
                                    data-href="lookup/getfirstscreen"
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
    <script type="application/javascript">
        var username = '{!! Auth::user()->User_FName.' '.Auth::user()->User_LName !!}';
        $(document).ready(function () {
            localStorage.removeItem('MergeKeys');

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
@stop
