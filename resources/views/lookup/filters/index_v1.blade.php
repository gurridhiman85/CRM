<div class="form-body">
    <div class="card-body pt-0">
        <div class="row pt-0">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">ZSS Segment</label>

                    <select name="ZSS_Segment" class="form-control" id="zss_segment-filter" multiple="multiple" data-placeholder="Select Values">
                        @foreach($ZSS_Segments as $ZSS_Segment)
                            <option value="{!! $ZSS_Segment->ZSS_Segment !!}">{!! $ZSS_Segment->ZSS_Segment !!}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <!--/span-->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Member Segment</label>
                    <select name="MemberSegment" id="member_segment-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($MemberSegments as $MemberSegment)
                            <option value="{!! $MemberSegment->MemberSegment !!}">{!! $MemberSegment->MemberSegment !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Donor Segment</label>
                    <select name="DonorSegment" id="donor_segment-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($DonorSegments as $DonorSegment)
                            <option value="{!! $DonorSegment->DonorSegment !!}">{!! $DonorSegment->DonorSegment !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Event Segment</label>
                    <select name="EventSegment" id="event_segment-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($EventSegments as $EventSegment)
                            <option value="{!! $EventSegment->EventSegment !!}">{!! $EventSegment->EventSegment !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--/span-->
        </div>

        <div class="row">

            <!--/span-->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Lifecycle Segment</label>
                    <select name="LifecycleSegment" id="lifecycle_segment-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($LifecycleSegments as $LifecycleSegment)
                            <option value="{!! $LifecycleSegment->LifecycleSegment !!}">{!! $LifecycleSegment->LifecycleSegment !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Address Quality</label>
                    <select name="AddressQuality" id="address-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($AddressQualities as $AddressQuality)
                            <option value="{!! $AddressQuality->AddressQuality !!}">{!! $AddressQuality->AddressQuality !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--/span-->

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Country</label>
                    <select name="country" id="country-filter" class="form-control" multiple="multiple" data-placeholder="Select Values">
                        @foreach($countries as $country)
                            <option value="{!! $country->country !!}">{!! $country->country !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Contact</label>
                    <input type="text" name="Extendedname" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>
        </div>


        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Company</label>
                    <input type="text" name="Company" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Address</label>
                    <input type="text" name="Address" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" name="Email" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Phone</label>
                    <input type="text" name="Phone" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Last 5yr Gift $</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Last_5Yrs_GiftsAmt_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Last_5Yrs_GiftsAmt" class="form-control" placeholder="Enter Last 5yr Gift $ " data-placeholder="Last 5yr Gift $ ">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">LifeTime Gift $</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Life2date_GiftsAmt_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Life2date_GiftsAmt" class="form-control" placeholder="Enter Life Gift $ " data-placeholder="LifeTime Gift $ ">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Last 5yr Spend $</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Last_5Yrs_SpendAmt_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Last_5Yrs_SpendAmt" class="form-control" placeholder="Enter Last 5yr Spend $ " data-placeholder="Last 5yr Spend $ ">
                        </div>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">LifeTime Spend $</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Life2date_SpendAmt_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Life2date_SpendAmt" class="form-control" placeholder="Enter Life Spend $ " data-placeholder="LifeTime Spend $ ">
                        </div>
                    </div>
                </div>
            </div>


            <!--/span-->
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Days Since Last Visit</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Dayssincelastvisit_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Dayssincelastvisit" class="form-control" placeholder="Enter Days - Last Visit " data-placeholder="Days Since Last Visit ">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Years Since 1st Visit</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="Yearssincefirstvisit_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="Yearssincefirstvisit" class="form-control" placeholder="Enter Years - 1st Visit " data-placeholder="Years Since 1st Visit ">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Days Since 1st Create</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="DaysSince1stCreate_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="DaysSince1stCreate" class="form-control" placeholder="Enter Days - 1st Create " data-placeholder="Days Since 1st Create ">
                        </div>
                    </div>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Days Since Last Update</label>
                    <div class="row">
                        <div class="col-md-3 p-r-0">
                            <select name="DaysSinceLastUpdate_op" class="form-control font-16" data-notallowed="true">
                                <option value=">">&gt;</option>
                                <option selected value="<">&lt;</option>
                            </select>
                        </div>
                        <div class="col-md-9 p-l-0">
                            <input type="text" name="DaysSinceLastUpdate" class="form-control" placeholder="Enter Days - Last Update " data-placeholder="Days Since Last Update ">
                        </div>
                    </div>
                </div>
            </div>


            <!--/span-->
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Activity Cat 1</label>
                    <input type="text" name="ActivityCat1" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Activity Cat 2</label>
                    <input type="text" name="ActivityCat2" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Activity Type</label>
                    <input type="text" name="Activity" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Notes</label>
                    <input type="text" name="Notes" class="form-control" placeholder="Enter search string" data-placeholder="">
                </div>
            </div> <!-- Notes -->
        </div>

        <div class="form-actions pull-right">
            <input type="hidden" name="contactids" id="contactid" onemptied="blankMergeData();" onchange="blankMergeData();" class="form-control" placeholder="" data-placeholder="">
            <input type="hidden" name="searchterm" class="form-control" placeholder="" data-placeholder="">
            <button type="submit" class="btn btn-info">Apply</button>
            <button type="button" class="btn border-secondary waves-effect waves-light btn-outline-secondary " onclick="clearFilters();" style="border-color: #dee2e6;">Clear</button>
        </div>
    </div>
</div>