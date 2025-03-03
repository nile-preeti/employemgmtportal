@extends('layouts.app')
@section('content')
<!-- Page Content  -->
<div id="content-page" class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="iq-card">
                    <!-- <div class="iq-card-header d-flex justify-content-between">
                                                                                                              <div class="iq-header-title">
                                                                                                                 <h4 class="card-title">User List</h4>
                                                                                                              </div>
                                                                                                           </div> -->
                    <div class="iq-card-body">
                        <div class="iq-card-filter">
                            <div class="row justify-content-between">
                                <div class="col-sm-12 col-md-7 col-lg-7">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control" id="selectMonth" onchange="filterByMonth(this.value)">
                                                    <option value="">--Filter By Month--</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        @php
                                                        $currentMonth=date('m'); // Get current month as "02" , "03" , etc.
                                                        $selectedMonth=request('month', $currentMonth); // Use requested month or default to current
                                                        @endphp
                                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ date("F", mktime(0, 0, 0, $i, 1)) }}
                                                        </option>
                                                        @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-md-2">
                                            <div class="form-group">
                                                <select class="form-control" id="selectYear" onchange="filterByYear(this.value)">
                                                    <option value="">--Filter By Year--</option>
                                                    @php
                                                        $currentYear = date('Y'); // Get current year
                                                        $selectedYear = request('year', $currentYear); // Use requested year or default to current
                                                    @endphp
                                                    @for ($y = $currentYear - 5; $y <= $currentYear; $y++) <!-- Show last 5 years -->
                                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                                            {{ $y }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="users-filter-search">
                                                <div id="user_list_datatable_info" class="dataTables_filter filter-search-info">
                                                <form class="position-relative d-flex align-items-center" style="gap: 10px;">
                                                        <div class="form-group mb-0  flex-grow-1">
                                                            <input type="search" class="form-control" name="search"
                                                                placeholder="Search" aria-controls="user-list-table" value="{{$search}}">
                                                        </div>
                                                        <button type="submit" class="d-flex align-items-center justify-content-center" style="border: none; background: none; cursor: pointer;">
                                                                <i class="fa fa-search" style="color:#0069ac;font-size:20px;border: 1px solid #0069ac;box-shadow: 0px 8px 13px 0px rgba(0, 0, 0, 0.05);padding: 10px 0px;text-align: center;border-radius: 5px;width: 45px;height:45px;"></i>
                                                            </button>
                                                    </form>
                                                </div>
                                                <div class="btn-reload" onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                    <img src="{{ asset('reset.png') }}" height="20" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <a href="javascript:void(0);" class="ImportExcelbtn" id="downloadCsvBtn">
                                                <i class="fa fa-download"></i> Download Report
                                            </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped table-borderless table-hover mt-4" role="grid"
                                    aria-describedby="user-list-page-info">
                                    <thead>
                                        <tr>
                                            <th>Emp Id</th>
                                            <th> Name</th>
                                            <th>email</th>
                                            <th>Designation</th>
                                            <th>Reporting Manager</th>
                                            <th>Phone No.</th>
                                            <th>Total Working Days</th>
                                            <th>Total Present Days</th>
                                            <th>Total Absent Days</th>
                                            <th>Total Working Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!empty($allUserAttendance) && count($allUserAttendance) > 0)
                                        @foreach ($allUserAttendance as $item)
                                        <tr>
                                            <td>{{$item['emp_id'] ?? 'N/A'}}</td>
                                            <td class="d-flex align-items-center">
                                                <img class="avatar-40 rounded mr-2"
                                                    src="{{ isset($item['image']) ? asset("uploads/images/{$item['image']}") : 'https://nileprojects.in/hrmodule/public/assets/images/user/image.png' }}"
                                                    alt="profile">
                                                {{ $item['name'] ?? 'N/A' }}
                                            </td>

                                            <td>{{ $item['email'] }}</td>
                                            <td>{{$item['designation'] ?? 'N/A'}}</td>
                                            <td>{{ !empty($item['rep_manager']) ? $item['rep_manager'] : 'N/A' }}</td>
                                            <td>{{ !empty($item['phone']) ? '+91' . $item['phone'] : 'N/A' }}</td>
                                            <td>{{ $item['total_working_days'] }}</td>
                                            <td>{{ $item['total_present_days'] }}</td>
                                            <td>{{ $item['total_absent_days'] }}</td>
                                            <td>{{ $item['total_working_hours'] }}</td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="9" align="center">No records found</td>
                                        </tr>
                                        @endif



                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            {{-- Previous Button --}}
                                            @if ($allUserAttendance->onFirstPage())
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $allUserAttendance->previousPageUrl() }}">Previous</a>
                                            </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($allUserAttendance->links()->elements[0] ?? [] as $page => $url)
                                            @if ($page == $allUserAttendance->currentPage())
                                            <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                            @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($allUserAttendance->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $allUserAttendance->nextPageUrl() }}">Next</a>
                                            </li>
                                            @else
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                                            </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $('#downloadCsvBtn').click(function() {
            var search = '{{ request("search") }}';
            var month = '{{ request("month", date("m")) }}';
            var year = '{{ request("year", date("Y")) }}';

            var url = "{{ route('reporting.download.csv') }}?search=" + search + "&month=" + month + "&year=" + year;
            window.location.href = url;
        });
    });
</script>
<script>
    function filterByMonth(month) {
        var url = new URL(window.location.href);
        url.searchParams.set('month', month);
        window.location.href = url.href;
    }
    function filterByYear(year) {
        let month = document.getElementById("selectMonth").value; // Get selected month
        window.location.href = `?month=${month}&year=${year}`;
    }
</script>
@endpush