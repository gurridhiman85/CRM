@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp


<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                <li class="{!! ($segment == "addcontact") ? 'active' : '' !!}">
                    <a
                        @if(in_array('Add Contact',$visiblities) || $User_Type == 'Full_Access')
                            @if($segment == "lookup") href="lookup/add" @else href="lookup?req=add" @endif
                        @else
                            href="javascript:void(0);"
                        @endif
                        class="waves-effect waves-dark
                        @if(in_array('Add Contact',$visiblities) || $User_Type == 'Full_Access')
                            @if($segment == "lookup") ajax-Link @endif
                        @endif
                            {!! ($segment == "addcontact") ? 'active' : '' !!}"
                        aria-expanded="false"
                    >
                        <i class="ti-plus"></i>
                        <span class="hide-menu">Add Contact</span>
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
                                Contact
                            </a>
                        </li>

                        <li>
                            <a
                                    @if(in_array('Import Zoom',$visiblities) || $User_Type == 'Full_Access')
                                        href="importzoom"
                                    @else
                                        href="javascript:void(0);"
                                    @endif
                            >
                                Zoom
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{!! ($segment == "lookup") ? 'active' : '' !!}">
                    <a
                        @if(in_array('Lookup Contact',$visiblities) || $User_Type == 'Full_Access')
                            href="lookup"
                        @else
                            href="javascript:void(0);"
                        @endif
                            class="waves-effect waves-dark {!! ($segment == "lookup") ? 'active' : '' !!}" aria-expanded="false"><i
                                class="ti-gallery"></i><span
                                class="hide-menu">Lookup Contact</span></a>
                </li>

                <li class="{!! ($segment == "phone") ? 'active' : '' !!}">
                    <a
                        @if(in_array('Phone',$visiblities) || $User_Type == 'Full_Access')
                            href="phone"
                        @else
                            href="javascript:void(0);"
                        @endif
                            class="waves-effect waves-dark {!! ($segment == "phone") ? 'active' : '' !!}" aria-expanded="false"><i
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
                            class="waves-effect waves-dark {!! ($segment == "report") ? 'active' : '' !!}" aria-expanded="false"><i
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
                            class="waves-effect waves-dark {!! ($segment == "campaign") ? 'active' : '' !!}" aria-expanded="false"><i
                            class="ti-bag"></i><span
                            class="hide-menu">Campaign</span></a>
                </li>


                <li class="{!! ($segment == "dashboard") ? 'active' : '' !!}">
                    <a
                        @if(in_array('Dashboard',$visiblities) || $User_Type == 'Full_Access')
                            href="javascript:void(0)"
                        @else
                            href="javascript:void(0)"
                        @endif
                            class="waves-effect waves-dark {!! ($segment == "dashboard") ? 'active' : '' !!}" aria-expanded="false">
                        <i class="icon-speedometer"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
              </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
