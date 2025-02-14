@extends('layouts.app') @section('content')
    <div id="content-page" class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="iq-card">
                        <div class="iq-card-header d-flex justify-content-between">
                            <div class="iq-header-title">
                                <h4 class="card-title">Change Password</h4>
                            </div>
                        </div>
                        <div class="iq-card-body">
                            <form method="POST" action="{{ route('change_password_post') }}" id="create_form"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row align-items-center">
                                    <div class="form-group col-sm-6">
                                        <label for="fname">Old Password:</label>
                                        <input type="password" class="form-control" name="old_password" id="old_password" />
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="uname">New Pasword</label>
                                        <input type="password" class="form-control" id="password" name="password" />
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="cname">Confirm Password:</label>
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" />
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary mr-2">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Add a custom validation rule
            $.validator.addMethod("passwordMatch", function(value, element) {
                return value === $('#password').val();
            }, "Password and confirmation must match.");

            $.validator.addMethod("minLength", function(value, element, param) {
                return this.optional(element) || value.length >= param;
            }, "Password must be at least {0} characters long.");


            $('#create_form').validate({
                rules: {
                    old_password: {
                        required: true,
                        minLength: true
                    },
                    password: {
                        required: true,
                        minLength: true,

                    },
                    password_confirmation: {
                        required: true,
                        minLength: true,
                        passwordMatch: true
                    },
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-group").append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $('.please-wait').click();
                    $(element).addClass("text-danger");
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


        });
    </script>
@endsection
