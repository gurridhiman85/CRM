@extends('layouts.guest')
@section('content')
    <form class="form-horizontal" id="recoverform" action="/password/email" method="post">
        {{csrf_field()}}
        <div class="form-group ">
            <div class="col-xs-12">
                <h3>Recover Password</h3>
                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
            </div>
        </div>
        <div class="form-group ">
            <div class="col-xs-12">
                <input type="email" name="email" value="" class="form-control" placeholder="Email address" required="">
            </div>
        </div>
        <div class="form-group text-center m-t-20">
            <div class="col-xs-12">
                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">
                    Reset
                </button>
            </div>
        </div>
    </form>
@stop