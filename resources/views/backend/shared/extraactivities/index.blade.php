@extends('backend.layouts.master')

@section('content')
<div class="mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="border-bottom border-primary">
            <h2>{{ $page_title }}</h2>
        </div>
        @include('backend.shared.extraactivities.partials.action')
    </div>
    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-12">
                        <div class="report-table-container">
                            <div class="table-responsive">
                                <table id="eca-activities-table"
                                    class="table table-bordered table-striped dataTable dtr-inline"
                                    aria-describedby="example1_info">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Player Type</th>
                                            <th>Status</th>
                                            <th>ECA Head</th>
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

    <!-- Participate Modal -->
    <div class="modal fade" id="participateModal" tabindex="-1" role="dialog" aria-labelledby="participateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.extraactivities_participate.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="participateModalLabel">Participate in Activity</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="eca_activity_id" id="eca_activity_id">
                        <input type="hidden" name="school_id" id="school_id" value="{{ auth()->user()->school_id }}">    

                        <div class="form-group">
                            <label for="activity_id">Activity ID</label>
                            <input type="text" id="activity_id" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="activity_title">Title</label>
                            <input type="text" id="activity_title" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="activity_description">Description</label>
                            <textarea id="activity_description" class="form-control" readonly></textarea>
                        </div>

                        <div class="form-group">
                            <label for="activity_player_type">Player Type</label>
                            <input type="text" id="activity_player_type" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="activity_status">Status</label>
                            <input type="text" id="activity_status" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="eca_head_name">ECA Head</label>
                            <input type="text" id="eca_head_name" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select name="class_id" id="class_id" class="form-control" required>
                                <option value="">Select Class</option>
                            </select>
                        </div>
    
                        <div class="form-group">
                            <label for="section_id">Section</label>
                            <select name="section_id" id="section_id" class="form-control" required>
                                <option value="">Select Section</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="student_ids">Participant Name(s)</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select_all_students">
                                <label class="form-check-label" for="select_all_students">Select All Students</label>
                            </div>
                            <select name="participant_name[]" id="student_ids" class="form-control" multiple required>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        
                        <div id="additional_participants"></div>
                        
                        <button type="button" id="add_more" class="btn btn-secondary">Add More</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createEcaActivity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add ECA Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="">
                <form method="post" id="ecaActivityForm" action="{{ route('admin.eca_activities.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="methodField" value="POST">
                    <input type="hidden" name="dynamic_id" id="dynamic_id">
                    <div class="col-md-12">
                        <div class="p-2 input-label">
                            <label>ECA Head<span class="must">*</span></label>
                            <div class="single-input-modal">
                                <select name="eca_head_id" class="input-text single-input-text" id="dynamic_eca_head_id"
                                    required>
                                    @foreach($ecaHeads as $head)
                                        <option value="{{ $head->id }}">{{ $head->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-2 input-label">
                            <label>Title<span class="must">*</span></label>
                            <div class="single-input-modal">
                                <input type="text" value="{{ old('title') }}" name="title"
                                    class="input-text single-input-text" id="dynamic_title" autofocus required>
                            </div>
                        </div>
                        <div class="p-2 label-input">
                            <label>Description<span class="must">*</span></label>
                            <div class="single-input-modal">
                                <textarea name="description" class="input-text single-input-text"
                                    id="dynamic_description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="p-2 input-label">
                            <label>PDF/Image</label>
                            <div class="single-input-modal">
                                <input type="file" name="pdf_image" class="input-text single-input-text"
                                    id="dynamic_pdf_image">
                            </div>
                        </div>
                        <div class="p-2 input-label">
                            <label>Player Type<span class="must">*</span></label>
                            <div class="single-input-modal">
                                <select name="player_type" class="input-text single-input-text" id="dynamic_player_type" required>
                                    <option value="single">Single Player</option>
                                    <option value="multi">Multi Player</option>
                                    <option value="competitive">Competitive</option>
                                </select>
                            </div>
                        </div>

                        @if($user_type === 'municipality')
                        <div class="p-2 input-label">
                            <label>Select Schools<span class="must">*</span></label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select_all_schools">
                                <label class="form-check-label" for="select_all_schools">Select All Schools</label>
                            </div>
                            <div class="single-input-modal">
                                <select name="school_ids[]" class="input-text single-input-text" id="dynamic_school_ids" multiple required>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @else
                        <div class="p-2 input-label">
                            <label>Select Classes<span class="must">*</span></label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="select_all_classes">
                                <label class="form-check-label" for="select_all_classes">Select All Classes</label>
                            </div>
                            <div class="single-input-modal">
                                <select name="class_ids[]" class="input-text single-input-text" id="dynamic_class_ids" multiple required>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="p-2 input-label">
                            <label>Status<span class="must">*</span></label>
                            <div class="col-sm-10">
                                <div class="btn-group">
                                    <input type="radio" class="btn-check" name="is_active" id="option1" value="1"
                                        autocomplete="off" checked />
                                    <label class="btn btn-secondary" for="option1">Active</label>

                                    <input type="radio" class="btn-check" name="is_active" id="option2" value="0"
                                        autocomplete="off" />
                                    <label class="btn btn-secondary" for="option2">Inactive</label>
                                </div>
                            </div>
                        </div>
                        <div class="border-top col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-success mt-2">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Store ECA Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resultForm" method="POST" action="{{ route('admin.eca.results.store') }}">
                    @csrf
                        <input type="hidden" name="eca_participation_id" id="ecaParticipationId" class="form-control" readonly>
                    <div class="form-group">
                        <label for="activityTitle">ECA Activity</label>
                        <input type="text" id="activityTitle" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="playerType">Player Type</label>
                        <input type="text" id="playerType" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="school">School</label>
                        <select id="school" class="form-control" required>
                            <!-- School dropdown options will be populated via JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-group" id="participantGroup" style="display:none;">
                        <label for="participantName">Participant Names</label>
                        <textarea id="participantName" name="participant_name" class="form-control" readonly></textarea>
                    </div>

                    <div class="form-group">
                        <label for="resultType">Result Type</label>
                        <select name="result_type" id="resultType" class="form-control" required>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                            <option value="third">Third</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="is_publish">Publish Result</label>
                        <input type="checkbox" name="is_publish" id="is_publish" value="1">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.result-eca-activity', function() {
    var participationId = $(this).data('participation-id');
    var activityTitle = $(this).data('activity-title');
    var playerType = $(this).data('player-type');
    var schools = $(this).data('schools');
    var participants = $(this).data('participants');

    $('#ecaParticipationId').val(participationId);
    $('#activityTitle').val(activityTitle);
    $('#playerType').val(playerType);

    var schoolSelect = $('#school');
    schoolSelect.empty(); 
    $.each(schools, function(schoolId, schoolName) {
        schoolSelect.append('<option value="' + schoolId + '">' + schoolName + '</option>');
    });

    if (playerType === 'competitive') {
        $('#participantGroup').show();
        var participantNames = participants[Object.keys(participants)[0]]; 
        $('#participantName').val(participantNames.join(', '));
    } else {
        $('#participantGroup').hide();
    }

    $('#resultModal').modal('show');
});

