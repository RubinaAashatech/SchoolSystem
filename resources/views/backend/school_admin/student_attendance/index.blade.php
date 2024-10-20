@extends('backend.layouts.master')
@section('content')
    <div class="mt-4">
        <div class="d-flex justify-content-between mb-4">
            <div class="border-bottom border-primary">
                <h2>{{ $page_title }}</h2>
            </div>
            @include('backend.school_admin.student_attendance.partials.action')
        </div>
        <!-- Holiday Range Modal -->
<div class="modal fade" id="holidayRangeModal" tabindex="-1" role="dialog" aria-labelledby="holidayRangeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="holidayRangeModalLabel">Mark Holiday Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="holidayStartDate">Start Date:</label>
                    <input type="text" class="form-control" id="holidayStartDate">
                </div>
                <div class="form-group">
                    <label for="holidayEndDate">End Date:</label>
                    <input type="text" class="form-control" id="holidayEndDate">
                </div>
                <div class="form-group">
                    <label for="holidayReason">Reason:</label>
                    <input type="text" class="form-control" id="holidayReason" placeholder="e.g., Summer Vacation, Dashain Vacation">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveHolidayRange">Save Holiday Range</button>
            </div>
        </div>
    </div>
</div>
        <div class="card">
            <div class="class-body">
                <form id="attendanceFilterForm">
                    <div class="col-md-12 col-lg-12 d-flex justify-content-around">
                        <div class="col-lg-3 col-sm-3 mt-2">
                            <label for="class_id"> Class:</label>
                            <div class="select">
                                <select name="class_id">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('class_id')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                        <div class="col-lg-3 col-sm-3 mt-2">
                            <label for="section_id"> Section:</label>
                            <div class="select">
                                <select name="section_id">
                                    <option disabled>Select Section</option>
                                    <option value=""></option>
                                </select>
                            @error('section_id')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3 mt-2">
                            <label for="datetimepicker">Date:</label>
                            <div class="form-group">
                                <div class="input-group date" id="admission-datetimepicker" data-target-input="nearest">
                                    <input id="admission-datepicker" name="date" type="text" class="form-control datetimepicker-input" />
                                </div>
                                @error('date')
                                <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <script>
                            $(document).ready(function () {
                                // Fetch current Nepali date
                                var currentDate = NepaliFunctions.GetCurrentBsDate();
                                // Pad month and day with leading zero if they are less than 10
                                var padZero = function (num) {
                                    return num < 10 ? '0' + num : num;
                                };
                                // Format the current date with padded month and day
                                var formattedDate = currentDate.year + '-' + padZero(currentDate.month) + '-' + padZero(currentDate.day);
                                // Set the formatted date to the input field
                                $('#admission-datepicker').val(formattedDate);
                            });
                        </script>
                    </div>
                    <!-- Add the Search button -->
                    <div class="form-group col-md-12 d-flex justify-content-end pt-2">
                        <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="studentContainer">
            <div class="card mt-2">
                <div class="card-body">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <!-- Save Attendance and Mark Holiday button -->
                        <div class="row mb-2">
                            <div class="col-sm-6 col-md-6 col-6 d-flex justify-content-start">
                                <button type="button" class="btn btn-primary" id="saveAttendanceButton">Save Attendance</button>
                            </div>
                            <div class="col-sm-6 col-md-6 col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-danger" id="markHolidayButton" style="margin-left: 5px;">Mark Holiday</button>
                                <button type="button" class="btn btn-primary" id="exportReportButton" style="margin-left: 5px;">Export Report</button>
                            </div>
                        </div>
                       
                            <div class="col-sm-12 col-md-12 col-12 d-flex justify-content-end align-items-center">
                                <div class="d-flex align-items-center">
                                    <select id="sortBy" class="form-select me-2" style="max-width: 150px;">
                                        <option value="" disabled selected>Sort Here</option>
                                        <option value="roll_no">Roll No</option>
                                        <option value="name">Name</option>
                                    </select>
                                    <button type="button" class="btn btn-primary">Sort By:</button>
                                </div>
                            </div>
                        </div>


                <div class="table-responsive">
                    <table id="student-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Admission No</th>
                                <th>Roll No</th>
                                <th>Name</th>
                                <th>Attendance</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <!-- Table body will be populated dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('styles')
<style>
    .d-flex {
        display: flex;
        flex-wrap: nowrap; /* Prevents wrapping */
        align-items: center; /* Vertically center items */
    }
    .form-select {
        margin-right: 5px; /* Space between dropdown and button */
    }
