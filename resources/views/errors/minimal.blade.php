<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <script src="{{ asset('assets/js/config-html.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <link id="app-style" href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card p-4">
                        <div class="auth-brand text-center mb-2">
                            <a href="{{ route('home') }}" class="logo-dark">
                                <img src="{{ asset('assets/images/logo-black.png') }}" alt="dark logo" height="28" />
                            </a>
                            <a href="{{ route('home') }}" class="logo-light">
                                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" height="28" />
                            </a>
                        </div>

                        <div class="p-2 text-center">
                            <div class="error-text-alt fs-72">@yield('code')</div>
                            <h3 class="fw-bold text-uppercase">@yield('title')</h3>
                            <p class="text-muted">@yield('message')</p>

                            <button class="btn btn-primary mt-3 rounded-pill"
                                onclick="window.location.href = '{{ route('account.dashboard') }}'">Go Home</button>
                        </div>
                    </div>

                    <p class="text-center text-muted mt-4 mb-0">
                        ©
                        <span data-current-year></span>
                        {{ config('app.name') }} — Made in
                        <span class="fw-semibold">India</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
