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
                        <div class="">
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

                                <div class="col-sm-4 col-md-4">
                                    <div class="user-list-files d-flex">
                                        <select class="form-control" id="selectcountry"
                                            onchange="changeStatus(this.value)">
                                            <option value="">--Filter By Status--</option>
                                            <option value="Checked In"
                                                @if (request()->has('status') && request('status') == 'Checked In') selected @endif>
                                                Checked In </option>

                                            <option value="Checked Out"
                                                @if (request()->has('status') && request('status') == 'Checked Out') selected @endif>
                                                Checked Out </option>

                                        </select>


                                    </div>
                                </div>


                                <div class="col-sm-4 col-md-4">
                                    <div class="user-list-files d-flex">
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
                                    <form id="filterForm">
                                        <input type="date" name="date" class="form-control" id="datePicker" value="{{ request('date') }}">
                                    </form>
                                </div>

                                <div class="col-md-1">
                                    <span style="cursor:pointer;padding-top:10px"
                                        onclick="window.location.href = window.location.origin + window.location.pathname;"><img
                                            src="{{ asset('reset.png') }}" height="20" alt=""></span>
                                </div>
                            </div>
                            <table id="user-list-table" class="table table-striped table-borderless mt-4" role="grid"
                                aria-describedby="user-list-page-info">
                                <thead>
                                    <tr>
                                        <th> Date</th>
                                        <th>Check In Time</th>
                                        <th>Check In Location</th>
                                        <th>Check Out Time</th>
                                        <th>Check Out Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                    <tr>
                                    <td>{{ $item->date ? date('Y-m-d', strtotime($item->date)) : 'N/A' }}</td>
                                    <td>{{ $item->check_in_time ?? 'N/A' }}</td>
                                    <td>{{ $item->check_in_full_address ? substr($item->check_in_full_address, 0, 30) : 'N/A' }}</td>
                                    <td>{{ $item->check_out_time ?? 'N/A' }}</td>
                                    <td>{{ $item->check_out_full_address ? substr($item->check_out_full_address, 0, 30) : 'N/A' }}</td>
                                    <td>{{ $item->status ?? 'N/A' }}</td>


                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" align="center">No records found</td>
                                    </tr>
                                    @endforelse



                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <p><b>Total Working Days - {{ $totalWorkingDays }}</b></p>
                        </div>
                        <div class="row justify-content-between mt-3">
                            <div id="user-list-page-info" class="col-md-6">
                                {{-- <span>Showing 1 to 5 of 5 entries</span> --}}
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end mb-0">
                                        @if ($data->onFirstPage())
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"
                                                aria-disabled="true">Previous</a>
                                        </li>
                                        @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $data->previousPageUrl() }}">Previous</a>
                                        </li>
                                        @endif

                                        @foreach ($data->links()->elements as $element)
                                        @if (is_string($element))
                                        <li class="page-item disabled"><a
                                                class="page-link">{{ $element }}</a></li>
                                        @endif

                                        @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                        @if ($page == $data->currentPage())
                                        <li class="page-item active"><a class="page-link"
                                                href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                        @else
                                        <li class="page-item"><a class="page-link"
                                                href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                        @endif
                                        @endforeach
                                        @endif
                                        @endforeach

                                        @if ($data->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $data->nextPageUrl() }}">Next</a>
                                        </li>
                                        @else
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1"
                                                aria-disabled="true">Next</a>
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