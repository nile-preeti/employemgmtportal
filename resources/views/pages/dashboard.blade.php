@extends('layouts.app')
@push('css')
    <style>
        .list-dot {
            padding-left: 15px !important;
            line-height: 50px !important;
            width: 50px !important;
            height: 50px !important;
            background: #805b33;
            border-radius: 30px;
            color: white;
            font-size: 30px;
        }
    </style>
@endpush
@section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row d-flex">
                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('admin.users.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-dark font-weight-normal">Total Employee</h6>
                                    <h2 class="text-dark font-weight-bold">{{ count($users) }}</h2>
                                </div>
                                <div class="rounded-circle iq-card-icon dark-icon-light iq-bg-primary "><i
                                        class="ri-group-line"></i></div>
                            </div>
                        </div>

                        
                    </div>
                </div>


                <div class="col-sm-6 col-md-6 col-lg-3">
                    <div class="iq-card iq-card-block iq-card-stretch "
                        onclick="location.replace('{{ route('admin.holidayss.index') }}')" style="cursor: pointer">
                        <div class="iq-card-body">
                            <div class="d-flex d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-dark font-weight-normal">Total Holidays</h6>
                                    <h2 class="text-dark font-weight-bold">{{ $totalHolidays }}</h2>
                                </div>
                                <div class="rounded-circle iq-card-icon dark-icon-light iq-bg-primary "><i
                                        class="ri-group-line"></i></div>
                            </div>
                        </div>

                        
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="iq-card iq-card-block iq-card-stretch iq-card-height">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Employee List</h4>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <form class="position-relative mr-5">
                                    <div class="form-group mb-0">
                                    <a class="btn btn-primary iq-bg-primary text-light rounded-pill py-2 px-3" href="{{ route('download.logs') }}">
                                        <i class="ri-download-line"></i> &nbsp; Download Logs
                                    </a>
                                    </div>
                                </form>

                                <div class="form-group mb-0">
                                        <a class="btn btn-primary iq-bg-primary text-light mr-4 rounded-pill py-2 px-3" href="{{ route('admin.users.index') }}">
                                            &nbsp; View All</a>
                                    </div>
                                <div class="todo-date d-flex mr-3">
                                    <i class="ri-calendar-2-line text-primary mr-2"></i>
                                    <span>{{ date('l, d M, Y') }}</span>
                                </div>
                            </div>

                            <!-- <div class="iq-card-header-toolbar d-flex align-items-center">
                                                          <div class="dropdown">
                                                             <span class="dropdown-toggle text-primary" id="dropdownMenuButton5" data-toggle="dropdown">
                                                             <i class="ri-more-fill"></i>
                                                             </span>
                                                             <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton5">
                                                                <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>
                                                                <a class="dropdown-item" href="#"><i class="ri-delete-bin-6-fill mr-2"></i>Delete</a>
                                                                <a class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                                                <a class="dropdown-item" href="#"><i class="ri-printer-fill mr-2"></i>Print</a>
                                                                <a class="dropdown-item" href="#"><i class="ri-file-download-fill mr-2"></i>Download</a>
                                                             </div>
                                                          </div>
                                                       </div> -->
                        </div>
                        <div class="iq-card-body">
                            <div class="table-responsive">
                                <table class="table mb-0 table-borderless">
                                    <thead>
                                        <tr>
                                            <th>Emp Id</th>
                                            <th> Name</th>
                                            <th> Email</th>
                                            <th>Designation</th>
                                            <th>Phone No.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $item)
                                            <tr>
                                            <td>{{$item->emp_id ?? 'N/A'}}</td>
                                                <td>
                                                    {{ $item->name }}
                                                </td>
                                                <td>
                                                    {{ $item->email }}
                                                </td>

                                                <td>
                                                    {{ $item->designation ?? 'N/A' }}
                                                </td>

                                                <td>{{$item->phone ?? 'N/A'}}</td>
                                                <td><span
                                                        class="badge dark-icon-light iq-bg-primary">{{ $item->status ? 'Active' : 'Inactive' }}</span>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=" 3">No records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-4">
                    <div class="iq-card iq-card-block iq-card-stretch ">
                        <div class="iq-card mb-0" style="box-shadow: none;">
                            <div class="iq-card-header d-flex justify-content-between">
                                <div class="iq-header-title">
                                    <h4 class="card-title">New Messages</h4>
                                </div>
                                <div class="iq-card-header-toolbar d-flex align-items-center">
                                    <a href="#">See All</a>
                                </div>
                            </div>
                        </div>
                        <div class="iq-card mb-0" style="box-shadow: none;">

                            <div class="iq-card-body">
                                <ul class="suggestions-lists m-0 p-0">



                                </ul>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

    </div>
@endsection