</style>


@endsection


@section('scripts')
@include('backend.includes.nepalidate')
<script>
$(document).ready(function() {
            // Attach change event handler to the class dropdown
            $('select[name="class_id"]').change(function() {
                // Get the selected class ID
                var classId = $(this).val();
                // Fetch sections based on the selected class ID
                $.ajax({
                    url: 'get-section-by-class/' + classId, // Replace with the actual route
                    type: 'GET',
                    success: function(data) {
                        // Clear existing options
                        $('select[name="section_id"]').empty();
       
                        // Add the default option
                        $('select[name="section_id"]').append('<option value="" selected>Select Section</option>');
       
                        // Add new options based on the fetched sections
                        $.each(data, function(key, value) {
                            $('select[name="section_id"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            });


            $('#saveAttendanceButton, #markHolidayButton, #exportReportButton').hide();


// Class dropdown change event
$('select[name="class_id"]').change(function() {
    console.log("Class dropdown changed");
    var classId = $(this).val();
    $.ajax({
        url: 'get-section-by-class/' + classId,
        type: 'GET',
        success: function(data) {
            console.log("Sections fetched successfully", data);
            var sectionSelect = $('select[name="section_id"]');
            sectionSelect.empty();
            sectionSelect.append('<option value="" selected>Select Section</option>');
            $.each(data, function(key, value) {
                sectionSelect.append('<option value="' + key + '">' + value + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching sections:", error);
        }
    });
});


// Search button click event
$('#searchButton').click(function() {
    console.log("Search button clicked");
    fetchAndPopulateStudents();
});


function fetchAndPopulateStudents() {
    var classId = $('select[name="class_id"]').val();
    var sectionId = $('select[name="section_id"]').val();
    var date = $('#admission-datepicker').val();
    var attendance_types = @json($attendance_types);


    console.log("Fetching students for class:", classId, "section:", sectionId, "date:", date);


    $.ajax({
        url: 'get-students-by-section/' + classId + '/' + sectionId + '/' + date,
        type: 'GET',
        success: function(data) {
            console.log("Students fetched successfully", data);
            populateStudentTable(data.original, attendance_types);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching students:", error);
        }
    });
}


function populateStudentTable(students, attendance_types) {
    console.log("Populating student table", students);
    var tableBody = $('#studentTableBody');
    tableBody.empty();


    if (students && students.length > 0) {
        students.forEach(function(studentData) {
            var student = studentData.student;
            var user = studentData.user;
            var row = createStudentRow(student, user, attendance_types);
            tableBody.append(row);
        });


        $('#saveAttendanceButton, #markHolidayButton, #exportReportButton').show();
        populateExistingAttendance(students);
    } else {
        tableBody.append('<tr><td colspan="5">No students found for the selected section</td></tr>');
        $('#saveAttendanceButton, #markHolidayButton, #exportReportButton').hide();
    }
}


function createStudentRow(student, user, attendance_types) {
    var fullName = [user.f_name, user.m_name, user.l_name].filter(Boolean).join(' ');
    var row = '<tr data-student-id="' + student.id + '" data-name="' + fullName + '">' +
        '<td>' + student.admission_no + '</td>' +
        '<td>' + student.roll_no + '</td>' +
        '<td>' + fullName + '</td>' +
        '<td>' + createAttendanceRadios(student, attendance_types) + '</td>' +
        '<td><input type="text" name="remarks[' + student.id + ']" value="' + (student.remarks || '') + '"></td>' +
        '</tr>';
    return row;
}


function createAttendanceRadios(student, attendance_types) {
    var radios = '';
    attendance_types.forEach(function(type) {
        // Check if the type is one of the specified IDs and create the radio button accordingly.
        if ([1, 2, 4].includes(type.id)) {
            // Check if the current type ID is the "Holiday" (ID: 4) and hide it by default.
            var isHidden = type.id === 4 ? 'style="display:none;" class="holiday-radio"' : '';
            var isChecked = student.attendance_type_id == type.id || (student.attendance_type_id === undefined && type.id == 1);


            radios += '<label class="attendance-radio" ' + isHidden + '>' +
                '<input type="radio" name="attendance_type_id[' + student.id + ']" value="' + type.id + '" ' +
                (isChecked ? 'checked' : '') + '> ' +
                '<span>' + type.type + '</span>' +
                '</label>';
        }
    });
    return radios;
}


// Function to populate existing attendance
function populateExistingAttendance(students) {
    $.each(students, function(index, studentData) {
        var student = studentData.student;
        if (studentData.student_attendances && studentData.student_attendances.length > 0) {
            var attendance = studentData.student_attendances[0];
            var attendanceTypeId = attendance.attendance_type_id;
            $('input[name="attendance_type_id[' + student.id + ']"][value="' + attendanceTypeId + '"]').prop('checked', true);
            $('input[name="remarks[' + student.id + ']"]').val(attendance.remarks);
        }
    });
}


// Sorting functionality
$('#sortBy').change(function() {
    console.log("Sort dropdown changed");
    var sortBy = $(this).val();
    sortStudentTable(sortBy);
});


function sortStudentTable(sortBy) {
    console.log("Sorting table by:", sortBy);
    var rows = $('#studentTableBody tr').get();


    rows.sort(function(a, b) {
        var keyA, keyB;


        if (sortBy === 'name') {
            keyA = $(a).data('name').toUpperCase();
            keyB = $(b).data('name').toUpperCase();
        } else { // Default to roll_no
            keyA = parseInt($(a).find('td:eq(1)').text());
            keyB = parseInt($(b).find('td:eq(1)').text());
        }


        if (keyA < keyB) return -1;
        if (keyA > keyB) return 1;
        return 0;
    });


    $.each(rows, function(index, row) {
        $('#studentTableBody').append(row);
    });
}


// Save Attendance button click event
$('#saveAttendanceButton').click(function() {
    console.log("Save Attendance button clicked");
    var classId = $('select[name="class_id"]').val();
    var sectionId = $('select[name="section_id"]').val();
    var date = $('#admission-datepicker').val();
    var attendanceData = [];


    $('#studentTableBody tr').each(function() {
        var studentId = $(this).data('student-id');
        var attendanceTypeId = $('input[name="attendance_type_id[' + studentId + ']"]:checked').val();
        var remarks = $('input[name="remarks[' + studentId + ']"]').val();


        attendanceData.push({
            student_id: studentId,
            attendance_type_id: attendanceTypeId,
            date: date,
            remarks: remarks
        });
    });


    console.log("Saving attendance data:", attendanceData);


    $.ajax({
        url: 'student-attendances/save-attendance',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
            class_id: classId,
            section_id: sectionId,
            attendance_data: attendanceData
        },
        success: function(response) {
            console.log("Attendance saved successfully", response);
            if (response.message) {
                toastr.success(response.message);
            } else {
                toastr.success('Attendance saved successfully');
            }
        },
        error: function(error) {
            console.error("Error saving attendance:", error);
            toastr.error('Error occurred while saving attendance. Please try again later.');
        }
    });
});


  // Mark holiday button click event
$('#markHolidayButton').click(function() {
    // Send an AJAX request to mark holiday
    $.ajax({
        url: '',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Show all hidden radio buttons for "Holiday" (value="4") and check them
            $('input[type="radio"][value="4"]').closest('.attendance-radio').show().find('input').prop('checked', true);
        },
        error: function(xhr, status, error) {
            // Handle the error response
            console.error('Error marking holiday:', error);
            alert('Error marking holiday. Please try again.');
        }
    });
});


                $('#markSchoolHolidayButton').click(function() {
                    var date = $('#admission-datepicker').val();
                    if (!date) {
                        toastr.warning('Please select a date first.');
                        return;
                    }


                    if (confirm('Are you sure you want to mark ' + date + ' as a holiday for the entire school?')) {
                        $.ajax({
                            url: '{{ route('admin.student.mark-holiday') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: { date: date },
                            success: function(response) {
                                console.log('Server response:', response);
                                if (response.success) {
                                    toastr.success(response.message);
                                    // Update UI to reflect the change
                                    updateUIForSchoolHoliday(date);
                                } else {
                                    toastr.error(response.message || 'Error marking holiday for the school.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error details:', xhr.responseText);
                                toastr.error('Error marking holiday for the school. Please check the console for details.');
                            }
                        });
                    }
                });


                function updateUIForSchoolHoliday(date) {
                    // Implement this function to update your UI
                    console.log('Updating UI for holiday on', date);
                    // For example, you might want to refresh the attendance table or update some indicators
                }


                // Attach click event handler to the Save Attendance button
                $('#saveAttendanceButton').click(function() {
                    // Get the selected class ID, section ID, and date
                    var classId = $('select[name="class_id"]').val();
                    var sectionId = $('select[name="section_id"]').val();
                    var date = $('#admission-datepicker').val();


                    // Prepare an array to store attendance data
                    var attendanceData = [];


                    // Loop through each row in the table
                    $('#studentTableBody tr').each(function() {
                        var studentId = $(this).data('student-id');
                        var attendanceTypeId = $('input[name="attendance_type_id[' + studentId +
                            ']"]:checked').val();
                        var remarks = $('input[name="remarks[' + studentId + ']"]').val();


                        // Add data to the array
                        attendanceData.push({
                            student_id: studentId,
                            attendance_type_id: attendanceTypeId,
                            date: date,
                            remarks: remarks
                        });
                    });


                        // Send an AJAX request to save the attendance data


                        $.ajax({
                        url: 'student-attendances/save-attendance',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        data: {
                            class_id: classId,
                            section_id: sectionId,
                            attendance_data: attendanceData
                        },
                        success: function(response) {
                            // Handle the response (e.g., show a success message)
                            if (response.message) {
                                // If there's a success message in the response, display it
                                toastr.success(response.message);
                            } else {
                                // If no success message is provided, display a default success message
                                toastr.success('Attendance saved successfully');
                            }
                        },
                        error: function(error) {
                            // Handle errors (e.g., show an error message)
                            console.error(error);
                            toastr.error(
                                'Error occurred while saving attendance. Please try again later.'
                            );
                        }
                    });
                });


                $('#exportReportButton').click(function() {
                    // Get the table data
                    var tableData = [];
                    $('#studentTableBody tr').each(function() {
                        var row = {
                            admission_no: $(this).find('td:eq(0)').text(),
                            roll_no: $(this).find('td:eq(1)').text(),
                            name: $(this).find('td:eq(2)').text(),
                            attendance: $(this).find('input[type="radio"]:checked').siblings('span').text(),
                            note: $(this).find('td:eq(4) input').val()
                        };
                        tableData.push(row);
                    });


    var csv = 'Admission No,Roll No,Name,Attendance,Note\n';
    tableData.forEach(function(row) {
        csv += `${row.admission_no},${row.roll_no},${row.name},${row.attendance},${row.note}\n`;
    });


    var blob = new Blob([csv], { type: 'text/csv' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'attendance_report.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
});


// Nepali date picker initialization for the main date input
$('#admission-datepicker').nepaliDatePicker({
    dateFormat: "YYYY-MM-DD",
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 200,
    onChange: function() {
        $(this).change();
    }
});


// Set current Nepali date on page load
$(document).ready(function () {
    var currentDate = NepaliFunctions.GetCurrentBsDate();
    var padZero = function (num) {
        return num < 10 ? '0' + num : num;
    };
    var formattedDate = currentDate.year + '-' + padZero(currentDate.month) + '-' + padZero(currentDate.day);
    $('#admission-datepicker').val(formattedDate);
});
});
      // Initialize date pickers for the holiday range modal
      $('#holidayStartDate, #holidayEndDate').nepaliDatePicker()
    $("#holidayStartDate").nepaliDatePicker({
    container: "#holidayRangeModal",
    dateFormat: "YYYY-MM-DD",
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 200,
    onChange: function() {
        $(this).change();
    }
});


$("#holidayEndDate").nepaliDatePicker({
    container: "#holidayRangeModal",
    dateFormat: "YYYY-MM-DD",
    ndpYear: true,
    ndpMonth: true,
    ndpYearCount: 200,
    onChange: function() {
        $(this).change();
    }
});


// Open the holiday range modal
$('#markHolidayRangeButton').click(function() {
    $('#holidayRangeModal').modal('show');
});


// Handle saving the holiday range
$('#saveHolidayRange').click(function() {
    var startDate = $('#holidayStartDate').val();
    var endDate = $('#holidayEndDate').val();
    var reason = $('#holidayReason').val();


    if (!startDate || !endDate) {
        toastr.warning('Please select both start and end dates.');
        return;
    }


    if (confirm('Are you sure you want to mark holidays from ' + startDate + ' to ' + endDate + '?')) {
        $.ajax({
            url: '{{ route("admin.student.mark-holiday-range") }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                start_date: startDate,
                end_date: endDate,
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#holidayRangeModal').modal('hide');
                    // Optionally, update UI or refresh data
                } else {
                    toastr.error(response.message || 'Error marking holiday range.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', xhr.responseText);
                toastr.error('Error marking holiday range. Please check the console for details.');
            }
        });
    }
});
           
</script>
   
@endsection



