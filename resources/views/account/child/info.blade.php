@php
    $meta = [
        'title' => 'View Child Report',
        'description' => 'View Child Report details in the Numerology software dashboard.',
        'keywords' => 'child report, numerology, view child report, child numerology, dashboard',
    ];
@endphp

@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Child Report" :lists="[
        route('account.child.list') => 'Child Report',
        '' => 'View Report',
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title mb-1">Report Detail</h2>
                        <p class="text-muted mb-0">
                            View the complete details of the selected Loshu Grid report.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.child.list') }}" class="btn btn-primary">
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
