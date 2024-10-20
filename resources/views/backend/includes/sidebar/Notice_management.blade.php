@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
<li class="nav-item">
    <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
        <div class="col-auto navbar-vertical-label">{{ __('Notice Board') }}</div>
        <div class="col ps-0">
            <hr class="mb-0 navbar-vertical-divider">
        </div>
    </div>
</li>
<li class="nav-item">
    <a class="nav-link dropdown-indicator" href="#dashboard30" role="button" data-bs-toggle="collapse"
        aria-expanded="true" aria-controls="dashboard30">
        <div class="d-flex align-items-center">
            <span class="nav-link-icon"><i class="fas fa-credit-card"></i></span>
            <span class="nav-link-text ps-1">{{ __("Notice Board") }}</span>
        </div>
    </a>
    <ul class="nav collapse {{ Request::segment(2) == 'notice-head' ? 'show' : '' }}" id="dashboard30">
        @can('list_notice_head')
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'notice' ? 'active' : '' }}"
                    href="{{ route('admin.notices.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-angle-double-right"></i>
                        {{ __('Notice Head') }}
                    </div>
                </a>
            </li>
        @endcan

        @can(' list_notice_head')
            <li class="nav-item">
                <a class="nav-link {{ Request::segment(2) == 'notice-activities' ? 'active' : '' }}"
                    href="{{ route('admin.notice.index') }}">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-angle-double-right"></i>
                        {{ __('Notice Activity') }}
                    </div>
                </a>
            </li>
        @endcan

       
    </ul>
</li>
@endhasanyrole
