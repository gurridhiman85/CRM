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
<form name="frmCust" class="ajax-Form" action="lookup/save" method="post">
    <div class="row">
        <div class="after-filter mt-1"></div>
    </div>
    <div class="row mb-2" style="border-bottom: 1px solid #dee2e6;">
        <div class="col-md-8">
            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14" role="tablist">

                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#contact" role="tab" aria-selected="true" onclick="$('.s-f').show();$('.SA-pagination').hide();$('#DownloadBtn').attr('data-screen','contact');">
                        <span class="hidden-sm-up"></span>
                        <span class="hidden-xs-down">Contact</span>
                    </a>
                </li>

                @if(isset($add) && !$add)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#summary" role="tab" aria-selected="false" onclick="$('.s-f').hide();$('.SA-pagination').hide();$('#DownloadBtn').attr('data-screen','summary');">
                            <span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down">Activity Summary</span>
                        </a>
                    </li>

                    @foreach($tabs as $tab)
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" data-tabid="{{str_replace(' ','_',$tab)}}" href="#{{str_replace(' ','_',$tab)}}" role="tab" aria-selected="false" onclick="setTab('{{ $tab }}','{{str_replace(' ','_',$tab)}}','{{ $contactid }}');">
                                <span class="hidden-sm-up"></span>
                                <span class="hidden-xs-down">{{ ucfirst($tab) }}</span>
                            </a>
                        </li>
                    @endforeach

                    {{--<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#detail" role="tab" aria-selected="false" onclick="$('.s-f').hide();$('.SA-pagination').show();$('.touch-pagination').hide();$('#DownloadBtn').attr('data-screen','detail');">
                            <span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down">Activity Detail</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#touch" role="tab" aria-selected="false" onclick="$('.s-f').hide();$('.SA-pagination').hide();$('.touch-pagination').show();$('#DownloadBtn').attr('data-screen','touch');">
                            <span class="hidden-sm-up"></span>
                            <span class="hidden-xs-down">Touch</span>
                        </a>
                    </li>--}}
                @endif
            </ul>
        </div>
        <div class="col-md-4">
            <div class="btn-toolbar pull-right mr-2" role="toolbar" aria-label="Toolbar with button groups">
                <div class="all-pagination pt-2 pr-2 sub-pagination"></div>
                <div class="input-group">
                    <button type="button" id="backBtnSecond" onclick="goBack();" href="javascript:void(0);" title="Go Back"  class="btn btn-light border-right-0 font-16 asBtn" style="float: right;box-shadow: none;"><i class="fas fa-arrow-circle-up ds-c"></i></button>

                    @if(isset($add) && !$add)
                        <div class="c-btn" style="display: none;"></div>
                        <div class="btn-group">
                            <button type="button"  title="Download"  class="btn btn-light dropdown-toggle font-16 ds-c" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float: right;box-shadow: none;"><i class="fas fa-download ds-c"></i></button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="downloadCMLink($(this))" data-href="lookup/downloadreport" id="DownloadBtn" data-prefix="" data-screen="contact">Download Tab</a>
                                <a class="dropdown-item ajax-downloadall-link" id="DownloadAllBtn"  href="javascript:void(0)" data-href="lookup/downloadallreports">Download All Tabs</a>
                            </div>
                        </div>
                    @endif
                    <button type="button" class="btn btn-light font-16 s-f" title="Save Contact" onclick="$('#updateContactBtn').trigger('click');"><i class="fas fa-save ds-c" ></i></button>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" id="edit_type" value=""/>
    <input type="hidden" id="selectedCompId">
    <div class="tab-content p-0" style="padding-left: 12px !important; display: contents !important;">
        <div id="contact" class="tab-pane active" style="padding-left: 12px !important;">
            @include('lookup.contact-tab')
			<div class="btn-toolbar mt-2 mr-2" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
				<div class="input-group pull-right">
					<button type="button" class="btn btn-info font-12 s-f" title="Save Contact" id="updateContactBtn">Save Contact</button>
				</div>
			</div>
        </div>
        <div id="summary" class="tab-pane" style="padding-left: 12px !important;">
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

        @foreach($tabs as $tab)
            <div id="{{ str_replace(' ','_',$tab) }}" class="tab-pane custom-tab" style="padding-left: 12px !important;"  role="tabpanel"></div>
        @endforeach
        {{--<div id="detail" class="tab-pane" style="padding-left: 12px !important;">

        </div>
        <div id="touch" class="tab-pane" style="padding-left: 12px !important;">
            <div id="touchDiv" style="display:block;"></div>
        </div>--}}
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#txtsfParentAccountId').click(function () {
            var SPAI = $('#SPAI').attr('href');
            $('#SPAI').attr("target", "_blank");
            window.open($('#SPAI').attr("href") + $.trim($('#txtsfParentAccountId').val()));
            //window.location.href = SPAI;
        });


        /********** Auto Add/Update from second screen - Start *************/

        var edit_timeout = null;
        var editable_field = $('body').find('#contact input,#contact select');



        ACFn.ajax_update_detail = function (F, R) {
            if (R.success) {
               updateContactTab(R);
            }
        }

        $('body').find('#contact input,#contact select').on('keyup change',function(e) {
            console.log(editable_field);
            console.log('------','#' + e.type + '#','--#' + e.target.tagName + '#');
            if((e.type == 'keyup' && e.target.tagName == 'INPUT') || (e.type == 'change' && e.target.tagName == 'SELECT')){
                if($('#customSwitch1').is(':checked') == true){
                    var obj = $(this);
                    var oldVal = obj.val();
                    delay(function(){

                        var name_attr = obj.attr('name');
                        if (typeof name_attr !== typeof undefined && name_attr !== false) {


                            var contactId = $.trim($('.txtds_mkc_contactid').text());
                            var updated_val = obj.val();
                            var edit_type = $('#edit_type').val();
                            console.log("edit_type--",edit_type);

                            var updateAddress = ['address','city','state','zip','country'];
                            if(jQuery.inArray(name_attr, updateAddress) !== -1){ console.log('button clicked');
                                var oldVal = $('.txtmail_status').val();
                                $('.txtmail_status').val('Updated');
                                var updated_val = $('.txtmail_status').val();
                                var name_attr = obj.attr('name');
                                //ActionOnDetails(edit_type,contactId,name_attr,updated_val,oldVal);
                                $('#updateContactBtn').trigger('click');
                                return false;
                            }

                            if(edit_type == 'old'){
                                ActionOnDetails(edit_type,contactId,name_attr,updated_val,oldVal)
                            }else if (edit_type == 'new') {
                                ActionOnDetails(edit_type,contactId,name_attr,updated_val,oldVal)
                            }

                        }else{
                            alert("Name attributes is missing");
                        }
                    }, 1000 );
                }else{
                    var obj = $(this);
                    var name_attr = obj.attr('name');
                    var updateAddress = ['address','city','state','zip','country'];
                    if(jQuery.inArray(name_attr, updateAddress) !== -1){
                        $('.txtmail_status').val('Updated');
                    }
                }
            }
        });

        function ActionOnDetails(edit_type,contactId,name_attr,updated_val,oldVal){
            if(edit_type == 'old'){
                ACFn.sendAjax('lookup/quickedit',
                    'POST',
                    {
                        tablename: 'Contact',
                        recordid: contactId,
                        fieldname: name_attr,
                        texteditor : updated_val,
                        oldtexteditor : oldVal,
                        username : '{{Auth::user()->User_FName.' '.Auth::user()->User_LName}}',
                        _token : '{!! csrf_token() !!}'
                    })
            }else if (edit_type == 'new') {
                ACFn.sendAjax('lookup/quickadd',
                    'POST',
                    {
                        tablename: 'Contact',
                        fieldname: name_attr,
                        texteditor : updated_val,
                        oldtexteditor : oldVal,
                        username : '{{Auth::user()->User_FName.' '.Auth::user()->User_LName}}',
                        _token : '{!! csrf_token() !!}'
                    })
            }
        }

        function updateContactTab(R){
            var is_person2_exist = 0;
            var is_person1_email_exist = 0;
            var is_person2_email_exist = 0;
            $("#updateContactBtn").text('Save Contact');
            $.each(R.response, function(idx, obj) {

                idx = idx.toLowerCase();
                console.log(idx,'------------');
                if($.trim(obj) != null  && $.trim(obj) != ''){

                    if(idx == 'ds_mkc_contactid'){
                        $('.txt'+idx).html('');
                        $('.txt'+idx).html(obj);


                    }else if(idx == 'ds_mkc_household_num'){

                        $('.txt'+idx).html(obj);

                    }else if(idx == 'ds_mkc_householdid'){
                        //console.log(idx+'---('+obj+')');
                        var link = '<a href="javascript:void(0);" onclick="jsfilterfiller($(this))" data-target-id="txtExtendedname">' + obj + '</a>';
                        $('.txt'+idx).html('');
                        $('.txt'+idx).html(link);
                        //$('.txt'+idx).html(obj);

                    }else if(idx == 'phone_type' || idx == 'phone2_type' || idx == 'opt_email' || idx == 'opt_email_sec'){
                        //console.log('ENTER ===='+obj)
                        if(obj != 'TBD'){
                            $('.txt'+idx).val('');
                            $('.txt'+idx).val(obj);
                        }else
                            $('.txt'+idx).val('');

                    }else{
                        if($('.txt'+idx).length && $('.txt'+idx)[0].nodeName == 'TD'){
                            $('.txt'+idx).html('');
                            $('.txt'+idx).html(obj);
                        }else{
                            obj = obj != '1900-01-01' ? obj : '';
                            $('.txt'+idx).val('');
                            $('.txt'+idx).val(obj);
                        }
                    }
                }else if(idx == 'email' || idx == 'email2' || idx == 'email_sec2' || idx == 'phone' || idx == 'phone2' || idx == 'phone2'){
                    //console.log(idx,obj)
                    if($.trim(obj) == null || $.trim(obj) == ''){
                        $('.txt'+idx).val('');
                        //console.log('Enter')
                        setTimeout(function () {
                            if(idx == 'email')
                                $('.txtopt_email').val('')
                            if(idx == 'email2')
                                $('.txtopt_email2').val('');
                            if(idx == 'phone')
                                $('.txtphone_type').val('');
                            if(idx == 'phone2')
                                $('.txtphone2_type').val('');
                        },500)

                    }
                }else if(idx == 'firstname2' || idx == 'lastname2' || idx == 'dharmaname2' || idx == 'middlename2'){

                    $('.txt'+idx).val('');
                    if(obj == "" || obj == null){
                        is_person2_exist++;
                        //console.log(is_person2_exist,idx,obj)
                    }
                }else if(idx == 'company'){
                    $('.txt'+idx).val('');
                    //console.log(idx+"-----------------------","("+obj+")")
                    if($.trim(obj) == "" || obj == null){
                        setTimeout(function () {
                            $('.txtcompanyinclude').val('');
                        },2000)
                    }
                }else{
                    $('.txt' + idx).val('');
                    /*$('.txt' + idx).html('&nbsp;');*/
                    if(idx == 'ds_mkc_household_num'){

                        $('.txt' + idx).html('&nbsp;');

                    }
                    //console.log(obj);
                }

            });

            if(is_person2_exist == 4){  // If firstname2, lastname2,DharmaName2 &Middlename2 is empty then, person2 data will blank
                //$('select.p2').val('');
                //$('input.p2').val('');

            }
            $('.dis').attr('disabled',true);

            /*************** Header Label Start****************************************/
            $('#selectedCompId').val(R.response.DS_MKC_ContactID);

            $('#ContactIDLabel').html("&nbsp;&nbsp;&nbsp;     ContactID:  " + R.response.DS_MKC_ContactID);
            //$('#ContactIDLabel').attr("style", "color:rgb(0, 102, 204);");

            $('#HouseHoldIDLabel').html("&nbsp;&nbsp;&nbsp;     Household ID:  " + R.response.ds_mkc_householdid);
            //$('#HouseHoldIDLabel').attr("style", "color:rgb(0, 102, 204);");

            $('#HouseHoldNameLabel').html(" Extended Name:  " + R.response.LetterName);
            //$('#HouseHoldNameLabel').attr("style", "color:rgb(0, 102, 204);");

            $('#OverallLabelSegment').html(" ZSS Segment:  " + R.response.ZSS_Segment);
            //$('#OverallLabelSegment').attr("style", "color:rgb(0, 102, 204);");

            $('#LifeCycleLabelSegment').html(" Life Cycle Segment:  " + R.response.LifecycleSegment);
        }

        ACFn.ajax_quick_add = function (F, R) {
            if (R.success) {
                ACFn.display_message(R.messageTitle,'','success');
                $('#edit_type').val('old')
                //$('#updateContactBtn').text('Update');
                ACFn.sendAjax('lookup/secondscreen/'+R.contactid,'get',{});

            }
        }

        /********** Auto Add/Update from second screen - End *************/

        /********** Save Manually Add/Update from second screen - Start *************/

        $('#updateContactBtn').on('click',function () {
            var process_type = $('#edit_type').val() == "" ? "old" : $('#edit_type').val();
            var contactid = $.trim($('.txtds_mkc_contactid').text());
            var elementsdata = [];
            $('#contact').find('input, select').each(function() {
                if(typeof $(this).attr('name') !== "undefined" && !$(this).hasClass('dis')){
                    elementsdata.push({
                        name : $(this).attr('name'),
                        value : $.trim($(this).val()),
                    })
                }
            });
            console.log(elementsdata);
            ACFn.sendAjax('lookup/manualsave','POST',
                {
                    pgaction : 'manualsave',
                    contactid: contactid,
                    tablename: 'Contact',
                    process_type : process_type,
                    username : '{{Auth::user()->User_FName.' '.Auth::user()->User_LName}}',
                    _token : '{!! csrf_token() !!}',
                    elementsdata : elementsdata,
                })
        })

        ACFn.ajax_manual_add = function (F, R) {
            if (R.success) {
                ACFn.display_message(R.messageTitle,R.messageTitle,'success');
                //$('#updateContactBtn').text('Update');
                $('#edit_type').val('old')
                ACFn.sendAjax('lookup/secondscreen/'+R.contactid,'get',{});

            }
        }

        $('body .ajax-download-link').on('click', function () { console.log('clicked on download');
            var url = $(this).data('href');
            var screen = $(this).attr('data-screen');
            var prefix = $(this).data('prefix');
            var contactid = $('#selectedCompId').val();
            var filters = getFilters($('#filter_form'));
            ACFn.sendAjax(url,'GET',{
                prefix : prefix,
                screen : screen,
                filters : filters,
                contactid : contactid
            },$(this));
        });

        ACFn.ajax_download_file = function(F,R){
            NProgress.done(true);
            if(R.success){
                window.location.href = R.download_url;
            }
        };

        /********** Save Manually Add/Update from second screen - Start *************/

        $('body .ajax-downloadall-link').on('click', function () { console.log('clicked on download');
            var url = $(this).data('href');
            var contactid = $('#selectedCompId').val();
            var filters = getFilters($('#filter_form'));
            ACFn.sendAjax(url,'GET',{
                filters : filters,
                contactid : contactid
            },$(this));

            setTimeout(function () {
                NProgress.done(true);
            },2000)
        });

    })
</script>
