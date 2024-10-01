@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
<li class="nav-item">
    <!-- label-->
    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
        <div class="col-auto navbar-vertical-label">{{__('User Managements')}}</div>
        <div class="col ps-0">
            <hr class="mb-0 navbar-vertical-divider">
        </div>
    </div>

<li class="nav-item">
    <a class="nav-link dropdown-indicator" href="#dashboard11" role="button" data-bs-toggle="collapse"
        aria-expanded="true" aria-controls="dashboard">
        <div class="d-flex align-items-center"><span class="nav-link-icon"><i class="fas fa-users"></i></span><span
                class="nav-link-text ps-1">{{__('Users') }}
            </span></div>
    </a>
    <ul class="nav collapse  {{ Request::segment(2) == 'district-users' || Request::segment(2) == 'municipality-users' || Request::segment(2) == 'designations' || Request::segment(2) == 'departments' || Request::segment(2) == 'inclusive-quotas' || Request::segment(2) == 'school-adminusers' ? 'show' : '' }}"
        id="dashboard11">
        @can('list_district_users')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'district-users' ? 'active' : '' }}"
                href="{{ route('admin.district-users.index') }}">
                <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{__('District
                    Users')}}

                </div>
            </a>
        </li>
        @endcan
        @can('list_municipality_users')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'municipality-users' ? 'active' : '' }}"
                href="{{ route('admin.municipality-users.index') }}">
                <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{__('Municipality Users')}}

                </div>
            </a>
        </li>
        @endcan

        @can('list_school_adminusers')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'school-adminusers' ? 'active' : '' }}"
                href="{{ route('admin.school-adminusers.index') }}">
                <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('School Admin Users') }}

                </div>
            </a>
        </li>
        @endcan

        @can('list_schools')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'schools' ? 'active' : '' }}"
                href="{{ route('admin.schools.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-angle-double-right"></i>{{__('Schools')}}
                </div>
            </a>
        </li>
        @endcan
        @can('list_school_groups')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'school-groups' ? 'active' : '' }}"
                href="{{ route('admin.school-groups.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-angle-double-right"></i>{{__('School Groups') }}
                </div>
            </a>
        </li>
        @endcan

        @can('list_departments')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'departments' ? 'active' : '' }}"
                href="{{ route('admin.departments.index') }}">
                <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                 {{__('Departments')}}

                </div>
            </a>
        </li>
        @endcan

        @can('list_designations')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'designations' ? 'active' : '' }}"
                href="{{ route('admin.designations.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-angle-double-right"></i> {{__('Designations')}}
                </div>
            </a>
        </li>
        @endcan
        @can('list_inclusive_quotas')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'inclusive-quotas' ? 'active' : '' }}"
                href="{{ route('admin.inclusive-quotas.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-angle-double-right"></i>{{__('Inclusive Quotas')}}
                </div>
            </a>
        </li>
        @endcan

        @can('list_head_schoolusers')
        <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'staff' ? 'active' : '' }}"
                href="{{ route('admin.head-schoolusers.index') }}">
                <div class="d-flex align-items-center">
                    <i class="fa fa-angle-double-right"></i> {{__('Head School Users')}}
                </div>
            </a>
        </li>
        @endcan
    </ul>
</li>
</li>
@endhasanyrole
