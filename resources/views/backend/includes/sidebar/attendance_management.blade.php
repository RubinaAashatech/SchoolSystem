@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{ __('Attendance Management') }}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard4" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-calendar-check"></i></span><span class="nav-link-text ps-1">{{ __('Attendance') }}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'attendance-types' || Request::segment(2) == 'student-attendances' || Request::segment(2) == 'staff-attendance' || Request::segment(2) == 'leave-types' || Request::segment(2) == 'student-leaverequests' || Request::segment(2) == 'staff-leaverequests' ? 'show' : '' }}"
            id="dashboard4">
            @can('list_attendance_types')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'attendance-types' ? 'active' : '' }}"
                        href="{{ route('admin.attendance-types.index') }}">
                        <div class="d-flex align-items-center"><i
                                class="fa fa-angle-double-right"></i>{{ __('Attendance Type') }}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_student_attendances')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'student-attendances' ? 'active' : '' }}"
                        href="{{ route('admin.student-attendances.index') }}">
                        <div class="d-flex align-items-center"><i
                                class="fa fa-angle-double-right"></i>{{ __('Student Attendance') }}

                        </div>
                    </a>
                </li>
            @endcan
            @can('create_staff_attendance')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'staff-attendance' ? 'active' : '' }}"
                        href="{{ route('admin.staff-attendance.index') }}">
                        <div class="d-flex align-items-center"><i
                                class="fa fa-angle-double-right"></i>{{ __('Staff Attendance') }}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_leave_types')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'leave-types' ? 'active' : '' }}"
                        href="{{ route('admin.leave-types.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Leave Type') }}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_student_leaverequests')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'student-leaverequests' ? 'active' : '' }}"
                        href="{{ route('admin.student-leaverequests.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Student Leave') }}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_staff_leaverequests')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'staff-leaverequests' ? 'active' : '' }}"
                        href="{{ route('admin.staff-leaverequests.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Staff Leave') }}

                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
