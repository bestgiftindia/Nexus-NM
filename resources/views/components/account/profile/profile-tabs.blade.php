@props(['activeTab'])
<div class="card-header card-tabs d-flex align-items-center">
    <div class="flex-grow-1">
        <h4 class="card-title">My Account</h4>
    </div>
    <ul class="nav nav-tabs card-header-tabs nav-bordered">
        <li class="nav-item">
            <a href="{{ route('account.profile.index') }}" class="nav-link {{ $activeTab == 'profile' ? 'active' : '' }}">
                <i data-lucide="circle-user-round" class="d-md-none d-block fs-lg"></i>
                <span class="d-none d-md-block fw-bold">Profile</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('account.notifications.index') }}"
                class="nav-link {{ $activeTab == 'notifications' ? 'active' : '' }}">
                <i data-lucide="clock" class="d-md-none d-block fs-lg"></i>
                <span class="d-none d-md-block fw-bold">Notifications</span>
            </a>
        </li>

        @can('social-account-list')
        <li class="nav-item">
            <a href="{{ route('account.socialMedia.index') }}"
                class="nav-link  {{ $activeTab == 'social' ? 'active' : '' }}">
                <i data-lucide="settings" class="d-md-none d-block fs-lg"></i>
                <span class="d-none d-md-block fw-bold">Social Accounts</span>
            </a>
        </li>
        @endcan

        <li class="nav-item">
            <a href="{{ route('account.login-history.index') }}"
                class="nav-link  {{ $activeTab == 'login-history' ? 'active' : '' }}">
                <i data-lucide="settings" class="d-md-none d-block fs-lg"></i>
                <span class="d-none d-md-block fw-bold">Login Histories</span>
            </a>
        </li>
    </ul>
</div>
