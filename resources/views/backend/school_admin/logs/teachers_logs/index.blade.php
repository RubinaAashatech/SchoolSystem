@extends('backend.layouts.master')


@section('content')
<div class="mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="border-bottom border-primary">
            <h2>{{ $page_title }}</h2>
        </div>
        @include('backend.school_admin.logs.teachers_logs.partials.action')
    </div>
   
    <div class="card mb-2">
        <div class="card-body">
            <div class="row">
                <form id="searchForm" action="javascript:void(0);" method="POST">
                    @csrf
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">
                            {{-- @if($isMunicipality)
                                <div class="col-lg-4 col-md-4">
                                    <label for="school_search">Search by School:</label>
                                    <input type="text" id="school_search" class="form-control" placeholder="Enter School Name">
                                </div>
                            @endif


                            <div class="col-lg-4 col-md-4">
                                <label for="class_search">Search by Class:</label>
                                <input type="text" id="class_search" class="form-control" placeholder="Enter Class Name">
                            </div>


                            <div class="col-lg-4 col-md-4">
                                <label for="teacher_search">Search by Teacher:</label>
                                <input type="text" id="teacher_search" class="form-control" placeholder="Enter Teacher Name">
                            </div>


                            <div class="form-group col-md-12 d-flex justify-content-end pt-2">
                                <button type="button" class="btn btn-primary" id="searchButton">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
   
    <div class="card">
        <div class="card-body">
            <div id="teacherLogWrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-12">
                        <div class="report-table-container">
                            <div class="table-responsive">
                                <table id="teacher_log_result-table" class="table table-bordered table-striped dataTable dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Subject Group</th>
                                            <th>Subject</th>
                                            <th>Lesson</th>
                                            <th>Topic</th>
                                            <th>Class Work</th>
                                            <th>Home Work</th>
                                            <th>Files</th>
                                            <th>Logged By (Teacher's Name)</th>
                                            <th>Logs of Dates</th>
                                            <th>Registered At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="createTeacherLogs" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Teacher's Log</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="">
                        <form method="post" id="examResultForm" action="{{ route('admin.teacher-logs.store') }}">
                            @csrf
                            @method('PUT')


                            <input type="hidden" name="_method" id="methodField" value="POST">
                            <input type="hidden" name="dynamic_id" id="dynamic_id">
                            <div class="col-md-12">


                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Class</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Class
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Section</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Section
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Subject Group</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Subject Group
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Subject</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Subject
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Topic</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Topic
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label for="incomehead_id">Lesson</label>
                                    <div class="select">


                                        <select id="class_is" name="class_id" data-iteration="0" class="incomehead_id"
                                            required="">
                                            <option disabled="" selected="" value="">Select Lesson
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="p-2 label-input">
                                    <label>Classroom Activities/ Classwork<span class="must">*</span></label>


                                    <div class="single-input-modal">
                                        <textarea name="notes" id="dynamic_notes" cols="30" rows="10" value="{{ old('notes') }}"
                                            class="single-input-text">
                                         
                                        </textarea>
                                        {{-- <input type="text"  name="notes"
                                            class="input-text single-input-text" id="dynamic_notes" autofocus required> --}}
                                    </div>
                                </div>
                                <div class="p-2 label-input">
                                    <label>HomeWork <span class="must">*</span></label>


                                    <div class="single-input-modal">
                                        <textarea name="notes" id="dynamic_notes" cols="30" rows="10" value="{{ old('notes') }}"
                                            class="single-input-text">


                                        </textarea>
                                        {{-- <input type="text"  name="notes"
                                            class="input-text single-input-text" id="dynamic_notes" autofocus required> --}}
                                    </div>
                                </div>




                                <div class="border-top col-md-12 d-flex justify-content-end p-2">
                                    <button type="submit" class="btn btn-sm btn-success mt-2">Submit</button>


                                </div>
                            </div>




                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#teacher_log_result-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.teacher-logs.get') }}',
                type: 'POST'
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },


                {
                    data: 'class',
                    name: 'class'
                },
                {
                    data: 'section',
                    name: 'section'
                },
                {
                    data: 'subjectgroup',
                    name: 'subjectgroup'


                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'lesson',
                    name: 'lesson'
                },
                {
                    data: 'topic',
                    name: 'topic'
                },
                {
                    data: 'classwork',
                    name: 'classwork'
                },
                {
                    data: 'homework',
                    name: 'homework'
                },
                {
                    data: 'file',
                    name: 'file '
                },
                {
                    data: 'teacher',
                    name: 'teacher '
                },
                {
                    data: 'log_book_date',
                    name: 'log_book_date'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ],
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                        .on('change', function() {
                            column.search($(this).val()).draw();
                        });
                });
            }
        });
        $(document).ready(function() {
            // Define a function for section selection through classID
            function fetchSections(classId, callback) {
                $.ajax({
                    url: '{{ url('admin/get-section-by-class') }}/' + classId,
                    type: 'GET',
                    success: function(data) {
                        // Clear existing options
                        $('select[name="section_id"]').empty();


                        // Add the default option
                        $('select[name="section_id"]').append(
                            '<option disabled selected>Select Section</option>');


                        // Add new options based on the fetched sections
                        $.each(data, function(key, value) {
                            $('select[name="section_id"]').append('<option value="' +
                                key + '">' + value + '</option>');
                        });
                        // Call the callback function after the checkboxes are added
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                });
            }


            // Define a function for section selection through classID
            function fetchSubjectGroup(classSectionData, callback) {
                // console.log(classSectionData);
                $.ajax({
                    url: '{{ url('admin/get-subject-group-by-class-and-section') }}',
                    type: 'GET',
                    data: classSectionData,
                    success: function(data) {
                        // Clear existing checkboxes
                        $('#dynamic_subject_group_id').empty();
                        $('select[name="subject_group_id"]').empty();
                        $('select[name="subject_id"]').empty();
                        $('select[name="lesson_id"]').empty();
                        $('select[name="topic_id"]').empty();


                        // Add checkboxes based on the fetched data
                        var selectContainer = $('#dynamic_subject_group_id');


                        if (data.length > 0) {
                            selectContainer.append(
                                '<option disabled selected value>Choose Subject Group</option>');
                            $.each(data, function(key, value) {
                                selectContainer.append('<option value="' + value.id + '">' +
                                    value
                                    .subject_group_name + '</option>');
                            });
                        } else {
                            selectContainer.append(
                                '<option value="">No Subject Group found</option>');
                        }
                        // Call the callback function after the checkboxes are added
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                });
            }


            // Define a function for subject selection through subjectGroupId
            function fetchSubjects(subjectGroupId, callback) {
                $.ajax({
                    url: '{{ url('admin/get-subjects-by-subject-group') }}/' + subjectGroupId,
                    type: 'GET',
                    success: function(data) {
                        // Clear existing options
                        $('select[name="subject_id"]').empty();
                        $('select[name="lesson_id"]').empty();
                        $('select[name="topic_id"]').empty();


                        // Add the default option
                        $('select[name="subject_id"]').append(
                            '<option disabled selected>Select Subject</option>');


                        // Add new options based on the fetched sections
                        $.each(data, function(index, subject) {
                            $('select[name="subject_id"]').append('<option value="' +
                                subject.id + '">' + subject.subject + '</option>');
                        });
                        // Call the callback function after the checkboxes are added
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                });
            }


            $('select[name="class_id"]').change(function() {
                // Get the selected class ID
                var classId = $(this).val();
                // Fetch sections based on the selected class ID
                fetchSections(classId, function() {
                    //onclicking the checkbox fetch subject-groups associated to class and section
                    $('select[name="section_id"]').change(function() {
                        var sectionsSelected = [];


                        // Iterate through checked checkboxes and collect section selected
                        sectionsSelected.push($(this).val());
                        // $('.section-checkbox:checked').each(function() {
                        // });
                        classSectionData = {
                            class_id: classId,
                            sections: sectionsSelected
                        }
                        fetchSubjectGroup(classSectionData, function() {
                            $('select[name="subject_group_id"]').change(function() {
                                var subjectGroupId = $(this).val();
                                fetchSubjects(subjectGroupId, function() {
                                    $('select[name="subject_id"]')
                                        .change(
                                            function() {
                                                var subjectId = $(
                                                    this).val();
                                                classSectionSubjectgroupSubjectData
                                                    = {
                                                        class_id: classId,
                                                        sections: sectionsSelected,
                                                        subject_group_id: subjectGroupId,
                                                        subject_id: subjectId
                                                    }
                                                fetchLessons(
                                                    classSectionSubjectgroupSubjectData,
                                                    function() {
                                                        $('select[name="lesson_id"]')
                                                            .change(
                                                                function() {
                                                                    var lessonId =
                                                                        $(
                                                                            this
                                                                        )
                                                                        .val();
                                                                    classSectionSubjectgroupSubjectLessonData
                                                                        = {
                                                                            class_id: classId,
                                                                            sections: sectionsSelected,
                                                                            subject_group_id: subjectGroupId,
                                                                            subject_id: subjectId,
                                                                            lesson_id: lessonId
                                                                        }
                                                                    fetchTopics
                                                                        (classSectionSubjectgroupSubjectLessonData,
                                                                            function() {


                                                                            }
                                                                        );


                                                                }
                                                            );
                                                    });


                                            });




                                });


                            });
                        });
                    });
                });
            });
        });
    </script>
@endsection
@endsection

