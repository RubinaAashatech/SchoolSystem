@extends('backend.layouts.master')

<!-- Main content -->
@section('content')
    <div class="mt-4">
        <div class="d-flex justify-content-between mb-4">

            <div class="border-bottom border-primary">
                <h2>
                    {{ $page_title }}

                </h2>
            </div>
            <a href="{{ url()->previous() }}"><button class="btn-primary btn-sm"><i class="fa fa-angle-double-left"></i>
                Back</button></a>

        </div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <form id="filterForm">
                            @csrf
                            <input type="hidden" id="examination_id" name="examination_id" value="{{ $examinations->id }}">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Class</label><small class="req"> *</small>
                                    <div class="form-group select">
                                        <select name="class_id">
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label>Section</label><small class="req"> *</small>
                                    <div class="form-group select">

                                        <select name="section_id" id="dynamic_section_id"
                                            class="input-text single-input-text">
                                            <option disabled>Select Section</option>
                                        </select>
                                        @error('section_id')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                    <div class="col-sm-4">
                                        <label>Teacher</label><small class="req"> *</small>
                                        <div class="form-group select">
                                            <select name="user_id" id="teacher_id">
                                                <option value="">Select Teacher</option>
                                                @foreach ($teachers as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            
                                            @error('user_id')
                                                <strong class="text-danger">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                   </div>

                            </div>
                            <div class="form-group col-md-12 d-flex justify-content-end pt-2">
                                <button type="button" class="btn btn-primary" id="saveButton">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="student-table"
                    class="table table-bordered table-striped dataTable dtr-inline"
                    aria-describedby="example1_info">
                    <thead>
                        <tr>

                            <th>Class</th>
                            <th>Section</th>
                            <th>Teacher Name</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        <!-- Student data will be dynamically added here -->
                    </tbody>
                </table>
            </div>
            {{-- <div class="form-group col-md-12 d-flex justify-content-end pt-2">
                <button type="submit" class="btn btn-primary btn-sm pull-right mr-1"
                    id="load"
                    data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait...">Save
                </button>

            </div> --}}
        </div>
    </div>

        @section('scripts')
        <script>
            $(document).ready(function() {
                $('#saveButton').click(function() {
                    // Get the selected class ID, section ID, teacher ID, and examination ID
                    var classId = $('select[name="class_id"]').val();
                    var sectionId = $('select[name="section_id"]').val();
                    var teacherId = $('select[name="user_id"]').val();
                    var examinationId = $('#examination_id').val();


                    console.log('examination:', examinationId);
                    console.log('teacher:', teacherId);
                    console.log('class:', classId);
                    console.log('section:', sectionId);

   // Send an AJAX request to save the assigned teacher data
                    $.ajax({
                        url: '{{ route('admin.assign-teachers.save') }}',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            examination_id: examinationId,
                            class_id: classId,
                            section_id: sectionId,
                            user_id: teacherId,

                        },

                        success: function(response) {
                            // Handle the response (e.g., show a success message)
                            if (response.message) {
                                // If there's a success message in the response, display it
                                toastr.success(response.message);
                            } else {
                                // If no success message is provided, display a default success message
                                toastr.success('Teacher assigned successfully');
                            }

                        },
                        error: function(error) {
                            // Handle errors (e.g., show an error message)
                            console.error(error);
                            toastr.error('Error occurred while assigning teacher. Please try again later.');
                        }
                    });
                });

                // Function to populate sections based on the selected class
                $('select[name="class_id"]').change(function() {
                    var classId = $(this).val();
                    $.ajax({
                        url: baseURL + '/admin/get-section-by-class/' +
                         classId,
                        type: 'GET',
                        data: {
                            class_id: classId
                        },
                        success: function(data) {
                            $('select[name="section_id"]').empty().append('<option disabled selected>Select Section</option>');
                            $.each(data, function(key, value) {
                                $('select[name="section_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });
                });
            });
        </script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#student-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.assign-teachers.get') }}',
            type: 'GET'
        },
        columns: [{
                data: 'class_id',
                name: 'class_id'
            },
            {
                data: 'section_id',
                name: 'section_id'
            },
            {
                data: 'user_id',
                name: 'user_id'
            },
            {
                data: 'actions',
                name: 'actions'
            }
        ],
        initComplete: function() {
            this.api().columns().every(function() {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                    .on('change', function() {
                        column.search($(this).val()).draw();
                    });
            });
        }
    });
    //edit funcion

</script>


    @endsection
@endsection
