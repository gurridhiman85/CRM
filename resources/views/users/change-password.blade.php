@extends('layouts.docker')
@section('content')
    <div class="login-box card">
        <div class="card-body">
            <form class="form-horizontal form-material ajax-Form" id="loginform" action="changepassword" method="post">
                {!! csrf_field() !!}
                <h3 class="text-center m-b-20">Change Password</h3>
					<div class="row  m-b-10">
						<div class="col-md-11">
							<input class="form-control" type="password" name="Password" placeholder="Password"
								   autocomplete="off">
						</div>
						<div class="col-md-1 pt-3">
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
						</div>
                    </div>
					
					<div class="row m-b-10">
						<div class="col-md-12">
							<input class="form-control" type="password" name="confirm_password"
								   placeholder="Confirm Password">
						</div>
                    </div>
                
                <div class="row text-center p-b-20">
                    <div class="col-md-12">
                        <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">Change
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop