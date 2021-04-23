<footer class="footer">{!! config('constant.footer_label') !!}</footer>


<div class="modal bs-example-modal-lg" id="changepasswordBox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title pl-1" id="myModalLabel">Change Password</h6>
                <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
            </div>
            <div class="modal-body p-1">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal ajax-Form" action="changepassword" class="ajax-Form" method="post">
                            {!! csrf_field() !!}
                            <div class="form-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Old Password</label>
                                                <input type="password" class="form-control form-control-sm" name="old_password" id="old_password" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">New Password
                                                    <span class="mytooltip tooltip-effect-3">
								<span class="tooltip-item" style="background: none;"><i class="far fa-question-circle" style="color: #46a9d2;"></i></span>
								<span class="tooltip-content clearfix">
								  <span class="tooltip-text">
									<ol>
										<li>Password length between 12-30 characters</li>
										<li>Username, first or last name not part of password</li>
										<li>Dictionary words not part of password.</li>
										<li>Three of the following four criteria
											<ul>
												<li>One uppercase letter</li>
												<li>One lowercase letter</li>
												<li>One number</li>
												<li>One special character</li>
											</ul>
										</li>
									</ol>
								  </span>
								</span>
							</span>
                                                </label>
                                                <input type="password" name="password" class="form-control form-control-sm" id="password" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 p-0">
                                            <div class="form-group">
                                                <label class="control-label">Confirm Password</label>
                                                <input type="password" name="confirm_password" class="form-control form-control-sm"  id="txtBcc1" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row pull-right">
                                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="display: block !important;">
                                            <div class="input-group pull-right">
                                                <button type="submit" class="btn btn-info font-12 s-f" title="Change Password" >Change</button>
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
