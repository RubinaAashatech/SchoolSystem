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
                        <div id="current_file_container" class="mt-2" style="display: none;">
                            <p>Current file: <span id="current_file_name"></span></p>
                            <div id="file_preview"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dynamic_release_date" class="form-label">Release Date<span class="must">*</span></label>
                        <input type="text" name="release_date" class="form-control" id="dynamic_release_date" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="send_to" class="form-label">Send To<span class="must">*</span></label>
                        @foreach($roles as $role)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="send_to[]" value="{{ $role->id }}" id="role_{{ $role->id }}">
                            <label class="form-check-label" for="role_{{ $role->id }}">
                                {{ $role->name }}
                            </label>
                        </div>
                        @endforeach
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
    
    <!-- View Notice Modal -->
    <div class="modal fade" id="viewNoticeModal" tabindex="-1" aria-labelledby="viewNoticeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewNoticeModalLabel">View Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="viewNoticeContent">
                    <!-- Content will be dynamically inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('backend.includes.nepalidate')
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
            { 
                data: 'release_date', 
                name: 'release_date',
                render: function(data, type, row) {
                    return data.split(' ')[0]; 
                }
            },
            { data: 'send_to', name: 'send_to' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });


        var releaseDateInput = document.getElementById("dynamic_release_date");
        if (releaseDateInput) {
            releaseDateInput.nepaliDatePicker({
                dateFormat: "YYYY-MM-DD",
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 200
            });

            var today = NepaliFunctions.GetCurrentBsDate();
            var formattedDate = NepaliFunctions.ConvertDateFormat(today, "YYYY-MM-DD");
            releaseDateInput.value = formattedDate;
        }
    });

    $(document).on('click', '.editNotice', function () {
        var id = $(this).data('id');
        $.get("{{ route('admin.notices.index') }}" + '/' + id + '/edit', function (data) {
            $('#noticeForm').attr('action', '{{ route('admin.notices.update', '') }}' + '/' + id);
            $('#methodField').val('PUT');
            $('#dynamic_id').val(id);
            $('#dynamic_title').val(data.title);
            $('#dynamic_description').val(data.description);

            var releaseDate = new Date(data.notice_released_date);
            var formattedDate = releaseDate.toISOString().split('T')[0]; 
            $('#dynamic_release_date').val(formattedDate);

            var sendTo = JSON.parse(data.notice_who_to_send);
            $('input[name="send_to[]"]').prop('checked', false); 
            sendTo.forEach(function (item) {
                $('#role_' + item).prop('checked', true);
            });

            if (data.pdf_image) {
                $('#current_file_container').show();
                $('#current_file_name').text(data.pdf_image.split('/').pop());
                
                var fileExtension = data.pdf_image.split('.').pop().toLowerCase();
                var filePath = "{{ asset('storage') }}/" + data.pdf_image;

                if (fileExtension === 'pdf') {
                    $('#file_preview').html('<iframe src="' + filePath + '" width="100%" height="200px"></iframe>');
                } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    $('#file_preview').html('<img src="' + filePath + '" alt="Notice Image" style="max-width: 100%; max-height: 200px;">');
                } else {
                    $('#file_preview').html('<p>File format not supported for preview.</p>');
                }
            } else {
                $('#current_file_container').hide();
                $('#file_preview').empty();
            }

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

    $(document).on('click', '.viewNotice', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    $.get("{{ url('admin/notices') }}/" + id, function(response) {
        var notice = response.notice;
        var content = `
            <h5>${notice.title}</h5>
            <p>${notice.description}</p>
        `;

        if (notice.pdf_image) {
            var fileExtension = notice.pdf_image.split('.').pop().toLowerCase();
            var filePath = "{{ asset('storage') }}/" + notice.pdf_image; 

            if (fileExtension === 'pdf') {
                content += `
                    <div class="pdf-container" style="width: 100%; height: 500px;">
                        <iframe src="${filePath}" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                `;
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) { 
                content += `
                    <div>
                        <img src="${filePath}" alt="Notice Image" class="img-fluid">
                    </div>
                `;
            } else {
                content += `<p>File format not supported for preview.</p>`;
            }
        }

        $('#viewNoticeContent').html(content);
        $('#viewNoticeModal').modal('show');
    });
});
var viewModal = new bootstrap.Modal(document.getElementById('viewNoticeModal'));
</script>
@endsection