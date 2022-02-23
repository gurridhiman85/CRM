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
