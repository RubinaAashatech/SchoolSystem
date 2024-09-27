@extends('backend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="page-title">{{ $page_title }}</h1>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <input type="text" id="custom_search" class="form-control" placeholder="Search by school name or major incidents">
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
<script>
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#municipality_head_teacherlogs_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.municipality-headteacher-logs.get') }}",
            error: function (xhr, error, thrown) {
                console.error('DataTables Ajax error:', error);
                console.error('Status:', xhr.status);
                console.error('Response:', xhr.responseText);
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

    $('#custom_search').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endsection