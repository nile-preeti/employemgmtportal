<style type="text/css">
    button:disabled {
        background-color: #ffffff !important;
        cursor: not-allowed;
        border: 1px solid #064086 !important;
        color: #064086 !important;
    }
</style>

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

                       <div class="iq-card-body ">
                            <div class="attendance-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Name:</div>
                                            <div class="userlist-value">{{$user->name}}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Employee Id:</div>
                                            <div class="userlist-value">{{$user->emp_id}}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Designation:</div>
                                            <div class="userlist-value">{{$user->designation}}</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Email:</div>
                                            <div class="userlist-value">{{$user->email}}</div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
 
                            <div class="attendance-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Total Working Days: </div>
                                            <div class="userlist-value">{{ $totalWorkingDays }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Total Present:</div>
                                            <div class="userlist-value">{{ $totalPresent }}</div>
                                        </div>
                                    </div>
 
                                    <div class="col-md-6">
                                        <div class="userlist-card">
                                            <div class="userlist-text">Total Absent:</div>
                                            <div class="userlist-value" style="color:red"> {{ $totalAbsent }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="iq-card-body">
                            <div class="iq-card-filter">
                                <div class="row justify-content-between">
                                    {{-- <div class="col-sm-12 col-md-6">
                                            <div id="user_list_datatable_info" class="dataTables_filter">
                                                <form class="mr-3 position-relative">
                                                    <div class="form-group mb-0">
                                                        <input type="search" class="form-control" name="search"
                                                            placeholder="Search by  name..." aria-controls="user-list-table">
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control" id="selectcountry"
                                                    onchange="changeStatus(this.value)">
                                                    <option value="">--Filter By Status--</option>
                                                    <option value="Present"
                                                        @if (request()->has('status') && request('status') == 'Present') selected @endif>
                                                        Present </option>

                                                    <option value="Absent"
                                                        @if (request()->has('status') && request('status') == 'Absent') selected @endif>
                                                        Absent </option>

                                                        <option value="Half-day"
                                                        @if (request()->has('status') && request('status') == 'Half-day') selected @endif>
                                                        Half-day </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="selectMonth" onchange="filterByMonth(this.value)">
                                                    <option value="">--Filter By Month--</option>
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        @php
                                                            $currentMonth = date('m'); // Get current month as "02", "03", etc.
                                                            $selectedMonth = request('month', $currentMonth); // Use requested month or default to current
                                                        @endphp
                                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                            {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                            {{ date("F", mktime(0, 0, 0, $i, 1)) }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <form id="filterForm">
                                                    <input type="date" name="date" class="form-control" id="datePicker" value="{{ request('date') }}">
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-1">
                                            <div class="form-group">
                                            <a href="{{ route('attendance.csv', ['id' => $user->id, 'month' => request('month', date('m')), 'year' => request('year', date('Y'))]) }}" class="ImportExcelbtn">
            <i class="fa fa-download"></i>
        </a>
                                            </div>
                                        </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <div class="btn-reload" onclick="window.location.href = window.location.origin + window.location.pathname;">
                                                <img src="{{ asset('reset.png') }}" height="20" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                            
                                <table id="user-list-table" class="table table-striped table-borderless mt-4">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Check In Time</th>
                                            <th>Check In Location</th>
                                            <th>Check Out Time</th>
                                            <th>Check Out Location</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($allDaysPaginated as $item)
                                        <tr>
                                            <td>{{ date('Y-m-d', strtotime($item['date'])) }}</td>
                                            <td>{{ $item['check_in_time'] ?? 'N/A' }}</td>
                                            <td title="{{ $item['check_in_full_address'] }}" style="width:300px;text-wrap:auto;">
                                            {{$item['check_in_full_address'] ?? 'N/A'}} 
                                            </td>
                                            <td>{{ $item['check_out_time'] ?? 'N/A' }}</td>
                                            <td title="{{ $item['check_out_full_address'] }}" style="width:300px;text-wrap:auto;">
                                                {{$item['check_out_full_address'] ?? 'N/A'}}
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    {{ $item['status'] == 'Absent' ? 'bg-danger' : 'iq-bg-primary' }}">
                                                    {{ is_array($item['status']) ? 'Present' : $item['status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" align="center">No records found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            

                            <div class="row justify-content-between mt-3">
                                <div id="user-list-page-info" class="col-md-6">
                                    {{-- <span>Showing 1 to 5 of 5 entries</span> --}}
                                </div>
                                <div class="col-md-6">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            {{-- Previous Button --}}
                                            @if ($allDaysPaginated->onFirstPage())
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $allDaysPaginated->previousPageUrl() }}">Previous</a>
                                                </li>
                                            @endif

                                            {{-- Page Numbers --}}
                                            @foreach ($allDaysPaginated->links()->elements[0] ?? [] as $page => $url)
                                                @if ($page == $allDaysPaginated->currentPage())
                                                    <li class="page-item active"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            {{-- Next Button --}}
                                            @if ($allDaysPaginated->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $allDaysPaginated->nextPageUrl() }}">Next</a>
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
</div> <!-- Large Approved modal -->
<script>
    function changeStatus(val) {
        var currentUrl = new URL(window.location.href);
        // Add or update the 'run_id' parameter
        currentUrl.searchParams.set('status', val);
        if (val == "") {
            currentUrl.searchParams.delete('status');

        }
        // Reload the page with the new URL
        window.location.href = currentUrl.toString();

    }

    function filterByMonth(month) {
        var url = new URL(window.location.href);
        url.searchParams.set('month', month);
        window.location.href = url.href;
    }
</script>
<script>
    document.getElementById('datePicker').addEventListener('keydown', function(e) {
        e.preventDefault(); // Prevent manual typing
    });
</script>
<script>
    $(document).ready(function() {
        $("#datePicker").change(function() {
            $("#filterForm").submit(); // Submit form automatically on date selection
        });
    });
</script>
@endsection