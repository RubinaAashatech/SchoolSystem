@hasanyrole('School Admin')
    <li class="nav-item">
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Student Profile Management')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard29" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-user"></i></span><span class="nav-link-text ps-1">{{ __("Student Profile")}}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'student-profile'? 'show' : '' }}"
            id="dashboard29">
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