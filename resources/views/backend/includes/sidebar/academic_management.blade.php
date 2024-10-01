@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Academic Management')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard1" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center">
                <span class="nav-link-icon"><i class="fas fa-university"></i></span>
                <span class="nav-link-text ps-1">{{ __('Academics') }}</span>
            </div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'academic-sessions' || Request::segment(2) == 'classes' || Request::segment(2) == 'assign-classteachers' || Request::segment(2) == 'subjects' || Request::segment(2) == 'sections' || Request::segment(2) == 'students-session' || Request::segment(2) == 'classes-time-table' || Request::segment(2) == 'lessons' || Request::segment(2) == 'topics' || Request::segment(2) == 'subject-groups' ? 'show' : '' }}"
            id="dashboard1">
            @can('list_academic_sessions')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'academic-sessions' ? 'active' : '' }}"
                        href="{{ route('admin.academic-sessions.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i> {{__('Academic Session')}}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_sections')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'sections' ? 'active' : '' }}"
                        href="{{ route('admin.sections.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i> {{__('Section') }}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_classes')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'classes' ? 'active' : '' }}"
                        href="{{ route('admin.classes.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Class/Grade') }}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_subjects')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'subjects' ? 'active' : '' }}"
                        href="{{ route('admin.subjects.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Subject') }}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_subject_groups')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'subject-groups' ? 'active' : '' }}"
                        href="{{ route('admin.subject-groups.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{__('Subject Group') }}
                        </div>
                    </a>
                </li>
            @endcan

            @can('view_assign_classteachers')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'assign-classteachers' ? 'active' : '' }}"
                        href="{{ route('admin.assign-classteachers.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{__('Assign Class Teacher') }}
                        </div>
                    </a>
                </li>
            @endcan
            {{-- @can('view_assign_classteachers')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'assign-classteachers' ? 'active' : '' }}"
                        href="{{ route('admin.assign-classteachers.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i> Assign Class Teacher
                        </div>
                    </a>
                </li>
            @endcan --}}
            {{-- @can('view_sections') --}}
            {{-- No permission Available --}}
            {{-- <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'students-session' ? 'active' : '' }}"
                    href="{{ route('admin.students-session.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-angle-double-right"></i> Student Session
                    </div>
                </a>
            </li> --}}
            {{-- No permission Available --}}
            {{-- @endcan --}}

            @can('list_class_timetables')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'classes-time-table' ? 'active' : '' }}"
                        href="{{ route('admin.class-timetables.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Class Time Table') }}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
