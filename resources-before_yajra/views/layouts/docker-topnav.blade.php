@php
    $segment = Request::segment(1);
    $segment2 = Request::segment(2);
@endphp
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="https://www.datasquare.com">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <!--<img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo"/> -->
                    <img src="{!! url('/').'/img/logo.gif' !!}" class="dark-logo" alt="homepage" style="height: 65px;"/>
                    <!-- Light Logo icon -->
                    <!--<img src="../assets/images/logo-light-icon.png" alt="homepage" class="light-logo"/>-->
                    <img src="{!! url('/').'/img/logo.gif' !!}" class="light-logo" alt="homepage" style="height: 65px;"/>

                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span>
                    <!-- dark Logo text -->
                    <!--<img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo"/> -->
                    <img src="{!! url('/').'/img/logo.gif' !!}" class="dark-logo" alt="homepage" style="   height: 65px;"/>
                    <!-- Light Logo text -->
                    <img src="{!! url('/').'/img/logo.gif' !!}" class="dark-logo" alt="homepage" style="   height: 65px;"/>



                </span>
            </a>
        </div>


        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto">
                <!-- This is  -->
                <li class="nav-item"><a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark"
                                        href="javascript:void(0)"><i class="ti-menu"></i></a></li>
				@if(Auth::check())
					{{--<li class="nav-item"><a
                            class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark"
                            href="javascript:void(0)"><i class="icon-menu"></i></a></li>--}}
				@endif

					<li class="nav-item ml-20">
					<a class="nav-link waves-effect waves-dark" href=""><img style="height: 55px; width: 60px;" src="{!! url('/').'/img/logo1.jpg' !!}"></a>
					</li>

                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <!--
                <li class="nav-item">
                    <form class="app-search d-none d-md-block d-lg-block">
                        <input type="text" class="form-control" placeholder="Search & enter">
                    </form>
                </li>-->
            </ul>

            <div class="center-title font-16">
                @if(Auth::check() || $segment == 'login')

				@else
				@endif
				{!! config('constant.top_nav_title') !!}
            </div>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                <!--
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                        <div class="notify"><span class="heartbit"></span> <span class="point"></span></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <ul>
                            <li>
                                <div class="drop-title">Notifications</div>
                            </li>
                            <li>
                                <div class="message-center">

                                    <a href="javascript:void(0)">
                                        <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Luanch Admin</h5> <span
                                                    class="mail-desc">Just see the my new admin!</span> <span
                                                    class="time">9:30 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span>
                                            <span class="time">9:10 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span>
                                            <span class="time">9:08 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:02 AM</span></div>
                                    </a>
                                </div>
                                </ul>
                            </li>
                            <li>
                                <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all
                                        notifications</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Messages -->
                <!-- ============================================================== -->
                <!--
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"> <i class="icon-note"></i>
                        <div class="notify"><span class="heartbit"></span> <span class="point"></span></div>
                    </a>
                    <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                        <ul>
                            <li>
                                <div class="drop-title">You have 4 new messages</div>
                            </li>
                            <li>
                                <div class="message-center">

                                    <a href="javascript:void(0)">
                                        <div class="user-img"><img src="../assets/images/users/1.jpg" alt="user"
                                                                   class="img-circle"> <span
                                                    class="profile-status online pull-right"></span></div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:30 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="user-img"><img src="../assets/images/users/2.jpg" alt="user"
                                                                   class="img-circle"> <span
                                                    class="profile-status busy pull-right"></span></div>
                                        <div class="mail-contnet">
                                            <h5>Sonu Nigam</h5> <span
                                                    class="mail-desc">I've sung a song! See you at</span> <span
                                                    class="time">9:10 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="user-img"><img src="../assets/images/users/3.jpg" alt="user"
                                                                   class="img-circle"> <span
                                                    class="profile-status away pull-right"></span></div>
                                        <div class="mail-contnet">
                                            <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span
                                                    class="time">9:08 AM</span></div>
                                    </a>

                                    <a href="javascript:void(0)">
                                        <div class="user-img"><img src="../assets/images/users/4.jpg" alt="user"
                                                                   class="img-circle"> <span
                                                    class="profile-status offline pull-right"></span></div>
                                        <div class="mail-contnet">
                                            <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span>
                                            <span class="time">9:02 AM</span></div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link" href="javascript:void(0);"> <strong>See all
                                        e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                -->
                <!-- ============================================================== -->
                <!-- End Messages -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- mega menu -->
                <!-- ============================================================== -->
                <!--
                <li class="nav-item dropdown mega-dropdown"><a class="nav-link dropdown-toggle waves-effect waves-dark"
                                                               href="" data-toggle="dropdown" aria-haspopup="true"
                                                               aria-expanded="false"><i
                                class="ti-layout-width-default"></i></a>
                    <div class="dropdown-menu animated bounceInDown">
                        <ul class="mega-dropdown-menu row">
                            <li class="col-lg-3 col-xlg-2 m-b-30">
                                <h4 class="m-b-20">CAROUSEL</h4>

                                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner" role="listbox">
                                        <div class="carousel-item active">
                                            <div class="container"><img class="d-block img-fluid"
                                                                        src="../assets/images/big/img1.jpg"
                                                                        alt="First slide"></div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="container"><img class="d-block img-fluid"
                                                                        src="../assets/images/big/img2.jpg"
                                                                        alt="Second slide"></div>
                                        </div>
                                        <div class="carousel-item">
                                            <div class="container"><img class="d-block img-fluid"
                                                                        src="../assets/images/big/img3.jpg"
                                                                        alt="Third slide"></div>
                                        </div>
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                                       data-slide="prev"> <span class="carousel-control-prev-icon"
                                                                aria-hidden="true"></span> <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                                       data-slide="next"> <span class="carousel-control-next-icon"
                                                                aria-hidden="true"></span> <span
                                                class="sr-only">Next</span> </a>
                                </div>

                            </li>
                            <li class="col-lg-3 m-b-30">
                                <h4 class="m-b-20">ACCORDION</h4>

                                <div class="accordion" id="accordionExample">
                                    <div class="card m-b-0">
                                        <div class="card-header bg-white p-0" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#collapseOne" aria-expanded="true"
                                                        aria-controls="collapseOne">
                                                    Collapsible Group Item #1
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card m-b-0">
                                        <div class="card-header bg-white p-0" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseTwo"
                                                        aria-expanded="false"
                                                        aria-controls="collapseTwo">
                                                    Collapsible Group Item #2
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card m-b-0">
                                        <div class="card-header bg-white p-0" id="headingThree">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"
                                                        data-toggle="collapse" data-target="#collapseThree"
                                                        aria-expanded="false"
                                                        aria-controls="collapseThree">
                                                    Collapsible Group Item #3
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                             data-parent="#accordionExample">
                                            <div class="card-body">
                                                Anim pariatur cliche reprehenderit, enim eiusmod high.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="col-lg-3  m-b-30">
                                <h4 class="m-b-20">CONTACT US</h4>

                                <form>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="exampleInputname1"
                                               placeholder="Enter Name"></div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Enter email"></div>
                                    <div class="form-group">
                                        <textarea class="form-control" id="exampleTextarea" rows="3"
                                                  placeholder="Message"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </form>
                            </li>
                            <li class="col-lg-3 col-xlg-4 m-b-30">
                                <h4 class="m-b-20">List style</h4>

                                <ul class="list-style-none">
                                    <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can
                                            give link</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a>
                                    </li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another
                                            Give link</a></li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a>
                                    </li>
                                    <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another
                                            fifth link</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                -->
                <!-- ============================================================== -->
                <!-- End mega menu -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- User Profile -->
                <!-- ============================================================== -->
				@if(Auth::check())
                    <li class="nav-item dropdown u-pro">
						<a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" style="color: #1f7293;" href=""
						   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="hidden-md-down">{{Auth::user()->User_FName.' '.Auth::user()->User_LName}} &nbsp;<i
										class="fa fa-angle-down"></i></span>
                        </a>
						<div class="dropdown-menu dropdown-menu-right animated "> <!-- flipInY-->
                            @if($User_Type == 'Full_Access')
                                <a href="javascript:void(0);" data-href="users/history" class="dropdown-item ajax-Link"><i class="fas fa-address-book"></i> Users Login History</a>
                            @endif
                            <a href="javascript:void(0);" onclick="$('#changepasswordBox').modal('show');" class="dropdown-item"><i class="fas fa-unlock-alt"></i> Change Password</a>
							<a href="logout" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
							<!-- text-->
						</div>
					</li>

                    <li class="nav-item dropdown">
                        <a class="nav-link waves-effect waves-dark" download href="help/CRM Square User Guide v5.4.pdf" title="Help" style="color: #1f7293;"> <i class="fas fa-question-circle" style="color: #5e92b1;"></i>
                        </a>
                    </li>

				@endif

				<li class="nav-item">
					<a
                            class="nav-link waves-effect waves-light"
                            @if(!Auth::check()) href="{{URL::to('/')}}/login" @else href="https://www.datasquare.com" @endif
                    >
                        <img style="width: 143px;" src="{!! url('/').'/img/crmlogo.png' !!}">
                    </a>
				</li>
            </ul>

        </div>
        <div class="progress wd" id="appprogress"></div>
    </nav>
</header>
<script>
    $(document).ready(function () {
        //$("body").hasClass("mini-sidebar") ? $('.light-logo').css({'height':'50px'}) : $('.light-logo').css({'height':'75px'});

        $('.sidebartoggler').on('click',function () {
            setTimeout(function () {
                if($("body").hasClass("mini-sidebar")){
                    console.log('yes has')
                    $('.light-logo').css({'height':'50px'});
                }else{
                    console.log('not at all')
                    $('.light-logo').css({'height':'65px'});
                }
            },100)

        })
    })
</script>
