<div class="btn-group" role="group">
    <a href="{{ route('admin.edit', $subjectTeacher->id) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-danger btn-sm delete-subject-teacher" data-id="{{ $subjectTeacher->id }}">
        <i class="fas fa-trash"></i>
    </button>
</div>

<script>
    $('.delete-subject-teacher').click(function() {
        var id = $(this).data('id');
        if(confirm('Are you sure you want to delete this assigned teacher?')) {
            $.ajax({
                url: '/admin/subject/teachers/delete/' + id,
                type: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(result) {
                    toastr.success(result.message);
                    $('#subjectTeachersTable').DataTable().ajax.reload();
                }
            });
        }
    });
</script>