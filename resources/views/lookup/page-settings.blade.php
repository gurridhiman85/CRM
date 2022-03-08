<style>
    button.ds-c4:hover {
        background-color: #3ea6d0;
        color: #fff;
    }
    button.ds-c4 {
        color: #5f93b2;
        background-color: #bfe6f6;
        border-color: #dae0e5
    }

    .dropdown-toggle::after {
        color: #5f93b2;
    }

    .asFstTd{
        padding: 4px 9px;
        font-size: 13px;
        border: 1px solid #d0d0d0;
        text-indent: 1px;
        height: 21px;
        background-color: #e1eeff;
        font-weight: 500;
        text-align: right;
    }

    .asAllTD{
        padding: 4px 9px;
        text-align: right;
        text-indent: 1px;
        direction: rtl;
        color: #000;
        font-weight: 400;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }

    .asParentTDLabel{
        padding-left:9px;
        color:#357EC7;
        font-weight:500;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }
    .asttRow{
        background-color: #f4f4f4;
    }
    .asChildTDLabel{
        padding-left:18px;
        color:#357EC7;
        font-weight:300;
        font-size: .76563rem;
        border: 1px solid #d0d0d0;
    }

</style>
<form class="ajax-Form" action="lookup/savepagesettings" method="post">
    {!! csrf_field() !!}
    <div class="row">
        <div class="after-filter mt-1"></div>
    </div>
    <div class="row mb-2" style="border-bottom: 1px solid #dee2e6;">
        <div class="col-md-8">
            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14" role="tablist">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#pscontact" role="tab" aria-selected="true">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down">Contact</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#pssummary" role="tab" aria-selected="false">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down">Activity Summary</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="btn-toolbar pull-right mr-2" role="toolbar" aria-label="Toolbar with button groups">
                <div class="all-pagination pt-2 pr-2 sub-pagination"></div>
                <div class="input-group">
                    <button type="button" onclick="properties();" href="javascript:void(0);" title="Properties" class="btn btn-light font-16" style="float: right;box-shadow: none;"><i class="fas fa-cog ds-c"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-content p-0" style="padding-left: 12px !important; display: contents !important;">
        <div id="pscontact" class="tab-pane active" style="padding-left: 12px !important;">
            <div class="row">
                <div class="col-md-2 border properties d-none">
                </div>
                <div class="col-md-12 contentbox">

                    <div class="divTable">
                        <div class="divTableBody">

                            <!-------------------- Name and Address  - Start -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[1][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" style="color: #357EC7;
font-weight: 500;" placeholder="R1:C1">
                                </div>
                                <div class="divTableCell" style="width: 25.5%;text-align: center;color: #ffffff;"><b>Person 1</b></div>
                                <div class="divTableCell" style="width: 14%;text-align: center;"></div>
                                <div class="divTableCell" style="width: 25.5%;text-align: center;color: #ffffff;"><b>Person 2</b></div>
                                <div class="divTableCell" style="width: 12%;">
                                </div>
                                <div class="divTableCell" style="width: 35%;text-align: right;"><!--<b>Household</b>-->


                                </div>

                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[2][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C1">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p1 form-control form-control-sm d-none R2_C1_fieldbox"></div>
                                    <button type="button" class="btn btn-light font-10 R2_C1_btn" title="Add" onclick="addField('R2_C1')"><i class="fas fa-plus-circle ds-c font-16"></i></button>
                                    <!--<input type="text" class="t8 dis txtDS_MKC_ContactID" name="txtDS_MKC_ContactID">-->
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[2][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C2">

                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p2 dis txtds_mkc_householdid form-control form-control-sm"></div>
                                </div>
                                <div class="divTableCell">
                                   <input type="text" name="R[2][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R2:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" style=" font-size:10px; width:504px" class="t6 h dis txtextendedname form-control form-control-sm" name="txtextendedname">
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtsalutation form-control form-control-sm" name="salutation">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 txtsalutation2 form-control form-control-sm" name="salutation2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[3][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R3:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h dis txtlettername form-control form-control-sm"
                                                                 name="lettername"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtdharmaname form-control form-control-sm" name="dharmaname">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 txtdharmaname2 form-control form-control-sm" name="dharmaname2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[4][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R4:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h txtaddress form-control form-control-sm"
                                                                 name="address"></div>
                            </div>

                            <div class="divTableRow">

                                <div class="divTableCell">
                                    <input type="text" name="R[5][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtfirstname form-control form-control-sm" name="firstname">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[5][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 txtfirstname2 form-control form-control-sm" name="firstname2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[5][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R5:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h txtcity form-control form-control-sm"
                                                                 name="city"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[6][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtmiddlename form-control form-control-sm" name="middlename">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[6][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 txtmiddlename2 form-control form-control-sm" name="middlename2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[6][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R6:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text"
                                           style=" font-size:10px; width:504px"
                                           class="t6 h txtstate form-control form-control-sm" name="state">
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[7][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtlastname form-control form-control-sm" name="lastname">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[7][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 txtlastname2 form-control form-control-sm" name="lastname2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[7][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R7:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h txtzip form-control form-control-sm" name="zip"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[8][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtsuffix form-control form-control-sm" name="suffix">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[8][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 txtsuffix2 form-control form-control-sm" name="suffix2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[8][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R8:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h txtcountry form-control form-control-sm" name="country"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[9][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C1">
                                </div>
                                <div class="divTableCell">

                                    <select class="t8 p1 txtgender form-control form-control-sm" name="gender">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non-binary">Non-binary</option>
                                        <option value="Transgender">Transgender</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[9][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C2">
                                </div>
                                <div class="divTableCell"><select class="t8 p2 txtgender2 form-control form-control-sm" name="gender2">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non-binary">Non-binary</option>
                                        <option value="Transgender">Transgender</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[9][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R9:C3">
                                </div>
                                <div class="divTableCell"><input type="text"
                                                                 style=" font-size:10px; width:504px"
                                                                 class="t6 h txtcompany form-control form-control-sm" name="company"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[10][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 txtjobtitle form-control form-control-sm" name="jobtitle">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[10][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C2">
                                </div>
                                <div class="divTableCell">
                                    <div class="input t8 p2 dis txtds_mkc_household_num form-control form-control-sm"></div>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[10][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R10:C3">
                                </div>
                                <div class="divTableCell">

                                    <select class="t6 h txtcompanyinclude form-control form-control-sm" name="companyinclude">
                                        <option value=""></option>
                                        <option selected value="Include">Include</option>
                                        <option value="Exclude">Exclude</option>
                                    </select>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[11][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p1 dis txtdflname form-control form-control-sm" name="DFLName">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[11][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 p2 dis txtdflname2 form-control form-control-sm" name="DFLName2">
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[11][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R11:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text"
                                           style=" font-size:10px; width:504px"
                                           class="t6 dis h txtaddressquality form-control form-control-sm"
                                           name="addressquality">
                                </div>
                            </div>

                            <!-------------------- Name and Address  - End -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!-------------------- Contactability Start -------------->
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;"><input type="text" name="R13:C1" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" placeholder="R13:C1">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[14][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t7 p2 txtphone form-control form-control-sm" name="phone">
                                    <select class="sel2  txtphone_type form-control form-control-sm" name="phone_type">
                                        <option selected value=""></option>
                                        <option value="Home">Home</option>
                                        <option value="Work">Work</option>
                                        <option value="Cell">Cell</option>
                                        <option value="Fax">Fax</option>
                                    </select>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[14][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C2">
                                </div>
                                <div class="divTableCell">

                                    <input type="text" class="t7 p2 txtphone2 form-control form-control-sm" name="phone2">
                                    <select class="sel2 txtphone2_type form-control form-control-sm" name="phone2_type">
                                        <option selected value=""></option>
                                        <option value="Home">Home</option>
                                        <option value="Work">Work</option>
                                        <option value="Cell">Cell</option>
                                        <option value="Fax">Fax</option>
                                    </select>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[14][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R14:C3">
                                </div>
                                <div class="divTableCell">
                                    <select class="sel2 h txtopt_mail form-control form-control-sm" name="opt_mail" style="font-size: 10px; width: 26%; ">
                                        <option selected value="">Select Opt Status</option>
                                        <option value="Optin">Optin</option>
                                        <option value="Optout">Optout</option>
                                    </select>
                                    <select class="sel2 h txtmail_status form-control form-control-sm" name="mail_status" style="font-size: 10px; width: 73%; ">
                                        <option selected value="">Select Return Status</option>
                                        <option value="Returned">Returned</option>
                                        <option value="Updated">Updated</option>
                                    </select>
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[15][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t7 p1 txtemail form-control form-control-sm" name="email">
                                    <select class="sel2 p1 txtemail_status form-control form-control-sm" name="email_status">
                                        <option selected value=""></option>
                                        <option value="Optin">Optin</option>
                                        <option value="Optout">Optout</option>
                                        <option disabled value="Unsubscribed">Unsubscribed</option>
                                        <option disabled value="Confirmed">Confirmed</option>
                                        <option disabled value="Active">Active</option>
                                        <option disabled value="Awaiting confirmation">Awaiting Confirmation</option>
                                    </select>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[15][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t7 p1 txtemail2 form-control form-control-sm" name="email2">
                                    <select class="sel2 p1 txtemail2_status form-control form-control-sm" name="email2_status">
                                        <option selected value=""></option>
                                        <option value="Optin">Optin</option>
                                        <option value="Optout">Optout</option>
                                        <option disabled value="Unsubscribed">Unsubscribed</option>
                                        <option disabled value="Confirmed">Confirmed</option>
                                        <option disabled value="Active">Active</option>
                                        <option disabled value="Awaiting confirmation">Awaiting Confirmation</option>
                                    </select>
                                </div>



                                <div class="divTableCell">
                                    <input type="text" name="R[15][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R15:C3">
                                </div>
                                <div class="divTableCell">
                                    <select class="t6 h txtsuppression form-control form-control-sm" name="suppression" style="font-size: 10px; width: 26%; ">
                                        <option value="">Select</option>
                                        <option value="Deceased">Deceased</option>
                                        <option value="Optout">Optout</option>
                                        <option value="Remove">Remove</option>
                                        <option value="Prison">Prison</option>
                                        <option value="Mail_Resident">Mail_Resident</option>
                                        <option value="Other">Other</option>
                                        <option value="None">None</option>
                                    </select>
                                    <input type="text" style="font-size: 10px; width: 73%; " class="t6 dis h txtemail_optout_reason form-control form-control-sm" name="company" autocomplete="off">
                                </div>

                                {{--<div class="divTableCell"><label class="l1">Contactable</label></div>
                                <div class="divTableCell">
                                    <input class="dis h txtcontactable form-control form-control-sm" name="contactable" style="font-size: 10px; width: 32.9%; background-color: rgb(239, 246, 255);">
                                    <input class="dis h txtmailable form-control form-control-sm" name="mailable" style="font-size: 10px; width: 32.9%; background-color: rgb(239, 246, 255);">
                                    <input class="dis h txtemailable form-control form-control-sm" name="emailable" style="font-size: 10px; width: 32.9%; background-color: rgb(239, 246, 255);">
                                </div>--}}
                            </div>

                        {{--<div class="divTableRow">
                            <div class="divTableCell"><label class="l1">Suppression</label></div>
                            <div class="divTableCell">
                                <select class="t8 p1 txtsuppression form-control form-control-sm" name="suppression">
                                    <option value="">Select</option>
                                    <option value="Deceased">Deceased</option>
                                    <option value="Optout">Optout</option>
                                    <option value="Remove">Remove</option>
                                    <option value="Prison">Prison</option>
                                    <option value="Mail_Resident">Mail_Resident</option>
                                    <option value="Other">Other</option>
                                    <option value="None">None</option>
                                </select>
                            </div>

                            <div class="divTableCell"><label class="l1">Offer Ride</label></div>
                            <div class="divTableCell"><input type="text"
                                                             style=" font-size:10px;"
                                                             class="t8 p2 txtarrival form-control form-control-sm"
                                                             name="arrival"></div>

                            <div class="divTableCell"><label class="l1">Need Ride</label></div>
                            <div class="divTableCell">
                                <input type="text"
                                       style="font-size: 10px; width: 504px; background-color: rgb(239, 246, 255);"
                                       class="t6 h txttransportation form-control form-control-sm"
                                       name="transportation">
                            </div>


                        </div>--}}

                        <!-------------------- Contactability End -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!--------------- Touch - Start --------------------->
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[16][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" placeholder="R16:C1">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[17][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C1">
                                </div>
                                <div class="divTableCell">
                                    <select class="t7 sel2 txttouchstatus form-control form-control-sm" name="touchstatus">
                                        <option value=""></option>
                                        <option value="Assigned">Assigned</option>
                                        <option value="Spoke on Phone">Spoke on Phone</option>
                                        <option value="Left Voicemail">Left Voicemail</option>
                                        <option value="Could not leave Voicemail">Could not leave Voicemail</option>
                                        <option value="Phone not in service">Phone not in service</option>
                                        <option value="Phone belongs to someone else">Phone belongs to someone else</option>
                                        <option  value="Suppressed">Suppressed</option>
                                    </select>
                                    <select class="sel2 txttouchchannel form-control form-control-sm" name="touchchannel">
                                        <option selected value=""></option>
                                        <option value="Phone">Phone</option>
                                        <option value="Email">Email</option>
                                        <option value="Direct Mail">Direct Mail</option>
                                        <option value="In-Person">In-Person</option>
                                    </select>
                                </div>


                                <div class="divTableCell">
                                    <input type="text" name="R[17][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C2">
                                </div>
                                @php
                                    $touchcampaigns = DB::select("SELECT DISTINCT(touchcampaign) as touchcampaign FROM touch");
                                @endphp
                                <div class="divTableCell">
                                    <div class="form-group row">
                                        <div class="col-sm-7 pl-2 pr-0">
                                            <select class=" txttouchcampaign form-control form-control-sm" name="touchcampaign">
                                                <option selected="" value=""></option>
                                                @if(count($touchcampaigns))
                                                    @foreach($touchcampaigns as $touchcampaign)
                                                        <option value="{{ $touchcampaign->touchcampaign }}">{{ $touchcampaign->touchcampaign }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-4 pl-1 pr-0">
                                            <div class="input-group">
                                                <input type="text" class="txttouchdate form-control form-control-sm js-datepicker" name="touchdate" style="height: 28px !important;" autocomplete="off">
                                                <div class="input-group-append">
                                                    <span class="input-group-text t8" onclick="$('[name=lasttouchdate]').trigger('focus');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[17][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R17:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" style=" font-size:10px; width:504px" class="t6 h txttouchnotes form-control form-control-sm" name="touchnotes" autocomplete="off">
                                </div>
                            </div>

                            <!--------------- Touch - End ----------------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!-------------------- Segment - Start -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[18][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" placeholder="R18:C1">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[19][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R19:C1">
                                </div>
                                <div class="divTableCell">
                                    <div class="input-group" style="width: 95% !important;">
                                        <input type="text" class="t8 txtfirstsesshindate form-control form-control-sm js-datepicker" name="first_sesshin_date" style="height: 28px !important;">
                                        <div class="input-group-append">
                                            <span class="input-group-text t8" onclick="$('[name=first_sesshin_date]').trigger('focus');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[19][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R19:C2">
                                </div>
                                <div class="divTableCell">
                                    <div class="input-group" style="width: 95% !important;">
                                        <input type="text" class="t8 txtjukai_date form-control form-control-sm js-datepicker" name="jukai_date" style="height: 28px !important;">
                                        <div class="input-group-append">
                                            <span class="input-group-text t8" onclick="$('[name=jukai_date]').trigger('focus');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[19][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R19:C3">
                                </div>
                                <div class="divTableCell">
                                    <div class="input-group">
                                        <input type="text" style=" font-size:10px;height: 28px !important;" class="t6 txtordainment_date form-control form-control-sm js-datepicker" name="ordainment_date">
                                        <div class="input-group-append">
                                            <span class="input-group-text t8" onclick="$('[name=ordainment_date]').trigger('focus');"><i class="fas fa-calendar-alt font-14 ds-c"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divTableRow">

                                <div class="divTableCell">
                                    <input type="text" name="R[20][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R20:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 dis txtzss_segment form-control form-control-sm" name="zss_segment">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[20][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R20:C2">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 dis txtmembersegment form-control form-control-sm" name="membersegment">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[20][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R20:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" style=" font-size:10px; width:504px" class="t6 dis txtemailsegment form-control form-control-sm" name="ds_mkc_source_feed">
                                </div>
                            </div>

                            <div class="divTableRow">
                                <div class="divTableCell">
                                    <input type="text" name="R[21][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R21:C1">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" class="t8 dis txtdonorsegment form-control form-control-sm" name="donorsegment">
                                </div>

                                <div class="divTableCell">
                                    <input type="text" name="R[21][C2]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R21:C2">
                                </div>
                                <div class="divTableCell">

                                    <input type="text" class="t8 dis txteventsegment form-control form-control-sm" name="eventsegment">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" name="R[21][C3]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R21:C3">
                                </div>
                                <div class="divTableCell">
                                    <input type="text" style=" font-size:10px; width:504px" class="t6 dis txtlifecyclesegment form-control form-control-sm" name="lifecyclesegment">
                                </div>
                            </div>

                            <!-------------------- Segment - End -------------->

                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 14%;color: #ffffff;
font-weight: bold;padding: 3px 4px !important;">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                            <!-------------------- Notes - Start -------------->

                            <div class="divTableRow" style="height: 14px !important;">
                                <div class="divTableCell" style="width: 14%;color: #357EC7;
font-weight: 500;padding: 3px 4px !important;">
                                    <input type="text" name="R[22][C1]" class="form-control form-control-sm font-14 border-0 pl-0 pr-0" placeholder="R22:C1">
                                </div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                                <div class="divTableCell"></div>
                            </div>

                        </div>
                    </div>
                    <div class="divTable">
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell" style="width: 10.8% !important;
    padding: 3px 0px !important;">
                                    <input type="text" name="R[23][C1]" class="form-control form-control-sm font-12 border-0 pl-0 pr-0" placeholder="R23:C1">
                                </div>
                                <div class="divTableCell" style="width: 93% !important;">
                                    <input type="text" style=" font-size:10px; height:50px;width: 100%;" class="txtnotes form-control" name="notes">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-------------------- Notes - End -------------->

            <div class="btn-toolbar mt-2 mr-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                <div class="input-group pull-right">
                    <button type="button" class="btn btn-info font-12 s-f" title="Save Contact" id="updateContactBtn">Save</button>
                </div>
            </div>
        </div>
        <div id="pssummary" class="tab-pane" style="padding-left: 12px !important;">
            <table id="detailTable" cellspacing="6" style=" margin: 0pt 0pt;">
                <tbody>
                <tr style="height: 24px;">
                    <td class="asFstTd" style="text-align: left !important;width: 13%;">Metric</td>
                    <td class="asFstTd">Lifetime</td>
                    <td class="asFstTd">Last 5 Years</td>
                    <td class="asFstTd">Last 6 Months</td>
                    <td class="asFstTd">Current Year</td>
                    <td class="asFstTd">Prior Year 1</td>
                    <td class="asFstTd">Prior Year 2</td>
                    <td class="asFstTd">Prior Year 3</td>
                    <td class="asFstTd">Prior Year 4</td>

                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">Total Spend Amount</td>
                    <td class="asAllTD asttRow txtlife2date_spendamt"></td>
                    <td class="asAllTD asttRow txtlast_5yrs_spendamt"></td>
                    <td class="asAllTD asttRow txtlast_6mth_spendamt"></td>
                    <td class="asAllTD asttRow txtcurrentyr_spendamt"></td>
                    <td class="asAllTD asttRow txtprior_yr1_spendamt"></td>
                    <td class="asAllTD asttRow txtprior_yr2_spendamt"></td>
                    <td class="asAllTD asttRow txtprior_yr3_spendamt"></td>
                    <td class="asAllTD asttRow txtprior_yr4_spendamt"></td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">Gift $</td>
                    <td class="asAllTD txtlife2date_giftsamt"></td>
                    <td class="asAllTD txtlast_5yrs_giftsamt"></td>
                    <td class="asAllTD txtlast_6mth_giftsamt"></td>
                    <td class="asAllTD txtcurrentyr_giftsamt"></td>
                    <td class="asAllTD txtprior_yr1_giftsamt"></td>
                    <td class="asAllTD txtprior_yr2_giftsamt"></td>
                    <td class="asAllTD txtprior_yr3_giftsamt"></td>
                    <td class="asAllTD txtprior_yr4_giftsamt"></td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">Membership $</td>

                    <td class="asAllTD txtlife2date_membramt"></td>
                    <td class="asAllTD txtlast_5yrs_membramt"></td>
                    <td class="asAllTD txtlast_6mth_membramt"></td>
                    <td class="asAllTD txtcurrentyr_membramt"></td>
                    <td class="asAllTD txtprior_yr1_membramt"></td>
                    <td class="asAllTD txtprior_yr2_membramt"></td>
                    <td class="asAllTD txtprior_yr3_membramt"></td>
                    <td class="asAllTD txtprior_yr4_membramt"></td>

                </tr>

                <tr>
                    <td class="asChildTDLabel">Event $</td>

                    <td class="asAllTD txtlife2date_eventamt"></td>
                    <td class="asAllTD txtlast_5yrs_eventamt"></td>
                    <td class="asAllTD txtlast_6mth_eventamt"></td>
                    <td class="asAllTD txtcurrentyr_eventamt"></td>
                    <td class="asAllTD txtprior_yr1_eventamt"></td>
                    <td class="asAllTD txtprior_yr2_eventamt"></td>
                    <td class="asAllTD txtprior_yr3_eventamt"></td>
                    <td class="asAllTD txtprior_yr4_eventamt"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Retail $</td>

                    <td class="asAllTD txtlife2date_rtailamt"></td>
                    <td class="asAllTD txtlast_5yrs_rtailamt"></td>
                    <td class="asAllTD txtlast_6mth_rtailamt"></td>
                    <td class="asAllTD txtcurrentyr_rtailamt"></td>
                    <td class="asAllTD txtprior_yr1_rtailamt"></td>
                    <td class="asAllTD txtprior_yr2_rtailamt"></td>
                    <td class="asAllTD txtprior_yr3_rtailamt"></td>
                    <td class="asAllTD txtprior_yr4_rtailamt"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Rental $</td>

                    <td class="asAllTD txtlife2date_rentlamt"></td>
                    <td class="asAllTD txtlast_5yrs_rentlamt"></td>
                    <td class="asAllTD txtlast_6mth_rentlamt"></td>
                    <td class="asAllTD txtcurrentyr_rentlamt"></td>
                    <td class="asAllTD txtprior_yr1_rentlamt"></td>
                    <td class="asAllTD txtprior_yr2_rentlamt"></td>
                    <td class="asAllTD txtprior_yr3_rentlamt"></td>
                    <td class="asAllTD txtprior_yr4_rentlamt"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Miscellaneous $</td>

                    <td class="asAllTD txtlife2date_misclamt"></td>
                    <td class="asAllTD txtlast_5yrs_misclamt"></td>
                    <td class="asAllTD txtlast_6mth_misclamt"></td>
                    <td class="asAllTD txtcurrentyr_misclamt"></td>
                    <td class="asAllTD txtprior_yr1_misclamt"></td>
                    <td class="asAllTD txtprior_yr2_misclamt"></td>
                    <td class="asAllTD txtprior_yr3_misclamt"></td>
                    <td class="asAllTD txtprior_yr4_misclamt"></td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">Total Activities</td>

                    <td class="asAllTD asttRow txtlife2date_nactivty"></td>
                    <td class="asAllTD asttRow txtlast_5yrs_nactivty"></td>
                    <td class="asAllTD asttRow txtlast_6mth_nactivty"></td>
                    <td class="asAllTD asttRow txtcurrentyr_nactivty"></td>
                    <td class="asAllTD asttRow txtprior_yr1_nactivty"></td>
                    <td class="asAllTD asttRow txtprior_yr2_nactivty"></td>
                    <td class="asAllTD asttRow txtprior_yr3_nactivty"></td>
                    <td class="asAllTD asttRow txtprior_yr4_nactivty"></td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">Total Paid Events Attended</td>

                    <td class="asAllTD asttRow txtlife2date_npaidevt"></td>
                    <td class="asAllTD asttRow txtlast_5yrs_npaidevt"></td>
                    <td class="asAllTD asttRow txtlast_6mth_npaidevt"></td>
                    <td class="asAllTD asttRow txtcurrentyr_npaidevt"></td>
                    <td class="asAllTD asttRow txtprior_yr1_npaidevt"></td>
                    <td class="asAllTD asttRow txtprior_yr2_npaidevt"></td>
                    <td class="asAllTD asttRow txtprior_yr3_npaidevt"></td>
                    <td class="asAllTD asttRow txtprior_yr4_npaidevt"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Kessei</td>

                    <td class="asAllTD txtlife2date_nkessei_"></td>
                    <td class="asAllTD txtlast_5yrs_nkessei_"></td>
                    <td class="asAllTD txtlast_6mth_nkessei_"></td>
                    <td class="asAllTD txtcurrentyr_nkessei_"></td>
                    <td class="asAllTD txtprior_yr1_nkessei_"></td>
                    <td class="asAllTD txtprior_yr2_nkessei_"></td>
                    <td class="asAllTD txtprior_yr3_nkessei_"></td>
                    <td class="asAllTD txtprior_yr4_nkessei_"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">ZSS Sesshins</td>

                    <td class="asAllTD txtlife2date_nzss_ses"></td>
                    <td class="asAllTD txtlast_5yrs_nzss_ses"></td>
                    <td class="asAllTD txtlast_6mth_nzss_ses"></td>
                    <td class="asAllTD txtcurrentyr_nzss_ses"></td>
                    <td class="asAllTD txtprior_yr1_nzss_ses"></td>
                    <td class="asAllTD txtprior_yr2_nzss_ses"></td>
                    <td class="asAllTD txtprior_yr3_nzss_ses"></td>
                    <td class="asAllTD txtprior_yr4_nzss_ses"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Open Sesshins</td>

                    <td class="asAllTD txtlife2date_nopn_ses"></td>
                    <td class="asAllTD txtlast_5yrs_nopn_ses"></td>
                    <td class="asAllTD txtlast_6mth_nopn_ses"></td>
                    <td class="asAllTD txtcurrentyr_nopn_ses"></td>
                    <td class="asAllTD txtprior_yr1_nopn_ses"></td>
                    <td class="asAllTD txtprior_yr2_nopn_ses"></td>
                    <td class="asAllTD txtprior_yr3_nopn_ses"></td>
                    <td class="asAllTD txtprior_yr4_nopn_ses"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Intro to Zen</td>

                    <td class="asAllTD txtlife2date_nitz_wkd"></td>
                    <td class="asAllTD txtlast_5yrs_nitz_wkd"></td>
                    <td class="asAllTD txtlast_6mth_nitz_wkd"></td>
                    <td class="asAllTD txtcurrentyr_nitz_wkd"></td>
                    <td class="asAllTD txtprior_yr1_nitz_wkd"></td>
                    <td class="asAllTD txtprior_yr2_nitz_wkd"></td>
                    <td class="asAllTD txtprior_yr3_nitz_wkd"></td>
                    <td class="asAllTD txtprior_yr4_nitz_wkd"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">All-Day Sits</td>

                    <td class="asAllTD txtlife2date_nall_day"></td>
                    <td class="asAllTD txtlast_5yrs_nall_day"></td>
                    <td class="asAllTD txtlast_6mth_nall_day"></td>
                    <td class="asAllTD txtcurrentyr_nall_day"></td>
                    <td class="asAllTD txtprior_yr1_nall_day"></td>
                    <td class="asAllTD txtprior_yr2_nall_day"></td>
                    <td class="asAllTD txtprior_yr3_nall_day"></td>
                    <td class="asAllTD txtprior_yr4_nall_day"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Zazenkai</td>

                    <td class="asAllTD txtlife2date_nsitting"></td>
                    <td class="asAllTD txtlast_5yrs_nsitting"></td>
                    <td class="asAllTD txtlast_6mth_nsitting"></td>
                    <td class="asAllTD txtcurrentyr_nsitting"></td>
                    <td class="asAllTD txtprior_yr1_nsitting"></td>
                    <td class="asAllTD txtprior_yr2_nsitting"></td>
                    <td class="asAllTD txtprior_yr3_nsitting"></td>
                    <td class="asAllTD txtprior_yr4_nsitting"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">ZSS Programs</td>

                    <td class="asAllTD txtlife2date_nzss_pgm"></td>
                    <td class="asAllTD txtlast_5yrs_nzss_pgm"></td>
                    <td class="asAllTD txtlast_6mth_nzss_pgm"></td>
                    <td class="asAllTD txtcurrentyr_nzss_pgm"></td>
                    <td class="asAllTD txtprior_yr1_nzss_pgm"></td>
                    <td class="asAllTD txtprior_yr2_nzss_pgm"></td>
                    <td class="asAllTD txtprior_yr3_nzss_pgm"></td>
                    <td class="asAllTD txtprior_yr4_nzss_pgm"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Open Programs</td>

                    <td class="asAllTD txtlife2date_nopn_pgm"></td>
                    <td class="asAllTD txtlast_5yrs_nopn_pgm"></td>
                    <td class="asAllTD txtlast_6mth_nopn_pgm"></td>
                    <td class="asAllTD txtcurrentyr_nopn_pgm"></td>
                    <td class="asAllTD txtprior_yr1_nopn_pgm"></td>
                    <td class="asAllTD txtprior_yr2_nopn_pgm"></td>
                    <td class="asAllTD txtprior_yr3_nopn_pgm"></td>
                    <td class="asAllTD txtprior_yr4_nopn_pgm"></td>
                </tr>

                <tr style="height: 24px;">
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                    <td class="asAllTD"></td>
                </tr>

                <tr>
                    <td class="asParentTDLabel asttRow">Total Free Events Attended</td>

                    <td class="asAllTD asttRow txtlife2date_nfreeevt"></td>
                    <td class="asAllTD asttRow txtlast_5yrs_nfreeevt"></td>
                    <td class="asAllTD asttRow txtlast_6mth_nfreeevt"></td>
                    <td class="asAllTD asttRow txtcurrentyr_nfreeevt"></td>
                    <td class="asAllTD asttRow txtprior_yr1_nfreeevt"></td>
                    <td class="asAllTD asttRow txtprior_yr2_nfreeevt"></td>
                    <td class="asAllTD asttRow txtprior_yr3_nfreeevt"></td>
                    <td class="asAllTD asttRow txtprior_yr4_nfreeevt"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Triple Sangha</td>

                    <td class="asAllTD txtlife2date_nzm3fold"></td>
                    <td class="asAllTD txtlast_5yrs_nzm3fold"></td>
                    <td class="asAllTD txtlast_6mth_nzm3fold"></td>
                    <td class="asAllTD txtcurrentyr_nzm3fold"></td>
                    <td class="asAllTD txtprior_yr1_nzm3fold"></td>
                    <td class="asAllTD txtprior_yr2_nzm3fold"></td>
                    <td class="asAllTD txtprior_yr3_nzm3fold"></td>
                    <td class="asAllTD txtprior_yr4_nzm3fold"></td>
                </tr>

                <tr>
                    <td class="asChildTDLabel">Other Zoom Meetings</td>

                    <td class="asAllTD txtlife2date_nzoom_ot"></td>
                    <td class="asAllTD txtlast_5yrs_nzoom_ot"></td>
                    <td class="asAllTD txtlast_6mth_nzoom_ot"></td>
                    <td class="asAllTD txtcurrentyr_nzoom_ot"></td>
                    <td class="asAllTD txtprior_yr1_nzoom_ot"></td>
                    <td class="asAllTD txtprior_yr2_nzoom_ot"></td>
                    <td class="asAllTD txtprior_yr3_nzoom_ot"></td>
                    <td class="asAllTD txtprior_yr4_nzoom_ot"></td>
                </tr>
                <tr>
                    <td><input type="hidden" name="hid"></td>
                    <td colspan="11" align="right"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    function properties() {
        if($('.properties').hasClass('d-none')){
            $('.properties').removeClass('d-none')
            $('.contentbox').removeClass('col-md-12').addClass('col-md-10')
        }else{
            $('.properties').addClass('d-none')
            $('.contentbox').removeClass('col-md-10').addClass('col-md-12')
        }
    }

    function addField(cls) {
        properties();
        $('.' + cls + '_fieldbox').removeClass('d-none');
        $('.' + cls + '_btn').addClass('d-none');
    }
</script>
