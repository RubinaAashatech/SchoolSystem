@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
<li class="nav-item">
    {{--
    <hr class="my-4"> --}}
    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
        <div class="col-auto navbar-vertical-label">{{__('ECA Activities Management')}}</div>
        <div class="col ps-0">
            <hr class="mb-0 navbar-vertical-divider">
        </div>
    </div>
<li class="nav-item">
    <a class="nav-link dropdown-indicator" href="#dashboard12" role="button" data-bs-toggle="collapse"
        aria-expanded="true" aria-controls="dashboard">
        <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                    class="fas fa-credit-card"></i></span><span class="nav-link-text ps-1">{{ __("ECA Activities")}}
            </span></div>
    </a>
    <ul class="nav collapse  {{ Request::segment(2) == 'extracurricular-head' ? 'show' : '' }}" id="dashboard12">
        @can('list_extracurricular_head')
            <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'extracurricular-head' ? 'active' : '' }}"
                    href="{{ route('admin.extracurricular-head.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                        {{ __('Extracurricular Head')}}

                    </div>
                </a>
            </li>
        @endcan

        @can('list_eca_activities')
            <li class="nav-item"><a
                    class="nav-link {{ Request::segment(2) == 'extracurricular-activities' ? 'active' : '' }}"
                    href="{{ route('admin.eca_activities.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                        {{ __('Extracurricular Activity')}}

                    </div>
                </a>
            </li>
        @endcan 
        @can('list_eca_activities_participate')
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'extraactivities_participate' ? 'active' : '' }}"
                    href="{{ route('admin.eca_participations.index') }}">
                    <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                        {{ __('Eca Partcipation List') }}</div>
                </a>
            </li>
        @endcan
    </ul>
</li>

@endhasanyrole