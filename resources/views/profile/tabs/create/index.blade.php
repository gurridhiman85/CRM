@extends('layouts.docker')
@section('content')
    <?php
    \App\Library\AssetLib::library('AReport');
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div class="container-fluid">
        <div class="row page-titles p-t-15 p-r-10 pb-2">
            <div class="col-md-12 align-self-center">
                <div class="row pt-2">
                    <div class="col-md-1">
                        <h6 class="text-themecolor" style="color: #3ea6d0;font-weight: 500;">Report</h6>
                    </div>
                    <div class="col-md-11">
                        <div onclick="$('#showSqlBtn').toggle()">
                            <button type="button"
                                    title="SQL"
                                    id='showSqlBtn'
                                    style="display: none"
                                    disabled
                                    onclick='create_query_onclick();'>
                                <i class="fas fa-database"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            $aData1 = DB::select("select row_id,t_name from ar_List_Templates Where t_type = 'A' order by t_name");
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
                        <div id="inserttext" style='display:none'>
                            <input type='text' id='txtnewCampname' name='txtnewCampname' style="width:252px;" value=''>
                        </div>


                    </div>
                    <div class="row mb-2" style="border-bottom: 1px solid #dee2e6;">
                        <div class="col-md-8">
                            <ul class="nav nav-tabs customtab2 mt-2 border-bottom-0 font-14 tab-hash tab-ajax"role="tablist" data-href="report/get" data-default-tab="tab_22">

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

                                <li class="nav-item create-new" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="23" href="#tab_23" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">Create Report</span>
                                    </a>
                                </li>

                                <li class="nav-item view-report" style="display: none;border-bottom: 1px solid #dee2e6;">
                                    <a class="nav-link" data-toggle="tab" data-tabid="24" href="#tab_24" role="tab" aria-selected="true">
                                        <span class="hidden-sm-up"></span>
                                        <span class="hidden-xs-down">View Report</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-toolbar pull-right" role="toolbar" aria-label="Toolbar with button groups">
                                <div class="input-group">
                                    <div class="all-pagination" style="vertical-align: middle;margin: 10px;"></div>
                                    <div class="c-btn" style="display: none;"></div>
                                    <!-- <button type="button" style="display: none;border-color: #bfe6f6 !important;" class="btn btn-light border-right-0 font-16 s-f cnt-btn cl-report-btn" title="Count" onclick="check_sql_count();"><i class="fas fas fa-calculator" style="color: #90c3d7"></i></button> -->
                                    <button type="button" style="display: none;" class="btn btn-light border-right-0 font-16 s-f cnt-btn cl-report-btn ds-c3" title="Count" onclick="check_sql_count();"><i class="fas fa-calculator"></i></button>
                                    <button type="button"style="display: none;"  class="btn btn-light border-right-0 font-16 s-f cl-report-btn ds-c3" title="Preview" onclick="loadpreview();"><i class="fas fa-eye" ></i></button>
                                    <button type="button"style="display: none;"  class="btn btn-light border-right-0 font-16 s-f cl-report-btn ds-c3" title="Download 10K" onclick="downloadFile50k('xlsx');"><i class="fas fa-download"></i></button>
                                    <button type="button" class="btn btn-light font-16 s-f cn-report-btn" title="Create New"><i class="fas fa-plus ds-c"></i></button>
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

    @include('report.tabs.create.modal.modals')
    <!-- ============================================================== -->
    <!-- End Modals -->
    <!-- ============================================================== -->
</div>
    <script src="elite/js/custom.js?ver=1582015557" type="text/javascript"></script>
    <script>
        var dDate = '{!! date('Ymd_Hi') !!}';
        var up_flag = 'new';
        $(document).ready(function () {
            $('[href="#tab_22"]').trigger('click');
            $('.cn-report-btn').on('click',function () {
                $(this).hide();
                $('.cl-report-btn').show();
                $('.view-report').hide();
                $('.create-new').show();
                $('.list-report').hide();
                $('.c-btn').html('');

                $('a[href="#tab_23"]').trigger('click');

            });

            $('.clr-btn').on('click',function () {
                $('.cl-report-btn').hide();
                $('.cn-report-btn').show();
                $('.create-new').hide();
                $('.view-report').hide();
                $('.list-report').show();

                $('#libflag_name').val('');
                $('#libcamp_name').val('');
                $('#libcamp_id').val('');

                $('a[href="#tab_22"]').trigger('click');

            });
        })
    </script>
@stop