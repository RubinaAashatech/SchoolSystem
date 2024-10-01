@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    {{-- <hr class="my-4">     --}}
    <li class="nav-item">

        <!-- label-->
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{ __('School Management') }}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard3" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fa-solid fa-school"></i></span><span class="nav-link-text ps-1">{{ __('Schools') }}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'school-houses' || Request::segment(2) == 'students' || Request::segment(2) == 'staffs' ? 'show' : '' }}"
            id="dashboard3">

            @can('list_school_houses')
                <li class="nav-item">
                    <a class="nav-link {{ Request::segment(2) == 'school-houses' ? 'active' : '' }}"
                        href="{{ route('admin.school-houses.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('School Houses') }}
                        </div>
                    </a>
                </li>
            @endcan

            @can('list_schools')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'schools' ? 'active' : '' }}"
                        href="{{ route('admin.schools.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Schools') }}
                        </div>
                    </a>
                </li>
            @endcan

            @can('list_students')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'students' ? 'active' : '' }}"
                        href="{{ route('admin.students.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __(' Student') }}
                        </div>
                    </a>
                </li>
            @endcan

            @can('list_staffs')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'staffs' ? 'active' : '' }}"
                        href="{{ route('admin.staffs.index') }}">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-angle-double-right"></i>{{ __('Staff') }}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>

    </li>
@endhasanyrole
