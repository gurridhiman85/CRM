@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp


<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            @php
                $dashboard_types =  DB::select("SELECT * FROM ZChart_Links");
                $dashboard_types = collect($dashboard_types)->map(function($x){ return (array) $x; })->toArray();
                $key = array_search($segment, array_column($dashboard_types, 'link'));
                $namekey = array_search('Donor', array_column($dashboard_types, 'name'));
            @endphp
            <ul id="sidebarnav">
                @if(!Auth::check())
                    @php
                        $visiblities = ['dashboard'];
                        $User_Type = '';
                    @endphp
                    <li class="{!! ($key > -1) ? 'active' : '' !!}">
                        <a
                                @if(in_array('Dashboard',$visiblities) || $User_Type == 'Full_Access')
                                @if(!$key)
                                href="{{ $dashboard_types[$namekey]['link'] }}"
                                @else
                                href="javascript:void(0)"
                                @endif
                                @else
                                href="javascript:void(0)"
                                @endif
                                class="@if($key > -1) has-arrow @endif waves-effect waves-dark {!! ($key > -1) ? 'active' : '' !!}"
                                aria-expanded="false">
                            <i class="icon-speedometer"></i>
                            <span class="hide-menu dashboard-nav">Dashboard</span>
                        </a>

                        @if($key > -1)
                            <ul aria-expanded="false" class="collapse dashboard-page">
                                @foreach($dashboard_types as $dashboard_type)
                                    <li>
                                        <a
                                                href="javascript:void(0);"
                                                class="{{ $dashboard_type['link'] ==  $segment ? 'active' : '' }}"
                                                data-page="{{ $dashboard_type['name'] }}"
                                                onclick="syncDashboard('{{ $dashboard_type['name'] }}','{{ $dashboard_type['link'] }}');"
                                        >
                                            {{ $dashboard_type['name'] }} Dashboard
                                        </a>
                                        <hr class="mt-1 mb-1">
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @else
                    <li class="{!! ($key > -1) ? 'active' : '' !!}">
                        <a
                                @if(in_array('Dashboard',$visiblities) || $User_Type == 'Full_Access')
                                @if(!$key)
                                href="{{ $dashboard_types[$namekey]['link'] }}"
                                @else
                                href="javascript:void(0)"
                                @endif
                                @else
                                href="javascript:void(0)"
                                @endif
                                class="@if($key > -1) has-arrow @endif waves-effect waves-dark {!! ($key > -1) ? 'active' : '' !!}"
                                aria-expanded="false">
                            <i class="icon-speedometer"></i>
                            <span class="hide-menu dashboard-nav">Dashboard</span>
                        </a>

                        @if($key > -1)
                            <ul aria-expanded="false" class="collapse dashboard-page">
                                @foreach($dashboard_types as $dashboard_type)
                                    <li>
                                        <a
                                                href="javascript:void(0);"
                                                class="{{ $dashboard_type['link'] ==  $segment ? 'active' : '' }}"
                                                data-page="{{ $dashboard_type['name'] }}"
                                                onclick="syncDashboard('{{ $dashboard_type['name'] }}','{{ $dashboard_type['link'] }}');"
                                        >
                                            {{ $dashboard_type['name'] }} Dashboard
                                        </a>
                                        <hr class="mt-1 mb-1">
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>

                    <li class="{!! ($segment == "lookup") ? 'active' : '' !!}">
                        <a
                                @if(in_array('Lookup Contact',$visiblities) || $User_Type == 'Full_Access')
                                href="lookup"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="waves-effect waves-dark {!! ($segment == "lookup") ? 'active' : '' !!}"
                                aria-expanded="false"><i
                                    class="ti-gallery"></i><span
                                    class="hide-menu">Lookup</span></a>
                    </li>

                    <li class="{!! ($segment == "activity") ? 'active' : '' !!}">
                        <a
                                @if(in_array('Activity',$visiblities) || $User_Type == 'Full_Access')
                                href="activity"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="waves-effect waves-dark {!! ($segment == "activity") ? 'active' : '' !!}"
                                aria-expanded="false"><i
                                    class="ti-layout-grid2"></i><span
                                    class="hide-menu">Activity</span></a>
                    </li>

                    <li class="{!! ($segment == "phone") ? 'active' : '' !!}">
                        <a
                                @if(in_array('Phone',$visiblities) || $User_Type == 'Full_Access')
                                href="phone"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="waves-effect waves-dark {!! ($segment == "phone") ? 'active' : '' !!}"
                                aria-expanded="false"><i
                                    class="ti-mobile"></i><span
                                    class="hide-menu">Phone</span></a>
                    </li>

                    <li class="{!! ($segment == "report") ? 'active' : '' !!}">
                        <a
                                @if(in_array('Report',$visiblities) || $User_Type == 'Full_Access')
                                href="report"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="waves-effect waves-dark {!! ($segment == "report") ? 'active' : '' !!}"
                                aria-expanded="false"><i
                                    class="ti-bar-chart"></i><span
                                    class="hide-menu">Report</span></a>
                    </li>

                    <li class="{!! ($segment == "campaign") ? 'active' : '' !!}">
                            <a
                                    @if(in_array('Campaign',$visiblities) || $User_Type == 'Full_Access')
                                    href="campaign"
                                    @else
                                    href="javascript:void(0);"
                                    @endif
                                    class="waves-effect waves-dark {!! ($segment == "campaign") ? 'active' : '' !!}"
                                    aria-expanded="false"><i
                                        class="ti-bag"></i><span
                                        class="hide-menu">Campaign</span></a>
                        </li>

                    <li class="{!! ($segment == "email") ? 'active' : '' !!}">
                        <a
                            @if(in_array('Email',$visiblities) || $User_Type == 'Full_Access')
                            href="email"
                            @else
                            href="javascript:void(0);"
                            @endif
                            class="waves-effect waves-dark {!! ($segment == "email") ? 'active' : '' !!}"
                            aria-expanded="false">
                            <i class="ti-email"></i>
                            <span class="hide-menu">Email</span>
                        </a>
                    </li>

                    <li class="{!! ($segment == "import") ? 'active' : '' !!}">
                        <a
                                @if((in_array('Import Contact',$visiblities) || in_array('Import Zoom',$visiblities)) || $User_Type == 'Full_Access')
                                href="import"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="has-arrow waves-effect waves-dark {!! ($segment == "import") ? 'active' : '' !!}"
                                aria-expanded="false">
                            <i class="ti-upload"></i>
                            <span class="hide-menu">Import</span>
                        </a>

                        <ul aria-expanded="false" class="collapse">

                            <li>
                                <a
                                        @if(in_array('Import Contact',$visiblities) || $User_Type == 'Full_Access')
                                        href="import"
                                        @else
                                        href="javascript:void(0);"
                                        @endif
                                >
                                    Bulk Contact
                                </a>
                                <hr class="mt-1 mb-1">
                            </li>

                            <li>
                                <a
                                        @if(in_array('Import Bulk CC',$visiblities) || $User_Type == 'Full_Access')
                                        href="importbulkcc"
                                        @else
                                        href="javascript:void(0);"
                                        @endif
                                >
                                    Bulk Email
                                </a>
                                <hr class="mt-1 mb-1">
                            </li>

                            <li>
                                <a
                                        @if(in_array('Import Zoom',$visiblities) || $User_Type == 'Full_Access')
                                        href="importzoom"
                                        @else
                                        href="javascript:void(0);"
                                        @endif
                                >
                                    Bulk Zoom
                                </a>
                                <hr class="mt-1 mb-1">
                            </li>


                            <li>
                                <a
                                        @if(in_array('Add Contact',$visiblities) || $User_Type == 'Full_Access')
                                        @if($segment == "lookup") href="lookup/add" @else href="lookup?req=add" @endif
                                        @else
                                        href="javascript:void(0);"
                                        @endif
                                        class="
                                    @if(in_array('Add Contact',$visiblities) || $User_Type == 'Full_Access')
                                        @if($segment == "lookup") ajax-Link @endif
                                        @endif
                                                "
                                        aria-expanded="false"
                                >
                                    Manual Contact
                                </a>
                                <hr class="mt-1 mb-1">
                            </li>

                            <li>
                                <a
                                        @if(in_array('Zoom Cleanse',$visiblities) || $User_Type == 'Full_Access')
                                        href="zoomcleanse"
                                        @else
                                        href="javascript:void(0);"
                                        @endif
                                >
                                    Zoom Cleanse
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="{!! ($segment == "taxonomy") ? 'active' : '' !!}">
                        <a
                                @if(in_array('Taxonomy',$visiblities) || $User_Type == 'Full_Access')
                                href="taxonomy"
                                @else
                                href="javascript:void(0);"
                                @endif
                                class="waves-effect waves-dark {!! ($segment == "taxonomy") ? 'active' : '' !!}"
                                aria-expanded="false">
                            <i class="ti-text"></i>
                            <span class="hide-menu">Taxonomy</span>
                        </a>
                    </li>
                @endif

                @if($key > -1)
                    <li class="" style="margin-left: auto;">
                        <a
                                href="javascript:void(0);"
                                onclick="$('#dashboardfiltersbox').modal({backdrop: true,show: true}); $('#dashboardfiltersbox').find('.modal-dialog').draggable({handle: '.modal-header'});"
                                class="waves-effect waves-dark text-center pl-1 pr-1"
                                aria-expanded="false">
                            <i class="fas fa-filter" style="color: #b7dee8;"></i>
                        </a>
                    </li>
                    <!--
                    <li class="" style="text-align: right;">
                        <a href="javascript:void(0);" onclick="printDashboard()"
                           class="waves-effect waves-dark text-center pl-1 pr-1" aria-expanded="false">
                            <i class="fas fa-print" style="color: #b7dee8;"></i>
                        </a>
                    </li>
                    <li class="" style="text-align: right;">
                        <a href="javascript:void(0);" class="waves-effect waves-dark text-center pl-1 pr-1"
                           aria-expanded="false">
                            <i class="fas fa-file-pdf" style="color: #e92639;"></i>
                        </a>
                    </li>
                    -->
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>

