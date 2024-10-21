@hasanyrole('Municipality Admin|School Admin|Teacher')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Reports Management')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard13" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-credit-card"></i></span><span class="nav-link-text ps-1">{{ __(" Report")}}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'attendence-report'? 'show' : '' }}"
            id="dashboard13">
            @can('list_attendence_report')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'extracurricular-head' ? 'active' : '' }}"
                        href="{{ route('admin.attendance_reports.report') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Attendence report')}}
                        </div>
                    </a>
                </li>
            @endcan

            @can('list_inventory_report')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'extracurricular-head' ? 'active' : '' }}"
                        href="{{ route('admin.municipalityAdmin.inventoryReport.report') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Inventory report')}}
                        </div>
                    </a>
                </li>
            @endcan
           
            @can('list_inventory_report')
            <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'extracurricular-head' ? 'active' : '' }}"
                    href="{{ route('admin.staff_reports.report') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Staff Report')}}
                    </div>
                </a>
            </li>
        @endcan


        @can('view_municipality_headteacher_logs')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'municipality-headteacher-logs' ? 'active' : '' }}"
                        href="{{ route('admin.municipality-headteacher-logs.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{__('Municipality Head Teacher Logs')}}
                        </div>
                    </a>
                </li>
        @endcan

        @can('school_attendance_report')
            <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'notice-head' ? 'active' : '' }}"
                    href="{{ route('admin.school_attendance_reports.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Attendance Report')}}
                    </div>
                </a>
            </li>
         @endcan

        @can('list_headteacherlog_reports')
            <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'headteacherlog-reports' ? 'active' : '' }}"
                    href="{{ route('admin.headteacherlog-reports.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{__('Head Teacher Log Report')}}
                    </div>
                </a>
            </li>
        @endcan
      
        @can('view_student_profile')
            <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'notice-head' ? 'active' : '' }}"
                    href="{{ route('admin.student-profile.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Student Profile')}}
                    </div>
                </a>
            </li>
        @endcan
        </ul>
    </li>
    </li>

 @endhasanyrole