</script>
       
    
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var userType = '{{ $user_type }}';

    if (userType === 'municipality') {
        $('#select_all_schools').change(function() {
            $('#dynamic_school_ids option').prop('selected', $(this).is(':checked'));
        });
    } else {
        $('#select_all_classes').change(function() {
            $('#dynamic_class_ids option').prop('selected', $(this).is(':checked'));
        });
    }


    // Form submission
    $('#ecaActivityForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        if (userType === 'school_admin') {
            formData.delete('school_ids[]');
            formData.append('school_id', '{{ auth()->user()->school_id }}');
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createEcaActivity').modal('hide');
                $('#eca-activities-table').DataTable().ajax.reload();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    
    // DataTable initialization
    $('#eca-activities-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.eca_activities.get") }}',
            type: 'POST',
            error: function (xhr, error, thrown) {
                console.log('DataTables error: ', error);
                console.log('Exception: ', thrown);
                console.log('Response: ', xhr.responseText);
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            { data: 'player_type', name: 'player_type' },
            { data: 'is_active', name: 'is_active' },
            { data: 'eca_head.name', name: 'eca_head.name' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '.edit-eca-activity', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var description = $(this).data('description');
        var player_type = $(this).data('player_type');
        var is_active = $(this).data('is_active');
        var eca_head_id = $(this).data('eca_head_id');

        $('#dynamic_id').val(id);
        $('#dynamic_title').val(title);
        $('#dynamic_description').val(description);
        $('#dynamic_player_type').val(player_type);
        $('#dynamic_eca_head_id').val(eca_head_id);

        $('input[name="is_active"]').prop('checked', false);
        $('input[name="is_active"][value="' + is_active + '"]').prop('checked', true);

        $('#ecaActivityForm').attr('action', '{{ route('admin.eca_activities.update', '') }}' + '/' + id);
        $('#methodField').val('PUT');

        // Fetch and populate schools or classes
        if (userType === 'municipality') {
            fetchSchools(id);
        } else if (userType === 'school_admin') {
            fetchClasses(id);
        }

        $('#createEcaActivity').modal('show');

        return false;
    });




    function fetchSchools(activityId) {
        $.ajax({
            url: '/admin/eca_activities/get-schools/' + activityId,
            type: 'GET',
            success: function(response) {
                var schoolSelect = $('#dynamic_school_ids');
                schoolSelect.empty();
                $.each(response.schools, function(index, school) {
                    var option = $('<option></option>').val(school.id).text(school.school_name);
                    if (school.selected) {
                        option.prop('selected', true);
                    }
                    schoolSelect.append(option);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching schools:", error);
            }
        });
    }

    $(document).on('click', '.participate-eca-activity', function () {
        var ecaActivityId = $(this).data('id');
        $('#eca_activity_id').val(ecaActivityId);

        var rowData = $(this).closest('tr').children('td');
        $('#activity_id').val(rowData.eq(0).text());
        $('#activity_title').val(rowData.eq(1).text());
        $('#activity_description').val(rowData.eq(2).text());
        $('#activity_player_type').val(rowData.eq(3).text());
        $('#activity_status').val(rowData.eq(4).text());
        $('#eca_head_name').val(rowData.eq(5).text());

        var schoolId = $('#school_id').val();
        console.log('School ID when opening modal:', schoolId);
        fetchClasses(schoolId);

        $('#participateModal').modal('show');
    });

    function fetchClasses(schoolId) {
        console.log('Fetching classes for school ID:', schoolId);
        $.ajax({
            url: '{{ route("admin.get_classes") }}',
            type: 'GET',
            data: { school_id: schoolId },
            success: function(response) {
                console.log('Classes received:', response);
                var classSelect = $('#class_id');
                classSelect.empty();
                classSelect.append('<option value="">Select Class</option>');
                $.each(response, function(index, classObj) {
                    classSelect.append('<option value="' + classObj.id + '">' + classObj.class + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching classes:", error);
                console.error("Response:", xhr.responseText);
            }
        });
    }

    $('#class_id').change(function() {
        var classId = $(this).val();
        if (classId) {
            fetchSections(classId);
        } else {
            $('#section_id').empty().append('<option value="">Select Section</option>');
        }
    });

    function fetchSections(classId) {
    var schoolId = $('#school_id').val();
    $.ajax({
        url: '{{ route("admin.get_sections") }}',
        type: 'GET',
        data: { 
            class_id: classId,
            school_id: schoolId
        },
        success: function(response) {
            console.log('Sections received:', response);
            var sectionSelect = $('#section_id');
            sectionSelect.empty();
            sectionSelect.append('<option value="">Select Section</option>');
            $.each(response, function(index, sectionObj) {
                sectionSelect.append('<option value="' + sectionObj.id + '">' + sectionObj.section_name + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error("Error fetching sections:", error);
            console.error("Response:", xhr.responseText);
        }
    });
}

$('#section_id').change(function() {
        var sectionId = $(this).val();
        var classId = $('#class_id').val();
        var schoolId = $('#school_id').val();
        if (sectionId) {
            fetchStudents(schoolId, classId, sectionId);
        } else {
            $('#student_ids').empty().append('<option value="">Select Student</option>');
        }
    });

    function fetchStudents(schoolId, classId, sectionId) {
        $.ajax({
            url: '{{ route("admin.get_students") }}',
            type: 'GET',
            data: { 
                school_id: schoolId,
                class_id: classId,
                section_id: sectionId
            },
            success: function(response) {
                console.log('Students received:', response);
                var studentSelect = $('#student_ids');
                studentSelect.empty();
                $.each(response, function(index, student) {
                    var fullName = student.f_name + ' ' + (student.m_name ? student.m_name + ' ' : '') + student.l_name;
                    studentSelect.append('<option value="' + student.id + '">' + fullName + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching students:", error);
                console.error("Response:", xhr.responseText);
            }
        });
    }

    $('#select_all_students').change(function() {
        $('#student_ids option').prop('selected', $(this).is(':checked'));
    });


    $(document).on('click', '.participate-eca-activity', function () {
        var ecaActivityId = $(this).data('id');
        $('#eca_activity_id').val(ecaActivityId);

        // Fetching other activity details via DataTables data attributes
        var rowData = $(this).closest('tr').children('td');
        $('#activity_id').val(rowData.eq(0).text());
        $('#activity_title').val(rowData.eq(1).text());
        $('#activity_description').val(rowData.eq(2).text());
        $('#activity_player_type').val(rowData.eq(3).text());
        $('#activity_status').val(rowData.eq(4).text());
        $('#eca_head_name').val(rowData.eq(5).text());

        // Optionally load class and section data dynamically

        $('#participateModal').modal('show');
    });

    $('#add_more').click(function () {
        var newField = `
                <div class="form-group">
                    <label for="participant_name">Participant Name</label>
                    <input type="text" name="participant_name[]" class="form-control participant_name" required>
                </div>
            `;
        $('#participant_names_wrapper').append(newField);
    });

});
</script>
@endsection