@hasanyrole('School Admin|Teacher')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{ __('Primary Class Settings') }}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard6" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><i class="fas fa-th-list"></i></span>
                <span class="nav-link-text ps-1">{{ __('Examination (1-3)') }}</span>
            </div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'primary_marks' ? 'show' : '' }}" id="dashboard6">

            @can('list_primary_lessonmarks')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'primary_lessonmarks' ? 'active' : '' }}"
                        href="{{ route('admin.primary-lessonmarks.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Thematic Learning Count') }}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
