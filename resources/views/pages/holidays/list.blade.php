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
                                    <div class="col-sm-12 col-md-6">
                                        <div id="user_list_datatable_info" class="dataTables_filter">
                                            <form class="mr-3 position-relative">
                                                <div class="form-group mb-0">
                                                    <input type="search" class="form-control" name="search"
                                                        placeholder="Search by  name..." aria-controls="user-list-table">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span style="cursor:pointer;padding-top:10px"
                                            onclick="window.location.href = window.location.origin + window.location.pathname;"><img
                                                src="{{ asset('reset.png') }}" height="20" alt=""></span>
                                    </div>
                                    <div class="col-sm-12 col-md-5">
                                        <div class="user-list-files d-flex">

                                            <a class="iq-bg-primary"
                                                onclick='initializeDropzone("myDropzone", "{{ route('image-upload') }}", null)'
                                                data-toggle="modal" data-target=".CreateModel" href="#">Add Holiday</a>
                                        </div>
                                    </div>
                                </div>
                                <table id="user-list-table" class="table table-striped table-borderless mt-4 table-hover" role="grid"
                                    aria-describedby="user-list-page-info">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Name</th>
                                            <th>Day</th>
                                            <th>Date</th>
                                            <th>Action &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data as $key=> $item)
                                            <tr>
                                                <td class="d-flex align-items-center">{{$key+1}}</td>

                                                <td>{{ $item->reason }}</td>
                                               
                                                <td>{{$item->day}}
                                                </td>
                                                <td>{{$item->date}}</td>

                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                    <a class="iq-bg-primary" data-toggle="modal"
   data-reason="{{ $item->reason ?? '' }}"
   data-date="{{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('Y-m-d') : '' }}"
   data-url="{{ route('admin.holidayss.update', $item->id) }}"
   onclick="showData(this)" data-target="#EditModel"
   style="cursor: pointer">
   <i class="ri-pencil-fill"></i>
