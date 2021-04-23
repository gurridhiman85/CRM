@extends('layouts.docker-horizontal')
@section('content')
    <?php
    \App\Library\AssetLib::library('campaign');
    $User_Type = Session::get('User_Type');
    ?>
    <style>
        td.green-text{
            background-color : #7de485 !important;
        }

        th.green-text{
            background-color : #69ce70 !important;
        }

        td.orange-text{
            background-color : #ffa74c !important;
        }
        th.orange-text{
            background-color : #ff8609 !important;
        }
        td.yellow-text{
            background-color : #ffef7a !important;
        }
        th.yellow-text{
            background-color : #efde67 !important;
        }
        td.grey-text{
            background-color : #e2dfdf !important;
        }
        th.grey-text{
            background-color : #d4c9c9 !important;
        }

        .fc {
            padding-left: 17px !important;
            width: 15%;
        }

        .ff {
            padding-left: 58px !important;
            width: 15%;
        }

        button.ds-c3 {
            color: #5f93b2;
            background-color: #bfe6f6;
            border-color: #dae0e5
        }
        button.ds-c3:hover {
            background-color: #3ea6d0;
            color: #fff;
        }

        button.ds-c4:hover {
            color: #5f93b2;
            background-color: #bfe6f6;
            border-color: #dae0e5
        }
        button.ds-c4 {
            background-color: #3ea6d0;
            color: #fff;
        }


        .inner-part {
            padding-left: 40px;
            padding-top: 15px;
            width: 552px;
            height: 20px;
        }

        .inner-part1 {
            padding-left: 40px;
            width: 552px;
            height: 20px;
        }

        .left-part {
            float: left;
            width: 190px;
            height: 30px;
        }

        .right-part {
            float: right;
            width: 350px;
            height: 30px;
        }

        .title-label {
            padding-right: 30px;
            font-size: 12px;
            display: inline-block;
            padding-top: 4px;
        }

    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="container-fluid">
        {{--<div class="row page-titles p-t-15 p-r-10 pb-2">
            <div class="col-md-12 align-self-center">
                <div class="row pt-2">
                    <div class="col-md-1">
                        <h6 class="text-themecolor" style="color: #3ea6d0;font-weight: 500;">Campaign</h6>
                    </div>
                    <div class="col-md-11" onclick="$('.sqlBtn').toggle()">
                        @if($User_Type == 'Full_Access')
                            <div class="btn-toolbar pull-right sqlBtn" role="toolbar" aria-label="Toolbar with button groups"  style="display: none">
                                <div class="input-group">
                                <button type="button"
                                        class="btn btn-light d-none d-lg-block font-16 "
                                        title="SQL"
                                        id='showSqlBtn'
                                        disabled
                                        onclick='create_query_onclick();'>
                                    <i class="fas fa-database ds-c"></i>
                                </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>--}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="filter-open-close">
                        <div class="filter collapse" id="collapseFilters" aria-expanded="false">
                            <form id="filter_form" class="filter-scroll-js filter-inner respo-filter-myticket" data-title="tickets#24#536" autocomplete="off">


                            </form>
                        </div>
                    </div>
                    <!-- Nav tabs -->
                    <div style="display: none">
                        <input type="text" id="libflag_name">
                        <input type="text" id="libcamp_name">
                        <input type="text" id="libcamp_id">

                        <input type="hidden" id="previewSql">
                        <input type="hidden" id="previewDownloadFileName">
                        <input type="hidden" id="previewDownloadFileType">

                        <select name='cmbsavedcampNew' id='cmbsavedcampNew' style="width:106px; display:none !important;">
                            <?php
                            $aData1 = DB::select("select row_id,t_name from UC_Campaign_Templates Where t_type = 'C' order by t_name");
                            $aData1 = collect($aData1)->map(function ($x) {
                                return (array)$x;
                            })->toArray();
                            echo "<option value=''></option> ";
                            if (!empty($aData1)) {
                                foreach ($aData1 as $key => $row) {
                                    $sD[] = implode(",", $row);
                                }

                                foreach ($sD as $kk => $vv) {
                                    $keys = explode(",", $vv);
                                    echo "<option value='" . $keys[0] . "'>" . $keys[1] . "</option> ";
                                }
                            }
                            ?>
                        </select>

                        <input style='border:0' type='radio' id="libview" value='view' name='rUpdate' checked
                               onClick='updateOpt(this);'>View
                        <input style='border:0' type='radio' id="libupdate" value='update' name='rUpdate'
                               onClick='updateOpt(this);'>Update
                        <input style='border:0' type='radio' id="libnew" value='new' name='rUpdate'
                               onClick='updateOpt(this);'>Save As

                        <input type='hidden' id='campchk' value=''>
                        <input type='hidden' id='mutliplePDF_IDs' value=''>
                        <input type='hidden' id='mutlipleReports' value=''>
                        <div id="inserttext" style='display:none'>
                            <input type='text' id='txtnewCampname' name='txtnewCampname' style="width:252px;" value=''>
                        </div>


                        <div id='divExecute' style='display:none'>
                            <form name='frmExec' method='post'><input type='hidden' name='sSQL'>
                                {!! csrf_field() !!}
                                <div id='divaddsub' style='display:block'></div>

                            </form>
                            <iframe name='iframeExecute' src='' frameborder=0 style='width:100%;height:600px; border: 0 !important;'></iframe>
                        </div>
                    </div>
                    <div class="row mb-2" style="border-bottom: 1px solid #dee2e6;">
                        <div class="col-md-8">
                            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax"role="tablist" data-href="campaign/get" data-method="get" data-default-tab="tab_22">

                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="20" href="#tab_20" role="tab" aria-selected="false">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Running</span>
                                    </a>
                                </li>

                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="21" href="#tab_21" role="tab" aria-selected="false">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Scheduled</span>
                                    </a>
                                </li>

                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="22" href="#tab_22" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Completed</span>
                                    </a>
                                </li>

                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="23" href="#tab_23" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Evaluation Summary</span>
                                    </a>
                                </li>

                                <li class="nav-item list-report" style="border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="24" href="#tab_24" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Evaluation Details</span>
                                    </a>
                                </li>

                                <li class="nav-item view-report" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="25" href="#tab_25" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">View Campaign</span>
                                    </a>
                                </li>

                                <li class="nav-item create-new" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-shouldblank="no" data-tabid="26" href="#tab_26" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Create</span>
                                    </a>
                                </li>

                                <li class="nav-item create-new" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-shouldblank="no" data-tabid="27" href="#tab_27" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Segment</span>
                                    </a>
                                </li>

                                <li class="nav-item create-new" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-shouldblank="no" data-tabid="28" href="#tab_28" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Export</span>
                                    </a>
                                </li>

                                <li class="nav-item create-new" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-shouldblank="no" data-tabid="29" href="#tab_29" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Meta Data</span>
                                    </a>
                                </li>


                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="input-group">
                                    <div class="all-pagination" style="vertical-align: middle;margin: 10px;"></div>
                                    <button type="button" style="display: none;" class="btn btn-light border-right-0 font-16 s-f dmpdf" title="Download Multiple PDF" onclick="downloadMutliplePDF()"><i class="fas fa-file-pdf ds-c" style="color: #e92639;"></i></button>
                                    <button type="button" style="" class="btn btn-light border-right-0 font-16 s-f emreport" title="Run Report" onclick="ExeMutlipleRep()"><i class="fas fa-arrow-circle-right ds-c"></i></button>
                                    <div class="c-btn" style="display: none;"></div>
                                    <button type="button" style="display: none;" class="btn btn-light border-right-0 font-16 s-f cnt-btn cl-report-btn ds-c3" title="Count" onclick="check_sql_count();"><i class="fas fa-calculator"></i></button>
                                    <button type="button"style="display: none;"  class="btn btn-light border-right-0 font-16 s-f prev-btn cl-report-btn ds-c3" title="Preview" onclick="loadpreview();"><i class="fas fa-eye" ></i></button>
                                    <button type="button"style="display: none;"  class="btn btn-light border-right-0 font-16 s-f dwn-btn cl-report-btn ds-c3" title="Download 10K" onclick="downloadFile50k('xlsx');"><i class="fas fa-download"></i></button>
                                    <button type="button" class="btn btn-light font-16 s-f cn-report-btn" title="Create New"><i class="fas fa-plus ds-c"></i></button>
                                    <button type="button" style="display: none;" class="btn btn-light border-right-0 font-16 s-f seg-clr-btn ds-c3" title="Clear" onclick="addsubClear();"><i class="fas fa-eraser"></i></button>
                                    <button type="button" style="display: none;" class="btn btn-light border-right-0 font-16 s-f meta-go-btn ds-c3" title="Go" onclick="dispReportMeta();"><i class="fas fa-list-alt"></i></button>
                                    <button type="button" id="save" style="display: none;" class="btn btn-light border-right-0 font-16 s-f ds-c3" title="Next"><i class="fas fa-arrow-right"></i></button>
                                   {{-- <button type="button" class="btn btn-light border-right-0 font-12 s-f ds-c3" title="Run Option" id="saveoption" style="display: none;" onclick="addsubSQL(true);"><i class="fas fa-ellipsis-h"></i></button>--}}
                                    <button type="button" style="display: none;" class="btn btn-light font-16 s-f clr-btn cl-report-btn ds-c3" title="Close"><i class="fas fa-times-circle"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Tab panes -->
                    <div class="tab-content br-n pn">
                        <div class="tab-pane customtab" id="tab_20" role="tabpanel"></div>
                        <div class="tab-pane customtab" id="tab_21" role="tabpanel"></div>
                        <div class="tab-pane customtab active" id="tab_22" role="tabpanel"></div>
                        <div class="tab-pane customtab" id="tab_23" role="tabpanel"></div>
                        <div class="tab-pane customtab" id="tab_24" role="tabpanel"></div>
                        <div class="tab-pane customtab" id="tab_25" role="tabpanel"></div>
                        <div class="tab-pane" id="tab_26" role="tabpanel"></div>
                        <div class="tab-pane" id="tab_27" role="tabpanel"></div>
                        <div class="tab-pane" id="tab_28" role="tabpanel"></div>
                        <div class="tab-pane" id="tab_29" role="tabpanel"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <div id="chartPO" style="display:none;width: 100%; max-width:800px; height: 530px; "></div>
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
            </div>
        </div>

    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    @include('layouts.docker-rightsidebar')
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- Modals -->
    <!-- ============================================================== -->

    @include('layouts.modal.modals')
    <!-- ============================================================== -->
    <!-- End Modals -->
    <!-- ============================================================== -->
</div>
    <script src="elite/js/custom.js?ver=1582015557" type="text/javascript"></script>
    <script>
        var dDate = '{!! date('Ymd_Hi') !!}';
        var up_flag = 'new';

    </script>
@stop