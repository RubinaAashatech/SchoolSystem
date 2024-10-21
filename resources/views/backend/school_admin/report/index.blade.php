@extends('backend.layouts.master')
@section('content')
<div class="container-fluid">
    <h1>Attendance Report</h1>
    <form action="{{ route('admin.school_attendance_reports.report') }}" method="GET">
        <div class="row align-items-end">
            <div class="col-lg-3 col-sm-3 mt-2">
                <div class="p-2 label-input">
                    <label for="nepali-datepicker">Date:</label>
                    <div class="form-group">
                        <div class="input-group date" id="admission-datetimepicker" data-target-input="nearest">
                            <input id="nepali-datepicker" name="date" type="text" class="form-control datetimepicker-input" />
                        </div>
                        @error('date')
                            <strong class="text-danger">{{ $message }}</strong>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label for="class_id">Class:</label>
                    <select name="class_id" id="class_id" class="form-control" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ (request('class_id') == $class->id) ? 'selected' : '' }}>
                                {{ $class->class }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label for="section_id">Section:</label>
                    <select name="section_id" id="section_id" class="form-control" required>
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ (request('section_id') == $section->id) ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
                             
            <div class="col-lg-3 col-sm-3 mt-2">
                <div class="search-button-container d-flex align-items-end">
                    <button type="submit" class="btn btn-sm btn-primary">Search</button>
                </div>
            </div>
        </div>
    </form>
    
    <div id="table-container" class="mt-4">
        <div id="buttons-container"></div>
        <table id="attendanceTable" class="table table-striped table-bordered w-100">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Attendance Type</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated by DataTables -->
            </tbody>
        </table>
    </div>
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
    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }
    #table-container {
        width: 100%;
        overflow-x: auto;
    }
    #buttons-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    #buttons-container .dt-buttons {
        display: flex;
        flex-direction: row;
    }
    #buttons-container .dt-buttons button {
        margin-right: 5px;
    }
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
    }
    #attendanceTable {
        width: 100% !important;
    }
</style>

<script type="text/javascript">
$(document).ready(function() {
    // Initialize nepali-datepicker
    $('#nepali-datepicker').nepaliDatePicker({
        dateFormat: 'YYYY-MM-DD',
        closeOnDateSelect: true
    });

    var currentDate = NepaliFunctions.GetCurrentBsDate();
    var padZero = function (num) {
        return num < 10 ? '0' + num : num;
    };
    var formattedDate = currentDate.year + '-' + padZero(currentDate.month) + '-' + padZero(currentDate.day);
    $('#nepali-datepicker').val(formattedDate);

    $('#table-container').hide();

    var table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        responsive: true,
        ajax: {
            url: '{{ route("admin.school_attendance_reports.data") }}',
            type: 'GET',
            data: function (d) {
                d.date = $('#nepali-datepicker').val();
                d.class_id = $('select[name="class_id"]').val();
                d.section_id = $('select[name="section_id"]').val();
            }
        },
        columns: [
            { data: 'student_name', name: 'student_name' },
            { data: 'attendance_type', name: 'attendance_type' },
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

    $('#class_id').on('change', function() {
        var classId = $(this).val();
        if (classId) {
            window.location.href = '{{ route("admin.school_attendance_reports.index") }}?class_id=' + classId;
        }
    });

    $('form').on('submit', function(e) {
        e.preventDefault();
        var classId = $('select[name="class_id"]').val();
        var sectionId = $('select[name="section_id"]').val();
        if (classId && sectionId) {
            $('#table-container').show();
            table.ajax.reload();
        } else {
            $('#table-container').hide();
            alert('Please select both Class and Section to view the report.');
        }
    });

    table.clear().draw();
});
</script>

@endsection