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
    <link rel="icon" type="image/jpeg" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('users/leaves.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
        .swal2-confirm{
                background-color: #ffffff !important;
                border: 1px solid #064086 !important;
                color: #064086 !important;
                padding: 9px 30px;
                border-radius: 50px;
            } 

            .swal2-confirm:hover{background: #fff !important;}

            .swal2-cancel {    padding: 10px 20px;
                font-size: 14px;
                border: none;
                border-radius: 50px;
                background-color: #064086 !important;
                color: white;
                font-weight: 500;
                display: inline-block;
            }
           
            div#swal2-html-container {
                color: #000;
                font-weight: 500;
            }

            .swal2-popup.swal2-modal.swal2-show{padding: 40px;}
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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

            .header {
                background: #064086;
            }

            .header img.logo {
                background: #fff;
                padding: 8px;
                border-radius: 6px;
            }

            .dropdown-toggle::after {
                color: #fff;
            }

            .profile-image {
                border: 2px solid #4183d1;
            }
        </style>
    </head>

    <body>
        <header class="header py-2">
            <div class="container-fluid">
                <div class="d-flex flex-wrap align-items-center justify-content-between">

                    <a href="#"> <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="logo card-img-absolute" alt="circle-image" height="50px"></a>




                    <div class="dropdown text-end">
                        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://nileprojects.in/hrmodule/public/assets/images/image.png" alt="mdo" width="40" height="40" class="rounded-circle profile-image">
                            <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
                        </a>
                        <ul class="dropdown-menu text-small" style="">
                            <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="py-4 text-dark mb-2 mt-2"><a href="javascript:history.back()"> <img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a> Holiday Calendar-{{date("Y")}}</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach ($holidays as $key => $item)
                    <div class="col-md-12 col-lg-6 col-sm-12 mb-3">
                        <div class="card">
                            <div class="p-2 d-flex justify-content-between date-time-sec">
                                <h6>{{ $key }}</h6>
                            </div>

                            <div class="card-body">
                                @foreach ($item as $subitem)
                                <div class="attendance-record-data ">
                                    <div class="d-md-flex justify-content-md-between">
                                        <div class="">
                                            <h6> Date : <span>{{ date('d M Y', strtotime($subitem->date)) }}</span>
                                            </h6>
                                        </div>
                                        <div class="">
                                            <h6> Day : <span>{{ $subitem->day }}</span></h6>
                                        </div>
                                        <div>
                                            <div class="d-md-flex justify-content-md-end">
                                                <h6> Holiday : <span>{{ $subitem->reason }}</span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="att-divider"></div> -->
                                @endforeach


                            </div>
                        </div>
                    </div>
                    @endforeach



                </div>
            </div>
        </div>
        <script>
       function logout() {

var title = 'Are you sure, you want to logout ?';
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


</body>

</html>