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
                <div class="col-md-12"><h2 class="py-4 text-dark mb-2 mt-2">Attendance Record</h2></div>
                <div class="col-md-12 attendance-record-data-tbl">
                    <!-- <div class=" table-responsive "> 
                        <table class="table table-bordered table-hover attendance-record-data-tbl">
                          <thead class="">
                            <tr>
                              <th>#</th>
                              <th>Date</th>
                              <th>Time</th>
                              <th>Check-in Time & Address</th>
                              <th>Check-out Time & Address</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                            <tr>
                              <th scope="row">1</th>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                            </tr>
                          </tbody>
                        </table>
                    </div> -->
                    <!-- Table 1 - Bootstrap Brain Component -->

                    <div class="table-responsive">
                      <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                        <thead>
                          <tr>
                              <th>#</th>
                              <th>Date</th>
                              <th >Time</th>
                              <th>Check-in Time & Address</th>
                              <th>Check-out Time & Address</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>

                          <tr>
                              <td>1</td>
                              <td>02 Feb 2025</td>
                              <td>10:00 AM </td>
                              <td>10:00 AM | 123/1, A block New Delhi</td>
                              <td>07:00 AM | 123/1, A block New Delhi</td>
                          </tr>
                         
                        </tbody>
                      </table>
                    </div>

                </div>
            </div>
            <!-- <div class="row">
                <ol style="padding-left: 50px;">
                    <li>
                        <div class="col-md-12 mt-2">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-md-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-md-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>    
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-md-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-md-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>    
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-md-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-md-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>    <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-md-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></li>

                    <li>    <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-md-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex  justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>    
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>    
                        <div class="col-md-12 mt-4">
                            <div class="card">
                                <div class="p-2 d-md-flex justify-content-between date-time-sec">
                                    <h6>Date : 02 Feb 2025</h6> 
                                    <h6>Time : 10:00 AM</h6>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-record-data">
                                        <div class="d-md-flex justify-content-between">
                                            <div class="">
                                                <h6> Check-in Time : <span>10:00</span></h6>
                                                <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                            </div>
                                            <div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Check-out Time : <span>10:00</span></h6></div>
                                                <div class="d-md-flex justify-content-md-end">
                                                    <h6> Address : <span>123/1, A block New Delhi</span></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                </ol>
            </div> -->
        </div>
    </div>
</body>

</html>
