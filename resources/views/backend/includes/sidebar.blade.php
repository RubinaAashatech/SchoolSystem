<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        document.querySelector('.navbar-vertical').classList.add(`navbar-inverted`);
        // document.querySelector('.navbar-vertical').classList.add('navbar-vibrant');
        // if (navbarStyle && navbarStyle !== 'transparent') {}
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" aria-label="Toggle Navigation" data-bs-original-title="Toggle Navigation"><span
                    class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
        </div><a class="navbar-brand" href="{{ url('/') }}">
            <div class="d-flex align-items-center py-3"><img class="me-2"
                    src="{{ asset('uploads/logo.png') }}" alt=""
                    width="40"><span class="font-sans-serif">{{ $initials ?? 'Admin' }}</span></div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                {{-- <li class="nav-item">
                    <a class="nav-link dropdown-indicator" href="#dashboard" role="button" data-bs-toggle="collapse"
                        aria-expanded="true" aria-controls="dashboard">
                        <div class="d-flex align-items-center"><span class="nav-link-icon">
                                <a href="#"><span class="nav-link-text ps-1">Dashboard</span></a>
                        </div>
                    </a>

                </li> --}}

                @include('backend.includes.sidebar.academic_management')
                @include('backend.includes.sidebar.lesson_plan_management')
                @include('backend.includes.sidebar.school_management')
                @include('backend.includes.sidebar.attendance_management')
                @include('backend.includes.sidebar.examination_management')
                @include('backend.includes.sidebar.primaryclass_settings')

                @include('backend.includes.sidebar.account_management')
                @include('backend.includes.sidebar.certificate_management')
                @include('backend.includes.sidebar.logbook_management')

                @include('backend.includes.sidebar.ECA_activities_management')
                @include('backend.includes.sidebar.Notice_management')
                @include('backend.includes.sidebar.attendence_reports')
                @include('backend.includes.sidebar.user_management')
                @include('backend.includes.sidebar.role_permission')
            </ul>
        </div>
    </div>
</nav>
