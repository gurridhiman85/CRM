@extends('layouts.guest')
@section('content')
    <div class="login-box card">
        <div class="card-body">
            <form class="ajax-Form form-horizontal" id="loginform" action="dlogin" method="post">
                {{csrf_field()}}
                <h3 class="text-center m-b-20">Login</h3>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="User_Email" id="email" placeholder="Username" aria-label="Email">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="far fa-user"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group mb-3" id="show_hide_password">
                            <input type="password" class="form-control" name="Password" id="password" placeholder="Password" aria-label="Password">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-eye-slash" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
							<!--
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkbox-signup"
                                       name="remember">
                                <label class="custom-control-label" for="customCheck1">Remember me</label>
                            </div>
							-->
                            <div class="ml-auto">
                                <a href="javascript:void(0)" id="to-recover" class="text-muted"><i
                                            class="fas fa-lock m-r-5"></i> Forgot password</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <div class="col-xs-12 p-b-20">
                        <button class="btn btn-block btn-lg btn-info" type="submit">Log In</button>
                    </div>
                </div>

				<div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <a href="register" class="text-info m-l-5"><b>New User ?</b></a>
                    </div>
                </div>
            </form>
            <form class="form-horizontal ajax-Form" id="recoverform" action="forgetpassword" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <div class="col-xs-12">
                        <h3>Recover Password</h3>
                        <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" name="User_Confirm" placeholder="Username"></div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="d-flex no-block align-items-center">
                            <div class="custom-control custom-checkbox">
                            </div>
                            <div class="ml-auto">
                                <a href="javascript:void(0)" id="to-login" class="text-muted"><i
                                            class="fas fa-backward m-r-5"></i> Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button class="btn btn-block btn-lg btn-info text-uppercase waves-effect waves-light"
                                type="submit">Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
