@hasanyrole('School Admin|Teacher')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Lesson Plan')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard2" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><i class="fas fa-th-list"></i></span>
                <span class="nav-link-text ps-1">{{ __('Lesson Management')}}</span>
            </div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'lessons' || Request::segment(2) == 'topics' ? 'show' : '' }}"
            id="dashboard2">

            @can('list_lessons')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'lessons' ? 'active' : '' }}"
                        href="{{ route('admin.lessons.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i> {{__('Lesson')}}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_topics')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'topics' ? 'active' : '' }}"
                        href="{{ route('admin.topics.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i> {{__('Topic')}}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
