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

        #pagination-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        #pagination-controls button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 0 10px;
        }

        #pagination-controls button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        #pagination-controls button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        #page-info {
            font-size: 16px;
            margin: 0 20px;
            color: #333;
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
                        <h6 class="m-0 p-0 text-light"> &nbsp; Profile</h6>
                    </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="{{route('user.profile')}}">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
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
                    <h2 class="py-4 text-dark mb-2 mt-2"><a href="javascript:history.back()" style="font-size: 19px;text-decoration:underline">
                            < Home</a> Attendance Record</h2>
                </div>
                <div class="row">
                    <ul id="recordsList" style="padding-left: 50px;list-style: none;">
                        <!-- Dynamic content will be added here by the JavaScript -->
                    </ul>

                    <div id="pagination-controls" class="d-flex justify-content-end">
                        <button id="prev-page" onclick="changePage('prev')" disabled>Previous</button>
                        <span id="page-info"></span>
                        <button id="next-page" onclick="changePage('next')">Next</button>
                    </div>
                </div>
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

                    <!-- <div class="table-responsive" id="recordsTable">
                        <table class="table table-borderless bsb-table-xl text-nowrap align-middle m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>

                                    <th>Check-in Time </th>
                                    <th>
                                        Check-in Address
                                    </th>
                                    <th>Check-out Time</th>
                                    <th>
                                        Check-out Address
                                    </th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div> -->

                </div>
            </div>

        </div>
    </div>
    <script>
     let currentPage = 1;
let lastPage = 1;

// Function to display records
function displayRecords(records) {
    console.log(records);
    const recordsList = document.querySelector("#recordsList");
    recordsList.innerHTML = ""; // Clear previous records

    records.forEach((record) => {
        const listItem = document.createElement("li");
        listItem.classList.add("mt-4");

        const formatTime = (time) => {
            if (!time || time === "N/A") return "N/A"; // Handle missing values properly
            return new Date(`1970-01-01T${time}`).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        const formatAddress = (address) => address || "N/A";

        // Determine Status for Display
        let statusLabel = "Present"; // Default Status
        let bgColor = "badge-success"; // Default Green for Present

        if (record.status === "Absent") {
            statusLabel = "Absent";
            bgColor = "badge-danger"; // Red for Absent
        } else if (record.status === "Holiday") {
            statusLabel = "Holiday";
            bgColor = "badge-warning"; // Yellow for Holiday
        } else if (record.status === "Weekly Off") {
            statusLabel = "Weekly Off";
            bgColor = "badge-info"; // Blue for Weekly Off
        } else if (record.status === "N/A") {  
            statusLabel = "N/A";
            bgColor = "badge-secondary"; // Grey for N/A
        }

        // If status is an object, it means the user has attendance (check-in/check-out times)
        if (typeof record.status === "object") {
            statusLabel = "Present";
            bgColor = "badge-success"; // Default for present
        }

        listItem.innerHTML = `
        <div class="col-md-12">
            <div class="card">
                <div class="d-flex justify-content-between date-time-sec">
                    <h6>
                        Date: ${new Date(record.date).toLocaleDateString('en-GB')} 
                        <span class="badge ${bgColor}">${statusLabel}</span>
                    </h6>
                </div>
                <div class="card-body py-2 px-2">
                    <div class="attendance-record-data">
                        <div class="d-md-flex justify-content-md-between">
                            <div>
                                <h6> Check-in Time: <span>${formatTime(record.status.check_in_time)}</span></h6>
                                <h6> Check-in Address: <span>${formatAddress(record.status.check_in_address)}</span></h6>
                            </div>
                            <div>
                                <div class="d-md-flex justify-content-md-end">
                                    <h6> Check-out Time: <span>${formatTime(record.status.check_out_time)}</span></h6>
                                </div>
                                <div class="d-md-flex justify-content-md-end">
                                    <h6> Check-out Address: <span>${formatAddress(record.status.check_out_address)}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

        recordsList.appendChild(listItem);
    });
}


// Function to update pagination controls
function updatePaginationControls() {
    const paginationControls = document.getElementById("pagination-controls");
    const pageInfo = document.getElementById("page-info");
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");

    pageInfo.textContent = `Page ${currentPage} of ${lastPage}`;

    prevButton.disabled = currentPage <= 1;
    nextButton.disabled = currentPage >= lastPage;

    // Hide pagination if there's only one page
    paginationControls.style.display = lastPage <= 1 ? "none" : "flex";
}

// Function to change the page
function changePage(direction) {
    if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    } else if (direction === 'next' && currentPage < lastPage) {
        currentPage++;
    }

    fetchRecords(currentPage);
}

// Function to fetch attendance records for a specific page
function fetchRecords(page = 1) {
    $.get("{{ route('user.attendance.fetch') }}", {
        id: user.id,
        page: page
    }, function(data) {
        if (data.success) {
            if (data.records) {
                displayRecords(data.records); // Display the records
                currentPage = data.current_page; // Update current page
                lastPage = data.last_page; // Update last page
                updatePaginationControls(); // Update pagination controls
            }
        }
    });
}

// Check if user is logged in and fetch records
var user = @json($user);
if (user) {
    fetchRecords(); // Fetch records for the first page
    $("#name").text(user.name); // Display user name
} else {
    window.location = "{{ route('user.dashboard') }}";
}
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        populateMonthFilter();
        fetchAttendance(); // Load attendance data initially
    });

    function populateMonthFilter() {
        const monthFilter = document.getElementById("monthFilter");
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1; // JavaScript months are 0-based
        const currentYear = currentDate.getFullYear();

        for (let i = 0; i < 12; i++) {
            const date = new Date(currentYear, currentMonth - 1 - i, 1);
            const monthValue = date.toISOString().slice(0, 7); // Format YYYY-MM
            const monthText = date.toLocaleString('default', { month: 'long', year: 'numeric' });

            const option = new Option(monthText, monthValue);
            if (monthValue === `${currentYear}-${String(currentMonth).padStart(2, '0')}`) {
                option.selected = true;
            }
            monthFilter.appendChild(option);
        }
    }

    function fetchAttendance(page = 1) {
        const userId = 1; // Replace with dynamic user ID
        const selectedMonth = document.getElementById("monthFilter").value;

        fetch(`/fetch-attendance?id=${userId}&month=${selectedMonth}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayRecords(data.records);
                    updatePagination(data.current_page, data.last_page);
                }
            })
            .catch(error => console.error("Error fetching attendance:", error));
    }
</script>
</body>

</html>