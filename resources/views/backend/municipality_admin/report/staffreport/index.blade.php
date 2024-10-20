@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Staff Report</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="reportForm" class="mb-4">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="search">Search Keywords:</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Search...">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Generate Report</button>
            </div>
        </div>
    </form>

    <div id="buttons-container" class="mt-3"></div>

    <table class="table mt-4" id="staffTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Contact Detail</th>
                <th>School Name</th>
            </tr>
        </thead>
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
        display: none;
    }
</style>

<script type="text/javascript">
$(document).ready(function() {
    var table = $('#staffTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.staff_reports.report") }}',
            data: function (d) {
                d.school_id = $('#school_id').val();
                d.search = { value: $('#search').val() };
            },
            dataSrc: function(json) {
                // Only return data if school is selected or search term is entered
                if ($('#school_id').val() || $('#search').val()) {
                    return json.data;
                } else {
                    return [];
                }
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'gender', name: 'gender' },
            { data: 'phone', name: 'phone' },
            { data: 'school_name', name: 'school_name' }
        ],
        dom: '<"d-flex justify-content-between"lB>rtip',
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
        ordering: true,
        language: {
            emptyTable: "Please select a school or enter a search term to view the staff report"
        }
    });

    $('#reportForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });

    // Add event listener for the search input
    $('#search').on('keyup', function() {
        table.ajax.reload();
    });

    // Add event listener for school selection
    $('#school_id').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endsection