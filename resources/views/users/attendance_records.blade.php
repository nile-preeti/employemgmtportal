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
                    <h2 class="py-4 text-dark mb-2 mt-2"><a href="javascript:history.back()"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Attendance Record</h2>
                </div>
                <div class="row">
        <!-- Month & Year Filter -->
        <div class="col-md-4">
            <label for="month">Select Month:</label>
            <select id="month" class="form-control">
                <option value="01">January</option>
                <option value="02">February</option>
                <option value="03">March</option>
                <option value="04">April</option>
                <option value="05">May</option>
                <option value="06">June</option>
                <option value="07">July</option>
                <option value="08">August</option>
                <option value="09">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>

        <div class="col-md-4">
    <label for="year">Select Year:</label>
    <input type="number" id="year" class="form-control" min="2000" max="2050">
</div>

        <div class="col-md-4">
            <button class="btn btn-primary mt-4" onclick="fetchAttendance(1)">Filter</button>
        </div>
    </div>

                <div class="row">
                    <ol id="recordsList" style="padding-left: 50px;list-style: none;">
                        <!-- Dynamic content will be added here by the JavaScript -->
                    </ol>

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
// Function to update pagination controls
function updatePaginationControls() {
    const paginationControls = document.getElementById("pagination-controls");
    const pageInfo = document.getElementById("page-info");
    const prevButton = document.getElementById("prev-page");
    const nextButton = document.getElementById("next-page");

    pageInfo.textContent = `Page ${currentPage} of ${lastPage}`;

    prevButton.disabled = currentPage <= 1;
    nextButton.disabled = currentPage >= lastPage;

    if (lastPage <= 1) {
        paginationControls.style.cssText = "display: none !important;"; // Force hide
    } else {
        paginationControls.style.cssText = "display: flex !important;"; // Force show
    }
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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let today = new Date();
        let currentMonth = String(today.getMonth() + 1).padStart(2, '0'); // Get month (01-12)
        let currentYear = today.getFullYear();

        // Set current month
        document.getElementById("month").value = currentMonth;

        // Populate year dropdown (from 2020 to current year + 5)
        let yearSelect = document.getElementById("year");
        let startYear = 2020;
        let endYear = currentYear + 5;
        
        for (let year = startYear; year <= endYear; year++) {
            let option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            if (year === currentYear) {
                option.selected = true; // Select current year
            }
            yearSelect.appendChild(option);
        }
    });
</script>
   <script>
    $(document).ready(function () {
        let currentYear = new Date().getFullYear();
        let yearDropdown = $("#year");

        // Populate year dropdown (5 years back to 2 years ahead)
        for (let i = currentYear - 5; i <= currentYear + 2; i++) {
            yearDropdown.append(`<option value="${i}" ${i === currentYear ? 'selected' : ''}>${i}</option>`);
        }

        // Auto-fetch attendance for current month & year
        fetchAttendance(1);
    });

    function fetchAttendance(page = 1) {
    let userId = 1; // Replace with actual user ID
    let selectedMonth = $("#month").val();
    let selectedYear = $("#year").val();

    $.ajax({
        url: "{{ route('user.attendance.fetch') }}",
        type: "GET",
        data: {
            id: userId,
            month: selectedMonth,
            year: selectedYear,
            page: page
        },
        success: function (response) {
            if (response.success) {
                displayRecords(response.records); // Use the displayRecords function

                $("#page-info").text(`Page ${response.current_page} of ${response.last_page}`);
                $("#prev-page").prop("disabled", response.current_page === 1);
                $("#next-page").prop("disabled", response.current_page === response.last_page);

                currentPage = response.current_page;
                lastPage = response.last_page; // Ensure lastPage is updated
                updatePaginationControls(); // Keep pagination controls updated
            } else {
                alert(response.message);
            }
        }
    });
}

    function changePage(direction) {
        if (direction === "next") {
            fetchAttendance(currentPage + 1);
        } else if (direction === "prev" && currentPage > 1) {
            fetchAttendance(currentPage - 1);
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let currentYear = new Date().getFullYear();
        document.getElementById("year").value = currentYear;
    });
</script>
</body>

</html>