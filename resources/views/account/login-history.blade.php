@php
    $meta = [
        'title' => 'Login History',
        'description' =>
            'View your recent login history, track account access, review login activity, and monitor your numerology account for better security.',
        'keywords' =>
            'login history, account login history, login activity, account access, security activity, numerology account security',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Login History" :lists="[
        route('account.profile.index') => 'Profile',
        '' => 'Login History',
    ]" />
    <div class="row">
        <x-account.profile.profile-card />

        <div class="col-xl-8">
            <div class="card">
                <x-account.profile.profile-tabs activeTab="login-history" />

                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="profile">
                            <div class="table-responsive">
                                <table data-tables="export-data-dropdown"
                                    data-url="{{ route('account.login-history.data') }}"
                                    class="table table-striped align-middle mb-0">
                                    <thead class="thead-sm text-uppercase fs-xxs">
                                        <tr class="text-uppercase fs-xxs">
                                            <th data-table-sort>Logged In</th>
                                            <th data-table-sort>IP Address</th>
                                            <th data-table-sort>Logged Out</th>
                                            <th data-table-sort>Browser/System</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@push('js')
    <script>
        const columns = [{
                data: "logged_in_at",
                name: "logged_in_at",
            },
            {
                data: "ip_address",
                name: "ip_address",
            },
            {
                data: "logged_out_at",
                name: "logged_out_at",
            },
            {
                data: "browser",
                name: "browser",
            },
        ];
    </script>
    <!-- Datatables js -->
    <script src="{{ asset('assets/plugins/datatables/dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/responsive.bootstrap5.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatables-export-data.js') }}"></script>
@endpush
