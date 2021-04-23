<?php
$n = isset($user) && !empty($user) ? false : true;
?>

<div class="card">
    <div class="form-body">
        <div class="card-body">
            <form class="ajax-Form" enctype="multipart/form-data" method="post" action="/settings/adduser">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="exampleInputEmail1"
                                   aria-describedby="emailHelp"
                                   placeholder=""
                                   value="<?= (!$n) ? $user->first_name : ''; ?>"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="exampleInputEmail1"
                                   aria-describedby="emailHelp"
                                   placeholder=""
                                   value="<?= (!$n) ? $user->last_name : ''; ?>"
                            >
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                                   aria-describedby="emailHelp"
                                   placeholder=""
                                   value="<?= (!$n) ? $user->email : ''; ?>"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="control-label">Profile</label>
                            <select name="profile_id" class="form-control custom-select">
                                <option value="">Select Profile</option>
                                @if($profiles)
                                    @foreach($profiles as $profile)
                                        <option  <?= (!$n && isset($user) && $user->profile_id == $profile->profile_id) ? 'selected' : ''; ?> value="{{$profile->profile_id}}">{{$profile->profile_name}}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="custom-control custom-checkbox mr-sm-2 mb-3">
                            <input type="checkbox" name="is_active" class="custom-control-input" id="checkbox0" value="1" <?= (!$n && isset($user) && $user->is_active == 1) ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="checkbox0">Active</label>
                        </div>
                    </div>
                </div>

                <div class="form-actions pull-right">
                    <input type="hidden" name="u_dataid" value="<?= (!$n) ? $user->u_dataid : '0'; ?>">
                    <input type="hidden" name="id" value="<?= (!$n) ? $user->id : '0'; ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Save</button>
                    <button type="reset" class="btn waves-effect waves-light btn-secondary">Cancel</button>

                </div>

            </form>
        </div>
    </div>
</div>