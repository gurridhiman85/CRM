@php
    $n = isset($DBpermissions) && !empty($DBpermissions) ? false : true;
@endphp
<div class="card">
    <div class="form-body">
        <div class="card-body">
            <form class="ajax-Form" enctype="multipart/form-data" method="post" action="/profile/permissions">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-4">Title</div>
                    <div class="col-md-2">Add</div>
                    <div class="col-md-2">View</div>
                    <div class="col-md-2">Edit</div>
                    <div class="col-md-2">Trash</div>
                </div>
                <div class="m-t-10">
                    @php
                        $permissions = config('constant.permissions');
                    @endphp
                    @foreach($permissions['modules'] as $modulename=>$permission)
                        <input type="hidden" name="permissions[{{$modulename}}][parents]" value=""/>
                        <div class="row">
                            <div class="col-md-4"><b>{{$permission['show_as']}}</b></div>
                            @foreach($permission['rights'] as $rightname=>$right)
                                @php
                                    if(count($DBpermissions) > 0){
                                        $result = \App\Helpers\Helper::searchForId(null,$modulename,$rightname, $DBpermissions);
                                    }
                                @endphp


                                <div class="col-md-2">
                                    <div class="m-b-10">
                                        <input type="checkbox"
                                               name="permissions[{{$modulename}}][rights][{{$rightname}}]"
                                               {{isset($result) && $result == 1 ? 'checked' : ($right == 1 ? 'checked' : '')}} class="js-switch"
                                               data-color="#00c292"
                                               data-size="small" value="1"/>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(count($permission['submodules']) > 0)
                            @foreach($permission['submodules'] as $submodulename=>$subpermission)
                                <input type="hidden" name="permissions[{{$submodulename}}][parents]"
                                       value="{{$modulename}}"/>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-3"><b>{{$subpermission['show_as']}}</b></div>
                                    @foreach($subpermission['rights'] as $rightname=>$right)
                                        @php
                                            if(count($DBpermissions) > 0){
                                                $result = \App\Helpers\Helper::searchForId($modulename,$submodulename,$rightname, $DBpermissions);
                                            }

                                        @endphp
                                        <div class="col-md-2">
                                            <div class="m-b-10">
                                                <input type="checkbox"
                                                       name="permissions[{{$submodulename}}][rights][{{$rightname}}]"
                                                       {{isset($result) && $result == 1 ? 'checked' : ($right == 1 ? 'checked' : '')}} class="js-switch"
                                                       data-color="#00c292"
                                                       data-size="small" value="1"/>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                </div>


                <div class="form-actions pull-right">
                    <input type="hidden" name="profile_id" value="<?= (!$n) ? $profileid : '0'; ?>">
                    <button type="submit" class="btn waves-effect waves-light btn-success">
                        <i class="fa fa-check"></i> Update
                    </button>
                    <button type="reset" class="btn waves-effect waves-light btn-secondary">Cancel</button>

                </div>

            </form>
        </div>
    </div>
</div>