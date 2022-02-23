<div class="row Proofs deploy ReDeploy TFD PR CR Re-ReDeploy Schedule" style="display:none;">
    <div class="col-md-3">
        <div class="form-group row">
            <label class="control-label pt-2  col-md-4">Campaign ID-HTML</label>
            <div class="col-md-8">
                <select id="input_html_id" name="input_html_id" class="form-control form-control text-box1">
                    <option value="">Select Campaign</option>
                    @if (count($camapigns) > 0) {
                    @foreach ($camapigns as $CampaignId => $CampaignName)
                        <option value="{{ $CampaignId }}">{!! $CampaignId . '-' . $CampaignName !!}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-3 deploy">
        <div class="btn-group" data-bs-toggle="buttons">
            <label class="btn btn-secondary border-0 font-weight-medium deploy">
                <div class="mr-sm-2 form-check">
                    {{--<input type="checkbox" class="material-inputs form-check-input" id="checkbox4">--}}
                    <input type="checkbox"
                           class="material-inputs form-check-input"
                           name="no_seedlist"
                           value="1"
                           id="checkbox4">
                    <label class="form-check-label" for="checkbox4">
                        <span class="d-block d-md-none">1</span>
                        <span class="d-none d-md-block">No Seedlist</span>
                    </label>
                </div>
            </label>
            <label class="btn btn-secondary border-0 font-weight-medium deploy" style="display: none;">
                <div class="mr-sm-2 form-check">
                    {{--<input type="checkbox" class="material-inputs form-check-input" id="checkbox5">--}}
                    <input type="checkbox"
                           class="material-inputs form-check-input"
                           name="is_segment"
                           id="checkbox5"
                           onclick="$('[name=is_segment]').is(':checked') ? $('.bysegmentinputs').show() : $('.bysegmentinputs').hide()"
                           value="1">
                    <label class="form-check-label" for="checkbox5">
                        <span class="d-block d-md-none">2</span>
                        <span class="d-none d-md-block">By Segment</span>
                    </label>
                </div>
            </label>
            <label class="btn btn-secondary border-0 font-weight-medium deploy" style="display: none;">
                <div class="mr-sm-2 form-check Schedulebox" style="display: none">
                    {{--<input type="checkbox" class="material-inputs form-check-input" id="checkbox6">--}}
                    <input type="checkbox"
                           class="material-inputs form-check-input"
                           name="is_schedule"
                           id="checkbox6"
                           onclick="$('[name=is_schedule]').is(':checked') ? $('.Scheduleinputs').show() : $('.Scheduleinputs').hide()"
                           value="1">
                    <label class="form-check-label" for="checkbox6">
                        <span class="d-block d-md-none">3</span>
                        <span class="d-none d-md-block">Schedule</span>
                    </label>
                </div>
            </label>
        </div>
    </div>
    {{--<div class="col-md-1 deploy">
        <label class="custom-control custom-checkbox m-b-0">
            <input type="checkbox"
                   class="custom-control-input checkbox"
                   name="no_seedlist"
                   value="1">
            <span class="custom-control-label  ">No Seedlist</span>
        </label>
    </div>
    <div class="col-md-1 deploy" style="display: none">
        <span class="bysegmentbox">
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox"
                       class="custom-control-input checkbox"
                       name="is_segment"
                       id="is_segment"
                       onclick="$('#is_segment').is(':checked') ? $('.bysegmentinputs').show() : $('.bysegmentinputs').hide()"
                       value="1">
                <span class="custom-control-label  ">By Segment</span>
            </label>
        </span>
    </div>
    <div class="col-md-1 deploy" style="display: none">

        <span class="Schedulebox" style="display: none">
            <label class="custom-control custom-checkbox m-b-0">
                <input type="checkbox"
                       class="custom-control-input checkbox"
                       name="is_schedule"
                       id="is_schedule"
                       onclick="$('#is_schedule').is(':checked') ? $('.Scheduleinputs').show() : $('.Scheduleinputs').hide()"
                       value="1">
                <span class="custom-control-label  ">Schedule</span>
            </label>
        </span>
    </div>--}}
    <div class="col-md-2 listid" style="visibility: hidden;">
        <div class="form-group row">
            <label class="control-label pt-2  col-md-3">Email</label>
            <div class="col-md-9">
                <input type="email"
                       id="input_email"
                       name="input_email"
                       class="form-control text-box1">
            </div>

        </div>
    </div>
    <div class="col-md-2 listid" style="visibility: hidden;">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Firstname</label>
                    <div class="col-md-9">
                        <input type="email"
                               id="input_firstname"
                               name="input_firstname"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
    <div class="col-md-5 listid text-right pl-0" style="display:none;">
        <span id="send_test_custom" class="yui-button yui-push-button Proofs " style="display:none;">
                <span class="first-child">
                    <button
                            type="button"
                            class="btn btn-info"
                            onclick="addEmailReport(14,'send_test_custom','send_test_ds','Send To Custom','insert')">Send to Custom</button>
                </span>
            </span>

        <span id="send_test_ds" class="yui-button yui-push-button Proofs" style="display:none;">
            <span class="first-child">
                <button
                        type="button"
                        class="btn btn-info"
                        onclick="addEmailReport(1,'send_test_ds','send_test_rd_part','Send To DS','insert')">Send to DS</button>
            </span>
        </span>

        <span id="send_test_rd_part" class="yui-button yui-push-button other " style="display:none;margin: auto 0 !important;display:none;">
                <span class="first-child">
                    <button
                            type="button"
                            class="btn btn-info"
                            onclick="addEmailReport(2,'send_test_rd_part','send_test_rd_full','Send To RD Part','send_test_ds',1);">Send to RD Part</button>
                </span>
            </span>

        <span id="send_test_rd_full" class="yui-button yui-push-button other " style="display:none; margin: auto 0 !important;display:none;">
                <span class="first-child">
                    <button
                            type="button"
                            class="btn btn-info"
                            onclick="if($('input[name=is_j]').is(':checked') == false){ alert('Please select radio button');}else{ addEmailReport(3,'send_test_rd_full','','Send To RD Full','send_test_rd_part',1);}">Send to RD Full
                    </button>
                </span>
            </span>

        <div class="btn-group is_j" style="display: none;" data-bs-toggle="buttons">
            <label class="btn btn-secondary border-0 text-info font-weight-medium mt-2">
                <div class="form-check">
                    <input type="radio" id="customRadio7" name="is_j" class="with-gap material-inputs radio-col-red form-check-input" value="J">
                    <label class="form-check-label" for="customRadio7"><span class="d-block d-md-none">1</span><span class="d-none d-md-block">J</span></label>
                </div>
            </label>
            <label class="btn btn-secondary border-0 text-info font-weight-medium mt-2">
                <div class="form-check">
                    <input type="radio" id="customRadio8" name="is_j" class="with-gap material-inputs radio-col-red form-check-input" value="A">
                    <label class="form-check-label" for="customRadio8"><span class="d-block d-md-none">2</span><span class="d-none d-md-block">A</span></label>
                </div>
            </label>
        </div>

        {{--<span class="is_j" style="display: none;"><input type="radio" name="is_j" value="J">J</span>
        <span class="is_j" style="display: none;"><input type="radio" name="is_j" value="A">A</span>--}}
    </div>
    <div class="col-md-6 text-right deploy" style="display:none;">
        <span id="create_stage1_deploy" class="yui-button yui-push-button deploy" style="display:none;display:none;">
            <span class="first-child">
                <button type="button"
                        class="btn btn-info"
                        onclick="addEmailReport(4,'create_stage1_deploy','deploy_campaign_deploy','Create Stage 1 List','send_test_rd_full')">Deploy Review
                </button>
            </span>
        </span>

        <span id="schedule_stage_deploy" class="yui-button yui-push-button Scheduleinputs" style="display:none;">
            <span class="first-child">
                <button type="button"
                        class="btn btn-info"
                        onclick="UpdateEmailReport($(this))">Deploy Schedule
                </button>
            </span>
        </span>

        <span id="deploy_campaign_auto_deploy" class="yui-button yui-push-button other" style="display:none;">
            <span class="first-child">
                <button type="button"
                        class="btn btn-info"
                        onclick="addEmailReport(5.1,'deploy_campaign_auto_deploy','','Deploy Campaign','create_stage1_deploy',1)">Deploy Auto Execute
                </button>
            </span>
        </span>

        <span id="deploy_campaign_deploy" class="yui-button yui-push-button other" style="display:none;">
            <span class="first-child">
                <button type="button"
                        disabled=""
                        class="btn btn-info"
                        onclick="addEmailReport(5,'deploy_campaign_deploy','','Deploy Campaign','create_stage1_deploy',1);">Deploy Execute
                </button>
            </span>
        </span>
    </div>
    <div class="col-md-9 text-right TFD" style="display:none;">
        <span id="create_stage1_TFD" class="yui-button yui-push-button TFD" style="display:none;">
                <span class="first-child">
                    <button type="button"
                            class="btn btn-info"
                            onclick="addEmailReport(6,'create_stage1_TFD','deploy_campaign_TFD','Create Stage 1 List','deploy_campaign_deploy')">Deploy After Test Review
                    </button>
                </span>
            </span>

        <span id="deploy_campaign_TFD" class="yui-button yui-push-button other" style="display:none;">
                <span class="first-child">
                    <button type="button"
                            disabled=""
                            class="btn btn-info"
                            onclick="addEmailReport(7,'deploy_campaign_TFD','','Deploy Campaign','create_stage1_TFD',1);">Deploy after Test Execute
                    </button>
                </span>
            </span>
    </div>
    <div class="col-md-9 text-right ReDeploy" style="display:none;">
        <span id="create_stage1_ReDeploy" class="yui-button yui-push-button ReDeploy" style="display:none;">
            <span class="first-child">
                <button type="button"
                        class="btn btn-info"
                        onclick="addEmailReport(8,'create_stage1_ReDeploy','deploy_campaign_ReDeploy','Create Stage 1 List','deploy_campaign_TFD')">ReDeploy Review
                </button>
            </span>
        </span>

        <span id="deploy_campaign_ReDeploy" class="yui-button yui-push-button other" style="display:none;">
            <span class="first-child">
                <button type="button"
                        disabled=""
                        class="btn btn-info"
                        onclick="addEmailReport(9,'deploy_campaign_ReDeploy','','Deploy Campaign','create_stage1_ReDeploy',1);">ReDeploy Execute
                </button>
            </span>
        </span>
    </div>
    <div class="col-md-9 text-right Re-ReDeploy" style="display:none;">
        <span id="create_stage1_Re-ReDeploy" class="yui-button yui-push-button Re-ReDeploy" style="display:none;display:none;">
            <span class="first-child">
                <button type="button"
                        class="btn btn-info"
                        onclick="addEmailReport(12,'create_stage1_Re-ReDeploy','deploy_campaign_Re-ReDeploy','Create Stage 1 List','deploy_campaign_ReDeploy')">RereDeploy Review
                </button>
            </span>
        </span>

        <span id="deploy_campaign_Re-ReDeploy" class="yui-button yui-push-button other" style="display:none;">
            <span class="first-child">
                <button type="button"
                        disabled=""
                        class="btn btn-info"
                        onclick="addEmailReport(13,'deploy_campaign_Re-ReDeploy','','Deploy Campaign','create_stage1_Re-ReDeploy',1);">RereDeploy Execute
                </button>
            </span>
        </span>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 ECinsert">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Campaign</label>
                    <div class="col-md-9">
                        <input type="text"
                               id="campaign_name"
                               name="campaign_name"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
            <div class="col-md-3 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Template</label>
                    <div class="col-md-9">
                        <input type="text"
                               id="template"
                               name="template"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
            <div class="col-md-3 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">CampData ID</label>
                    <div class="col-md-9">
                        <input type="text"
                               id="campaignID_data"
                               name="campaignID_data"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
            <div class="col-md-2 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-6">&nbsp;# of Subject Versions&nbsp;&nbsp;</label>
                    <div class="col-md-6">
                        <select id="test_subject" name="test_subject" class="form-control text-box1">
                            <option value="0">0</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="">
        <div class="row">
            <div class="col-md-3 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Subject 1&nbsp; </label>
                    <div class="col-md-9">
                        <input type="text"
                               id="subject1"
                               name="subject1"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
            <div class="col-md-3 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Subject 2</label>
                    <div class="col-md-9">
                        <input type="text"
                               id="subject2"
                               name="subject2"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>
            <div class="col-md-3 ECinsert" style="">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3"> &nbsp;&nbsp; Subject 3&nbsp;&nbsp;&nbsp; </label>
                    <div class="col-md-9">
                        <input type="text"
                               id="subject3"
                               name="subject3"
                               class="form-control text-box1">
                    </div>
                </div>
            </div>

            <div class="col-md-2 ECinsert">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-6 ">% for Split Subject Test</label>
                    <div class="col-md-6">
                        <input type="text" id="TestSubjectPct" name="TestSubjectPct" class="form-control" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="col-md-1 ECinsert">
                 <span id="insert" class="yui-button yui-push-button pull-right" style="margin: auto 0 !important;">
                     <span class="first-child">
                         <button type="button" class="btn btn-info" onclick="addEmailReport(0,'insert','','Email Config','')">Insert
                         </button>
                     </span>
                 </span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3 Schedule Scheduleinputs no-padding" style="display:none;">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-4">Date </label>
                    <div class="col-md-8">
                        <input class="form-control date js-datepicker"
                               id="StartDate_input"
                               name="startDate"
                               type="text"
                               value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
            </div>

            <div class="col-md-2 Schedule Scheduleinputs no-padding" style="display:none;">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Time </label>
                    <div class="col-md-9">
                        <input type="text"
                               id="Time1_input"
                               name="Time1"
                               class="form-control js-clockpicker-24h"
                               value="<?= date('H:i') ?>">
                    </div>
                </div>
            </div>

            <div class="col-md-4 bysegmentinputs" style="display:none;">
                <div class="form-group row">
                    <label class="control-label pt-2  col-md-3">Segment </label>
                    <div class="col-md-6">
                        <input type="text"
                               id="by_segments"
                               name="by_segments"
                               class="form-control"
                        >
                    </div>
                </div>
            </div>
            <!--
            <div class="col-md-3 Schedule">
               <span id="Update" class="yui-button yui-push-button Schedule" class="form-control text-box1" style="display:none; width:54px !important"><span class="first-child"><button type="button" onclick="UpdateEmailReport()">Update</button></span></span>
            </div>
            -->
        </div>
    </div>
</div>
