<!doctype html>
<html lang="en">

<!-- Mirrored from coderthemes.com/ubold/bootstrap/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Jun 2026 06:00:34 GMT -->

<head>
    <meta charset="utf-8" />


    <x-meta :title="$meta['title'] ?? 'Dashboard'" :description="$meta['description'] ?? ''" :keywords="$meta['keywords'] ?? ''" :author="$meta['author'] ?? 'Your Company'" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('assets/js/config-html.js') }}"></script>

    <script src="{{ asset('assets/js/config.js') }}"></script>

    @stack('css')

    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
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
    <!-- Begin page -->
    <div class="wrapper">
        <x-account.top-bar />
        <!-- Topbar End -->

        <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <form>
                        <div class="card mb-1">
                            <div class="px-3 py-2 d-flex flex-row align-items-center" id="top-search">
                                <i data-lucide="search" class="fs-22"></i>
                                <input type="search" class="form-control border-0" id="search-modal-input"
                                    placeholder="Search for actions, people," />
                                <button type="submit" class="btn p-0" data-bs-dismiss="modal"
                                    aria-label="Close">[esc]</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <x-account.side-bar />
        <!-- Sidenav Menu End -->


        <!-- ============================================================== -->
        <!-- Start Main Content -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container -->

            <!-- Footer Start -->
            <x-account.footer />
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End of Main Content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <div id="google_translate_element" style="display: none;"></div>

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    @stack('js')
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>

<!-- Mirrored from coderthemes.com/ubold/bootstrap/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Jun 2026 06:01:58 GMT -->

</html>
