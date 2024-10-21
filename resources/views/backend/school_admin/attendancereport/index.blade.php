@extends('backend.layouts.master')
@section('content')
<div class="container">
    <h1>Attendance Report</h1>
    <div class="row">
        <div class="col-lg-3 col-sm-3 mt-2">
            <div class="p-2 label-input">
                <label for="nepali-datepicker">From Date:</label>
                <div class="form-group">
                    <div class="input-group date" id="admission-datetimepicker" data-target-input="nearest">
                        <input id="fromDatepicker" name="from_date" type="text" class="form-control datetimepicker-input" />
                    </div>
                    @error('date')
                        <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-3 mt-2">
            <div class="p-2 label-input">
                <label for="nepali-datepicker">To Date:</label>
                <div class="form-group">
                    <div class="input-group date" id="admission-datetimepicker" data-target-input="nearest">
                        <input id="toDatepicker" name="to_date" type="text" class="form-control datetimepicker-input" />
                    </div>
                    @error('date')
                        <strong class="text-danger">{{ $message }}</strong>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-3 mt-2">
            <div class="p-2 label-input">
                <label for="classSelect">Select class:</label>
                <select id="classSelect" name="class_id" class="form-control">
                    <option value="">Select class</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                    @endforeach
                </select>
                @error('class_id')
                    <strong class="text-danger">{{ $message }}</strong>
                @enderror
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 mt-2">
            <div class="p-2 label-input">
                <label for="studentName">Student Name:</label>
                <input id="studentName" name="student_name" type="text" class="form-control" placeholder="Enter student name" />
                @error('student_name')
                    <strong class="text-danger">{{ $message }}</strong>
                @enderror
            </div>
        </div>
        <div class="col-lg-3 col-sm-3 mt-5">
            <div class="search-button-container d-flex align-items-end">
                <button id="searchButton" class="btn btn-sm btn-primary">Search</button>
            </div>
        </div>
        <div class="col-lg-6 col-sm-6 mt-2 d-flex justify-content-end">
            <div id="buttons-container" class="d-flex align-items-center"></div>
        </div>
    </div>
    <table id="attendanceTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Student Name</th>
                @foreach ($dates as $date)
                    <th>{{ $date }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <!-- Data will be populated by DataTables -->
        </tbody>
    </table>
</div>

<!-- DataTables and Buttons extension CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">

<!-- jQuery and DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- Buttons extension JS -->
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.4.min.js"></script>

<style>
    #buttons-container {
        display: flex;
        align-items: center;
    }
    #buttons-container .dt-buttons {
        display: flex;
        flex-direction: row;
    }
    #buttons-container .dt-buttons button {
        margin-right: 5px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: left;
        text-align: right;
    }
</style>

<script type="text/javascript">
  $(document).ready(function() {
    // Initialize nepali-datepicker
    $('#fromDatepicker').nepaliDatePicker({
        dateFormat: 'YYYY-MM-DD',
        closeOnDateSelect: true
    });

    $('#toDatepicker').nepaliDatePicker({
        dateFormat: 'YYYY-MM-DD',
        closeOnDateSelect: true,
    });

    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: '{{ route("admin.attendance_schoolreports.data") }}',
            data: function (d) {
                d.from_date = $('#fromDatepicker').val();
                d.to_date = $('#toDatepicker').val();
                d.class_id = $('#classSelect').val();
                d.student_name = $('#studentName').val();
            },
            error: function(xhr, error, code) {
                console.log(xhr.responseText);
            }
        },
        columns: [
            { data: 'student_name', name: 'student_name' },
            @foreach ($dates as $date)
                { data: 'attendance', name: 'attendance', render: function(data, type, row) {
                    const attendance = data.split(',');
                    return attendance[@foreach ($dates as $index => $date){{ $index }}@endforeach];
                }},
            @endforeach
        ],
        dom: '<"d-flex justify-content-between"lfB>rtip',
        buttons: {
            dom: {
                button: {
                    className: 'btn btn-sm btn-primary'
                }
            },
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            container: '#buttons-container'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ordering: false,
        language: {
            emptyTable: "No matching records found"
        }
    });

    $('#searchButton').on('click', function() {
        if ($('#fromDatepicker').val() && $('#toDatepicker').val()) {
            table.draw();
        } else {
            alert('Please select both From Date and To Date before searching.');
        }
    });
});
  </script>
  @endsection
