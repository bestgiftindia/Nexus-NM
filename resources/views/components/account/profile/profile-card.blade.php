<div class="col-xl-4">
    <div class="card card-top-sticky">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3 position-relative">
                    @if (!empty(loginAccount()['account_profile']))
                        <x-image-preview class="rounded-circle"
                            altName="{{ loginAccount()['account_name'] ?? 'Customer' }}" width="72" imagepath="users"
                            :image="loginAccount()['account_profile'] ?? ''" />
                    @else
                        <span style="width: 72px; height: 72px; font-size: 18px;"
                            class="btn btn-icon rounded-circle btn-info">{{ loginAccount()['short_name'] ?? 'C' }}</span>
                    @endif
                </div>
                <div>
                    <h5 class="mb-0 d-flex align-items-center">
                        <a href="#!" class="link-reset">{{ loginAccount()['account_name'] ?? 'Customer' }}</a>
                    </h5>
                    <p class="text-muted mb-2">User Id: #{{ loginAccount()['account_user_id'] ?? '' }}</p>
                    @if (auth()->user()->role != 'user')
                        <span class="badge text-bg-light badge-label">Role:
                            {{ loginAccount()['account_role'] }}</span>
                    @endif
                </div>
            </div>

            <div class="">

                <div class="d-flex align-items-center gap-2 mb-2">
                    <div
                        class="avatar-sm text-bg-light bg-opacity-75 d-flex align-items-center justify-content-center rounded-circle">
                        <i data-lucide="phone" class="fs-xl"></i>
                    </div>
                    <p class="mb-0 fs-sm">
                        Contact :
                        @if (!empty(loginAccount()['account_phone'] ?? ''))
                            <span
                                class="text-dark fw-semibold">+{{ loginAccount()['account_phone_code'] ?? '91' }}-</span>{{ loginAccount()['account_phone'] ?? '------' }}
                        @else
                            -----
                        @endif
                    </p>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <div
                        class="avatar-sm text-bg-light bg-opacity-75 d-flex align-items-center justify-content-center rounded-circle">
                        <i data-lucide="mail" class="fs-xl"></i>
                    </div>
                    <p class="mb-0 fs-sm">
                        Email :
                        <a href="mailto:{{ loginAccount()['account_email'] ?? '' }}"
                            class="text-primary fw-semibold">{{ loginAccount()['account_email'] ?? '------' }}</a>
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div
                        class="avatar-sm text-bg-light bg-opacity-75 d-flex align-items-center justify-content-center rounded-circle">
                        <i data-lucide="map-pin" class="fs-xl"></i>
                    </div>
                    <p class="mb-0 fs-sm">
                        Lives in :
                        <span class="text-dark fw-semibold">{{ loginAccount()['account_address'] ?? '------' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
