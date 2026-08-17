@php
    $meta = [
        'title' => 'Role Management',
        'description' =>
            'Manage user roles, assign responsibilities, and control access permissions within the numerology software administration panel.',
        'keywords' =>
            'role management, user roles, access control, admin roles, numerology software, user permissions, role administration',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Roles" :lists="[
        route('account.roles.index') => 'Roles List',
        '' => 'Add Role',
    ]" />


    <div class="row">
        <div class="col-xxl-12">
            <div data-table data-table-rows-per-page="8" class="card">
                <div class="card-header border-light justify-content-between">
                    <div>
                        <h2 class="card-title d-block">Add Role</h2>
                        <p class="text-muted mb-0">
                            Create a new role and assign the appropriate permissions.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.roles.index') }}" class="btn btn-danger">
                            <i data-lucide="arrow-left" class="me-1" style="width:16px;height:16px;"></i>
                            Back
                        </a>
                    </div>
                </div>
                <form method="post" action="{{ route('account.roles.store') }}" class="card-body border-top">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="lastname" class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Enter Role Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="lastname" class="form-label">Role Status <span class="text-danger">*</span></label>
                            <select name="is_publish" id="is_publish" class="form-select @error('is_publish') is-invalid @enderror">
                                <option value="1" @selected(old('is_publish') == 1)>Publish</option>
                                <option value="0" @selected(old('is_publish') == 0)>Unpublish</option>
                            </select>
                            @error('is_publish')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="lastname" class="form-label">Role Permissions</label>
                            <select name="permissions[]" class="select2 form-control select2-multiple" data-toggle="select2"
                                multiple="multiple" data-placeholder="Choose ...">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}" @selected(in_array($permission->name, old('permissions',[])))>
                                        {{ $permission->name }}</option>
                                @endforeach
                            </select>
                            @error('permissions')
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
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}" />
@endpush
@push('js')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-select2.js') }}"></script>
@endpush