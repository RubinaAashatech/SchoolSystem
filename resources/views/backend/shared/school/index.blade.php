@extends('backend.layouts.master')

<!-- Main content -->
@section('content')
<div class="mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="border-bottom border-primary">
            <h2>{{ $page_title }}</h2>
        </div>
        @include('backend.shared.school.partials.action')
    </div>

    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-12">
                        <div class="report-table-container">
                            <div class="table-responsive">
                                <table id="school-table" class="table table-bordered table-striped dataTable dtr-inline"
                                    aria-describedby="example1_info">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Address</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      {{-- <div class="modal fade" id="createDesignation" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <form method="post" id="designationForm" action="{{ route('admin.designations.store') }}">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="POST">
                        <input type="hidden" name="dynamic_id" id="dynamic_id">
                        <div class="col-md-12 d-flex flex-wrap gap-5">
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Name<span class="must">*</span></label>

                                <div class="col-sm-10">
                                    <input type="text" value="{{ old('name') }}" name="name" class="input-text"
                                        id="dynamic_name" autofocus required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 col-xs-12">
                                <label>Status<span class="must">*</span></label>
                                <div class="col-sm-10">
                                    <div class="btn-group">
                                        <input type="radio" class="btn-check" name="is_active" id="option1" value="1"
                                            autocomplete="off" checked />
                                        <label class="btn btn-secondary" for="option1">Active</label>

                                        <input type="radio" class="btn-check" name="is_active" id="option2" value="0"
                                            autocomplete="off" />
                                        <label class="btn btn-secondary" for="option2">Inactive</label>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-success mt-2">Submit</button>

                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Password Reset Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="resetPasswordForm" method="POST"
                   action="">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="schoolId" name="school_id">
                        <div class="form-group">
                            <label for="password"> Create a new password</label>
                            <input type="password" class="form-control" id="password" minlength="6" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm a new password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" minlength="6" required>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="togglePasswordVisibility"> Show Password
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#school-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.schools.get') }}',
            type: 'post'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'address', name: 'address' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'status', name: 'status' },
            { data: 'actions', name: 'actions' }
        ]
    });

    // Show the reset password modal
    $(document).on('click', '.reset-password-btn', function () {
        var schoolId = $(this).data('id');
        $('#schoolId').val(schoolId);  // Set school ID in the hidden input field
        $('#resetPasswordForm').attr('action', '/admin/schools/reset-password/' + schoolId);  // Set form action dynamically
        $('#resetPasswordModal').modal('show');  // Show the modal
    });

    // Toggle password visibility
    $('#togglePasswordVisibility').on('change', function () {
        var passwordField = $('#password');
        var confirmPasswordField = $('#password_confirmation');
        if ($(this).is(':checked')) {
            passwordField.attr('type', 'text');
            confirmPasswordField.attr('type', 'text');
        } else {
            passwordField.attr('type', 'password');
            confirmPasswordField.attr('type', 'password');
        }
    });

    $('#resetPasswordForm').on('submit', function () {
        e.preventDefault();

        var form = $(this);
        var actionUrl = form.attr('action');

        // Check if the form action is set correctly
        console.log(actionUrl);

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                if (response.success) {
                    $('#resetPasswordModal').modal('hide'); // Close the modal
                    location.reload(); // Reload the page to show the flash message
                } else {
                    alert(response.message);
                }
            },
            // error: function (xhr) {
            //     console.log(xhr); // Check the console for any error messages.
            //     xhr.responseJSON && xhr.responseJSON.errors {
            //         var errors = xhr.responseJSON.errors;
            //         var message = errors.password ? errors.password[0] : 'Validation error';
            //         alert(message);
            //     }
                
            // }
        });
    });
   




</script>
@endsection
@endsection
