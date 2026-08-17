@php
    $meta = [
        'title' => 'Permission Management',
        'description' =>
            'Manage system permissions, define user access levels, and control role-based authorization within the numerology software administration panel.',
        'keywords' =>
            'permission management, system permissions, role permissions, access control, user authorization, numerology software, admin permissions',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Permissions" :lists="[
        route('account.permissions.index') => 'Permissions List',
        '' => 'Add Permission',
    ]" />


    <div class="row">
        <div class="col-xxl-12">
            <div data-table data-table-rows-per-page="8" class="card">
                <div class="card-header border-light justify-content-between">
                    <div>
                        <h2 class="card-title d-block">Add Permission</h2>
                        <p class="text-muted mb-0">
                            Create a new permission to define and control access within the system.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.permissions.index') }}" class="btn btn-danger">
                            <i data-lucide="arrow-left" class="me-1" style="width:16px;height:16px;"></i>
                            Back
                        </a>
                    </div>
                </div>
                <form method="post" action="{{ route('account.permissions.store') }}" class="card-body border-top">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-10">
                            <label for="lastname" class="form-label">Permission Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Enter Permission Name">
                            <small class="text-secondary">(NOTE: Please enter the permission name in the format <b>user-lists</b>. If you
                                enter it in a different format, it will be automatically converted to this format.)</small>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="lastname" class="form-label">Permission Status <span class="text-danger">*</span>
                            </label>
                            <select name="is_publish" id="is_publish"
                                class="form-select @error('is_publish') is-invalid @enderror ">
                                <option value="1" @selected(old('is_publish') == 1)>Publish</option>
                                <option value="0" @selected(old('is_publish') == 0)>Unpublish</option>
                            </select>
                            @error('is_publish')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success" data-loading-button data-loader-text="Submitting...">
                            <span id="loaderBtn" class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                aria-hidden="true" data-spinner>
                            </span>

                            <span id="saveBtn" data-button-text>
                                Submit & Save
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
