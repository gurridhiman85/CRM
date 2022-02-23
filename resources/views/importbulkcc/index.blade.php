@extends('layouts.docker-horizontal')
@section('content')
    <?php
     \App\Library\AssetLib::library('importbulkcc');
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row m-t-10">
                        <div class="col-md-8">
                            <h5 id="steps-title">Import 1-month Opens</h5>
                            <small class="form-text text-muted">Step <span class="stepsCnt">1</span> of 5</small>
                        </div>
                        <div class="col-md-4">
                            <div class="row">

                                <div class="col-md-12 pl-0 pr-0">
                                    <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group" role="group" aria-label="First group">

                                            <button type="button" title="Reset"
                                                    class="btn btn-light font-14 font-weight-bold" aria-expanded="false"
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
                    <div class="alert alert-success hide"></div><div>
                        <div class="fieldset active" data-title="Import 1-month Opens" data-step="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="filessubmit" class="form-horizontal ajax-Form" action="/importbulkcc/step1"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <div class="drop-file">
                                            <input id="docs-input-files" name="files[]" type="file">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Import 6-month Opens" data-step="2">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="formstep2" class="form-horizontal ajax-Form" action="/importbulkcc/step2"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <div class="drop-file">
                                            <input id="docs-input-files_step2" name="files[]" type="file">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Import 12-month Opens" data-step="3">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="formstep3" class="form-horizontal ajax-Form" action="/importbulkcc/step3"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <div class="drop-file">
                                            <input id="docs-input-files_step3" name="files[]" type="file">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Import All Emails" data-step="4">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" id="formstep4" class="form-horizontal ajax-Form" action="/importbulkcc/step4"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <div class="drop-file">
                                            <input id="docs-input-files_step4" name="files[]" type="file">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Process Updates in CRM Database & Download file for CC processing" data-step="5">
                            <div class="row">
                                <form role="form" id="formstep5" class="form-horizontal ajax-Form" action="importbulkcc/step5"
                                      method="POST" enctype="multipart/form-data" autocomplete="off">
                                    {!! csrf_field() !!}
                                </form>

                            </div>
                            <div class="drop-file">
                                <div class="file-input file-input-ajax-new"><div class="file-preview ">
                                        <button type="button" class="close fileinput-remove" aria-label="Close" style="display: none;">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                        <div class=" file-drop-zone clearfix">
                                            <div class="file-drop-zone-title">
                                                <a href="javascript:void(0);"
                                                   id="downloadfinalfile"
                                                   class="btn btn-info font-14"
                                                   target="_blank"
                                                   style="display: none"
                                                   download="">
                                                    Download
                                                </a>
                                            </div>

                                            <div class="file-preview-status text-center text-success"></div>
                                            <div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="kv-upload-progress kv-hidden" style="display: none;"><div class="progress">
                                            <div class="progress-bar bg-success progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">
                                                0%
                                            </div>
                                        </div></div><div class="clearfix"></div>
                                    <div class="input-group file-caption-main" style="display: none;">
                                        <div class="file-caption form-control kv-fileinput-caption" tabindex="500">
                                            <span class="file-caption-icon"></span>
                                            <input class="file-caption-name" onkeydown="return false;" onpaste="return false;" placeholder="Select file...">
                                        </div>
                                        <div class="input-group-btn input-group-append">
                                            <button type="button" tabindex="500" title="Clear all unprocessed files" class="btn btn-default btn-secondary fileinput-remove fileinput-remove-button" style="display: none;"><i class="glyphicon glyphicon-trash"></i>  <span class="hidden-xs">Remove</span></button>
                                            <button type="button" tabindex="500" title="Abort ongoing upload" class="btn btn-default btn-secondary kv-hidden fileinput-cancel fileinput-cancel-button"><i class="glyphicon glyphicon-ban-circle"></i>  <span class="hidden-xs">Cancel</span></button>


                                            <div tabindex="500" class="btn btn-primary btn-file"><i class="glyphicon glyphicon-folder-open"></i>&nbsp;  <span class="hidden-xs">Browse …</span><input id="docs-input-files" name="files[]" type="file"></div>
                                        </div>
                                    </div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @include('layouts.docker-rightsidebar')
    </div>

@stop
