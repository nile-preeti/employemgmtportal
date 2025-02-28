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
        
        #recordsList li:first-child{margin-top: 0px !important;}
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
    <header class="header py-2">
        <div class="container-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <a href="#"> <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="logo card-img-absolute" alt="circle-image" height="50px"></a>




                <div class="dropdown text-end">
                    <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ auth()->user()->image ? asset('uploads/images/' . auth()->user()->image) : 'https://nileprojects.in/hrmodule/public/assets/images/image.png' }}" 
                alt="mdo" width="40" height="40" class="rounded-circle profile-image">
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
                    <h2 class="py-4 text-dark mb-2 mt-2"><a href="javascript:history.back()"><img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a>Employee Directory</h2>
                </div>
                <div class="col-md-6 mb-2">
                    <form class="mr-3 position-relative">
                        <div class="form-group mb-0">
                            <input type="search" class="form-control" name="search"
                                placeholder="Search" aria-controls="user-list-table" value="">
                        </div>
                    </form>
                </div>
                <div class="">
                    <div id="recordsList" style=";list-style: none;">
                        <!-- Dynamic content will be added here by the JavaScript -->
                    </div>

                    <div id="pagination-controls" class="d-flex justify-content-end">
                        <button id="prev-page" onclick="changePage('prev')" disabled>Previous</button>
                        <span id="page-info"></span>
                        <button id="next-page" onclick="changePage('next')">Next</button>
                    </div>
                </div>
                <div class="col-md-12 attendance-record-data-tbl">

                </div>
            </div>

        </div>
    </div>
    <script>
        let currentPage = 1;
        let lastPage = 1;
        let searchQuery = ''; // Store the search query

        // Function to display employee directory
        function displayEmployees(employees) {
            const recordsList = document.querySelector("#recordsList");
            recordsList.innerHTML = ""; // Clear previous records

            if (employees.length === 0) {
                // Show "No Records Found" when there is no data
                recordsList.innerHTML = `
            <li class="text-center mt-4">
                <h5 class="text-danger">No Records Found</h5>
            </li>`;
                return;
            }

            employees.forEach((employee) => {
                const listItem = document.createElement("li");
                listItem.classList.add("mt-4");

                listItem.innerHTML = `
        <div class="col-md-12">
            <div class="card">
                <div class="d-flex justify-content-between date-time-sec">
                    <h6>Name: ${employee.name}</h6>
                </div>
                <div class="card-body py-2 px-2">
                    <div class="attendance-record-data">
                        <div class="d-md-flex justify-content-md-between">
                            <div>
                                <h6>Employee ID: <span>${employee.emp_id}</span></h6>
                                <h6>Email: <span>${employee.email}</span></h6>
                            </div>
                            <div>
                                <div class="d-md-flex justify-content-md-end">
                                    <h6>Designation: <span>${employee.designation || 'N/A'}</span></h6>
                                </div>
                                <div class="d-md-flex justify-content-md-end">
                                    <h6>Reporting Manager: <span>${employee.rep_manager || 'N/A'}</span></h6>
                                </div>
                                <div class="d-md-flex justify-content-md-end">
                                     <h6>Phone: <span>${employee.phone ? `+91${employee.phone}` : 'N/A'}</span></h6>
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

            fetchEmployees(currentPage, searchQuery);
        }

        // Function to fetch employees with search
        function fetchEmployees(page = 1, search = '') {
            $.get("{{ route('user.employee.directory') }}", {
                page: page,
                search: search
            }, function(data) {
                if (data.success) {
                    displayEmployees(data.employees);
                    currentPage = data.current_page;
                    lastPage = data.last_page;
                    updatePaginationControls();
                    document.querySelector("input[name='search']").value = search; // Retain search query
                }
            });
        }

        // Search input event listener
        document.querySelector("input[name='search']").addEventListener("input", function() {
            searchQuery = this.value;
            fetchEmployees(1, searchQuery);
        });

        // Load employees when the page is ready
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.querySelector("input[name='search']");
            searchQuery = searchInput.value; // Get existing search query if any
            fetchEmployees(1, searchQuery);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
                const monthText = date.toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });

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

</body>

</html>