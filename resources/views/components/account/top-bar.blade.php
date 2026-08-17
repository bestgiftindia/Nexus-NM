@php
    $languages = [
        [
            ['code' => 'en', 'name' => 'English'],
            ['code' => 'hi', 'name' => 'Hindi'],
            ['code' => 'as', 'name' => 'Assamese'],
            ['code' => 'bn', 'name' => 'Bengali'],
            ['code' => 'brx', 'name' => 'Bodo'],
            ['code' => 'doi', 'name' => 'Dogri'],
        ],
        [
            ['code' => 'gu', 'name' => 'Gujarati'],
            ['code' => 'kn', 'name' => 'Kannada'],
            ['code' => 'ks', 'name' => 'Kashmiri'],
            ['code' => 'gom', 'name' => 'Konkani'],
            ['code' => 'mai', 'name' => 'Maithili'],
            ['code' => 'ml', 'name' => 'Malayalam'],
        ],
        [
            ['code' => 'mni', 'name' => 'Manipuri'],
            ['code' => 'mr', 'name' => 'Marathi'],
            ['code' => 'ne', 'name' => 'Nepali'],
            ['code' => 'or', 'name' => 'Odia'],
            ['code' => 'pa', 'name' => 'Punjabi'],
            ['code' => 'sa', 'name' => 'Sanskrit'],
        ],
        [
            ['code' => 'ta', 'name' => 'Tamil'],
            ['code' => 'te', 'name' => 'Telugu'],
            ['code' => 'sat', 'name' => 'Santali'],
            ['code' => 'sd', 'name' => 'Sindhi'],
            ['code' => 'ur', 'name' => 'Urdu'],
        ],
    ];
@endphp

