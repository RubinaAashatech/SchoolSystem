@can('edit_schools')
<a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-outline-primary btn-sm mx-1 edit-student"
    data-toggle="tooltip"
   data-placement="top" title="Edit">
   <i class="fa fa-edit"></i>
</a>
@endcan

@can('delete_schools')
<button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
        data-bs-target="#delete{{ $school->id }}" data-toggle="tooltip" data-placement="top" title="Delete">
    <i class="far fa-trash-alt"></i>
</button>

<div class="modal fade" id="delete{{ $school->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.schools.destroy', $school->id) }}">
                @method('DELETE')
                @csrf
                <div class="modal-body">
                    <p>Are you sure to delete <span id="underscore" class="must">{{ $school->name }}</span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<button type="button" class="btn btn-warning btn-sm reset-password-btn" data-id="{{ $school->id }}">Reset</button>
@endcan
