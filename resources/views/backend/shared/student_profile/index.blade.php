@extends('backend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-2 text-gray-800">Student Profiles</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Students</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.students.profile.search') }}" method="GET">
                @csrf
                <div class="form-row align-items-center">
                    <div class="form-group col-md-9 d-flex">
                        <input type="text" class="form-control flex-grow-1" id="search_term" name="search_term" placeholder="Search by Name/Email" value="{{ request('search_term') }}">
                        <button type="submit" class="btn btn-primary ml-2">Search</button>
                    </div>
                </div>                              
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->user->f_name }} {{ $student->user->m_name }} {{ $student->user->l_name }}</td>
                                <td>{{ $student->user->email }}</td>
                                <td>{{ $student->user->phone }}</td>
                                <td>{{ $student->user->dob }}</td>
                                <td>
                                    <a href="{{ route('admin.students.profile.show', $student->id) }}" class="btn btn-info btn-sm">View Profile</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endpush