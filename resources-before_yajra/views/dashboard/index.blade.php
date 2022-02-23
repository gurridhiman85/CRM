<?php
if(!isset($sm))
    $sm = '';
?>
@extends('layouts.docker-horizontal')
@section('content')
    <?php
    \App\Library\AssetLib::library('sparkline');
    ?>
    <!--<link rel="stylesheet" type="text/css" href="js/Chart.js-2.7.2/docs/style.css?ver={{ time() }}">-->
    <style>
        canvas {
            background-color: #ffffff;
            width: 100% !important;
            max-width: 800px;
            padding: 5px !important;
            /*height: 250px !important;*/
        }

        .round {
            line-height: 37px;
            color: #fff;
            width: 35px;
            height: 35px;
            display: inline-block;
            text-align: center;
        }

        .table th, .table thead th {
            font-size: 13px;
        }
        .table td, .table th {
            padding: 8.4px 8px;
        }

        .c-border{
            border: 3px solid #e9ecef !important;
        }

        .border-bottom-0 {
            border-bottom: 0 !important;
        }

        .cp-3{
            padding: 1.25rem !important;
        }

    </style>
    <div class="container-fluid">

     <div class="dash" id="dash"></div>
    <!-- ============================================================== -->
    <!-- End Review -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Comment - chats -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Comment - chats -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Right sidebar -->
    <!-- ============================================================== -->
    <!-- .right-sidebar -->
    @include('layouts.docker-rightsidebar')

        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js?ver={{ time() }}"></script>
        <script src="js/Chart.js-2.7.2/dist/Chart.bundle.js?ver={{ time() }}"></script>
        <script src="js/Chart.js-2.7.2/samples/utils.js?ver={{ time() }}"></script>--}}
        <script src="js/Chart.js-master/dist/chart.js?ver={{ time() }}"></script>

    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->

        <script src="https://html2canvas.hertzen.com/dist/html2canvas.js?ver={{ time() }}"></script>
        <script type="application/javascript">
            var base_url = '{!! URL::to('/') !!}';
        </script>

        <script src="js/dashboard_charts.js?ver={{ time() }}"></script>
    </div>



    <div class="modal bs-example-modal-sm" id="dashboardfiltersbox" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm mr-0">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title pl-1" id="myModalLabel">Filters</h6>
                    <button type="button" class="close pr-3" data-dismiss="modal" aria-label="Close" style="background: transparent;"><i class="fas fa-times-circle" style="color: #d9d7d7;"></i></button>
                </div>
                <div class="modal-body p-1">
                    <div class="card mb-1">
                        <div class="card-body p-1">
                            <form class="ajax-Form" id="dashboard_form" method="post" action="{{ URL::to('/') }}/getdashboardinfo">
                                {!! csrf_field() !!}

                                <div class="form-body">
                                    <div class="card-body pt-1 pb-1">


                                        <div class="row d-none">
                                            <div class="col-md-12 p-0">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Filter Type</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control form-control-sm ajax-Select" id="filtertype" name="filtertype">
                                                            @php
                                                                $segment = Request::segment(1);
                                                                $dashboard_types =  DB::select("SELECT * FROM ZChart_Links");
                            $dashboard_types = collect($dashboard_types)->map(function($x){ return (array) $x; })->toArray();
                                                            @endphp
                                                            @foreach($dashboard_types as $dashoard_type)
                                                                <option {{ $segment == $dashoard_type['name'] ? 'selected' : '' }} value="{{ $dashoard_type['name'] }}">{{ $dashoard_type['name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 p-0">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Filter 1</label>
                                                    <div class="col-md-9">
                                                        <select name="filter1" class="form-control form-control-sm ajax-Select" id="filter1" data-placeholder="Select Values">
                                                            {!! $f1Options !!}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 p-0">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Filter 2</label>
                                                    <div class="col-md-9">
                                                        <select name="filter2" class="form-control form-control-sm ajax-Select" id="filter2" data-placeholder="Select Values">
                                                            {!! $f2Options !!}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 p-0">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Filter 3</label>
                                                    <div class="col-md-9">
                                                        <select name="filter3" class="form-control form-control-sm ajax-Select" id="filter3" data-placeholder="Select Values">
                                                            {!! $f3Options !!}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 p-0">
                                                <div class="form-group row">
                                                    <label class="control-label col-md-3">Filter 4</label>
                                                    <div class="col-md-9">
                                                        <select name="filter4" class="form-control form-control-sm ajax-Select" id="filter4" data-placeholder="Select Values">
                                                            {!! $f4Options !!}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@stop
