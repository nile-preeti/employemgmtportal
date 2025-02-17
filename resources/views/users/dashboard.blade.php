<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Check-in/Check-out with Map</title>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('users/leaves.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">

    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #map {
            flex: 1;
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .info {
            text-align: center;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Check-in/Check-out with Map</title>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('jquery.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('style.css') }}">
        <link rel="stylesheet" href="{{ asset('users/attendance_records.css') }}">

        <style>
            body {
                margin: 0;
                display: flex;
                flex-direction: column;
                height: 100vh;
            }

            #map {
                flex: 1;
            }

            button:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }

            .info {
                text-align: center;
                font-size: 13px;
                margin-top: 5px;
            }

            .nav .nav-item button.active {
                    background-color: #8e8efe;
                    width: 100%;
                    text-align: left;
                    border-radius: 0;
                    padding: 18px;
                    font-size: 16px;
                    border-bottom: 1px solid #bbbbff;
            }

            .nav .nav-item button.active::after {
                content: "";
                border-right: 6px solid #bbbbff;
                height: 100%;
                position: absolute;
                right: -1px;
                top: 0;
            }

            .date-time-sec h2 {color: #000 !important;}

            .bg-gradient-danger{background-image: linear-gradient(310deg, #f5365c, #f56036);}
            .bg-gradient-success{background-image: linear-gradient(310deg,#2dce89,#2dcecc);}
            .bg-gradient-warning{background-image: linear-gradient(310deg, #fb6340, #fbb140);}
            .ic-dash img{height: 80px; background: #fff; padding: 14px; border-radius: 12px;}
            .card.card-img-holder {position: relative;}
            .card.card-img-holder .card-img-absolute {position: absolute; top: -170px; right: -14px; height: 440px;}


        </style>


    </head>

    <body>
        <div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="text-dark mb-4 mt-4 pb-0"> Dashboard</h2>
                            <a href="#" class="btn btn-primary" onclick="logout()">Logout</a>
                        </div>
                    </div>
                    <!-- <div class="col-md-12">
                        <div class=" d-flex align-items-start">
                          <ul class="nav naav-pills flex-column nav-pills border-end border-3 me-3 align-items-end" id="pills-tab" role="tablist" style="width: 20%; background: #4242da; height: 100vh; border-radius: 0px 20px 20px 0px;">
                            <li class="nav-item w-100" role="presentation">
                              <button class="nav-link text-light active position-relative" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" style="padding:18px;">Mark Attandance</button>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                              <button class="nav-link text-light position-relative" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" style="padding: 18px;"> Holiday</button>
                            </li>
                            <li class="nav-item w-100" role="presentation">
                              <button class="nav-link text-light position-relative" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" style="padding: 18px;">View Attandance</button>
                            </li>
                          </ul>
                          <div class="tab-content border rounded-3 border-primary p-3 text-danger w-100" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                              <h2 class="text-dark">Mark Attandance</h2>
                              <div class="container-fluid">
                                  <div class="row">
                                      
                                  </div>
                              </div>

                            </div>
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                              <div class="container-fluid">
                              <h2 class="text-dark">Holiday</h2>
                                  <div class="row">
                                      <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="p-2 d-flex justify-content-between date-time-sec">
                                                <h6>January 2025</h6>
                                            </div>

                                            <div class="card-body">
                                                <div class="attendance-record-data ">
                                                    <div class="d-md-flex justify-content-md-between">
                                                        <div class="">
                                                            <h6> Date : <span>01 Jan 2025</span>
                                                            </h6>
                                                        </div>
                                                        <div class="">
                                                            <h6> Day : <span>Monday</span></h6>
                                                        </div>
                                                        <div>
                                                            <div class="d-md-flex justify-content-md-end">
                                                                <h6> Holiday : New Year<span></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="p-2 d-flex justify-content-between date-time-sec">
                                                <h6>January 2025</h6>
                                            </div>

                                            <div class="card-body">
                                                <div class="attendance-record-data ">
                                                    <div class="d-md-flex justify-content-md-between">
                                                        <div class="">
                                                            <h6> Date : <span>01 Jan 2025</span>
                                                            </h6>
                                                        </div>
                                                        <div class="">
                                                            <h6> Day : <span>Monday</span></h6>
                                                        </div>
                                                        <div>
                                                            <div class="d-md-flex justify-content-md-end">
                                                                <h6> Holiday : New Year<span></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="p-2 d-flex justify-content-between date-time-sec">
                                                <h6>January 2025</h6>
                                            </div>

                                            <div class="card-body">
                                                <div class="attendance-record-data ">
                                                    <div class="d-md-flex justify-content-md-between">
                                                        <div class="">
                                                            <h6> Date : <span>01 Jan 2025</span>
                                                            </h6>
                                                        </div>
                                                        <div class="">
                                                            <h6> Day : <span>Monday</span></h6>
                                                        </div>
                                                        <div>
                                                            <div class="d-md-flex justify-content-md-end">
                                                                <h6> Holiday : New Year<span></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="p-2 d-flex justify-content-between date-time-sec">
                                                <h6>January 2025</h6>
                                            </div>

                                            <div class="card-body">
                                                <div class="attendance-record-data ">
                                                    <div class="d-md-flex justify-content-md-between">
                                                        <div class="">
                                                            <h6> Date : <span>01 Jan 2025</span>
                                                            </h6>
                                                        </div>
                                                        <div class="">
                                                            <h6> Day : <span>Monday</span></h6>
                                                        </div>
                                                        <div>
                                                            <div class="d-md-flex justify-content-md-end">
                                                                <h6> Holiday : New Year<span></span></h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                              <div class="container-fluid">
                              <h2 class="text-dark">View Attandance</h2>
                                  <div class="row">
                                      <div class="col-md-12 attendance-record-data-tbl">
                                        <div class="table-responsive" id="recordsTable">
                                            <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Check-in Time &amp; Address</th>
                                                        <th>Check-out Time &amp; Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>15 Jan 2025</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>15 Jan 2025</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                    </tr>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>15 Jan 2025</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                        <td>10:00 | 97/1, Block A main road Top floor Delhi</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div> -->
                    <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                        <div class="mark-attendance-sec">
                        <a href="{{ route('user.attendance')}}">
                            <div class="bg-gradient-danger card card-img-holder">
                                <div class="card-body p-3">
                                  <img src="../public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                      
                                        <p class="text-light text-sm text-uppercase fw-medium">Mark Attendance</p>
                                        <h3 class="text-light font-weight-bolder pb-0"> &nbsp; </h3>
                                       
                                      </div>
                                    </div>
                                    
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                        <img src="https://nileprojects.in/hrmodule/public/assets/images/ic-mark-attendance.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                        <div class="mark-attendance-sec">
                        <a href="{{ route('user.holidays')}}">
                            <div class="bg-gradient-success card card-img-holder">
                                <div class="card-body p-3">
                                  <img src="../public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                        <p class="text-light text-sm text-uppercase fw-medium">Holiday</p>
                                        <h3 class="text-light font-weight-bolder pb-0"> {{ $holidaysCount}} </h3>
                                      </div>
                                    </div>
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                        <img src="https://nileprojects.in/hrmodule/public/assets/images/ic-holiday.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                          </a>
                        </div>
                    </div>

                    <!-- <div class="col-md-4">
                        <div class="mark-attendance-sec">
                            <div class="bg-gradient-success card card-img-holder">
                                <div class="card-body p-3">
                                    <img src="..public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                        <p class="text-light text-sm text-uppercase fw-medium">Holiday</p>
                                        <h3 class="text-light font-weight-bolder pb-0"> 50 </h3>
                                      </div>
                                    </div>
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                        <img src="https://nileprojects.in/employee-portal/public/assets/images/ic-holiday.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-sm-6 col-md-6 col-lg-4 mb-3">
                        <div class="mark-attendance-sec">
                          <a href="{{route('user.attendance_records')}}">
                            <div class="bg-gradient-warning card card-img-holder">
                                <div class="card-body p-3">
                                  <img src="../public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                        <p class="text-light text-sm text-uppercase fw-medium">View Attendance</p>
                                        <h3 class="text-light font-weight-bolder pb-0"> &nbsp; </h3>
                                      </div>
                                    </div>
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                        <img src="https://nileprojects.in/hrmodule/public/assets/images/ic-view-attendace.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-6 col-lg-4">
                        <div class="mark-attendance-sec">
                          <a href="{{route('user.profile')}}">
                            <div class="bg-gradient-warning card card-img-holder">
                                <div class="card-body p-3">
                                  <img src="../public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                        <p class="text-light text-sm text-uppercase fw-medium">My Profile</p>
                                        <h3 class="text-light font-weight-bolder pb-0 opacity-0">0 </h3>
                                      </div>
                                    </div>
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                      <img src="https://nileprojects.in/hrmodule/public/assets/images/ic-profile.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>


                    <!-- <div class="col-md-4">
                        <div class="mark-attendance-sec">
                            <div class="bg-gradient-warning card card-img-holder">
                                <div class="card-body p-3">
                                  <img src="..public/assets/images/circle.svg" class="card-img-absolute" alt="circle-image">
                                  <div class="row">
                                    <div class="col-8">
                                      <div class="numbers">
                                        <p class="text-light text-sm text-uppercase fw-medium">View Attandance</p>
                                        <h3 class="text-light font-weight-bolder pb-0"> 120 </h3>
                                      </div>
                                    </div>
                                    <div class="col-4 text-end align-items-center d-flex justify-content-end">
                                      <div class="ic-dash rounded-circle">
                                        <img src="https://nileprojects.in/learni/public/assets/images/ic-view-attendace.png">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                </div>
              
            </div>
        </div>
    </body>

    </html>

    <script>
       function logout() {

var title = ' you want to logout ?';
Swal.fire({
    title: '',
    text: title,
    // iconHtml: '<img src="{{ asset('assets/images/question.png') }}" height="25px">',
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

        // localStorage.removeItem('user')
        $.get("{{ route('user.logout') }}", function(data) {
            if (data.success) {
                Swal.fire("Success", "Logged out successfully", 'success').then((result) => {
                    if (result.value) {

                        location.replace("{{ route('user.login') }}");


                    }
                });
            }
        })


    }

})

}
    </script>

</body>

</html>
