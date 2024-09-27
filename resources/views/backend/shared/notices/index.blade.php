@extends('backend.layouts.master')

@section('content')
<div class="mt-4">
    @include('backend.shared.notices.partials.action')

    <div class="card">
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-12">
                        <div class="report-table-container">
                            <div class="table-responsive">
                                <table id="notices-table" class="table table-bordered table-striped dataTable dtr-inline" aria-describedby="example1_info">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Release Date</th>
                                            <th>Sent To</th>
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

    <!-- Create/Edit Notice Modal -->
    <div class="modal fade" id="createNotice" tabindex="-1" aria-labelledby="createNoticeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createNoticeLabel">Add/Edit Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="noticeForm" action="{{ route('admin.notices.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="methodField" value="POST">
                        <input type="hidden" name="dynamic_id" id="dynamic_id">
                        <div class="mb-3">
                            <label for="dynamic_title" class="form-label">Title<span class="must">*</span></label>
                            <input type="text" value="{{ old('title') }}" name="title" class="form-control" id="dynamic_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="dynamic_description" class="form-label">Description<span class="must">*</span></label>
                            <textarea name="description" class="form-control" id="dynamic_description" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="dynamic_pdf_image" class="form-label">PDF/Image</label>
                            <input type="file" name="pdf_image" class="form-control" id="dynamic_pdf_image">
                        </div>
                        <div class="mb-3">
                            <label for="dynamic_release_date" class="form-label">Release Date<span class="must">*</span></label>
                            <input type="date" value="{{ old('release_date', date('Y-m-d')) }}" name="release_date" class="form-control" id="dynamic_release_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="send_to" class="form-label">Send To<span class="must">*</span></label>
                            @if($user_type == 'municipality')
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_to[]" value="school" id="school">
                                    <label class="form-check-label" for="school">Schools</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="send_to[]" value="school_group_head" id="school_group_head">
                                    <label class="form-check-label" for="school_group_head">School Group Head</label>
                                </div>
                            @endif
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_to[]" value="teacher" id="teacher">
                                <label class="form-check-label" for="teacher">Teachers</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_to[]" value="parent" id="parent">
                                <label class="form-check-label" for="parent">Parents</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="send_to[]" value="student" id="student">
                                <label class="form-check-label" for="student">Students</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#notices-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('admin.notices.get') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' },
                { data: 'description', name: 'description' },
                { data: 'release_date', name: 'release_date' },
                { data: 'send_to', name: 'send_to' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });

    $(document).on('click', '.editNotice', function () {
        var id = $(this).data('id');
        $.get("{{ route('admin.notices.index') }}" + '/' + id + '/edit', function (data) {
            $('#noticeForm').attr('action', '{{ route('admin.notices.update', '') }}' + '/' + id);
            $('#methodField').val('PUT');
            $('#dynamic_id').val(id);
            $('#dynamic_title').val(data.title);
            $('#dynamic_description').val(data.description);
            $('#dynamic_release_date').val(data.notice_released_date);
            var sendTo = JSON.parse(data.notice_who_to_send);
            $('input[name="send_to[]"]').prop('checked', false);
            sendTo.forEach(function (item) {
                $('#' + item).prop('checked', true);
            });
            $('#createNotice').modal('show');
        });
    });

    $('#noticeForm').submit(function () {
        $('#createNotice').modal('hide');
    });

    $(document).on('click', '.deleteNotice', function () {
    var id = $(this).data('id');
    
    if (confirm('Are you sure you want to delete this notice?')) {
        $.ajax({
            url: '{{ route('admin.notices.destroy', '') }}' + '/' + id,
            type: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function (response) {
                if (response.success) {
                    $('#notices-table').DataTable().ajax.reload();
                }
            },
            error: function (xhr) {
                alert('Error deleting notice');
            }
        });
    }
});


    // Add a click event for the "Add Notice" button
    $(document).on('click', '#addNoticeBtn', function() {
        // Reset the form
        $('#noticeForm')[0].reset();
        $('#noticeForm').attr('action', '{{ route('admin.notices.store') }}');
        $('#methodField').val('POST');
        $('#dynamic_id').val('');
        $('#createNoticeLabel').text('Add Notice');
        $('#createNotice').modal('show');
    });
</script>
@endsection