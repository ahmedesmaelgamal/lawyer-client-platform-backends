<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        {{--        @dd($setting) --}}
        {{--        <a class="header-brand1" href="{{ route('adminHome') }}"> --}}
        {{--            <img src="{{ $setting->where('key', 'logo')->first() !== null ?getFile($setting->where('key', 'logo')->first()):asset('logo.png') }}" class="header-brand-img" --}}
        {{--                 alt="logo"> --}}
        {{--        </a> --}}
        <a class="header-brand1" href="#">
            <img src="{{ asset($setting->where('key', 'logo')->first()->value ?? 'logo.png') }}"
                class="header-brand-img mobile-icon" alt="logo">
        </a>
    </div>
    <ul class="side-menu">
        <li>
            <h3>{{ trns('elements') }}</h3>
        </li>

        <!-- Dashboard -->
        <li class="slide">
            <a class="side-menu__item {{ routeActive('adminHome') }}" href="{{ route('adminHome') }}">
                <i class="fe fe-grid side-menu__icon"></i>
                <span class="side-menu__label">{{ trns('home') }}</span>
            </a>
        </li>

        <!-- User Management -->
        @canany(['read_admin_management', 'read_client_management'])
            <li class="slide {{ arrRouteActive(['admins.index', 'clients.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['admins.index', 'clients.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-users m-2 "></i>
                    <span class="side-menu__label">{{ trns('users_management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @can('read_admin_management')
                        <li class="{{ routeActive('admins.index') }}">
                            <a href="{{ route('admins.index') }}" class="slide-item {{ routeActive('admins.index') }}">
                                <i class="fa-solid fa-user-tie m-2 "></i>{{ trns('admins') }}
                            </a>
                        </li>
                    @endcan
                    @can('read_client_management')
                        <li class="{{ routeActive('clients.index') }}">
                            <a href="{{ route('clients.index') }}" class="slide-item {{ routeActive('clients.index') }}">
                                <i class="fa-regular fa-user m-2 "></i>{{ trns('clients') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        {{-- <i class="fa-solid fa-scale-balanced"></i> --}}

        <!-- Lawyer Management -->
        @canany(['read_lawyer_management', 'read_level_management', 'read_speciality_management'])
            <li class="slide {{ arrRouteActive(['lawyers.index', 'levels.index', 'specialities.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['lawyers.index', 'levels.index', 'specialities.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-scale-balanced m-2"></i>
                    <span class="side-menu__label">{{ trns('lawyer_management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @can('read_lawyer_management')
                        <li class="{{ routeActive('lawyers.index') }}">
                            <a href="{{ route('lawyers.index') }}" class="slide-item {{ routeActive('lawyers.index') }}">
                                <i class="fa-solid fa-id-badge m-2 "></i>
                                {{ trns('lawyers') }}
                            </a>
                        </li>
                    @endcan
                    @can('read_level_management')
                        <li class="{{ routeActive('levels.index') }}">
                            <a href="{{ route('levels.index') }}" class="slide-item {{ routeActive('levels.index') }}">
                                <i class="fa-solid fa-layer-group m-2 "></i>{{ trns('levels') }}
                            </a>
                        </li>
                    @endcan
                    @can('read_speciality_management')
                        <li class="{{ routeActive('specialities.index') }}">
                            <a href="{{ route('specialities.index') }}"
                                class="slide-item {{ routeActive('specialities.index') }}">
                                <i class="fa-solid fa-tags m-2 "></i>{{ trns('specialities') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        <!-- wallet Management -->
        @canany(['read_wallet_management'])
            <li class="slide {{ arrRouteActive(['withdraw_requests.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['withdraw_requests.index'], 'active') }}" data-toggle="slide"
                    href="#">
                    <i class="fa-solid fa-money-bill m-2"></i>
                    <span class="side-menu__label">{{ trns('wallets_management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @can('read_wallet_management')
                        <li class="{{ routeActive('withdraw_requests.index') }}">
                            <a href="{{ route('withdraw_requests.index') }}"
                                class="slide-item {{ routeActive('withdraw_requests.index') }}">
                                <i class="fa-solid fa-wallet m-2 "></i>{{ trns('withdraw_requests') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Lawyer Package Management -->
        @canany(['read_offer_package_management', 'lawyer_package_management'])
            <li class="slide {{ arrRouteActive(['offer_packages.index', 'lawyer_packages.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['offer_packages.index', 'lawyer_packages.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-box m-2 "></i>
                    <span class="side-menu__label">{{ trns('lawyer_package_management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @can('read_offer_package_management')
                        <li class="{{ routeActive('offer_package.index') }}">
                            <a href="{{ route('offer_packages.index') }}"
                                class="slide-item {{ routeActive('offer_packages.index') }}">
                                <i class="fa-solid fa-tags m-2 "></i>{{ trns('offer_packages') }}
                            </a>
                        </li>
                    @endcan
                    @can('read_lawyer_package_management')
                        <li class="{{ routeActive('lawyer_packages.index') }}">
                            <a href="{{ route('lawyer_packages.index') }}"
                                class="slide-item {{ routeActive('lawyer_packages.index') }}">
                                <i class="fa-regular fa-handshake m-2 "></i>
                                {{ trns('lawyer_packages') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Area Management -->
        @canany(['read_country_management', 'read_city_management'])
            <li class="slide {{ arrRouteActive(['countries.index', 'cities.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['countries.index', 'cities.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-globe m-2 "></i>
                    <span class="side-menu__label">{{ trns('area_management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    @can('read_country_management')
                        <li class="{{ routeActive('countries.index') }}">
                            <a href="{{ route('countries.index') }}" class="slide-item {{ routeActive('countries.index') }}">
                                <i class="fa-solid fa-flag m-2 "></i>{{ trns('countries') }}
                            </a>
                        </li>
                    @endcan
                    @can('read_city_management')
                        <li class="{{ routeActive('cities.index') }}">
                            <a href="{{ route('cities.index') }}" class="slide-item {{ routeActive('cities.index') }}">
                                <i class="fa-solid fa-city m-2 "></i>{{ trns('cities') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        <!-- Advertisement Management -->
        @can('read_ad_management')
            <li class="slide {{ arrRouteActive(['ads.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['ads.index'], 'active') }}" data-toggle="slide"
                    href="#">
                    <i class="fa-solid fa-rectangle-ad m-2 "></i>
                    <span class="side-menu__label">{{ trns('advertisement_management') }}</span>
                    <i class="angle fa fa-angle-right m-2 "></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('ads.index') }}">
                        <a href="{{ route('ads.index') }}" class="slide-item {{ routeActive('ads.index') }}">
                            <i class="fa-solid fa-list-check m-2 "></i>{{ trns('all_ads') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('ads.index') }}">
                        <a href="{{ route('ads.index', ['status' => 'active', 'ad_confirmation' => 'confirmed']) }}"
                            class="slide-item {{ routeActive('ads.index') }}">
                            <i class="fa-regular fa-square-check m-2 "></i>{{ trns('all_active_ads') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('ads.index') }}">
                        <a href="{{ route('ads.index', ['status' => 'inactive', 'ad_confirmation' => 'confirmed']) }}"
                            class="slide-item {{ routeActive('ads.index') }}">
                            <i class="fa-regular fa-rectangle-xmark m-2 "></i>{{ trns('all_inactive_ads') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('ads.index') }}">
                        <a href="{{ route('ads.index', ['status' => 'active', 'ad_confirmation' => 'rejected']) }}"
                            class="slide-item {{ routeActive('ads.index') }}">
                            <i class="fa-solid fa-ban m-2 "></i>{{ trns('rejected_ads') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('ads.index') }}">
                        <a href="{{ route('ads.index', ['status' => 'inactive', 'ad_confirmation' => 'requested']) }}"
                            class="slide-item {{ routeActive('ads.index') }}">
                            <i class="fa-solid fa-hourglass-half m-2 "></i>{{ trns('requested_ads') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- Community Management -->
        @can('read_community_management')
            <li
                class="slide {{ arrRouteActive(['community_categories.index', 'community_sub_categories.index', 'community_services.index', 'contract_categorys.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['community_categories.index', 'community_sub_categories.index', 'community_services.index', 'contract_categorys.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-network-wired m-2"></i>
                    <span class="side-menu__label">{{ trns('community management') }}</span>
                    <i class="angle fa fa-angle-right m-2 "></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('community_categories.index') }}">
                        <a href="{{ route('community_categories.index') }}"
                            class="slide-item {{ routeActive('community_categories.index') }}">
                            <i class="fa-solid fa-grip-vertical m-2 "></i>
                            {{ trns('community Categories') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('community_sub_categories.index') }}">
                        <a href="{{ route('community_sub_categories.index') }}"
                            class="slide-item {{ routeActive('community_sub_categories.index') }}">
                            <i class="fa-solid fa-network-wired m-2 "></i>{{ trns('community sub categories') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('community_services.index') }}">
                        <a href="{{ route('community_services.index') }}"
                            class="slide-item {{ routeActive('community_services.index') }}">
                            <i class="fa-solid fa-users m-2 "></i>
                            {{ trns('community service') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('contract_categorys.index') }}">
                        <a href="{{ route('contract_categorys.index') }}"
                            class="slide-item {{ routeActive('contract_categorys.index') }}">
                            <i class="fa-solid fa-file-contract m-2"></i>
                            {{ trns('contract_categorys') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        <!-- Market Management -->
        @can('read_market_management')
            <li
                class="slide {{ arrRouteActive(['market_products.index', 'market_product_categories.index', 'market_offers.index', 'orders.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['market_products.index', 'market_product_categories.index', 'market_offers.index', 'orders.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-shop m-2 "></i>
                    <span class="side-menu__label">{{ trns('market management') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('market_products.index') }}">
                        <a href="{{ route('market_products.index') }}"
                            class="slide-item {{ routeActive('market_products.index') }}">
                            <i class="fa-regular fa-rectangle-list m-2 "></i>{{ trns('market product') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('market_product_categories.index') }}">
                        <a href="{{ route('market_product_categories.index') }}"
                            class="slide-item {{ routeActive('market_product_categories.index') }}">
                            <i class="fa-solid fa-grip-vertical m-2 "></i>{{ trns('market product category') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('market_offers.index') }}">
                        <a href="{{ route('market_offers.index') }}"
                            class="slide-item {{ routeActive('market_offers.index') }}">
                            <i class="fa-solid fa-tags m-2 "></i>{{ trns('market product offer') }}
                        </a>
                    </li>
                    <!-- market_offer_management -->

                    <!-- order_management -->
                    <li class="{{ routeActive('orders.index') }}">
                        <a href="{{ route('orders.index') }}" class="slide-item {{ routeActive('orders.index') }}">
                            <i class="fa-solid fa-basket-shopping m-2 "></i>{{ trns('orders') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        <!-- Court Case Management -->
        @can('read_court_case_management')
            <li
                class="slide {{ arrRouteActive(['court_cases.index', 'refuse_reasons.index', 'court_case_events.index', 'court_case_updates.index', 'court_case_levels.index', 'Case_Specializations.index', 'court_case_dues.index', 'court_case_cancellation.index', 's_o_s_requests.index', 'SubCase_Specializations.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['court_cases.index', 'refuse_reasons.index', 'court_case_events.index', 'court_case_updates.index', 'court_case_levels.index', 'Case_Specializations.index', 'court_case_dues.index', 'court_case_cancellation.index', 's_o_s_requests.index', 'SubCase_Specializations.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-landmark-dome m-2 "></i>
                    <span class="side-menu__label">{{ trns('court_cases_management') }}</span>
                    <i class="angle fa fa-angle-right m-2 "></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('court_cases.index') }}">
                        <a href="{{ route('court_cases.index') }}"
                            class="slide-item {{ routeActive('court_cases.index') }}">
                            <i class="fa-regular fa-rectangle-list m-2 "></i>
                            {{ trns('court cases') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('court_case_events.index') }}">
                        <a href="{{ route('court_case_events.index') }}"
                            class="slide-item {{ routeActive('court_case_events.index') }}">
                            <i class="fa-solid fa-location-arrow m-2 "></i>
                            {{ trns('court case events') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('court_case_updates.index') }}">
                        <a href="{{ route('court_case_updates.index') }}"
                            class="slide-item {{ routeActive('court_case_updates.index') }}">
                            <i class="fa-solid fa-file-pen m-2 "></i>
                            {{ trns('court case updates') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('court_case_levels.index') }}">
                        <a href="{{ route('court_case_levels.index') }}"
                            class="slide-item {{ routeActive('court_case_levels.index') }}">
                            <i class="fa-solid fa-exclamation m-2 "></i>
                            {{ trns('court_case_levels') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('Case_Specializations.index') }}">
                        <a href="{{ route('Case_Specializations.index') }}"
                            class="slide-item {{ routeActive('Case_Specializations.index') }}">
                            <i class="fa-solid fa-briefcase m-2"></i>
                            {{ trns('case_specializations') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('SubCase_Specializations.index') }}">
                        <a href="{{ route('SubCase_Specializations.index') }}"
                            class="slide-item {{ routeActive('SubCase_Specializations.index') }}">
                            <i class="fa-solid fa-th-large m-2"></i> <!-- SubCase_Specializations Icon -->
                            {{ trns('SubCase_Specializations') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('court_case_dues.index') }}">
                        <a href="{{ route('court_case_dues.index') }}"
                            class="slide-item {{ routeActive('court_case_dues.index') }}">
                            <i class="fa-solid fa-hand-holding-dollar m-2 "></i>
                            {{ trns('court case dues') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('court_case_cancellation.index') }}">
                        <a href="{{ route('court_case_cancellation.index') }}"
                            class="slide-item {{ routeActive('court_case_cancellation.index') }}">
                            <i class="fa-solid fa-ban m-2 "></i>
                            {{ trns('court case cancellation') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('refuse_reasons.index') }}">
                        <a href="{{ route('refuse_reasons.index') }}"
                            class="slide-item {{ routeActive('refuse_reasons.index') }}">
                            <i class="fa-solid fa-clipboard-question m-2"></i>
                            {{ trns('Event Refuse Reasons') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('s_o_s_requests.index') }}">
                        <a href="{{ route('s_o_s_requests.index') }}"
                            class="slide-item {{ routeActive('s_o_s_requests.index') }}">
                            <i class="fa-solid fa-person-running m-2"></i>
                            {{ trns('s_o_s_requests') }}
                        </a>
                    </li>

                </ul>
            </li>
        @endcan

        @can('read_lawyer_report_management')
            <li class="slide {{ arrRouteActive(['Lawyer_Report.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['Lawyer_Report.index'], 'active') }}" data-toggle="slide"
                    href="#">
                    <i class="fa-solid fa-landmark-dome m-2 "></i>
                    <span class="side-menu__label">{{ trns('Lawyer_Report') }}</span>
                    <i class="angle fa fa-angle-right m-2 "></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('Lawyer_Report.index') }}">
                        <a href="{{ route('Lawyer_Report.index') }}"
                            class="slide-item {{ routeActive('Lawyer_Report.index') }}">
                            <i class="fa-solid fa-person-running m-2"></i>
                            {{ trns('Lawyer_Report') }}
                        </a>
                    </li>

                </ul>
            </li>
        @endcan
        <!-- setting Management -->
        {{--        <li class="slide {{ arrRouteActive(['general_settings.index', 'role_and_permissions.index', 'system_activities.index']) }}"> --}}
        {{--            <a class="side-menu__item {{ arrRouteActive(['general_settings.index', 'role_and_permissions.index','system_activities.index'], 'active') }}" --}}
        {{--               data-toggle="slide" href="#"> --}}

        @can('read_settings_management')
            <li class="slide {{ arrRouteActive(['settings.index', 'secure_settings.index', 'roles.index', 'activity_logs.index']) }}">
                <a class="side-menu__item {{ arrRouteActive(['settings.index' ,'secure_settings.index', 'roles.index', 'activity_logs.index'], 'active') }}"
                    data-toggle="slide" href="#">
                    <i class="fa-solid fa-gear m-2"></i>
                    <span class="side-menu__label">{{ trns('settings') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ routeActive('settings.index') }}">
                        <a href="{{ route('settings.index') }}"
                            class="slide-item {{ routeActive('settings.index') }}">
                            <i class="fa-solid fa-screwdriver-wrench m-2"></i>
                            {{ trns('general settings') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('secure_settings.index') }}">
                        <a href="{{ route('secure_settings.index') }}"
                            class="slide-item {{ routeActive('secure_settings.index') }}">
                            <i class="fa-solid fa-screwdriver-wrench m-2"></i>
                            {{ trns('security_settings') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('roles.index') }}">
                        <a href="{{ route('roles.index') }}" class="slide-item {{ routeActive('roles.index') }}">
                            <i class="fa-solid fa-universal-access m-2"></i>
                            {{ trns('role and permission') }}
                        </a>
                    </li>
                    <li class="{{ routeActive('activity_logs.index') }}">
                        <a href="{{ route('activity_logs.index') }}"
                            class="slide-item {{ routeActive('activity_logs.index') }}">
                            <i class="fa-solid fa-file-waveform m-2 "></i>
                            {{ trns('activity log') }}
                        </a>
                    </li>
                </ul>
            </li>

        @endcan
        <!-- Logout -->
        {{-- <li class="slide">
            <a class="side-menu__item {{ routeActive('admin.logout') }}" href="{{ route('admin.logout') }}">
                <i class="fe fe-log-out side-menu__icon m-2 "></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li> --}}
        {{--  OtherApps --}}
        <li class="slide {{ arrRouteActive(['OtherApps.index']) }}">
            <a class="side-menu__item {{ arrRouteActive(['OtherApps.index'], 'active') }}" data-toggle="slide"
                href="#">
                <i class="fas fa-sitemap side-menu__icon"></i> <!-- others Icon -->
                <span class="side-menu__label">{{ trns('other_apps') }}</span>
                <i class="angle fa fa-angle-right"></i>
            </a>

            <ul class="slide-menu">
                <!-- OtherApps -->
                <li class="{{ routeActive('OtherApps.index') }}">
                    <a class="slide-item {{ routeActive('OtherApps.index') }}"
                        href="{{ route('OtherApps.index') }}">
                        <i class="fas fa-cog side-menu__icon"></i> <!-- services Icon -->
                        {{ trns('other_apps') }}
                    </a>
                </li>
                <!-- OtherApps -->
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item text-danger  {{ routeActive('admin.logout') }}"
                href="{{ route('admin.logout') }}">
                <i class="fe fe-log-out side-menu__icon m-2"></i>
                <span class="side-menu__label">{{ trns('logout') }}</span>
            </a>
        </li>

    </ul>
</aside>
