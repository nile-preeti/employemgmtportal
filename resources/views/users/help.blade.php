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

        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">


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
                <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
              </a>
              <ul class="dropdown-menu text-small" style="">
                <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
                <li><a class="dropdown-item" href="{{route('user.help')}}">Help</a></li>
              </ul>
            </div>
          </div>
        </div>
      </header>
      


        <div>
            <div class="container">
                <div class="row">
                <div class="col-md-12">
                    <h2 class="py-4 text-dark mb-2 mt-2"><a href="{{route('user.dashboard')}}"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a></h2>
                </div>
                    <div class="col-md-12">
                    <div class="d-flex flex-column">
                        <span><strong>To enable location access:</strong></span>
                        <ul>
                            <li>📱 <strong>Android (Chrome)</strong> → Settings → Site Settings → Location</li>
                            <li>🍏 <strong>iPhone (Safari)</strong> → Settings → Privacy → Location Services</li>
                        </ul>
                    </div>
                    </div>

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
    confirmButtonColor: '#064086',
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
