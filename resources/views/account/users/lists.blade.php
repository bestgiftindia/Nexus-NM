@php
    $meta = [
        'title' => 'User Management',
        'description' =>
            'Manage user accounts, assign roles, and control access permissions within the numerology software administration panel.',
        'keywords' =>
            'user management, users, user accounts, role assignment, access control, admin panel, numerology software, user permissions',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Users" :lists="[
        '' => 'Users List',
    ]" />


    <div class="row">
        <div class="col-xxl-12">
            <div data-table data-table-rows-per-page="8" class="card">
                <div class="card-header border-light justify-content-between">
                    <div>
                        <h2 class="card-title d-block">Users List</h2>
                        <p class="text-muted mb-0">
                            View, create, edit, and manage user accounts and their assigned roles.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.users.create') }}" class="btn btn-secondary">Add User</a>
                    </div>
                </div>

                <div class="table-responsive p-3">
                    <table data-tables="export-data-dropdown" data-url="{{ route('account.users.lists.data') }}"
                        class="table table-custom table-centered table-select table-hover w-100 mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr class="text-uppercase fs-xxs">
                                <th data-table-sort>#</th>
                                <th data-table-sort>Account Id</th>
                                <th data-table-sort>User Name</th>
                                <th data-table-sort>Contact Info</th>
                                <th data-table-sort>Activate</th>
                                <th data-table-sort>Actions</th>
                            </tr>
                        </thead>
                    </table>

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
        const publishUrl = "{{ route('account.users.publish') }}";
        const columns = [{
                data: "DT_RowIndex",
                name: "DT_RowIndex",
            },
            {
                data: "account_id",
                name: "account_id",
            },
            {
                data: "name",
                name: "name",
            },
            {
                data: "contact_info",
                name: "contact_info",
            },
            {
                data: "status",
                name: "status",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ];
    </script>
    <!-- Datatables js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
