@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Teacher')
    <li class="nav-item">

        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{ __('Examination Management') }}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard5" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-credit-card"></i></span><span class="nav-link-text ps-1">{{ __('Examination (4-8)') }}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'marks_grades' || Request::segment(2) == 'marks_division' || Request::segment(2) == 'admit_card_design' || Request::segment(2) == 'mark_sheet_design' || Request::segment(2) == 'examinations' || Request::segment(2) == 'exam_schedules' || Request::segment(2) == 'exam_student' || Request::segment(2) == 'admit_card_designsprint' || Request::segment(2) == 'mark-sheetdesigns' || Request::segment(2) == 'generate-marksheets' || Request::segment(2) == 'exam-results' || Request::segment(2) == 'generate-results-marksheetdesigns' ? 'show' : '' }}"
            id="dashboard5">
            @can('list_marks_grades')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'marks-grades' ? 'active' : '' }}"
                        href="{{ route('admin.marks-grades.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Marks Grade') }}

                        </div>
                    </a>
                </li>
            @endcan

            {{-- @can('list_marks_divisions')
                    <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'marks-divisions' ? 'active' : '' }}"
                            href="{{ route('admin.marks-divisions.index') }}">
                            <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> Marks Division

                            </div>
                        </a>
                    </li>
                    @endcan --}}

            @can('list_admit_carddesigns')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'admit-carddesigns' ? 'active' : '' }}"
                        href="{{ route('admin.admit-carddesigns.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Admit Card Design') }}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_generate_admitcards')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'generate-admitcards' ? 'active' : '' }}"
                        href="{{ route('admin.generate-admitcards.index') }}">
                        <div class="d-flex align-items-center"><i
                                class="fa fa-angle-double-right"></i>{{ __('Generate Admit Card') }}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_mark_sheetdesigns')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'mark-sheetdesigns' ? 'active' : '' }}"
                        href="{{ route('admin.mark-sheetdesigns.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Marksheet Design') }}
                        </div>
                    </a>
                </li>
            @endcan
            {{-- @can('create_generate_results')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'generate-results' ? 'active' : '' }}"
                        href="{{ route('admin.generate-results.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Print MarkSheet') }}
                        </div>
                    </a>
                </li>
            @endcan --}}

            {{-- GENERATE MARKSHEET --}}
            @can('list_generate_marksheets')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'generate-marksheets' ? 'active' : '' }}"
                        href="{{ route('admin.generate-marksheets.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Generate Marksheet ') }}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_examinations')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'examinations' ? 'active' : '' }}"
                        href="{{ route('admin.examinations.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Examination') }}

                        </div>
                    </a>
                </li>
            @endcan

            {{-- @can('list_exam_students')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'exam-students' ? 'active' : '' }}"
                        href="{{ route('admin.exam-students.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Exam Student') }}

                        </div>
                    </a>
                </li>
            @endcan --}}

            {{-- @can('list_exam_results')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'exam-results' ? 'active' : '' }}"
                        href="{{ route('admin.exam-results.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Exam Result') }}

                        </div>
                    </a>
                </li>
            @endcan --}}
        </ul>

    </li>

    {{-- <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard12" role="button" data-bs-toggle="collapse"
            aria-expanded="true" aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-credit-card"></i></span><span
                    class="nav-link-text ps-1">{{ __('Examination (1-3)') }}
                </span></div>
        </a>

        <ul class="nav collapse  {{ Request::segment(2) == 'primary-examinations' ? 'show' : '' }}" id="dashboard12">
            @can('list_primary_examinations')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'primary-examinations' ? 'active' : '' }}"
                        href="{{ route('admin.primary-examinations.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Primary Exam') }}

                        </div>
                    </a>
                </li>
            @endcan
        </ul>

    </li> --}}
@endhasanyrole
