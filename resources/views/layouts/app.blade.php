<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ config('constant.siteTitle') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <!-- Chart list Js -->
    <link rel="stylesheet" href="{{ asset('assets/js/chartist/chartist.min.css') }}" />
    <!-- Typography CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/typography.css') }}" />
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
    @stack('css')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Wrapper Start -->
    <div class="wrapper">
        <!-- Sidebar  -->
        <div class="iq-sidebar">
            <div class="iq-sidebar-logo d-flex justify-content-center">
                <a href="index.html">
                    <div class="iq-light-logo">
                        <div class="iq-light-logo">
                            <img src="{{ asset('hrmodule.png') }}" height="200" class="" alt="" />
                        </div>
                    </div>
                </a>
                <div class="iq-menu-bt-sidebar">
                    <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                            <div class="main-circle">
                                <i class="ri-arrow-left-s-line"></i>
                            </div>
                            <div class="hover-circle">
                                <i class="ri-arrow-right-s-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="sidebar-scrollbar">
                <nav class="iq-sidebar-menu">
                    <ul id="iq-sidebar-toggle" class="iq-menu">
                        <li @if (Route::is('admin.dashboard')) class="active" @endif>
                            <a href="{{ route('admin.dashboard') }}" class="iq-waves-effect"><i class="ri-home-4-line"></i>
                                <span>Dashboard</span></a>
                        </li>

                        

                      
                        <li @if (Route::is('admin.users.index')) class="active" @endif>
                            <a href="{{ route('admin.users.index') }}" class="iq-waves-effect">
                                <i class="ri-user-settings-line"></i><span>Employee Management</span></a>
                        </li>

                        <li @if (Route::is('admin.holidayss.index')) class="active" @endif>
                            <a href="{{ route('admin.holidayss.index') }}" class="iq-waves-effect">
                            <i class="ri-calendar-event-line"></i><span>Holidays</span></a>
                        </li>

                        <li>
                            <a href="#" class="iq-waves-effect" onclick="askLogout()">
                                <i class="ri-login-box-line"></i><span>Logout</span>
                            </a>
                        </li>
                      
                    </ul>
                </nav>
                <div class="p-3"></div>
            </div>
        </div>
        <!-- TOP Nav Bar -->
        <!-- TOP Nav Bar -->
        <div class="iq-top-navbar">
            <div class="iq-navbar-custom">
                <nav class="navbar navbar-expand-lg navbar-light p-0">
                    <div class="navbar-left">
                        <div class="iq-search-bar d-none d-md-block">
                            <form action="#" class="searchbox">
                                @if (isset($back_url) )
                                
                                    <a class="btn btn-primary mr-2"
                                        href="{{ isset($back_url) ? $back_url : route('dashboard') }}"><i
                                            class="ri-arrow-left-line p-0"></i></a>
                                @endif

                                <h4 style="display: inline">{{ isset($title) ? $title : 'Dashboard' }}</h4>
                            </form>
                        </div>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-label="Toggle navigation">
                        <i class="ri-menu-3-line"></i>
                    </button>
                    <div class="iq-menu-bt align-self-center">
                        <div class="wrapper-menu">
                            <div class="main-circle"><i class="ri-arrow-left-s-line"></i></div>
                            <div class="hover-circle"><i class="ri-arrow-right-s-line"></i></div>
                        </div>
                    </div>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    </div>
                    <ul class="navbar-list">
                        <li>
                            <a href="#"
                                class="search-toggle iq-waves-effect d-flex align-items-center bg-primary rounded">
                                <img src="{{ asset('assets/images/user/image.png') }}" class="img-fluid rounded mr-3"
                                    alt="user">
                                <div class="caption">
                                    <h6 class="mb-0 line-height text-white">{{ auth()->user()->name }}</h6>
                                    <!-- <span class="font-size-12 text-white">Available</span> -->
                                </div>
                            </a>
                            <div class="iq-sub-dropdown iq-user-dropdown d-none">
                                <div class="iq-card shadow-none m-0">
                                    <div class="iq-card-body p-0 ">
                                        <div class="bg-primary p-3">
                                            <h5 class="mb-0 text-white line-height">{{ auth()->user()->name }}
                                            </h5>
                                            <span class="text-white font-size-12">Available</span>
                                        </div>

                                        <a href="{{ route('admin.profile') }}" class="iq-sub-card iq-bg-primary-hover">
                                            <div class="media align-items-center">
                                                <div class="rounded iq-card-icon iq-bg-primary">
                                                    <i class="ri-profile-line"></i>
                                                </div>
                                                <div class="media-body ml-3">
                                                    <h6 class="mb-0 ">Edit Profile</h6>
                                                    <p class="mb-0 font-size-12">Modify your personal details.</p>
                                                </div>
                                            </div>
                                        </a>

                                        <a href="{{ route('admin.change_password') }}"
                                            class="iq-sub-card iq-bg-primary-hover">
                                            <div class="media align-items-center">
                                                <div class="rounded iq-card-icon iq-bg-primary">
                                                    <i class="ri-account-box-line"></i>
                                                </div>
                                                <div class="media-body ml-3">
                                                    <h6 class="mb-0 ">Change Password</h6>
                                                    <p class="mb-0 font-size-12">Modify your password.</p>
                                                </div>
                                            </div>
                                        </a>

                                        <div class="d-inline-block w-100 text-center p-3">
                                            <a class="btn btn-primary dark-btn-primary" style="cursor: pointer"
                                                onclick="askLogout()" href="#" role="button">Sign out<i
                                                    class="ri-login-box-line ml-2"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- TOP Nav Bar END -->
        <!-- TOP Nav Bar END -->
        @yield('content')
    </div>


    <!-- Wrapper END -->
    <!-- Footer -->
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="row">
                <!-- <div class="col-lg-6">
                  <ul class="list-inline mb-0">
                     <li class="list-inline-item"><a href="privacy-policy.html">Privacy Policy</a></li>
                     <li class="list-inline-item"><a href="terms-of-service.html">Terms of Use</a></li>
                  </ul>
               </div> -->
                <div class="col-lg-12 text-center">
                    Copyright 2025 <a href="#">{{ config('constant.siteTitle') }}</a> All Rights
                    Reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer END -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="{{ asset('assets/js/Chart.js') }}"></script>

    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- Appear JavaScript -->
    <script src="{{ asset('assets/js/jquery.appear.js') }}"></script>
    <!-- Countdown JavaScript -->
    <script src="{{ asset('assets/js/countdown.min.js') }}"></script>
    <!-- Counterup JavaScript -->
    <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Apexcharts JavaScript -->
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <!-- Slick JavaScript -->
    <!-- <script src="{{ asset('assets/js/slick.min.js') }}"></script> -->
    <!-- Select2 JavaScript -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <!-- Magnific Popup JavaScript -->
    <script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Smooth Scrollbar JavaScript -->
    <script src="{{ asset('assets/js/smooth-scrollbar.js') }}"></script>
    <!-- lottie JavaScript -->
    <script src="{{ asset('assets/js/lottie.js') }}"></script>
    <!-- am core JavaScript -->
    <script src="{{ asset('assets/js/core.js') }}"></script>

    <!-- am animated JavaScript -->
    <script src="{{ asset('assets/js/animated.js') }}"></script>
    {{-- for dashbaor use only --}}
    {{-- <!-- ChartList Js -->
    <script src="{{ asset('assets/js/chartist/chartist.min.js') }}"></script>
    <!-- Chart Custom JavaScript -->
    <script async src="{{ asset('assets/js/chart-custom.js') }}"></script> --}}
    <!-- Custom JavaScript -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        function askLogout() {
            var title = ' you want to logout ?';
            Swal.fire({
                title: '',
                text: title,
                iconHtml: '<img src="{{ asset('assets/images/question.png') }}" height="25px">',
                customClass: {
                    icon: 'no-border'
                },
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    window.location = "{{ route('admin.logout') }}";

                }

            })

        }
    </script>
    @stack('js')
</body>

</html>
