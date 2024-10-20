@extends('backend.layouts.master')


@section('content')
<div class="container">
    <h1>Attendance Report</h1>


    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <form action="{{ route('admin.attendance_reports.report') }}" method="GET" class="mb-4">
        @csrf
        <div class="row align-items-end">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="school_id">Select School:</label>
                    <select name="school_id" id="school_id" class="form-control">
                        <option value="">-- Select a School --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="from_date">From Date:</label>
                    <input type="text" name="from_date" id="fromDatepicker" class="form-control nepali-datepicker" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="to_date">To Date (Optional):</label>
                    <input type="text" name="to_date" id="toDatepicker" class="form-control nepali-datepicker">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Generate Report</button>
            </div>
        </div>
    </form>


    <div id="buttons-container" class="mt-3"></div>


    <table class="table mt-4" id="attendanceTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Students</th>
                <th>Present Students</th>
                <th>Absent Students</th>
                <th>Total Staffs</th>
                <th>Present Staffs</th>
                <th>Absent Staffs</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($schoolData) && is_array($schoolData) && count($schoolData) > 0)
                @foreach($schoolData as $data)
                <tr>
                    <td>{{ $data['date'] }}</td>
                    <td>{{ $data['total_students'] ?? 0 }}</td>
                    <td>{{ $data['present_students'] ?? 0 }}</td>
                    <td>{{ $data['absent_students'] ?? 0 }}</td>
                    <td>{{ $data['total_staff'] ?? 0 }}</td>
                    <td>{{ $data['present_staff'] ?? 0 }}</td>
                    <td>{{ $data['absent_staff'] ?? 0 }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No data found for the selected criteria.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


<!-- DataTables and Buttons extension CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
<script src="http://nepalidatepicker.sajanmaharjan.com.np/nepali.datepicker/js/nepali.datepicker.v4.0.4.min.js"></script>


<!-- Custom CSS for button styling -->
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
    /* Hide the processing message */
    .dataTables_processing {
        display: none !important;
    }
    /* Add a loading overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


<script type="text/javascript">
$(document).ready(function() {


     // Retrieve the last selected school ID from localStorage
     var lastSelectedSchool = localStorage.getItem('lastSelectedSchool');
    if (lastSelectedSchool) {
        $('#school_id').val(lastSelectedSchool);
    }


    // Save the selected school ID to localStorage when changed
    $('#school_id').change(function() {
        localStorage.setItem('lastSelectedSchool', $(this).val());
    });
   
    // Initialize nepali-datepicker
    $('#fromDatepicker').nepaliDatePicker({
        dateFormat: 'YYYY-MM-DD',
        closeOnDateSelect: true
    });


    $('#toDatepicker').nepaliDatePicker({
        dateFormat: 'YYYY-MM-DD',
        closeOnDateSelect: true
    });


    // Add loading overlay to the table
    $('#attendanceTable').wrap('<div class="position-relative"></div>').before('<div class="loading-overlay"><div class="loading-spinner"></div></div>');


    // Initialize DataTable
    var table = $('#attendanceTable').DataTable({
        processing: false,
        serverSide: true,
        searching: false,
        ajax: {
            url: '{{ route("admin.attendance_reports.data") }}',
            data: function (d) {
                d.from_date = $('#fromDatepicker').val();
                d.to_date = $('#toDatepicker').val();
                d.school_id = $('#school_id').val();
            },
            beforeSend: function() {
                $('.loading-overlay').show();
            },
            complete: function() {
                $('.loading-overlay').hide();
            },
            error: function(xhr, error, code) {
                console.log(xhr.responseText);
                $('.loading-overlay').hide();
            }
        },
        columns: [
            { data: 'date', name: 'date' },
            { data: 'total_students', name: 'total_students' },
            { data: 'present_students', name: 'present_students' },
            { data: 'absent_students', name: 'absent_students' },
            { data: 'total_staff', name: 'total_staff' },
            { data: 'present_staff', name: 'present_staff' },
            { data: 'absent_staff', name: 'absent_staff' },
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


    // Search button click event
    $('#searchButton').on('click', function() {
        $('.loading-overlay').show();
        table.ajax.reload(function() {
            $('.loading-overlay').hide();
        });
    });
});
</script>
@endsection

