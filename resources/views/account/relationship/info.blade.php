@php
    $meta = [
        'title' => 'View Relationship Report',
        'description' => 'View Relationship report details in the Numerology software dashboard.',
        'keywords' =>
            'relationship report, numerology, view relationship report, relationship compatibility, dashboard',
    ];
@endphp

@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Relationship Report" :lists="[
        route('account.relationship.list') => 'Relationship Report',
        '' => 'View Report',
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title mb-1">Report Detail</h2>
                        <p class="text-muted mb-0">
                            View the complete details of the selected Relationship report.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.relationship.list') }}" class="btn btn-primary">
                            <i data-lucide="table" class="me-1" style="width:16px;height:16px;"></i>
                            Report Lists
                        </a>
                    </div>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection
