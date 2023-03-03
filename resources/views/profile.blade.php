@extends('master')
@section('title', auth()->user()->first_name . ' ' . auth()->user()->last_name . ' | User Profile')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset('dist/img/male1.png') }}" alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center">
                                    {{ $user->title . ' ' . $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}
                                </h3>
                                <p class="text-muted text-center">
                                    @foreach($user->roles->pluck('name') as $roleName)
                                        {{ $roleName }} |
                                    @endforeach
                                    @if ($user->gender == 'M')
                                        <span>MALE</span>
                                    @elseif ($user->gender == 'F')
                                        <span>FEMALE</span>
                                    @elseif ($user->gender == 'O')
                                        <span>OTHER</span>
                                    @endif
                                    |
                                    @if ($user->status == 1)
                                        <span class="text-warning bg-success pl-1 pr-1 rounded">Active</span>
                                    @else
                                        <span class="text-warning bg-danger pl-1 pr-1 rounded">Suspended</span>
                                    @endif
                                </p>
                                @if ($user->role_name == 'STUDENT')
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Class</b> <a class="float-right">{{ $user->class_name }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Section</b> <a class="float-right">{{ $user->section_name }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Transport</b> <a class="float-right">{{ $user->transport_route_name }}</a>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- About Me Box -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Basic Information</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fas fa-user-tie mr-1"></i> Father Name</strong>
                                <p class="text-muted">{{ $user->father_name }}</p>
                                <hr>
                                <strong><i class="fas fa-user-alt mr-1"></i> Mother Name</strong>
                                <p class="text-muted">{{ $user->mother_name }}</p>
                                <hr>
                                <strong><i class="fas fa-mobile-android mr-1"></i> Contact Number</strong>
                                <p class="text-muted">{{ $user->contact_number }}@if ($user->contact_number2)
                                        | {{ $user->contact_number2 }}
                                    @endif
                                </p>
                                <hr>
                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                <p class="text-muted">{{ $user->address_line1 }}, {{ $user->city }},
                                    {{ $user->state }},
                                    {{ $user->country }}, {{ $user->pin_code }}</p>
                                <hr>
                                <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>
                                <p class="text-muted">{{ $user->remarks }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">User
                                            information</a></li>
                                </ul>

                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="profile">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="card collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <strong>Permissions: </strong>
                                                        </h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body p-0">
                                                        <div class="d-md-flex">
                                                            <p class="pl-3 pr-3 pt-3 rounded-sm">
                                                                @foreach (Auth()->user()->getPermissionsViaRoles()->pluck('name') as $getPermissionsViaRoles)
                                                                    <span
                                                                        class="bg-success rounded-pill d-inline-flex mb-1  pl-2 pr-2">{{ $getPermissionsViaRoles }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div><!-- /.d-md-flex -->
                                                    </div><!-- /.card-body -->
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="card collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <strong>Special Permissions: </strong>
                                                        </h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div><!-- /.card-header -->
                                                    <div class="card-body p-0">
                                                        <div class="d-md-flex">
                                                            <p class="pl-3 pr-3 pt-3 rounded-sm">
                                                                @foreach (Auth()->user()->getDirectPermissions()->pluck('name') as $getDirectPermissions)
                                                                    <span
                                                                        class="bg-success rounded-pill d-inline-flex mb-1  pl-2 pr-2">{{ $getDirectPermissions }}</span>
                                                                @endforeach
                                                            </p>
                                                        </div><!-- /.d-md-flex -->
                                                    </div><!-- /.card-body -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="card card-primary">
                                                    <div class="card-body">
                                                        <strong><i class="fas fa-user-tie mr-1"></i> Father
                                                            Name</strong>
                                                        <p class="text-muted">{{ $user->father_name }}</p>
                                                        <hr>
                                                        <strong><i class="fas fa-user-alt mr-1"></i> Mother
                                                            Name</strong>
                                                        <p class="text-muted">{{ $user->mother_name }}</p>
                                                        <hr>
                                                        <strong><i class="fas fa-mobile-android mr-1"></i> Contact
                                                            Number</strong>
                                                        <p class="text-muted">{{ $user->contact_number }}@if ($user->contact_number2)
                                                                | {{ $user->contact_number2 }}
                                                            @endif
                                                        </p>
                                                        <hr>
                                                        <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                                                        <p class="text-muted">{{ $user->email }}</p>
                                                        <hr>
                                                        <strong><i class="fas fa-map-marker-alt mr-1"></i>
                                                            Address</strong>
                                                        <p class="text-muted">{{ $user->address_line1 }},
                                                            {{ $user->city }}, {{ $user->state }},
                                                            {{ $user->country }},
                                                            {{ $user->pin_code }}</a>
                                                        </p>
                                                        <hr>
                                                        <strong><i class="fas fa-id-card mr-1"></i> Aadhar
                                                            Number</strong>
                                                        <p class="text-muted">{{ $user->aadhaar_number }}</p>
                                                        <hr>
                                                        <strong><i class="fas fa-calendar-day mr-1"></i> Date of
                                                            Birth</strong>
                                                        <p class="text-muted">
                                                            {{ date('Y-M-d', strtotime($user->date_of_birth)) }}</p>
                                                        <hr>
                                                        <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>
                                                        <p class="text-muted">{{ $user->remarks }}</p>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            </div>
                                            @if ($user->role_name !== 'STUDENT')
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="card card-primary">
                                                        <div class="card-body">
                                                            <strong> Joining
                                                                Date</strong>
                                                            <p class="text-muted">
                                                                @if ($user->joining_date)
                                                                    {{ date('Y-M-d', strtotime($user->joining_date)) }}
                                                                @endif
                                                            </p>
                                                            <hr>
                                                            <strong> Termination
                                                                Date</strong>
                                                            <p class="text-muted">
                                                                @if ($user->termination_date)
                                                                    {{ date('Y-M-d', strtotime($user->termination_date)) }}
                                                                @endif
                                                            </p>
                                                            <hr>
                                                            <strong> Allocated Casual Leave</strong>
                                                            <p class="text-muted">
                                                                {{ $user->allocated_casual_leave }}
                                                            </p>
                                                            <hr>
                                                            <strong> Allocated Sick Leave</strong>
                                                            <p class="text-muted">
                                                                {{ $user->allocated_sick_leave }}
                                                            </p>
                                                            <hr>
                                                            <strong> PF Number</strong>
                                                            <p class="text-muted">
                                                                {{ $user->pf_number }}
                                                            </p>
                                                            <hr>
                                                            <strong> ESI Number</strong>
                                                            <p class="text-muted">
                                                                {{ $user->esi_number }}
                                                            </p>
                                                            <hr>
                                                            <strong> Bank Account Number</strong>
                                                            <p class="text-muted">
                                                                {{ $user->bank_account_number }}
                                                            </p>
                                                            <hr>
                                                            <strong> IFSC Code</strong>
                                                            <p class="text-muted">
                                                                {{ $user->ifsc_code }}
                                                            </p>
                                                            <hr>
                                                            <strong> Union Number</strong>
                                                            <p class="text-muted">
                                                                {{ $user->un_number }}
                                                            </p>
                                                            <hr>
                                                            <strong> PAN Number</strong>
                                                            <p class="text-muted">
                                                                {{ $user->pan_number }}
                                                            </p>
                                                            <hr>
                                                            <strong> Travel Allowance</strong>
                                                            <p class="text-muted">
                                                                {{ $user->travel_allowance }}
                                                            </p>
                                                            <hr>
                                                            <strong> Gross Salary</strong>
                                                            <p class="text-muted">
                                                                {{ $user->gross_salary }}
                                                            </p>
                                                            <hr>
                                                            <strong> Basic Salary</strong>
                                                            <p class="text-muted">
                                                                {{ $user->basic_salary }}
                                                            </p>
                                                            <hr>
                                                            <strong> Grade Salary</strong>
                                                            <p class="text-muted">
                                                                {{ $user->grade_salary }}
                                                            </p>
                                                            <hr>
                                                            <strong> Salary Review Date</strong>
                                                            <p class="text-muted">
                                                                {{ $user->salary_review_date }}
                                                            </p>
                                                            <hr>
                                                            <strong> PF</strong>
                                                            <p class="text-muted">
                                                                @if ($user->pf == 1)
                                                                    YES
                                                                @else
                                                                    NO
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($user->role_name == 'STUDENT')
                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                    <div class="card card-primary">
                                                        <div class="card-body">
                                                            <strong> Local Guardian
                                                                Date</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->local_guardian_profile_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Relationship</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->relationship ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Student Email</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->email_student ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Student Quota</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->student_quota_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Registration</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->registration_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Acadamic Session</strong>
                                                            <p class="text-muted">
                                                                {{ $admission->academic_session_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Acadamic Session</strong>
                                                            <p class="text-muted">
                                                                {{ $promotion->student_admission_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Acadamic Session</strong>
                                                            <p class="text-muted">
                                                                {{ $promotion->student_class_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Acadamic Session</strong>
                                                            <p class="text-muted">
                                                                {{ $promotion->student_section_id ?? '' }}
                                                            </p>
                                                            <hr>
                                                            <strong> Acadamic Session</strong>
                                                            <p class="text-muted">
                                                                {{ $promotion->promotion_status ?? '' }}
                                                            </p>
                                                        </div>
                                                        <!-- /.card-body -->
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="activity">
                                        <!-- Post -->
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="../../dist/img/user1-128x128.jpg" alt="user image">
                                                <span class="username">
                                                    <a href="#">Jonathan Burke Jr.</a>
                                                    <a href="#" class="float-right btn-tool"><i
                                                            class="fas fa-times"></i></a>
                                                </span>
                                                <span class="description">Shared publicly - 7:30 PM today</span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Lorem ipsum represents a long-held tradition for designers,
                                                typographers and the like. Some people hate it and argue for
                                                its demise, but others ignore the hate as they create awesome
                                                tools to help create filler text for everyone from bacon lovers
                                                to Charlie Sheen fans.
                                            </p>

                                            <p>
                                                <a href="#" class="link-black text-sm mr-2"><i
                                                        class="fas fa-share mr-1"></i> Share</a>
                                                <a href="#" class="link-black text-sm"><i
                                                        class="far fa-thumbs-up mr-1"></i> Like</a>
                                                <span class="float-right">
                                                    <a href="#" class="link-black text-sm">
                                                        <i class="far fa-comments mr-1"></i> Comments (5)
                                                    </a>
                                                </span>
                                            </p>

                                            <input class="form-control form-control-sm" type="text"
                                                placeholder="Type a comment">
                                        </div>
                                        <!-- /.post -->

                                        <!-- Post -->
                                        <div class="post clearfix">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                                <span class="username">
                                                    <a href="#">Sarah Ross</a>
                                                    <a href="#" class="float-right btn-tool"><i
                                                            class="fas fa-times"></i></a>
                                                </span>
                                                <span class="description">Sent you a message - 3 days ago</span>
                                            </div>
                                            <!-- /.user-block -->
                                            <p>
                                                Lorem ipsum represents a long-held tradition for designers,
                                                typographers and the like. Some people hate it and argue for
                                                its demise, but others ignore the hate as they create awesome
                                                tools to help create filler text for everyone from bacon lovers
                                                to Charlie Sheen fans.
                                            </p>

                                            <form class="form-horizontal">
                                                <div class="input-group input-group-sm mb-0">
                                                    <input class="form-control form-control-sm" placeholder="Response">
                                                    <div class="input-group-append">
                                                        <button type="submit" class="btn btn-danger">Send</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.post -->

                                        <!-- Post -->
                                        <div class="post">
                                            <div class="user-block">
                                                <img class="img-circle img-bordered-sm"
                                                    src="../../dist/img/user6-128x128.jpg" alt="User Image">
                                                <span class="username">
                                                    <a href="#">Adam Jones</a>
                                                    <a href="#" class="float-right btn-tool"><i
                                                            class="fas fa-times"></i></a>
                                                </span>
                                                <span class="description">Posted 5 photos - 5 days ago</span>
                                            </div>
                                            <!-- /.user-block -->
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <img class="img-fluid" src="../../dist/img/photo1.png"
                                                        alt="Photo">
                                                </div>
                                                <!-- /.col -->
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <img class="img-fluid mb-3" src="../../dist/img/photo2.png"
                                                                alt="Photo">
                                                            <img class="img-fluid" src="../../dist/img/photo3.jpg"
                                                                alt="Photo">
                                                        </div>
                                                        <!-- /.col -->
                                                        <div class="col-sm-6">
                                                            <img class="img-fluid mb-3" src="../../dist/img/photo4.jpg"
                                                                alt="Photo">
                                                            <img class="img-fluid" src="../../dist/img/photo1.png"
                                                                alt="Photo">
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->
                                                </div>
                                                <!-- /.col -->
                                            </div>
                                            <!-- /.row -->

                                            <p>
                                                <a href="#" class="link-black text-sm mr-2"><i
                                                        class="fas fa-share mr-1"></i> Share</a>
                                                <a href="#" class="link-black text-sm"><i
                                                        class="far fa-thumbs-up mr-1"></i> Like</a>
                                                <span class="float-right">
                                                    <a href="#" class="link-black text-sm">
                                                        <i class="far fa-comments mr-1"></i> Comments (5)
                                                    </a>
                                                </span>
                                            </p>

                                            <input class="form-control form-control-sm" type="text"
                                                placeholder="Type a comment">
                                        </div>
                                        <!-- /.post -->
                                    </div>
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane" id="timeline">
                                        <!-- The timeline -->
                                        <div class="timeline timeline-inverse">
                                            <!-- timeline time label -->
                                            <div class="time-label">
                                                <span class="bg-danger">
                                                    10 Feb. 2014
                                                </span>
                                            </div>
                                            <!-- /.timeline-label -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-envelope bg-primary"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                                    <h3 class="timeline-header"><a href="#">Support Team</a> sent
                                                        you an email</h3>

                                                    <div class="timeline-body">
                                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                        quora plaxo ideeli hulu weebly balihoo...
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-user bg-info"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                                                    <h3 class="timeline-header border-0"><a href="#">Sarah Young</a>
                                                        accepted your friend request
                                                    </h3>
                                                </div>
                                            </div>
                                            <!-- END timeline item -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-comments bg-warning"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                                                    <h3 class="timeline-header"><a href="#">Jay White</a> commented
                                                        on your post</h3>

                                                    <div class="timeline-body">
                                                        Take me to your leader!
                                                        Switzerland is small and neutral!
                                                        We are more like Germany, ambitious and misunderstood!
                                                    </div>
                                                    <div class="timeline-footer">
                                                        <a href="#" class="btn btn-warning btn-flat btn-sm">View
                                                            comment</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END timeline item -->
                                            <!-- timeline time label -->
                                            <div class="time-label">
                                                <span class="bg-success">
                                                    3 Jan. 2014
                                                </span>
                                            </div>
                                            <!-- /.timeline-label -->
                                            <!-- timeline item -->
                                            <div>
                                                <i class="fas fa-camera bg-purple"></i>

                                                <div class="timeline-item">
                                                    <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                                                    <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded
                                                        new photos</h3>

                                                    <div class="timeline-body">
                                                        {{-- <img src="https://placehold.it/150x100" alt="...">
                                                        <img src="https://placehold.it/150x100" alt="...">
                                                        <img src="https://placehold.it/150x100" alt="...">
                                                        <img src="https://placehold.it/150x100" alt="..."> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END timeline item -->
                                            <div>
                                                <i class="far fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection()
