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

            .bg-gradient-danger{background-image: linear-gradient(310deg, #ff315a, #f56036);}
            .bg-gradient-success{background-image: linear-gradient(310deg, #54f9b2, #2dcecc);}
            .bg-gradient-warning{background-image: linear-gradient(310deg, #fa613e, #ffb33e);}
/*            .bg-gradient-warning{background-image: linear-gradient(310deg, #00a1ff, #60c4ff);}*/
            
            .ic-dash img{height: 80px; background: #fff; padding: 14px; border-radius: 12px;}
            .card.card-img-holder {position: relative;}
            .card.card-img-holder .card-img-absolute {position: absolute; top: -170px; right: -14px; height: 440px;}
            .header {background:#064086; }
            .header img.logo {background: #fff; padding: 8px; border-radius: 6px;}
            .dropdown-toggle::after{color: #fff;}
            .profile-image{border: 2px solid #4183d1;}

        </style>


    </head>

    <body>
    <!-- <div class="header">
        <nav class="navbar">
            <div class="navbar-menu-wrapper" style="cursor: pointer;">
                <ul class="navbar-nav f-navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link nav-toggler" data-toggle="minimize">
                        <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="card-img-absolute" alt="circle-image" height="50px">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div> -->
    <header class="header py-2">
        <div class="container-fluid">
          <div class="d-flex flex-wrap align-items-center justify-content-between">
            
            <a href="#"> <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="logo card-img-absolute" alt="circle-image" height="50px"></a>

           


            <div class="dropdown text-end">
              <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://nileprojects.in/hrmodule/public/assets/images/image.png" alt="mdo" width="40" height="40" class="rounded-circle profile-image"> 
                <h6 class="m-0 p-0 text-light"> &nbsp; Profile</h6>
              </a>
              <ul class="dropdown-menu text-small" style="">
                <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </header>
    </body>

    </html>

   

</body>

</html>
