@extends('backend.layouts.master')

@section('content')
    <div class="mt-4">
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ url()->previous() }}"><button class="btn-primary btn-sm"><i class="fa fa-angle-double-left"></i>
                Back</button></a>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <form action="{{ route('admin.subject-teachers.store', [
                            'subjectGroupId' => $subjectGroup->id,
                            'classId' => $class->id ?? '',
                            'sectionId' => $section->id ?? '' 
                        ]) }}" method="POST" class="mb-3">
                        @csrf
                        <input type="hidden" name="subject_group_id" value="{{ $subjectGroup->id }}">
                        <input type="hidden" name="class_id" value="{{ $class->id ?? '' }}">
                        <input type="hidden" name="section_id" value="{{ $section->id ?? '' }}">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Subject</label><small class="req"> *</small>
                                    <div class="form-group select">
                                        <select name="subject_id" id="subject_id">
                                            <option value="">Select Subject</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id')
                                            <strong class="text-danger">{{ $message }}</strong>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label>Class</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $class->class }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <label>Section</label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" value="{{ $section->section_name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
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
                                <button type="submit" class="btn btn-primary" id="saveButton">Assign Teacher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Assigned Teachers</h5>
                    <form action="{{ route('admin.subject-teachers.assign', [
                            'subjectGroupId' => $subjectGroup->id,
                            'classId' => $class->id ?? '',
                            'sectionId' => $section->id ?? '' 
                        ]) }}" method="GET" class="mb-3">
                        {{-- <div class="row">
                            <div class="col-md-4">
                                <select name="class_id" class="form-control">
                                    <option value="">All Classes</option>
                                    @foreach($class as $class)
                                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->class }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div> --}}
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assignedTeachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->subject->subject }}</td>
                                        <td>{{ $teacher->class->class }}</td>
                                        <td>{{ $teacher->section->section_name }}</td>
                                        <td>{{ $teacher->user->f_name . ' ' . $teacher->user->l_name }}</td>
                                        {{-- <td>
                                            <a href="{{ route('admin.edit', $teacher->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.delete', $teacher->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $assignedTeachers->links() }} 
                </div>
            </div>
        </div>
    </div>
@endsection
