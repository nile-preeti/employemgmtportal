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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


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

    .nav .nav-item button.active {
      background-color: #8e8efe;
      width: 100%;
      text-align: left;
      border-radius: 0;
      padding: 18px;
      font-size: 16px;
      border-bottom: 1px solid #bbbbff;
    }

    .nav .nav-item button.active::after {
      content: "";
      border-right: 6px solid #bbbbff;
      height: 100%;
      position: absolute;
      right: -1px;
      top: 0;
    }

    .date-time-sec h2 {
      color: #000 !important;
    }

    .bg-gradient-danger {
      background-image: linear-gradient(310deg, #ff315a, #f56036);
    }

    .bg-gradient-success {
      background-image: linear-gradient(310deg, #54f9b2, #2dcecc);
    }

    .bg-gradient-warning {
      background-image: linear-gradient(310deg, #fa613e, #ffb33e);
    }

    /*            .bg-gradient-warning{background-image: linear-gradient(310deg, #00a1ff, #60c4ff);}*/

    .ic-dash img {
      height: 80px;
      background: #fff;
      padding: 14px;
      border-radius: 12px;
    }

    .card.card-img-holder {
      position: relative;
    }

    .card.card-img-holder .card-img-absolute {
      position: absolute;
      top: -170px;
      right: -14px;
      height: 440px;
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

    a.submit {
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 50px;
      background-color: #064086;
      color: white;
      box-shadow: 0 0 10px hwb(0deg 0% 100% / 5%);
    }

    .profile-form .mt-4 {
      margin-top: 20px;
    }

    .profile-form .form-control {
      padding: 10px;
    }
  </style>


</head>

<body>
  <!-- <div class="header">
        <nav class="navbar">
            <div class="navbar-menu-wrapper" style="cursor: pointer;">
                <ul class="navbar-nav f-navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link nav-toggler" data-toggle="minimize">
                        <img src="https://nileprojects.in/hrmodule/public/assets/images/nile-logo.jpg" class="card-img-absolute" alt="circle-image" height="50px">
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div> -->
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
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#" onclick="logout()">Sign out</a></li>
          </ul>
        </div>
      </div>
    </div>
  </header>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="py-4 text-dark mb-2 mt-2"><a href="{{route('user.dashboard')}}"> <img src="https://nileprojects.in/hrmodule/public/assets/images/arrow-left.svg" class="ic-arrow-left"> </a> Profile</h2>
      </div>

      <form>
        <div class="card profile-form">
          <div class="row">
            <div class="col-lg-2 d-flex align-items-center justify-content-center" style="background: #f8f8ff;">
              <img src="https://nileprojects.in/hrmodule/public/assets/images/image.png" class="user-profile">
            </div>
            <div class="col-lg-10">
              <div class="row px-4">
                <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                  <label for="inputEmail4" class="form-label">Name</label>
                  <p>{{ auth()->user()->name }}</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                  <label for="inputPassword4" class="form-label">Designation</label>
                  <p>{{ auth()->user()->designation }}</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                  <label for="inputAddress" class="form-label">Phone Number</label>
                  <p>{{ auth()->user()->phone }}</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                  <label for="inputAddress2" class="form-label">Email ID</label>
                  <p>{{ auth()->user()->email }}</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mt-4">
                  <label for="inputAddress2" class="form-label">Employee ID</label>
                  <p>{{ auth()->user()->emp_id }}</p>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-4 mb-4">
                  <a class="submit" data-toggle="modal"
                    data-name="{{ auth()->user()->name ?? '' }}"
                    data-status="{{ auth()->user()->status ?? '' }}"
                    data-email="{{ auth()->user()->email ?? '' }}"
                    data-designation="{{auth()->user()->designation}}"
                    data-phone="{{auth()->user()->phone}}"
                    data-emp="{{auth()->user()->emp_id}}"
                    data-image="{{ auth()->user()->image ? asset('uploads/images/' . auth()->user()->image) : null }}"
                    data-url="{{ route('admin.users.update', auth()->user()->id) }}"
                    onclick="showData(this)" data-target="#EditModel"
                    style="cursor: pointer">Edit Profile</a>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="inputEmail4" class="form-label">Name</label>
                        <input type="" class="form-control" id="inputEmail4" placeholder="Enter Name">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="inputPassword4" class="form-label">Designation</label>
                        <input type="" class="form-control" id="inputPassword4" placeholder="Enter Designation">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="inputAddress" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="Enter Phone No.">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="inputAddress2" class="form-label">Email ID</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Enter Email ID">
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 mt-4">
                        <label for="inputAddress2" class="form-label">Employee ID</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Enter Employee ID">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
                        <button type="submit" class="btn btn-submit px-5">Submit</button>
                      </div>
                  </div> -->

        </div>
      </form>
    </div>
  </div>
  <div class="modal fade EditModel" tabindex="-1" role="dialog" aria-hidden="true" id="EditModel">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('admin.users.store') }}" method="post" id="edit_form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="container-fluid">
                            <div class="form-group">
                                <label for="name">Name*</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Emp Id*</label>
                                <input type="text" name="emp_id" id="emp_id" class="form-control" required pattern="\d{4}" minlength="4" maxlength="4" >
                            </div>
                            <div class="form-group">
                                <label for="name">Email*</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Designation*</label>
                                <input type="text" name="designation" id="designation" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="name">Phone No.*</label>
                                <input type="text" name="phone" id="phone" class="form-control" required pattern="\d{10}" minlength="10" maxlength="10">

                            </div>
                            <div class="form-group">
                                <div>
                                    <button type="button" class="btn btn-primary my-1" onclick="togglePassword()">Reset
                                        Password</button>
                                </div>
                                <div>
                                    <input type="text" name="password" class="form-control" id="password"
                                        style="display: none">

                                </div>
                            </div>
                            <input type="hidden" name="image" id="edit_image" class="form-control">

                            <div class="form-group">
                                <div class="dropzone" id="editDropzone"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Status</label>
                                <select class="form-control" name="status" id="status">

                                    <option value="1">Active </option>
                                    <option value="0">Inactive </option>

                                </select>
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
  <script>
    function togglePassword() {
    let passwordField = $('#password');

    if (passwordField.is(':visible')) {
        passwordField.hide().removeAttr('required');
        passwordField.val(''); // Clear the field when hiding
    } else {
        passwordField.show().attr('required', 'required').focus();
    }
}
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
  <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            $('#edit_form').validate({
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
                                var form = $("#edit_form");
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

        function showData(ele) {
            $("#edit_form").attr("action", ele.getAttribute("data-url"));

            $("#email").val(ele.getAttribute("data-email"));

            $("#designation").val(ele.getAttribute("data-designation"));
            $("#phone").val(ele.getAttribute("data-phone"));
            $("#emp_id").val(ele.getAttribute("data-emp"));

            $("#status").val(ele.getAttribute("data-status"));
            $("#name").val(ele.getAttribute("data-name"));

        }
    </script>
    
</body>

</html>