</a>
                                                        {{-- delete  button --}}
                                                        <a class="iq-bg-danger" data-id="{{ $item->id }}"
                                                            style="cursor: pointer"
                                                            data-url="{{ route('admin.holidayss.destroy', $item->id) }}"
                                                            onclick="deletePublic(this)"><i
                                                                class="ri-delete-bin-7-line"></i></a>


                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" align="center">No records found</td>
                                            </tr>
                                        @endforelse



                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-between mt-3">
                                <div id="user-list-page-info" class="col-md-6">
                                    {{-- <span>Showing 1 to 5 of 5 entries</span> --}}
                                </div>
                                @if ($data->total() > $data->perPage()) 
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
                                                    <a class="page-link"
                                                        href="{{ $data->previousPageUrl() }}">Previous</a>
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
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Large Approved modal -->

    <div class="modal fade CreateModel" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('admin.holidayss.store') }}" method="post" id="create_form"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Holiday</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="reason" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Date*</label>
                                <input type="date" name="date" required="" class="form-control"
                                    placeholder="Date" value="" id="datePicker">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-success">Approve</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade EditModel" tabindex="-1" role="dialog" aria-hidden="true" id="EditModel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('admin.holidayss.store') }}" method="post" id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Holiday</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="reason" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="name">Date*</label>
                                <input type="date" name="date" class="form-control" id="datePicker2"
                                value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-success">Approve</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            // Add a custom validation rule
            $.validator.addMethod("imageFile", function(value, element) {
                // Check if the file extension is of an image type
                return this.optional(element) || /\.(jpg|jpeg|png|gif)$/i.test(value);
            }, "Please select a valid image file (JPG, JPEG, PNG, GIF).");
            $.validator.addMethod("phoneValid", function(value) {
                return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
            }, 'Invalid phone number.');
            $.validator.addMethod("numericOrDecimal", function(value, element) {
                return this.optional(element) || /^[0-9]+(\.[0-9]+)?$/.test(value);
            }, "Please enter a valid numeric value .");

            $('#create_form').validate({
                rules: {

                    name: {
                        required: true,
                        maxlength: 191,
                    },
                    description: {
                        maxlength: 100
                    },

                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger ");
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass("text-danger");
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    let formData = new FormData(form);

                    $.ajax({
                        type: 'post',
                        url: form.action,
                        data: formData,
                        dataType: 'json',
                        contentType: false,
                        processData: false,

                        success: function(response) {
                            if (response.success) {

                                Swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',

                                }).then((result) => {

                                    if (response.redirect == true) {
                                        window.location = response.route;
                                    }
                                    var url = $('#redirect_url').val();
                                    if (url !== undefined || url != null) {
                                        window.location = url;
                                    } else {
                                        location.reload(true);
                                    }
                                })

                                return false;
                            }

                            if (response.success == false) {
                                Swal.fire(
                                    'Error',
                                    response.message,
                                    'error'
                                );

                                return false;
                            }
                        },
                        error: function(data) {
                            if (data.status == 422) {
                                var form = $("#create_form");
                                let li_htm = '';
                                $.each(data.responseJSON.errors, function(k, v) {
                                    const $input = form.find(
                                        `input[name=${k}],select[name=${k}],textarea[name=${k}]`
                                    );
                                    if ($input.next('small').length) {
                                        $input.next('small').html(v);
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').next('small').html(v);
                                        }
                                    } else {
                                        $input.after(
                                            `<small class='text-danger'>${v}</small>`
                                        );
                                        if (k == 'services' || k == 'membership') {
                                            $('#myselect').after(
                                                `<small class='text-danger'>${v[0]}</small>`
                                            );
                                        }
                                    }
                                    li_htm += `<li>${v}</li>`;
                                });

                                return false;
                            } else {
                                Swal.fire(
                                    'Error',
                                    data.statusText,
                                    'error'
                                );
                            }
                            return false;

                        }
                    });
                }
            })

            $('#edit_form').validate({
    rules: {
        reason: {
            required: true,
            maxlength: 191,
        },
        date: {
            required: true,
        },
    },
    errorElement: "small",
    errorPlacement: function(error, element) {
        error.addClass("text-danger");
        element.closest(".form-group").append(error);
    },
    highlight: function(element) {
        $(element).addClass("text-danger");
    },
    unhighlight: function(element) {
        $(element).removeClass("text-danger");
    },
    submitHandler: function(form, event) {
        event.preventDefault();
        let formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: form.action,
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,

            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                    }).then(() => {
                        if (response.redirect) {
                            window.location = response.route;
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(data) {
                if (data.status == 422) {
                    var form = $("#edit_form");
                    form.find('small.text-danger').remove(); // Remove old error messages

                    $.each(data.responseJSON.errors, function(k, v) {
                        const $input = form.find(`[name="${k}"]`);
                        if ($input.length > 0) {
                            if ($input.next('small.text-danger').length) {
                                $input.next('small.text-danger').html(v);
                            } else {
                                $input.after(`<small class='text-danger'>${v}</small>`);
                            }
                        }
                    });

                    Swal.fire('Validation Error', 'Please correct the errors and try again.', 'error');
                } else {
                    Swal.fire('Error', data.statusText, 'error');
                }
            }
        });
    }
});
        });

        function showData(ele) {
    console.log("Date from button:", ele.getAttribute("data-date")); // Debugging

    $("#edit_form").attr("action", ele.getAttribute("data-url"));
    $("#name").val(ele.getAttribute("data-reason"));

    let dateValue = ele.getAttribute("data-date"); // Get the date from the button

    if (dateValue) {
        $("#datePicker2").val(dateValue).trigger("change"); // Ensure input updates
    } else {
        $("#datePicker2").val(""); // Reset if no date
    }

    console.log("Date in input field:", $("#datePicker2").val()); // Debugging check
}


        function deletePublic(ele) {
            var title = 'Are you sure, you want to delete this holiday ?';
            Swal.fire({
                title: '',
                text: title,
                iconHtml: '<img src="{{ asset('assets/images/question.png') }}" height="25px">',
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
                    var id = ele.getAttribute("data-id");
                    var url = ele.getAttribute("data-url");

                    var _token = '{{ csrf_token() }}';

                    var obj = {

                        _token
                    };
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: obj,
                        success: function(data) {
                            // console.log(data);
                            if (data.success) {
                                Swal.fire("Success", data.message, 'success').then((result) => {
                                    if (result.value) {
                                        var url = $('#redirect_url').val();
                                        if (url !== undefined || url != null) {
                                            window.location = url;
                                        } else {
                                            location.reload(true);
                                        }

                                    }
                                });
                            } else {
                                Swal.fire("Error", data.message, 'error');
                            }
                        }
                    });
                }

            })

        }

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
    </script>
    <script>
        document.getElementById('datePicker').addEventListener('keydown', function(e) {
            e.preventDefault(); // Prevent manual typing
        });
    </script>
@endpush
