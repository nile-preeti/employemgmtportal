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
        </style>
    </head>

    <body>
        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="py-4 text-dark mb-2 mt-2"><a href="{{route("user.dashboard")}}" style="font-size: 19px;text-decoration:underline">< Home</a> Holiday Calendar-{{date("Y")}}</h2>
                    </div>
                </div>
                <div class="row">
                    @foreach ($holidays as $key => $item)
                        <div class="col-md-12 mb-3">
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
                                        <div class="att-divider"></div>
                                    @endforeach


                                </div>
                            </div>
                        </div>
                    @endforeach



                </div>
            </div>
        </div>
    </body>

    </html>


</body>

</html>
