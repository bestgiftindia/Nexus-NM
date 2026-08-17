@php
    $languages = [
        'en' => 'English',
        'hi' => 'Hindi',
        'as' => 'Assamese',
        'bn' => 'Bengali',
        'brx' => 'Bodo',
        'doi' => 'Dogri',
        'gu' => 'Gujarati',
        'kn' => 'Kannada',
        'ks' => 'Kashmiri',
        'kok' => 'Konkani',
        'mai' => 'Maithili',
        'ml' => 'Malayalam',
        'mni' => 'Manipuri',
        'mr' => 'Marathi',
        'ne' => 'Nepali',
        'or' => 'Odia',
        'pa' => 'Punjabi',
        'sa' => 'Sanskrit',
        'ta' => 'Tamil',
        'te' => 'Telugu',
        'sat' => 'Santali',
        'sd' => 'Sindhi',
        'ur' => 'Urdu',
    ];
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Sign In | UBold - Multipurpose Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description"
        content="UBold is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features." />
    <meta name="keywords"
        content="UBold, admin dashboard, ThemeForest, Bootstrap 5 admin, Tailwind CSS, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template" />
    <meta name="author" content="Coderthemes" />

    <link rel="shortcut icon" href="assets/images/favicon.ico" />
    <link id="app-style" href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        .skiptranslate {
            display: none !important;
        }

        body {
            top: 0 !important;
        }

        .goog-te-gadget {
            display: none !important;
        }

        #google_translate_element {
            display: none !important;
        }
    </style>
</head>

<body>
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card p-4">
                        <div class="position-absolute top-0 end-0" style="width: 180px">
                            <img src="assets/images/auth-card-bg.svg" class="auth-card-bg-img" alt="auth-card-bg" />
                        </div>
                        <div class="auth-brand text-center mb-4">
                            <a href="index.html" class="logo-dark">
                                <img src="assets/images/logo-black.png" alt="dark logo" />
                            </a>
                            <a href="index.html" class="logo-light">
                                <img src="assets/images/logo.png" alt="logo" />
                            </a>
                            <p class="text-muted w-lg-75 mt-3 mx-auto">Let’s get you signed in. Enter your email and
                                password to continue.</p>
                        </div>
                        <form method="post" id="loginForm" action="{{ route('login.authenticate') }}">
                            @csrf
                            <div class="mb-2">
                                <label for="userEmail" class="form-label">
                                    Email address
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror "
                                    name="email" id="userEmail" placeholder="you@example.com" required />

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="userEmail" class="form-label">
                                    Language
                                </label>
                                <select name="language" class="form-select notranslate" id="languageSwitcher">
                                    @foreach ($languages as $key => $language)
                                        <option value="{{ $key }}">{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid">
                                <x-attributes.button :options="[
                                    'type' => 'submit',
                                    'buttonText' => 'Sign In',
                                    'buttonId' => 'Signbtn',
                                    'loaderText' => 'Signing...',
                                    'loaderId' => 'loaderBtn',
                                ]" />
                            </div>
                        </form>
                    </div>

                    <p class="text-center text-muted mt-4 mb-0">
                        ©
                        <span data-current-year></span>
                        {{ config('app.name') }} — Made In
                        <span class="fw-semibold">India</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="google_translate_element" style="display: none;"></div>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>

</html>
