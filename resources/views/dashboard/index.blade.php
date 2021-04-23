<?php //echo '<pre>'; print_r(Auth::user()->umeta); die; ?>
@extends('layouts.docker')
@section('content')

    <div class="container-fluid">

    <!-- ============================================================== -->
    <!-- Sales Chart and browser state-->
    <!-- ============================================================== -->
    <div class="row">

        <!-- Column
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5 class="card-title m-b-40">SALES IN 2018</h5>
                            <p>Lorem ipsum dolor sit amet, ectetur adipiscing elit. viverra tellus. ipsumdolorsitda amet, ectetur adipiscing elit.</p>
                            <p>Ectetur adipiscing elit. viverra tellus.ipsum dolor sit amet, dag adg ecteturadipiscingda elitdglj. vadghiverra tellus.</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div id="morris-area-chart" style="height:250px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         Column -->
    </div>
    <!-- ============================================================== -->
    <!-- End Sales Chart -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Review -->
    <!-- ============================================================== -->
    <div class="row">
        <!-- Column
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ORDER STATUS</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>User</th>
                            <th>Order date</th>
                            <th>Amount</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tracking Number</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a href="javascript:void(0)" class="link"> Order #53431</a></td>
                            <td>Steve N. Horton</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 22, 2014</span></td>
                            <td>$45.00</td>
                            <td class="text-center">
                                <div class="label label-table label-success">Paid</div>
                            </td>
                            <td class="text-center">-</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link"> Order #53432</a></td>
                            <td>Charles S Boyle</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 24, 2014</span></td>
                            <td>$245.30</td>
                            <td class="text-center">
                                <div class="label label-table label-info">Shipped</div>
                            </td>
                            <td class="text-center"><i class="fa fa-plane"></i> CGX0089734531</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link"> Order #53433</a></td>
                            <td>Lucy Doe</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 24, 2014</span></td>
                            <td>$38.00</td>
                            <td class="text-center">
                                <div class="label label-table label-info">Shipped</div>
                            </td>
                            <td class="text-center"><i class="fa fa-plane"></i> CGX0089934571</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link"> Order #53434</a></td>
                            <td>Teresa L. Doe</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 15, 2014</span></td>
                            <td>$77.99</td>
                            <td class="text-center">
                                <div class="label label-table label-info">Shipped</div>
                            </td>
                            <td class="text-center"><i class="fa fa-plane"></i> CGX0089734574</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link"> Order #53435</a></td>
                            <td>Teresa L. Doe</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 12, 2014</span></td>
                            <td>$18.00</td>
                            <td class="text-center">
                                <div class="label label-table label-success">Paid</div>
                            </td>
                            <td class="text-center">-</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link">Order #53437</a></td>
                            <td>Charles S Boyle</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 17, 2014</span></td>
                            <td>$658.00</td>
                            <td class="text-center">
                                <div class="label label-table label-danger">Refunded</div>
                            </td>
                            <td class="text-center">-</td>
                        </tr>
                        <tr>
                            <td><a href="javascript:void(0)" class="link">Order #536584</a></td>
                            <td>Scott S. Calabrese</td>
                            <td><span class="text-muted"><i class="fa fa-clock-o"></i> Oct 19, 2014</span></td>
                            <td>$45.58</td>
                            <td class="text-center">
                                <div class="label label-table label-warning">Unpaid</div>
                            </td>
                            <td class="text-center">-</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         Column -->
    </div>
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
    <!-- ============================================================== -->
    <!-- End Right sidebar -->
    <!-- ============================================================== -->
</div>
@stop