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
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
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
                    <div id="map"></div>
                    <div class="controls">
                        <div class="hrmodule-punching-controls-box">
                            {{-- <div class="punching-controls-icon">
                                <img src="{{ asset('watch-icon.svg') }}">
                            </div> --}}
                            <div style="display: flex;justify-content:space-between">
                                <h5>Name: <span id="name"></span></h5>
                                <div class="text-center"><Button class="btn btn-danger"
                                        onclick="logout()">Logout</Button></div>
                            </div>
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
                            <div class="hrmodule-punching-item-action">
                                <div class="punching-btn">
                                    <a href="{{ route('user.attendance_records') }}"
                                        style="text-decoration: underline">User
                                        Attendance</a>

                                </div>
                                <div class="punching-btn">
                                    <a href="{{ route('user.holidays') }}"
                                        style="text-decoration: underline">Holidays</a>

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
            user = JSON.parse(localStorage.getItem('user'));
            console.log(user);
            if (user) {
            
                getLocation();
                fetchRecords();
                $("#name").text(user.name)
            }

            function fetchRecords() {
                $.get("{{ route('user.attendance.fetch') }}" + "?id=" + user.id, function(data) {
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
                                checkoutInfo.textContent = ` ${data.today.check_out_time}`;

                            }
                        }
                    }
                });
            }



        });


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
