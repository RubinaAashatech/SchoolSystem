@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    <li class="nav-item">
        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Log Books Managements')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>

    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard9" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fas fa-history"></i></span><span
                    class="nav-link-text ps-1">{{__('Log Books') }}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'teacher-logs' || Request::segment(2) == 'headteacher-logs' ? 'show' : '' }}"
            id="dashboard9">
            @can('list_teacher_logs')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'teacher-logs' ? 'active' : '' }}"
                        href="{{ route('admin.teacher-logs.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Teachers Log Book')}}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_headteacher_logs')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'headteacher-logs' ? 'active' : '' }}"
                        href="{{ route('admin.headteacher-logs.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{__('Head Teachers Log Book')}}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
