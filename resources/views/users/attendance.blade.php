<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Check-in/Check-out with Map</title>
    <link rel="apple-touch-icon" sizes="180x180" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NileTech">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('jquery.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <link rel="icon" type="image/jpeg" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <!-- Standard Favicon -->
    <link rel="shortcut icon" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" type="image/x-icon">

    <!-- Android and iOS Home Screen Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg">
    <link rel="apple-touch-icon" sizes="180x180" href="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <style>
        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        #map {
            flex: 1;
            height: 300px;
            position: relative !important;
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .info {
            text-align: center;
            font-size: 14px;
            margin-top: 5px;
            color: #000;
            font-weight: 500;
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

        .ic-arrow-left {
            padding: 6px;
            border-radius: 8px;
            border: 2px solid #064086;
            margin-right: 12px;
            background: #fff;
        }

        .swal2-confirm {
            background-color: #ffffff !important;
            border: 1px solid #064086 !important;
            color: #064086 !important;
            padding: 9px 30px;
            border-radius: 50px;
        }

        .swal2-confirm:hover {
            background: #fff !important;
        }

        .swal2-cancel {
            padding: 10px 20px;
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

        .swal2-popup.swal2-modal.swal2-show {
            padding: 40px;
        }
    </style>
</head>

<body>
    <header class="header py-2">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <a href="#"> <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="logo card-img-absolute"
                        alt="circle-image" height="50px"></a>

                <div class="dropdown text-end">
                    <a href="#"
                        class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->image ? asset('uploads/images/' . auth()->user()->image) : 'https://nileprojects.in/hrmodule/public/assets/images/image.png' }}" 
                alt="mdo" width="40" height="40" class="rounded-circle profile-image">
                        <h6 class="m-0 p-0 text-light profile-name"> &nbsp; Profile</h6>
                    </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{route('user.help')}}">Help</a></li>
                        <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </header>
    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" style="background: #e5e5e5">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" id="password" class="form-control" required />
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Login</button>
                    </form>
                    <p id="loginError" style="color: red; display: none;"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="hrmodule-section">

        <div class="hrmodule-punching-section">
            <div class="container">

                <div class="hrmodule-punching-item">
                    <div class="backbtn-ovrlp text-center"><a href="javascript:history.back()">
                            <img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg"
                                class="ic-arrow-left"></a>
                    </div>
                    
                    <div id="map"></div>
                    <div class="controls">
                        <div class="hrmodule-punching-controls-box">
                            {{-- <div class="punching-controls-icon">
                                <img src="{{ asset('watch-icon.svg') }}">
                        </div> --}}
                            <div style="display: flex;justify-content:center;">
                                <!-- <div class="text-center"><a href="javascript:history.back()">
                                    <img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"></a>
                            </div> -->
                            </div>
                            <div class="mb-3" style="display: flex;justify-content:center;">
                                <!-- <div class="d-flex align-items-center">
                                <div class="me-2"><a href="javascript:history.back()">
                                    <img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"></a>
                                </div>
                            </div> -->
                                <img src="https://nileprojects.in/hrmodule/public/assets/images/ic-clock.svg"
                                    class="" height="130px"></a>
                            </div>
                            <p class="info"> {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                            <div class="punching-time">
                                <span id="hours">00</span>:<span id="minutes">00</span>:<span
                                    id="seconds">00</span>
                            </div>
                            <div class="hrmodule-punching-item-action">
                                <div class="punching-btn">
                                    <button id="checkinBtn" class="checkinBtn">Check-in</button>
                                    <div class="info" id="checkinInfo"></div>
                                </div>
                                <div class="punching-btn">
                                    <button id="checkoutBtn" class="checkoutBtn" disabled>Check-out</button>
                                    <div class="info" id="checkoutInfo"></div>
                                </div>
                            </div>
                        </div>
                        <div class="hrmodule-table-card d-none">
                            {{-- <button class="btn-bl mb-2" id="fetchRecordsBtn">Fetch Records</button> --}}
                            <div class="crm-card-table table-responsive">
                                <table id="recordsTable" class="table">
                                    <thead>
                                        <tr>
                                            <th colspan="4">Date:{{ date('m-d-Y') }}</th>
                                        </tr>
                                        <tr>
                                            <th>Action</th>
                                            <th>Time</th>
                                            <th>Address</th>

                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const MAPBOX_TOKEN =
            "pk.eyJ1IjoidXNlcnMxIiwiYSI6ImNsdGgxdnpsajAwYWcya25yamlvMHBkcGEifQ.qUy8qSuM_7LYMSgWQk215w";
        document.addEventListener("DOMContentLoaded", function() {
            mapboxgl.accessToken = MAPBOX_TOKEN;
            // Initialize time variables


            let interval;

            function startClock() {
                // Clear any previous interval
                if (interval) {
                    clearInterval(interval);
                }

                // Start the interval to update the time every second
                interval = setInterval(updateClock, 1000);

                // Call once immediately to avoid delay
                updateClock();
            }

            function updateClock() {
                const now = new Date(); // Get current system time

                // Extract hours, minutes, and seconds
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                // Display the time
                document.getElementById('hours').textContent = hours;
                document.getElementById('minutes').textContent = minutes;
                document.getElementById('seconds').textContent = seconds;
            }

            // Start the clock immediately when the page loads
            startClock();


            // Call startTimer function when user checks in
            document.getElementById("checkinBtn").addEventListener("click", () => startTimer(Date.now()));

            let user = @json($user);
            console.log(user);
            const csrfToken = "{{ csrf_token() }}";
            // Show modal on page load




            // Modify POST requests to include the user ID
            async function saveDataToPHP(url, data) {
                const formData = new FormData();
                Object.entries(data).forEach(([key, value]) => {
                    formData.append(key, value);
                });

                const response = await fetch(url, {
                    method: "POST",
                    body: formData,
                });

                return response.json();
            }

            // Initialize Map and Buttons
            function getLocation() {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const {
                            latitude,
                            longitude
                        } = position.coords;
                        setupMap(latitude, longitude);
                        initializeButtons(latitude, longitude);
                    },
                    (error) => {
                        alert("Unable to get location: " + error.message);
                    }
                );
            }

            // Set up Map
            function setupMap(lat, lng) {
                map = new mapboxgl.Map({
                    container: "map",
                    style: "mapbox://styles/mapbox/streets-v11",
                    center: [lng, lat],
                    zoom: 14,
                });

                marker = new mapboxgl.Marker()
                    .setLngLat([lng, lat])
                    .addTo(map);
            }

            // Initialize Buttons
            function initializeButtons(lat, lng) {
                const checkinBtn = document.getElementById("checkinBtn");
                const checkoutBtn = document.getElementById("checkoutBtn");
                const fetchRecordsBtn = document.getElementById("fetchRecordsBtn");
                const checkinInfo = document.getElementById("checkinInfo");
                const checkoutInfo = document.getElementById("checkoutInfo");

                const startBreakBtn = document.getElementById("startBreakBtn");
                const endBreakBtn = document.getElementById("endBreakBtn");
                const startBreakInfo = document.getElementById("startBreakInfo");
                const endBreakInfo = document.getElementById("endBreakInfo");

                checkinBtn.addEventListener("click", async () => {
                    const address = await getAddressFromCoordinates(lat, lng);

                    // Send Check-in data to the backend
                    const result = await saveDataToPHP("{{ route('user.attendance.store') }}", {
                        _token: csrfToken, // Include CSRF token
                        user_id: user.id,
                        check_in_full_address: address,
                        check_in_latitude: lat,
                        check_in_longitude: lng,
                    });

                    if (result.status === "success") {
                        checkinInfo.textContent = ` ${result.data.check_in_time}`;
                        checkinBtn.disabled = true;
                        checkoutBtn.disabled = false;
                        Swal.fire("Success", result.message, "success");

                    } else {
                        // alert(result.message);
                        Swal.fire("Error", result.message, "error");
                    }
                });

                checkoutBtn.addEventListener("click", async () => {
                    const address = await getAddressFromCoordinates(lat, lng);

                    // Send Check-out data to the backend
                    const result = await saveDataToPHP("{{ route('user.attendance.update') }}", {
                        _token: csrfToken, // Include CSRF token
                        user_id: user.id,
                        check_out_full_address: address,
                        check_out_latitude: lat,
                        check_out_longitude: lng,
                    });

                    if (result.status === "success") {
                        checkoutInfo.textContent = ` ${result.data.check_out_time}`;
                        // checkoutBtn.disabled = true;
                        Swal.fire("Success", result.message, "success");
                    } else {
                        Swal.fire("Error", result.message, "error");
                    }
                });
                // fetchRecordsBtn.addEventListener("click", async () => {
                //     const result = await saveDataToPHP("{{ route('user.attendance.fetch') }}" + "?id=" +
                //         user.id, {
                //             _token: csrfToken, // Include CSRF token
                //             userId: user.id,
                //             action: "fetch",
                //         });

                //     if (result.status === "success") {
                //         displayRecords(result.data);
                //     } else {
                //         alert(result.message);
                //     }
                // });
            }

            // Fetch Address from Coordinates
            async function getAddressFromCoordinates(lat, lng) {
                const response = await fetch(
                    `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${MAPBOX_TOKEN}`
                );
                const data = await response.json();
                return data.features[0]?.place_name || "Unknown address";
            }

            // Display Records
            function displayRecords(records) {
                const recordsTableBody = document.querySelector("#recordsTable tbody");
                recordsTableBody.innerHTML = "";

                records.forEach((record) => {
                    console.log(record);

                    const row1 = document.createElement("tr");
                    row1.innerHTML = `
                            <td>Check In</td>
                            <td style="white-space: nowrap;">${record.check_in_time}</td>
                            <td style="white-space: nowrap;">${record.check_in_full_address}</td>
                           
                        `;
                    recordsTableBody.appendChild(row1);
                    if (record.check_out_time) {
                        const row2 = document.createElement("tr");
                        row2.innerHTML = `
                            <td>Check Out</td>
                            <td style="white-space: nowrap;">${record.check_out_time}</td>
                            <td style="white-space: nowrap;">${record.check_out_full_address}</td>
                          
                        `;
                        recordsTableBody.appendChild(row2);

                    }

                });
            }



            // If user is logged in, proceed with map and buttons
            user = @json($user);;
            console.log(user);
            if (user) {

                getLocation();
                fetchRecords();
                $("#name").text(user.name)
            }

            function fetchRecords() {
                $.get("{{ route('user.attendance.fetch.today') }}" + "?id=" + user.id, function(data) {
                    if (data.success) {
                        if (data.records) {


                            //     displayRecords(data.records);
                        }




                        if (data.today) {

                            if (data.today.check_in_time) {
                                const checkinInfo = document.getElementById("checkinInfo");
                                $("#checkinBtn").attr("disabled", true);
                                $("#checkoutBtn").attr("disabled", false);
                                checkinInfo.textContent = `${data.today.check_in_time}`;

                                // Convert the start time string to a timestamp and start the timer



                            }
                            if (data.today.check_out_time) {


                                const checkoutInfo = document.getElementById("checkoutInfo");
                                // $("#checkoutBtn").attr("disabled", true);
                                $("#startBreakBtn").attr("disabled", true);

                                checkoutInfo.textContent = ` ${data.today.check_out_time}`;

                            }
                        }
                    }
                });
            }



        });


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
                            Swal.fire({
                                title: "",
                                text: "Logged out successfully", // Show only the text
                                iconHtml: "", // Removes the default success icon
                                showConfirmButton: true,
                                confirmButtonText: "OK"
                            }).then((result) => {
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
    {{-- <script>
        let interval;

        // Function to parse time string and get timestamp
        function getTimestampFromTime(timeStr) {
            const [hours, minutes, seconds] = timeStr.split(':').map(Number);
            const now = new Date();
            now.setHours(hours, minutes, seconds, 0); // Set time to today's date
            return now.getTime();
        }

        // Function to start the timer when user checks in
        function startTimer(startTime) {
            // Clear any previous interval
            if (interval) {
                clearInterval(interval);
            }

            // Start the interval to update the time every second
            interval = setInterval(() => updateTime(startTime), 1000);
        }

        // Function to update the time display
        function updateTime(startTime) {
            const currentTime = Date.now();
            const elapsedTime = currentTime - startTime; // Time difference in milliseconds

            // Calculate elapsed time in hours, minutes, and seconds
            const hours = Math.floor(elapsedTime / (1000 * 60 * 60));
            const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

            // Format and display the time with leading zeros
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }
    </script> --}}
</body>

</html>
