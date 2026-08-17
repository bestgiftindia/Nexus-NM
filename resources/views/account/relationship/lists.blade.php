@php
    $meta = [
        'title' => 'Relationship Reports',
        'description' => 'Manage Relationship reports in the Numerology software dashboard.',
        'keywords' => 'relationship report, numerology, relationship compatibility, love compatibility, marriage compatibility, dashboard',
    ];
@endphp

@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Relationship Reports" :lists="[
        route('account.relationship.index') => 'Relationship Reports',
        '' => 'List',
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title mb-1">Report Lists</h2>
                        <p class="text-muted mb-0">
                            View and manage all Relationship reports.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.relationship.index') }}" class="btn btn-primary">
                            <i data-lucide="plus" class="me-1" style="width:16px;height:16px;"></i>
                            Create Report
                        </a>
                    </div>
                </div>

                <div class="table-responsive p-3">
                    <table data-tables="export-data-dropdown" data-url="{{ route('account.relationship.get.list') }}"
                        class="table table-custom table-centered table-select table-hover w-100 mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr class="text-uppercase fs-xxs">
                                <th data-table-sort>#</th>
                                <th data-table-sort>Generate Date</th>
                                <th data-table-sort>Name & DOB</th>
                                <th data-table-sort>Contact Info</th>
                                <th data-table-sort>Gender</th>
                                <th data-table-sort>Actions</th>
                            </tr>
                        </thead>
                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        const columns = [{
                data: "DT_RowIndex",
                name: "DT_RowIndex",
            },
            {
                data: "date_time",
                name: "date_time",
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
                data: "gender",
                name: "gender",
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ];
    </script>
    <x-account.datatable />
@endpush
