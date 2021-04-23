@extends('layouts.docker')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles p-t-15 p-r-10 pb-2">
            <div class="col-md-12 align-self-center">
                <div class="row pl-2 pt-2">
                    <h6 class="text-themecolor">Help</h6>
                </div>
            </div>
        </div>

        <div class="row all-doc pl-2 pr-2">
            <div class="col-md-12 pr-0">
                <div class="card">
                    <div class="card-body">
                        <div id="jstree_my_files_div" class="clearfix" style="width: 20%; border: 0.5px solid #e9ecef;background-color: #f9f9f9;">
                            <ul>
                            </ul>
                        </div>

                        <div class="main-doc">
                            <a class="btn default btn-outline image-popup-vertical-fit" id="helpSection" data-fancybox="gallery"
                               href=""></a>
                        </div>
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
    </div>

@stop
