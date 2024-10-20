@can('edit_subject_groups')
    <a href="#" class="btn btn-outline-primary btn-sm mx-1 edit-subject" data-id="{{ $subjectGroups['id'] }}"
        data-subject_group_name="{{ $subjectGroups['subject_group_name'] }}"
        data-class_id="{{ isset($subjectGroups['classes'][0]['id']) ? $subjectGroups['classes'][0]['id'] : null }}"
        data-sections="{{ $subjectGroups['sections'] }}" data-is_active="{{ $subjectGroups['is_active'] }}"
        data-toggle="tooltip" data-placement="top" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
@endcan

@can('delete_subject_groups')
    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
        data-bs-target="#delete{{ $subjectGroups['id'] }}" data-toggle="tooltip" data-placement="top" title="Delete">
        <i class="far fa-trash-alt"></i>
    </button>
    <div class="modal fade" id="delete{{ $subjectGroups['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.subject-groups.destroy', $subjectGroups['id']) }}"
                    accept-charset="UTF-8" method="POST">
                    <div class="modal-body">
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <p>Are you sure to delete <span id="underscore"> {{ $subjectGroups['subject_group_name'] }} </span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcan

@can('assign_subject_teachers')
    <a href="{{ route('admin.subject-teachers.assign', [
            'subjectGroupId' => $subjectGroups['id'], 
            'classId' => $subjectGroups['classes'][0]['id'] ?? null,
            'sectionId' => $subjectGroups['sections'][0]['id'] ?? null
        ]) }}" 
       class="btn btn-outline-success btn-sm mx-1" 
       data-toggle="tooltip" 
       data-placement="top" 
       title="Assign Teachers">
        <i class="fas fa-user-plus"></i> Assign Teachers
    </a>
@endcan



