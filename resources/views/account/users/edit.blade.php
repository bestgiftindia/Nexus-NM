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
        route('account.users.index') => 'Users List',
        '' => 'Edit User',
    ]" />


    <div class="row">
        <div class="col-xxl-12">
            <div data-table data-table-rows-per-page="8" class="card">
                <div class="card-header border-light justify-content-between">
                    <div>
                        <h2 class="card-title d-block">Edit User</h2>
                        <p class="text-muted mb-0">
                            Update the user's account information, role, status, and permissions.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.users.index') }}" class="btn btn-danger">
                            <i data-lucide="arrow-left" class="me-1" style="width:16px;height:16px;"></i>
                            Back
                        </a>
                    </div>
                </div>
                <form method="post" action="{{ route('account.users.update', ['user' => $user->id]) }}"
                    enctype="multipart/form-data" class="card-body border-top">
                    @csrf
                    @method('patch')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}" placeholder="Enter Full Name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" placeholder="Enter Email Address">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">

                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>

                            <div class="input-group">
                                <select name="phone_code" id="phone_code"
                                    class="form-select @error('phone_code') is-invalid @enderror" style="max-width: 130px;">
                                    @foreach (getAllCountries() as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('phone_code', $user->phone_code) ?? defaultCountry()['id'] == $country->id ? 'selected' : '' }}>
                                            {{ $country->iso ?? $country->name }}
                                            (+{{ $country->phonecode }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="text" value="{{ old('phone', $user->phone ?? '') }}"
                                    class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                    placeholder="9898989898" />
                            </div>

                            @error('phone_code')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                            @error('phone')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-4">
                            <label for="lastname" class="form-label">Account Status <span
                                    class="text-danger">*</span></label>
                            <select name="is_publish" id="is_publish"
                                class="form-select @error('is_publish') is-invalid @enderror">
                                <option value="1" @selected(old('is_publish', $user->is_publish) == 1)>Publish</option>
                                <option value="0" @selected(old('is_publish', $user->is_publish) == 0)>Unpublish</option>
                            </select>
                            @error('is_publish')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="role" class="form-label">
                                Account Role <span class="text-danger">*</span>
                            </label>

                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">Choose Role</option>

                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role', $user->roles->first()?->id) == $role->id)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="account_id" class="form-label">Account ID <span class="text-danger">*</span></label>
                            <input type="text" readonly style="cursor: no-drop"
                                class="form-control @error('account_id') is-invalid @enderror" id="account_id"
                                name="account_id" value="{{ !empty($user->user_id) ? $user->user_id : generateUserId() }}"
                                placeholder="Enter Account id">
                            @error('account_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="lastname" class="form-label">User Permissions</label>
                            <select name="permissions[]" class="select2 form-control select2-multiple" data-toggle="select2"
                                multiple="multiple" data-placeholder="Choose ...">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}" @selected(in_array($permission->name, old('permissions',$user->getDirectPermissions()->pluck('name')->toArray())))>
                                        {{ $permission->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted ">(NOTE: If you leave the User Permissions section blank, the user will
                                automatically inherit all permissions assigned to their selected role.)</small>
                            @error('permissions')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-3">

                            <label for="profilephoto" class="form-label">Profile Photo</label>
                            <input class="form-control @error('profile') is-invalid @enderror" type="file"
                                id="profilephoto" name="profile" />
                            @error('profile')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                        <div class="col-md-1">
                            <x-image-preview class="img-fluid w-100 rounded border" id="imagePreview" width="200"
                                imagepath="users" :image="$user->avatar ?? ''" />
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success" data-loading-button
                            data-loader-text="Updating...">
                            <span id="loaderBtn" class="spinner-border spinner-border-sm me-1 d-none" role="status"
                                aria-hidden="true" data-spinner>
                            </span>

                            <span id="saveBtn" data-button-text>
                                Submit & Update
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
    <script>
        const imageInput = document.getElementById('profilephoto');
        const imagePreview = document.getElementById('imagePreview');
    </script>
@endpush
