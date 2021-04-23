
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Profile</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <!-- Row -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <center class="m-t-30">
                            <img src="{{$user->ProfileImageThumb}}"
                                 class="img-circle pp_img"
                                 onclick="$('#input-file-now-custom-1').trigger('click'); $('.pp').show()"
                                 width="150"/>
                            <div class="m-t-5">

                                <form class="ajax-Form" action="/user/profileimage" method="post" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <input type="file" name="user_image" id="input-file-now-custom-1" class="dropify" style="display: none !important;"/>
                                    <div class="form-group pp" style="display: none;">
                                        <div class="col-sm-12">
                                            <button class="btn btn-success" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <h4 class="card-title m-t-10">{{ $user->FullName }}</h4>
                            <h6 class="card-subtitle">{!! isset($user->details->fav_quote) ? $user->details->fav_quote : '' !!}</h6>

                        </center>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">

                        <small class="text-muted">Email address</small>
                        <h6>{!! $user->email !!}</h6>
                        <small class="text-muted p-t-30 db">Phone</small>
                        <h6>{!! isset($user->details->phone_no) ? $user->details->phone_no : '-' !!}</h6>
                        <small class="text-muted p-t-30 db">Address</small>
                        <h6>{!! isset($user->details->address) ? $user->details->address : '-' !!}</h6>
                        <!--
                        <div class="map-box">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d470029.1604841957!2d72.29955005258641!3d23.019996818380896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C+Gujarat!5e0!3m2!1sen!2sin!4v1493204785508"
                                    width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                        </div>
                        <small class="text-muted p-t-30 db">Social Profile</small>
                        <br/>
                        <button class="btn btn-circle btn-secondary"><i class="fab fa-facebook-f"></i></button>
                        <button class="btn btn-circle btn-secondary"><i class="fab fa-twitter"></i></button>
                        <button class="btn btn-circle btn-secondary"><i class="fab fa-youtube"></i></button>
                        -->
                    </div>
                </div>
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <div class="card">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs profile-tab" role="tablist">
                        <!--
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a>
                        </li> -->
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile"
                                                role="tab">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#settings"
                                                role="tab">Settings</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!--
                        <div class="tab-pane active" id="home" role="tabpanel">
                            <div class="card-body">
                                <div class="profiletimeline">
                                    <div class="sl-item">
                                        <div class="sl-left"><img src="../assets/images/users/1.jpg" alt="user"
                                                                  class="img-circle"/></div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                        class="sl-date">5 minutes ago</span>
                                                <p>assign a new task <a href="javascript:void(0)"> Design weblayout</a>
                                                </p>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 m-b-20"><img
                                                                src="../assets/images/big/img1.jpg"
                                                                class="img-responsive radius"/></div>
                                                    <div class="col-lg-3 col-md-6 m-b-20"><img
                                                                src="../assets/images/big/img2.jpg"
                                                                class="img-responsive radius"/></div>
                                                    <div class="col-lg-3 col-md-6 m-b-20"><img
                                                                src="../assets/images/big/img3.jpg"
                                                                class="img-responsive radius"/></div>
                                                    <div class="col-lg-3 col-md-6 m-b-20"><img
                                                                src="../assets/images/big/img4.jpg"
                                                                class="img-responsive radius"/></div>
                                                </div>
                                                <div class="like-comm"><a href="javascript:void(0)" class="link m-r-10">2
                                                        comment</a> <a href="javascript:void(0)" class="link m-r-10"><i
                                                                class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="sl-item">
                                        <div class="sl-left"><img src="../assets/images/users/2.jpg" alt="user"
                                                                  class="img-circle"/></div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                        class="sl-date">5 minutes ago</span>
                                                <div class="m-t-20 row">
                                                    <div class="col-md-3 col-xs-12"><img
                                                                src="../assets/images/big/img1.jpg" alt="user"
                                                                class="img-responsive radius"/></div>
                                                    <div class="col-md-9 col-xs-12">
                                                        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                            Integer nec odio. Praesent libero. Sed cursus ante dapibus
                                                            diam. </p> <a href="javascript:void(0)"
                                                                          class="btn btn-success"> Design weblayout</a>
                                                    </div>
                                                </div>
                                                <div class="like-comm m-t-20"><a href="javascript:void(0)"
                                                                                 class="link m-r-10">2 comment</a> <a
                                                            href="javascript:void(0)" class="link m-r-10"><i
                                                                class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="sl-item">
                                        <div class="sl-left"><img src="../assets/images/users/3.jpg" alt="user"
                                                                  class="img-circle"/></div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                        class="sl-date">5 minutes ago</span>
                                                <p class="m-t-10"> Lorem ipsum dolor sit amet, consectetur adipiscing
                                                    elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus
                                                    diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis
                                                    sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue
                                                    semper </p>
                                            </div>
                                            <div class="like-comm m-t-20"><a href="javascript:void(0)"
                                                                             class="link m-r-10">2 comment</a> <a
                                                        href="javascript:void(0)" class="link m-r-10"><i
                                                            class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="sl-item">
                                        <div class="sl-left"><img src="../assets/images/users/4.jpg" alt="user"
                                                                  class="img-circle"/></div>
                                        <div class="sl-right">
                                            <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                        class="sl-date">5 minutes ago</span>
                                                <blockquote class="m-t-10">
                                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                                                    eiusmod tempor incididunt
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        -->
                        <!--second tab-->
                        <div class="tab-pane active" id="profile" role="tabpanel">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 b-r"><strong>Full Name</strong>
                                        <br>
                                        <p class="text-muted">{{ $user->FullName }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                        <br>
                                        <p class="text-muted">{!! isset($user->details->phone_no) ? $user->details->phone_no : '-' !!}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6 b-r"><strong>Email</strong>
                                        <br>
                                        <p class="text-muted">{{ $user->email }}</p>
                                    </div>
                                    <div class="col-md-3 col-xs-6"><strong>Location</strong>
                                        <br>
                                        <p class="text-muted">{!! isset($user->details->country) ? $user->details->country : '-' !!}</p>
                                    </div>
                                </div>

                                <h4 class="font-medium m-t-30">About Me</h4>
                                <hr>

                                {!! isset($user->details->about_me) ? \App\Helpers\InputSenitizer::SanitizeHTML($user->details->about_me) : '-' !!}

                            </div>
                        </div>
                        <div class="tab-pane" id="settings" role="tabpanel">
                            <div class="card-body">
                                <form class="m-t-10 ajax-Form" method="post" action="/updateprofile">
                                    {!! csrf_field() !!}

                                    <div class="form-group">
                                        <h6>First Name <span class="text-danger">*</span></h6>
                                        <div class="controls">
                                            <input type="text" name="first_name" value="{{$user->first_name}}" placeholder=""
                                                   class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>Last Name <span class="text-danger">*</span></h6>
                                        <div class="controls">
                                            <input type="text" name="last_name" value="{{$user->last_name}}" placeholder=""
                                                   class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>Address 1</h6>
                                        <div class="controls">
                                            <input type="text" name="address1" value="{!! isset($user->details->address1) ? $user->details->address1 : '' !!}" placeholder=""
                                                   class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>Address 2</h6>
                                        <div class="controls">
                                            <input type="text" name="address2" value="{!! isset($user->details->address2) ? $user->details->address2 : '' !!}" placeholder=""
                                                   class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>Zip Code</h6>
                                        <div class="controls">
                                            <input type="text" name="zip_code" value="{!! isset($user->details->zip_code) ? $user->details->zip_code : '' !!}" placeholder=""
                                                   class="form-control form-control-line">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h6>Phone <span class="text-danger">*</span>
                                            <small class="text-muted">(999) 999-9999</small>
                                        </h6>
                                        <div class="controls">
                                            <input type="text" name="phone_no" value="{!! isset($user->details->phone_no) ? $user->details->phone_no : '' !!}" placeholder=""
                                                   class="form-control phone-inputmask"
                                                   id="phone-mask">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>About Me</h6>
                                        <div class="controls">
                                            <textarea rows="5" class="form-control form-control-line"
                                                      name="about_me">{!! isset($user->details->about_me) ? $user->details->about_me : '' !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <h6>Country</h6>
                                        <div class="controls">
                                            <select class="form-control form-control-line" name="country">
                                                <option value="">Select</option>
                                                <option {!! isset($user->details->country) && $user->details->country == 'India' ? 'selected' : '' !!} value="India">India</option>
                                                <option {!! isset($user->details->country) && $user->details->country == 'USA' ? 'selected' : '' !!} value="USA">USA</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="hidden" name="u_dataid" value="{{$user->u_dataid}}">
                                            <button class="btn btn-success" type="submit">Update Profile</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
        <!-- Row -->
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        @include('layouts.docker-rightsidebar')
    </div>
