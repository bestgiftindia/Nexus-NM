<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="{{ route('account.dashboard') }}" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ asset('assets/images/logo.png')}}" alt="{{ config('app.name') }}" /></span>
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png')}}" alt="{{ config('app.name') }}" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ asset('assets/images/logo-black.png')}}" alt="{{ config('app.name') }}" /></span>
            <span class="logo-sm"><img src="{{ asset('assets/images/logo-sm.png')}}" alt="{{ config('app.name') }}" /></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-on-hover">
        <span class="btn-on-hover-icon"></span>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-offcanvas">
        <i data-lucide="menu" class="align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar="">
        <!--- Sidenav Menu -->
        <div id="sidenav-menu">
            <ul class="side-nav">
                <li class="side-nav-title mt-2" data-lang="main">Welcome</li>
                <li class="side-nav-item">
                    <a href="{{ route('account.dashboard') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="layout-dashboard"></i></span>
                        <span class="menu-text" data-lang="dashboards">Dashboards</span>
                    </a>
                </li>
                @can('user-list')
                <li class="side-nav-item">
                    <a href="{{ route('account.users.index') }}" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="users"></i></span>
                        <span class="menu-text" data-lang="users">Users</span>
                    </a>
                </li>
                @endcan

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#layout-options" aria-expanded="false" aria-controls="layout-options" class="side-nav-link">
                        <span class="menu-icon"><i data-lucide="sparkles"></i></span>
                        <span class="menu-text" data-lang="layout-options">Numerology</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="layout-options">
                        <ul class="sub-menu">
                            <li class="side-nav-item">
                                <a href="{{ route('account.loshugrid.index') }}" class="side-nav-link">
                                    <span class="menu-text" title="Loshu Grid Mastery" data-lang="layouts-scrollable">Loshu Grid Mastery</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('account.pronology.index') }}" class="side-nav-link">
                                    <span class="menu-text" title="Pronology Software" data-lang="layouts-scrollable">Pronology Software</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('account.mobile.basic.index') }}" class="side-nav-link">
                                    <span class="menu-text" title="Basic Mobile Numerology" data-lang="layouts-scrollable">Basic Mobile Numerology</span>
                                </a>
                            </li>
                            <li class="side-nav-item">
                                <a href="{{ route('account.mobile.advance.index') }}" class="side-nav-link">
                                    <span class="menu-text" title="Advance Mobile Numerology" data-lang="layouts-scrollable">Advance Mobile Numerology</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="side-nav-item">
                    <a href="#" class="side-nav-link disabled">
                        <span class="menu-icon"><i data-lucide="ban"></i></span>
                        <span class="menu-text" data-lang="disabled-menu">Disabled Menu</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>