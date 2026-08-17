@php
    $meta = [
        'title' => 'Edit Child Report',
        'description' => 'Update Child Report records in the Numerology software dashboard.',
        'keywords' => 'edit child report, numerology, child report, child numerology, dashboard',
    ];
@endphp

@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Manage Child Report" :lists="[
        route('account.child.list') => 'Child Report',
        '' => 'Edit Report',
    ]" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-light d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-title mb-1">Edit Report</h2>
                        <p class="text-muted mb-0">
                            Update the required details to modify the Child Report record.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('account.child.list') }}" class="btn btn-primary">
                            <i data-lucide="table" class="me-1" style="width:16px;height:16px;"></i>
                            Report List
                        </a>
                    </div>
                </div>

                <form method="post" action="{{ route('account.child.update', ['child' => $child->id]) }}"
                    class="card-body border-top">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control only-string @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name"
                                value="{{ old('first_name', $child->first_name ?? '') }}"
                                placeholder="Enter First Name">
                            @error('first_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="middle_name" class="form-label">Middle Name </label>
                            <input type="text"
                                class="form-control only-string @error('middle_name') is-invalid @enderror" id="middle_name"
                                name="middle_name" value="{{ old('middle_name', $child->middle_name ?? '') }}"
                                placeholder="Enter Middle Name">
                            @error('middle_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name </label>
                            <input type="text" class="form-control only-string @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" value="{{ old('last_name', $child->last_name ?? '') }}"
                                placeholder="Enter Last Name">
                            @error('last_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="dob" class="form-label">Date Of Birth <span
                                    class="text-danger">*</span></label>
                            <input type="date" onclick="showPicker(this)"
                                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                class="form-control @error('dob') is-invalid @enderror" id="dob"
                                name="dob" value="{{ old('dob', $child->dob ?? '') }}"
                                placeholder="Enter Date Of Birth">
                            @error('dob')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="text" class="form-control only-email @error('name') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $child->email ?? '') }}"
                                placeholder="Enter Email Address">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">

                            <label for="phone" class="form-label">Phone Number </label>

                            <div class="input-group">
                                <select name="phone_code" id="phone_code"
                                    class="form-select @error('phone_code') is-invalid @enderror" style="max-width: 130px;">
                                    @foreach (getAllCountries() as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('phone_code', $child->phone_code ?? '') ?? defaultCountry()['id'] == $country->id ? 'selected' : '' }}>
                                            {{ $country->iso ?? $country->name }}
                                            (+{{ $country->phonecode }})
                                        </option>
                                    @endforeach
                                </select>

                                <input type="text" value="{{ old('mobile_number', $child->mobile_number ?? '') }}"
                                    class="form-control only-number @error('mobile_number') is-invalid @enderror" id="mobile_number"
                                    name="mobile_number" placeholder="9898989898" />
                            </div>

                            @error('phone_code')
                                <span class="text-danger  mt-1">
                                    {{ $message }}
                                </span>
                            @enderror

                            @error('mobile_number')
                                <span class="text-danger  mt-1">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                        <div class="col-md-4">
                            <label for="role" class="form-label">
                                Gender <span class="text-danger">*</span>
                            </label>

                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Choose Gender</option>

                                @foreach (getGenders() as $gend)
                                    <option value="{{ $gend['id'] }}" @selected(old('gender', $child->gender ?? '') == $gend['id'])>
                                        {{ $gend['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-end mt-4">

                        <button type="submit" class="btn btn-success" data-loading-button data-loader-text="Updating...">

                            <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"
                                data-spinner>
                            </span>

                            <span data-button-text>
                                <i data-lucide="save" class="me-1" style="width:16px;height:16px;"></i>
                                Update Report
                            </span>
                        </button>

                        <a href="{{ route('account.loshugrid.lists') }}" class="btn btn-outline-secondary">
                            <i data-lucide="arrow-left" class="me-1" style="width:16px;height:16px;"></i>
                            Cancel
                        </a>

                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
