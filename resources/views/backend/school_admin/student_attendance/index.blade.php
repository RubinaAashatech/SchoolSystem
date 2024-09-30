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
                            <div class="col-sm-12 col-md-12 col-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="saveAttendanceButton">Save Attendance</button>
                                <button type="button" class="btn btn-primary" id="markHolidayButton" style="margin-left: 5px;">Mark Holiday</button>
                                <button type="button" class="btn btn-primary" id="exportReportButton" style="margin-left: 5px;">Export Report</button>
                            </div>
                        </div>
                        <!-- Search input -->
                        {{-- <div class="row mb-2">
                            <div class="col-sm-3 col-md-3 col-3 d-flex justify-content-end position-relative">
                                <div style="position: relative;">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search">
                                    <span id="clearSearchInput" class="position-absolute top-50 end-0 translate-middle-y text-muted" style="cursor: pointer;">&times;</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-12">
                                <div class="report-table-container">
                                    <div class="table-responsive">
                                        <table id="student-table" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
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
                                                <!-- Student data will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var table = $('#student-table').DataTable({
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    "order": [[1, 'asc']],  // Default sorting by Roll No column in ascending order
                    "columnDefs": [
                        { "targets": [0, 2, 3, 4], "orderable": false },  // Disable sorting for all columns except index 1 (Roll No)
                        { "targets": [1], "orderable": true }  // Roll No column - sortable
                    ]
                });

                // Function to fetch students dynamically
                function fetchStudents(sortBy, sortOrder) {
                    $.ajax({
                        url: '/admin/student/get',
                        type: 'POST',
                        data: {
                            sortBy: sortBy,
                            sortOrder: sortOrder
                        },
                        success: function(response) {
                            updateTable(response.students); // Update the table with sorted data
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching student data:', error);
                        }
                    });
                }

                // Initial fetch of students
                fetchStudents('roll_no', 'asc'); // Initial fetch sorted by Roll No column

                // Handle DataTables sorting event for Roll No column only
                $('#student-table').on('click', 'th', function() {
                    var columnIndex = table.column(this).index();
                    var column = table.settings().init().columns[columnIndex];
                    var sortBy = column.data;

                    if (sortBy === 'roll_no') {
                        var sortOrder = table.order()[0][1]; // Get the sort order ('asc' or 'desc')
                        fetchStudents(sortBy, sortOrder); // Fetch students based on clicked column for sorting
                    }
                });

                // Function to update table based on student data
                function updateTable(students) {
                    const tableBody = $('#studentTableBody');
                    tableBody.empty();

                    if (students.length === 0) {
                        tableBody.append('<tr><td colspan="5" class="text-center">No results found</td></tr>');
                    } else {
                        students.forEach(student => {
                            const row = `<tr>
                                <td>${student.admission_no}</td>
                                <td>${student.roll_no}</td>
                                <td>${student.f_name}</td>
                                <td>${student.attendance_type_id}</td>
                                <td>${student.remarks}</td>
                            </tr>`;
                            tableBody.append(row);
                        });
                    }

                    // Redraw DataTable after updating table content
                    table.clear().rows.add(tableBody.find('tr')).draw();
                }

                // Search functionality
                $('#searchInput').on('input', function () {
                    const query = $(this).val().toLowerCase();
                    updateTableBasedOnSearch(query);
                });

                $('#clearSearchInput').on('click', function () {
                    $('#searchInput').val('');
                    fetchStudents('roll_no', 'asc'); // Clear search and re-fetch sorted by Roll No column
                });

                // Function to update table based on search input
                function updateTableBasedOnSearch(query) {
                    const tableRows = $('#studentTableBody').find('tr');

                    tableRows.each(function() {
                        const rollNo = $(this).find('td:nth-child(2)').text().toLowerCase();
                        const studentName = $(this).find('td:nth-child(3)').text().toLowerCase();

                        // Check if the roll number is between 1 and 1000
                        const rollNoInt = parseInt(rollNo, 10);
                        const isRollNoInRange = rollNoInt >= 1 && rollNoInt <= 1000;

                        // Display the row if it matches the query and the roll number is in range
                        if ((rollNo.includes(query) || studentName.includes(query)) && isRollNoInRange) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        </script>

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

            // Initially hide the Save Attendance and Mark Holiday buttons
            $('#saveAttendanceButton, #markHolidayButton,#exportReportButton').hide();

            $('#searchButton').click(function() {
                // Get the selected class ID and section ID
                var classId = $('select[name="class_id"]').val();
                var sectionId = $('select[name="section_id"]').val();
                var date = $('#admission-datepicker').val();
                var attendance_types = @json($attendance_types);

                // Fetch students based on the selected class and section
                $.ajax({
                    url: 'get-students-by-section/' + classId + '/' + sectionId + '/' + date,
                    type: 'GET',
                    success: function(data) {
                        // Clear existing content in the student container
                        $('#studentTableBody').empty();

                        // Check if there are any students
                        if (data.original && data.original.length > 0) {
                            // Append new rows based on the fetched students
                            $.each(data.original, function(index, studentData) {
                                var student = studentData.student; // Extract student data
                                var user = studentData.user; // Extract user data
                                var row = '<tr data-student-id="' + student.id + '">' +
                                    '<td>' + student.admission_no + '</td>' +
                                    '<td>' + student.roll_no + '</td>' +
                                    '<td>' + (user ? (user.f_name ? user.f_name + ' ' : '') + (user.m_name ? user.m_name + ' ' : '') + (user.l_name ? user.l_name : '') : '') + '</td>' +
                                    '<td>';

                                    if (typeof attendance_types !== 'undefined') {
                                        // Append radio buttons for each attendance type (IDs 1, 2, and 4)
                                        $.each(attendance_types, function(i, attendance_type) {
                                            // Check if the attendance type ID is 1, 2, or 4
                                            if (attendance_type.id === 1 || attendance_type.id === 2 || attendance_type.id === 4) {
                                                var isChecked = student.attendance_type_id == attendance_type.id || (student.attendance_type_id === undefined && attendance_type.id == 1);

                                                // For the class to hide the button of Holiday
                                                


                                                row += '<label for="attendance_type_' +
                                                    student.id + '_' + attendance_type.id +
                                                    '" class="attendance-radio">' +
                                                    '<input type="radio" name="attendance_type_id[' +
                                                    student.id + ']" value="' +
                                                    attendance_type.id +
                                                    '" id="attendance_type_' + student
                                                    .id + '_' + attendance_type.id +
                                                    '" ' +
                                                    (isChecked ? 'checked' : '') +
                                                    '> ' +
                                                    '<span>' + attendance_type.type +
                                                    '</span>' +
                                                    '</label>';
                                            }
                                        });

                                        // Show the Save Attendance button
                                        $('#saveAttendanceButton').show();
                                        // Show the Mark Holiday button
                                        $('#markHolidayButton').show();
                                        // Show the Export button
                                        $('#exportReportButton').show();
                                    } else {
                                        // Handle the case where attendance_types is not defined
                                        row += 'Attendance types not available';
                                    }

                                    row += '</td>' +
                                        '<td><input type="text" name="remarks[' + student
                                        .id + ']" value="' +
                                        (student.remarks ? student.remarks : '') +
                                        '"></td>' +
                                        '</tr>';

                                    $('#studentTableBody').append(row);
                                });

                                // Populate existing attendance data in the form
                                populateExistingAttendance(data.original);
                            } else {
                                // If there are no students, display a message or handle accordingly
                                $('#studentTableBody').append(
                                    '<tr><td colspan="5">No students found for the selected section</td></tr>'
                                );
                                // Hide the Save Attendance and Mark Holiday buttons
                                $('#saveAttendanceButton, #markHolidayButton').hide();
                            }
                        }
                    });
                });

                // Function to populate existing attendance data in the form
                function populateExistingAttendance(students) {
                    $.each(students, function(index, studentData) {
                        var student = studentData.student;
                        var user = studentData.user;

                        // Assuming there is only one attendance record per student for the given date
                        if (studentData.student_attendances && studentData.student_attendances.length > 0) {
                            var attendance = studentData.student_attendances[0];
                            var attendanceTypeId = attendance.attendance_type_id;

                            // Assuming the attendance_type_id and remarks correspond to the existing data
                            $('input[name="attendance_type_id[' + student.id + ']"][value="' +
                                attendanceTypeId + '"]').attr('checked', true);
                            $('input[name="remarks[' + student.id + ']"]').val(attendance.remarks);
                        }
                    });
                }

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
                            // Check all holiday radio buttons
                            $('input[type="radio"][value="4"]').prop('checked', true);
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

                    // Convert the table data to CSVs
                    var csv = 'Admission No,Roll No,Name,Attendance,Note\n';
                    tableData.forEach(function(row) {
                        csv += `${row.admission_no},${row.roll_no},${row.name},${row.attendance},${row.note}\n`;
                    });

                    // Create a blob and link to download the CSV
                    var blob = new Blob([csv], { type: 'text/csv' });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'attendance_report.csv';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
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
            });
        </script>
    @endsection
@endsection
