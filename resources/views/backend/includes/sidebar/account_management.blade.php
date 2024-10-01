
@hasanyrole('Super Admin|District Admin|Municipality Admin|Head School|School Admin|Staff')
    <li class="nav-item">
        {{-- <hr class="my-4"> --}}
        <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
            <div class="col-auto navbar-vertical-label">{{__('Account Management')}}</div>
            <div class="col ps-0">
                <hr class="mb-0 navbar-vertical-divider">
            </div>
        </div>
    <li class="nav-item">
        <a class="nav-link dropdown-indicator" href="#dashboard7" role="button" data-bs-toggle="collapse" aria-expanded="true"
            aria-controls="dashboard">
            <div class="d-flex align-items-center"><span class="nav-link-icon"><i
                        class="fas fa-credit-card"></i></span><span class="nav-link-text ps-1">{{ __("Accounts")}}
                </span></div>
        </a>
        <ul class="nav collapse  {{ Request::segment(2) == 'income-head' || Request::segment(2) == 'incomes' || Request::segment(2) == 'expense-head' || Request::segment(2) == 'expenses' || Request::segment(2) == 'fee-types' || Request::segment(2) == 'fee-groups' || Request::segment(2) == 'fee-gropus-types' || Request::segment(2) == 'fee-collections' || Request::segment(2) == 'payment_mode' || Request::segment(2) == 'fee-dues' ? 'show' : '' }}"
            id="dashboard7">
            @can('list_income_head')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'income-head' ? 'active' : '' }}"
                        href="{{ route('admin.income-head.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Income Head')}}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_incomes')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'incomes' ? 'active' : '' }}"
                        href="{{ route('admin.incomes.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Income')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_inventory_head')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'inventory-head' ? 'active' : '' }}"
                        href="{{ route('admin.inventory-head.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i> {{ __('Inventory Head')}}

                        </div>
                    </a>
                </li>
            @endcan
            
            @can('list_inventories')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'inventoriess' ? 'active' : '' }}"
                        href="{{ route('admin.inventories.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>{{ __('Inventory')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_expenses_head')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'expenses-head' ? 'active' : '' }}"
                        href="{{ route('admin.expenses-head.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                           {{__('Expense Head')}}

                        </div>
                    </a>
                </li>
            @endcan
            @can('list_expenses')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'expenses' ? 'active' : '' }}"
                        href="{{ route('admin.expenses.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Expense')}}
                        </div>
                    </a>
                </li>
            @endcan
            @can('list_fee_types')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'fee-types' ? 'active' : '' }}"
                        href="{{ route('admin.fee-types.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Fee Type')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_fee_groups')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'fee-groups' ? 'active' : '' }}"
                        href="{{ route('admin.fee-groups.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Fee Groups')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_fee_grouptypes')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'fee-groups_types' ? 'active' : '' }}"
                        href="{{ route('admin.fee-grouptypes.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{__('Fee Groups Type')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_fee_grouptypes')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'fee-collections' ? 'active' : '' }}"
                        href="{{ route('admin.fee-collections.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{__('Fee Collections')}}

                        </div>
                    </a>
                </li>
            @endcan

            @can('list_fee_dues')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'fee-dues' ? 'active' : '' }}"
                        href="{{ route('admin.fee-dues.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{ __('Fee Dues')}}

                        </div>
                    </a>
                </li>
            @endcan


            @can('list_payment_mode')
                <li class="nav-item"><a class="nav-link {{ Request::segment(2) == 'payment_mode' ? 'active' : '' }}"
                        href="{{ route('super-admin.payment-mode.index') }}">
                        <div class="d-flex align-items-center"><i class="fa fa-angle-double-right"></i>
                            {{__('Payment Mode')}}

                        </div>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
    </li>
@endhasanyrole