<header class="app-topbar">
    <div class="container-fluid topbar-menu">
        <div class="d-flex align-items-center gap-2">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="{{ route('account.dashboard') }}" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="{{ config('app.name') }}" />
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="{{ route('account.dashboard') }}" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-black.png') }}" alt="{{ config('app.name') }}" />
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="{{ config('app.name') }}" />
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button btn btn-default btn-icon">
                <i data-lucide="menu"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu">
                <i data-lucide="menu"></i>
            </button>

            <div id="megamenu-pages" class="topbar-item d-none d-md-flex">
                <div class="dropdown notranslate">
                    <button class="topbar-link btn fw-medium btn-link dropdown-toggle drop-arrow-none px-2"
                        data-bs-toggle="dropdown" type="button" aria-haspopup="false" aria-expanded="false">
                        <span id="current-lang">English</span>
                        <i data-lucide="chevron-down" class="ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-xxl p-0">
                        <div class="h-100" style="max-height: 380px" data-simplebar="">
                            <div class="row g-0">
                                <!-- Dashboard & Analytics -->
                                @foreach ($languages as $language)
                                    <div class="col-md-3">
                                        <div class="p-2">
                                            <ul class="list-unstyled megamenu-list">
                                                @foreach ($language as $lang)
                                                    <li>
                                                        <a href="javascript:void(0);"
                                                            class="dropdown-item  language-switcher"
                                                            data-language-name="{{ $lang['name'] }}"
                                                            data-language="{{ $lang['code'] }}">
                                                            {{ $lang['name'] }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <!-- end .h-100-->
                    </div>
                    <!-- .dropdown-menu-->
                </div>
                <!-- .dropdown-->
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div id="search-box-rounded-right" class="app-search d-none d-xl-flex">
                <input type="search" class="form-control rounded-pill topbar-search" name="search"
                    placeholder="Quick Search..." />
                <i data-lucide="search" class="app-search-icon text-muted"></i>
            </div>



            <div id="notification-dropdown-people" class="topbar-item">
                <div class="dropdown">
                    <button class="topbar-link dropdown-toggle drop-arrow-none" data-bs-toggle="dropdown" type="button"
                        data-bs-auto-close="outside" aria-haspopup="false" aria-expanded="false">
                        <i data-lucide="bell" class="topbar-link-icon animate-ring"></i>
                        <span class="badge text-bg-danger badge-circle topbar-badge">5</span>
                    </button>

                    <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                        <div class="px-3 py-2 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-md fw-semibold">Notifications</h6>
                                </div>
                                <div class="col text-end">
                                    <a href="#!" class="badge badge-soft-success badge-label py-1">07
                                        Notifications</a>
                                </div>
                            </div>
                        </div>

                        <div style="max-height: 300px" data-simplebar="">
                            <!-- Notification 6 -->
                            <div class="dropdown-item notification-item py-2 text-wrap" id="message-5">
                                <span class="d-flex align-items-center gap-3">
                                    <span class="flex-shrink-0 position-relative">
                                        <img src="{{ asset('assets/images/users/user-8.jpg') }}"
                                            class="avatar-md rounded-circle" alt="User Avatar" />
                                        <span class="position-absolute rounded-pill bg-secondary notification-badge">
                                            <i data-lucide="square-pen" class="align-middle"></i>
                                            <span class="visually-hidden">edit</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Isabella White</span>
                                        updated the document in
                                        <span class="fw-medium text-body">Product Specs</span>
                                        <br />
                                        <span class="fs-xs">2 hours ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 text-muted btn btn-link p-0 position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-5">
                                        <i data-lucide="x-square" class="fs-xxl"></i>
                                    </button>
                                </span>
                            </div>

                            <!-- Notification 7 - Deployment Success -->
                            <div class="dropdown-item notification-item py-2 text-wrap" id="message-7">
                                <span class="d-flex align-items-center gap-3">
                                    <span class="flex-shrink-0 position-relative">
                                        <span
                                            class="avatar-md rounded-circle bg-light d-flex align-items-center justify-content-center">
                                            <i data-lucide="rocket" class="fs-4"></i>
                                        </span>
                                        <span class="position-absolute rounded-pill bg-success notification-badge">
                                            <i data-lucide="check" class="align-middle"></i>
                                            <span class="visually-hidden">deployment</span>
                                        </span>
                                    </span>
                                    <span class="flex-grow-1 text-muted">
                                        <span class="fw-medium text-body">Production Server</span>
                                        deployment completed successfully
                                        <br />
                                        <span class="fs-xs">30 minutes ago</span>
                                    </span>
                                    <button type="button"
                                        class="flex-shrink-0 text-muted btn btn-link p-0 position-absolute end-0 me-2 d-none noti-close-btn"
                                        data-dismissible="#message-7">
                                        <i data-lucide="x-square" class="fs-xxl"></i>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);"
                            class="dropdown-item text-center text-reset text-decoration-underline link-offset-2 fw-bold notify-item border-top border-light py-2">Read
                            All Messages</a>
                    </div>
                    <!-- End dropdown-menu -->
                </div>
                <!-- end dropdown-->
            </div>

            <div id="fullscreen-toggler" class="topbar-item d-none d-md-flex">
                <button class="topbar-link" type="button" data-toggle="fullscreen">
                    <i data-lucide="maximize" class="topbar-link-icon"></i>
                    <i data-lucide="minimize" class="topbar-link-icon d-none"></i>
                </button>
            </div>

            <div id="simple-user-dropdown" class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                        href="#!" aria-haspopup="false" aria-expanded="false">
                        @if (!empty(loginAccount()['account_profile']))
                            <x-image-preview class="rounded-circle me-lg-2 d-flex"
                                altName="{{ loginAccount()['account_name'] ?? 'Customer' }}" width="32"
                                imagepath="users" :image="loginAccount()['account_profile'] ?? ''" />
                        @else
                            <span
                                class="btn btn-icon rounded-circle btn-info btn-sm me-1">{{ loginAccount()['short_name'] ?? 'C' }}</span>
                        @endif

                        <div class="d-lg-flex align-items-center gap-1 d-none">
                            <h5 class="my-0">{{ loginAccount()['account_name'] ?? 'Customer' }}</h5>
                            <i data-lucide="chevron-down" class="align-middle"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- Header -->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome back!</h6>
                        </div>

                        <!-- My Profile -->
                        <a href="{{ route('account.profile.index') }}" class="dropdown-item">
                            <i data-lucide="circle-user-round" class="me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Profile</span>
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('account.notifications.index') }}" class="dropdown-item">
                            <i data-lucide="bell-ring" class="me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Notifications</span>
                        </a>

                        <!-- Role -->
                        @can('role-list')
                        <a href="{{ route('account.roles.index') }}" class="dropdown-item">
                            <i data-lucide="user-cog" class="me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Roles</span>
                        </a>
                        @endcan

                        @can('permission-list')
                        <!-- Permission -->
                        <a href="{{ route('account.permissions.index') }}" class="dropdown-item">
                            <i data-lucide="shield-check" class="me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Permissions</span>
                        </a>
                        @endcan

                        <!-- Divider -->
                        <div class="dropdown-divider my-1"></div>

                        <!-- Logout -->
                        <a role="button" data-bs-toggle="modal" data-bs-target="#logoutModal"
                            class="dropdown-item text-danger fw-semibold">
                            <i data-lucide="log-out" class="me-1 fs-lg align-middle"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
