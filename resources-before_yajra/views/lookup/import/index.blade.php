@extends('layouts.docker-horizontal')
@section('content')
    <?php
     \App\Library\AssetLib::library('importcontact');
    ?>
    <style type="text/css">
        .fieldset:not(:first-of-type) {
            display: none;
        }

        .progress {
            font-size: .15625rem !important;
        }

        .progress-bar {
            background-color: #b7dee8 !important;
        }

        .file-drop-zone {
            border: none !important;
        }

        .table-bordered td {
            border: 1px solid #e9ecef !important;
        }

        .unmatched {
            background-color: #f8d3d3 !important;
        }

        .matched {
            background-color: #add5b3 !important;
        }

        .new-rec{
            background-color: #edfaff !important;
        }



    </style>
    <div class="container-fluid">
        {{--<div class="row page-titles p-t-15 p-r-10 pb-2">
            <div class="col-md-12 align-self-center">
                <div class="row pl-3 pt-2">
                        <h6 class="text-themecolor">Import Contacts</h6>
                </div>
            </div>
        </div>--}}

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row m-t-10">
                        <div class="col-md-9">
                            <h5 id="steps-title">Upload from file</h5>
                            <small class="form-text text-muted">Step <span class="stepsCnt">1</span> of 6</small>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-3 pl-0 pr-0 pt-2">
                                    <label class="custom-control custom-checkbox pt-0 m-b-0">
                                        <input type="checkbox" id="is_no_address" class="custom-control-input checkbox" value="1">
                                        <span class="custom-control-label pt-1">No Address</span>
                                    </label>
                                </div>
                                <div class="col-md-5 pr-0">
                                    <div class="form-group">
                                        <select class="form-control form-control-sm"
                                                style="min-height: 34px !important;border: 1px solid #d5ecf2 !important;"
                                                id="sourceOuterSelector"
                                                onchange="$('[name=source]').val($(this).val()),$('[name=source3]').val($(this).val())">
                                            <option value="">Select Source</option>
                                            <?php $sources = ['QB', 'Qgiv','Manual','Zoom'];//\App\Helpers\Helper::getSource(); ?>
                                            @if(count($sources) > 0)
                                                @foreach($sources as $source)
                                                    {{--<option value="{{$source['id']}}">{{$source['code_value']}}</option>--}}
                                                    <option value="{{$source}}">{{$source}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="error-block help-block help-block-SourceOuter"></span>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-0">
                                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group" role="group" aria-label="First group">
                                            <button type="button" title="Reset"
                                                    class="btn btn-light border-left-0 font-14 font-weight-bold" aria-expanded="false"
                                                    style="float: right;box-shadow: none;" onclick="resetIMP();"><i
                                                        class="ti-reload font-weight-bold ds-c"></i></button>

                                            <button type="button" disabled title="Back" class="btn btn-light no-border font-14 back"
                                                    aria-expanded="false" style="float: right;box-shadow: none;"><i
                                                        class="ti-arrow-left font-weight-bold ds-c"></i></button>

                                            <button type="button" disabled title="Next"
                                                    class="btn btn-light no-border font-14 continue" aria-expanded="false"
                                                    style="float: right;box-shadow: none;"><i
                                                        class="ti-arrow-right font-weight-bold ds-c"></i></button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress mb-3">
                        <div class="progress-bar wow animated progress-animated" role="progressbar" aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <div class="alert alert-success hide"></div>

                    <div>
                        <div class="fieldset active" data-title="Upload File" data-step="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="filessubmit" class="form-horizontal ajax-Form" action="/docs/fileupload"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="source" value="">
                                        <div class="drop-file">
                                            <input id="docs-input-files" name="files[]" type="file">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Match Field Names" data-step="2">
                            <div class="row">
                                <form action="import/execute" id="importExecuteFrm" method="post" class="ajax-Form">
                                    {!! csrf_field() !!}
                                    <input type="hidden" name="source3" value="">
                                    <input type="hidden" id="Import_Filename" name="Import_Filename" value="">
                                    <input type="hidden" id="Import_Id" name="Import_Id" value="">
                                    <input type="hidden" id="no_address" name="no_address" value="0">
                                    <div class="col-md-12" id="overviewImport"></div>
                                </form>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Import and Cleanse File" data-step="3">
                            <center>
                                <span class="font-16">Please wait..........<br/>File Import and Cleansing is in progress.<br/> Your input will be required in approximately 3-5 minutes.</span>
                                <span id="cd_h" style="display: none;">00</span>
                                <span id="cd_m" class="font-14">00</span>:
                                <span id="cd_s" class="font-14">00</span>
                                <span id="cd_ms" style="display: none;">00</span>

                                <div style="display: none;">
                                    <input type="button" value="Start" id="cd_start" />
                                    <input type="button" value="Pause" id="cd_pause" />
                                    <input type="button" value="Stop"  id="cd_stop" />
                                    <input type="button" value="Reset" id="cd_reset" />
                                    <input type="text" value="300" id="cd_seconds" />
                                    <span id="cd_status">Idle</span>
                                </div>
                            </center>
                            <div class="row">
                                <div class="col-md-12"></div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Update Address" data-step="4"> <!-- Show address -->
                            <div class="row">
                                <form action="import/updateaddress" method="post" class="ajax-Form" id="updateaddress"
                                      style="width: 100%;">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="updateAddressList"></div>
                                </form>

                            </div>
                        </div>
                        <div class="fieldset" data-title="Update Name" data-step="5"> <!-- Show messages -->
                            <div class="row">
                                <form action="import/updatename" method="post" class="ajax-Form" id="updatename"
                                      style="width: 100%;">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="updateNameList"></div>
                                </form>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Success" data-step="6"></div>
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.docker-rightsidebar')
    </div>

@stop
