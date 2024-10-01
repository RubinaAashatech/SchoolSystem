@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    <li class="nav-item">
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Certificate Management')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard8" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-credit-card"></i></span><span class="nav-link-text ps-1">{{__('Certificates') }}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'student-certificates' || Request::segment(2) == '' ? 'show' : '' }}"
            id="dashboard8">

            @can('list_student_certificates')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'student-certificates' ? 'active' : '' }}"
                        href="{{ route('admin.student-certificates.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{__('Student Certificate')}}
                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
