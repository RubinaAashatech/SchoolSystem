@extends('backend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">{{ $page_title }}</h1>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-2">
            <select id="school_filter" class="form-control">
                <option value="">Select School</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" id="custom_search" class="form-control" placeholder="Search">
        </div>
        <div class="col-md-2">
            <input type="text" id="nepali-datepicker" class="form-control nepali-date" placeholder="Start Date (YYYY-MM-DD)">
        </div>
        <div class="col-md-2">
            <input type="text" id="nepali-datepicker2" class="form-control nepali-date" placeholder="End Date (YYYY-MM-DD)">
        </div>
        <div class="col-md-2">
            <button id="search_button" class="btn btn-primary">Search</button>
        </div>
        <div class="col-md-2">
            <button id="export_excel" class="btn btn-success">Export to Excel</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="municipality_head_teacherlogs_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>School Name</th>
                                <th>Major Incidents</th>
                                <th>Major Work Observation/Accomplishment/Progress</th>
                                <th>Assembly Management/ECA/CCA</th>
                                <th>Miscellaneous</th>
                                <th>Logged Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('backend.includes.nepalidate')
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#municipality_head_teacherlogs_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.municipality-headteacher-logs.get') }}",
                data: function(d) {
                    d.school_id = $('#school_filter').val();
                    d.start_date = $('#nepali-datepicker').val();
                    d.end_date = $('#nepali-datepicker2').val();
                    d.search = $('#custom_search').val();
                }
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'school_name', name: 'schools.name'},
                {data: 'major_incidents', name: 'head_teacher_logs.major_incidents'},
                {data: 'major_work_observation', name: 'head_teacher_logs.major_work_observation'},
                {data: 'assembly_management', name: 'head_teacher_logs.assembly_management'},
                {data: 'miscellaneous', name: 'head_teacher_logs.miscellaneous'},
                {data: 'logged_date', name: 'head_teacher_logs.logged_date'},
            ]
        });

        $('#search_button').on('click', function() {
            table.draw();
        });

        $('#custom_search').on('keyup', function() {
            table.draw();
        });

        $('#school_filter').on('change', function() {
            table.draw();
        });

        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#search_button').click();
            }
        });

        $('#export_excel').on('click', function() {
            var school_id = $('#school_filter').val();
            var start_date = $('#nepali-datepicker').val();
            var end_date = $('#nepali-datepicker2').val();
            var search = $('#custom_search').val();

            var url = "{{ route('admin.municipality-headteacher-logs.export') }}?" +
                      "school_id=" + school_id +
                      "&start_date=" + start_date +
                      "&end_date=" + end_date +
                      "&search=" + search;

            window.location.href = url;
        });
    });
</script>
@endsection