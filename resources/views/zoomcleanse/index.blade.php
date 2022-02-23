@extends('layouts.docker-horizontal')
@section('content')
    <?php
     \App\Library\AssetLib::library('zoomcleanse');
    ?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

        .ui-autocomplete-loading {
            background: white url("https://jqueryui.com/resources/demos/autocomplete/images/ui-anim_basic_16x16.gif") right center no-repeat;
        }

    </style>
    <div class="container-fluid">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row m-t-10">
                        <div class="col-md-9">
                            <h5 id="steps-title">Clean Zoom Records</h5>
                            <small class="form-text text-muted">Step <span class="stepsCnt">1</span> of 8</small>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12 pl-0">
                                    <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                        <div class="btn-group" role="group" aria-label="First group">
                                            <div class="c-btn" style="display: none;"></div>
                                            <button type="button" title="Reset"
                                                    class="btn btn-light  font-14 font-weight-bold" aria-expanded="false"
                                                    style="float: right;box-shadow: none;" onclick="resetIMP();">
                                                <i class="ti-reload font-weight-bold ds-c"></i>
                                            </button>

                                            <button type="button"
                                                    disabled
                                                    title="Next"
                                                    class="btn btn-light no-border font-14 continue"
                                                    aria-expanded="false"
                                                    style="float: right;box-shadow: none;">
                                                <i class="ti-arrow-right font-weight-bold ds-c"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="progress mb-3 ">
                        <div class="progress-bar wow animated progress-animated" role="progressbar" aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <div class="alert alert-success hide"></div>

                    <div>
                        <div class="fieldset active" data-title="Clean Zoom Records" data-step="1">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="filessubmit" class="form-horizontal ajax-Form" action="zoomcleanse/step1"
                                          method="POST">
                                        {!! csrf_field() !!}
                                            <button type="submit"
                                                    class="btn btn-info"
                                                    >
                                                Start Cleaning
                                            </button>
                                    </form>
                                    {{--<form role="form" id="filessubmit" class="form-horizontal ajax-Form" action="/docs/fileupload"
                                          method="POST" enctype="multipart/form-data" autocomplete="off">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="source" value="Zoom">
                                        <div class="drop-file">
                                            <input id="docs-input-files" name="files[]" type="file" multiple>
                                        </div>
                                    </form>--}}
                                </div>
                            </div>
                        </div>
                        <div class="fieldset" data-title="Match Zoom Name to CRM Portal Based on AI Algorithm 1" data-step="2">
                            <div class="row">
                                <form action="zoomcleanse/step2" id="zoomstep2" method="post" class="ajax-Form">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep2table"></div>
                                </form>
                            </div>
                        </div>

                        <div class="fieldset" data-title="Match Zoom Name to CRM Portal Based on AI Algorithm 2" data-step="3"> <!-- Show address -->
                            <div class="row">
                                <form action="zoomcleanse/step3" method="post" class="ajax-Form" id="zoomstep3">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep3table"></div>
                                </form>

                            </div>
                        </div>

                        <div class="fieldset" data-title="Match Zoom Name to CRM Portal Based on AI Algorithm 3" data-step="4"> <!-- Show messages -->
                            <div class="row">
                                <form action="zoomcleanse/step4" method="post" class="ajax-Form" id="zoomstep4">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep4table"></div>
                                </form>
                            </div>
                        </div>

                        <div class="fieldset" data-title="Select records from CRM Portal that match Zoom name" data-step="5">
                            <div class="row">
                                <form action="zoomcleanse/step5" method="post" class="ajax-Form" id="zoomstep5">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep5table"></div>
                                </form>
                            </div>
                        </div>

                        <div class="fieldset" data-title="Tag records for update in CRM Portal" data-step="6">
                            <div class="row">
                                <form action="zoomcleanse/step6" method="post" class="ajax-Form" id="zoomstep6">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep6table"></div>
                                </form>
                            </div>
                        </div>

                        <div class="fieldset" data-title="Edit and Tag Records for Insert in CRM Portal" data-step="7">
                            <div class="row">
                                <form action="zoomcleanse/figure" method="post" class="ajax-Form" id="zoomstep7">
                                    {!! csrf_field() !!}
                                    <div class="col-md-12" id="zoomstep7table"></div>
                                </form>
                            </div>
                        </div>

                        <div class="fieldset" data-title="Success" data-step="9"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.docker-rightsidebar')
    </div>

@stop
