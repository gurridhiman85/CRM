<div class="modal bs-example-modal-lg" id="schedulePopup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="max-width: 1600px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-2" id="myModalLabel">Schedule</h6>
                <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body">
                <div id='divschedule' style='width:auto;height:650px;'><span
                            id="indicationCFMsgForPreview"></span>
                    <iframe name="iframeSchedule" style="width: 100%;height: 100%; border: 0 !important;"></iframe>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal bs-example-modal-lg" id="sharePopup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-2" id="share-title"></h6>
                <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body p-2">
                <div class="card m-0">
                    <div class="card-body">
                        <form class="form-horizontal ajax-Form" id="sharereport" action="sharereport" class="ajax-Form" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="eCampid" class="eCampid">
                            <input type="hidden" class="t_type" name="t_type" id="ppt_type" value="A">
                            <input type="hidden"  name="user_id" value="<?=Auth::user()->User_ID;?>">
                            <div class="form-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group row">
                                                <label class="control-label col-md-4">Users:</label>
                                                <div class="col-md-8">
                                                    <select  name="users[]" id="userFieldList" class="form-control form-control-sm" multiple="multiple">
                                                        <?php
                                                        $users = \App\Helpers\Helper::getUsers(Auth::user()->User_ID);
                                                        foreach($users as $user){
                                                            echo '<option value='.$user['User_ID'].'>'.$user['User_FName'].' '.$user['User_LName'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group row">
                                                <label class="control-label col-md-4">Add Custom Message:</label>
                                                <div class="col-md-8">
                                                    <label class="custom-control custom-checkbox m-b-0" style="width: fit-content;">
                                                        <input type="checkbox" class="custom-control-input checkbox" value="1" id="chkSREmail" name="chkSREmail" onclick="$(this).is(':checked') ? $('.s-cmessage').show() : $('.s-cmessage').hide()">
                                                        <span class="custom-control-label"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row s-cmessage" style="display: none;">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group row">
                                                <label class="control-label col-md-4">Comments:</label>
                                                <div class="col-md-8">
                                                    <textarea id="limitedtextarea4" class="form-control form-control-sm" name="limitedtextarea4" onkeydown="limitText(this.form.limitedtextarea4,this.form.countdown4,250);" onkeyup="limitText(this.form.limitedtextarea4,this.form.countdown4,250);" cols="33" rows="5"></textarea><font size="1"><br>(Maximum characters: 250).
                                                    You have <input readonly="" type="text" id="countdown4" name="countdown4" size="3" value="250"> characters left.</font>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pull-right">
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                                            <div class="input-group pull-right">
                                                <button type="submit" class="btn btn-info font-12 s-f" title="Share Report" >Share</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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


<div class="modal bs-example-modal-lg" id="emailBox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-1" id="sendemail-title">Send Report via Email</h6>
                <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body p-1">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal ajax-Form" id="sendreportviaemail" action="sendviaemail" class="ajax-Form" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="eCampid" class="eCampid" value="55">
                            <input type="hidden" name="t_type" id="set_type" value="A">
                            <div class="form-body">
                                <div class="card-body">
                                <?php $users = \App\Helpers\Helper::getUsers(Auth::user()->User_ID); ?>
                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">To:</label>
                                                {{--<input type="text" class="form-control form-control-sm" name="txtTo" id="txtTo1" autocomplete="off">--}}
                                                <select id="txtTo1" name="txtTo[]" class="form-control form-control-sm" multiple="multiple">
                                                    <?php
                                                    foreach($users as $user){
                                                        echo '<option value='.$user['User_ID'].'>'.$user['User_FName'].' '.$user['User_LName'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Cc:</label>
                                                <input type="text" name="txtCc" class="form-control form-control-sm" id="txtCc1" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Bcc:</label>
                                                <input type="text" name="txtBcc" class="form-control form-control-sm"  id="txtBcc1" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Subject:</label>
                                                <input type="text" name="txtSub" class="form-control form-control-sm" id="txtSub1" autocomplete="off">
                                                <input type="hidden" id="clientname" value="{!! config('constant.client_name') !!}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Comments:</label>
                                                <textarea id="limitedtextarea1" class="form-control form-control-sm" name="limitedtextarea1" onkeydown="limitText(this.form.limitedtextarea1,this.form.countdown,250);" onkeyup="limitText(this.form.limitedtextarea1,this.form.countdown,250);" cols="33" rows="5"></textarea><font size="1"><br>(Maximum characters: 250).
                                                    You have <input readonly="" type="text" id="countdown" name="countdown" size="3" value="250"> characters left.</font>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Attachment</label>
                                                <select id="Email_Attachment" name="Email_Attachment" class="form-control form-control-sm">
                                                    <option value="onlylist">List Only</option>
                                                    <option value="onlyreport">Report Only</option>
                                                    <option value="both">Both</option>
                                                    <option value="none">None</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pull-right">
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                                            <div class="input-group pull-right">
                                                <button type="submit" class="btn btn-info font-12 s-f" title="Send Report" >Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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



