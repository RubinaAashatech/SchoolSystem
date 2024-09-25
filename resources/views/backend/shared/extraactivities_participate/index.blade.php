@extends('backend.layouts.master')

@section('content')
<div class="mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="border-bottom border-primary">
            <h2>Eca participation list</h2>
        </div>

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
                                            <th>ID</th>
                                            <th>Activity Title</th>
                                            <th>Participant Name</th>
                                            <th>School</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated by DataTables -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#eca-activities-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.eca_participations.get') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'eca_activity.title', name: 'ecaActivity.title' },
                { data: 'participant_name', name: 'participant_name' },
                { data: 'school.name', name: 'school.name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    // Handle the participation modal display
    $('#participateModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var activityId = button.data('id');
        var activityTitle = button.data('title');
        var playerType = button.data('player-type');

        var modal = $(this);
        modal.find('.modal-title').text('Participate in ' + activityTitle);
        modal.find('#eca_activity_id').val(activityId);

        if (playerType === 'multi') {
            $('#add_more').removeClass('d-none');
        } else {
            $('#add_more').addClass('d-none');
        }

        // Fetch classes based on school ID
        // var schoolId = "{{ auth()->user()->school_id }}";
        // $.get("{{ url('classes') }}/" + schoolId, function (data) {
        //     var classSelect = $('#class_id');
        //     classSelect.empty();
        //     $.each(data, function (key, value) {
        //         classSelect.append('<option value="' + value.class_id + '">' + value.class.name + '</option>');
        //     });
        // });
    });

    // Fetch sections based on selected class
    // $('#class_id').change(function () {
    //     var classId = $(this).val();
    //     $.get("{{ url('sections') }}/" + classId, function (data) {
    //         var sectionSelect = $('#section_id');
    //         sectionSelect.empty();
    //         $.each(data, function (key, value) {
    //             sectionSelect.append('<option value="' + value.section_id + '">' + value.section.name + '</option>');
    //         });
    //     });
    // });

    // Add more participant names if player_type is "multi"
    $('#add_more').click(function () {
        $('#participant_names_wrapper').append(`
                <div class="form-group">
                    <label for="participant_name">Participant Name</label>
                    <input type="text" name="participant_name[]" class="form-control participant_name" required>
                </div>
            `);
    });
    
</script>
@endsection