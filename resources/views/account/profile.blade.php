@php
    $meta = [
        'title' => 'My Profile',
        'description' =>
            'Manage your profile information, personal details, account preferences, and settings for a personalized numerology experience.',
        'keywords' =>
            'numerology profile, my profile, account settings, personal details, numerology account, profile management',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Profile" :lists="[
        '' => 'Profile',
    ]" />


    
        <div class="row">
            <x-account.profile.profile-card />

            <div class="col-xl-8">
                <div class="card">
                    <x-account.profile.profile-tabs activeTab="profile" />

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <form method="post" action="{{ route('account.profile.update') }}"
                                    enctype="multipart/form-data" id="profileForm">
                                    @csrf
                                    @method('PATCH')
                                    <!-- Personal Info -->
                                    <h5
                                        class="mb-3 text-uppercase bg-light-subtle p-1 border-dashed border rounded border-light d-flex justify-content-center align-items-center gap-1">
                                        <i data-lucide="circle-user-round" class="fs-lg"></i>
                                        Personal Info
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="firstname" class="form-label">Full Name <span
                                                        class="text-danger">*</span> </label>
                                                <input type="text" value="{{ old('full_name', $user->name ?? '') }}"
                                                    class="form-control @error('full_name') is-invalid @enderror "
                                                    id="full_name" name="full_name" placeholder="Enter full name" />
                                                @error('full_name')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number <span
                                                        class="text-danger">*</span></label>

                                                <div class="input-group">
                                                    <select name="phone_code" id="phone_code"
                                                        class="form-select @error('phone_code') is-invalid @enderror"
                                                        style="max-width: 130px;">
                                                        @foreach (getAllCountries() as $country)
                                                            <option value="{{ $country->id }}"
                                                                {{ old('phone_code', $user->phone_code ?? '') ?? defaultCountry()['id'] == $country->id ? 'selected' : '' }}>
                                                                {{ $country->iso ?? $country->name }}
                                                                (+{{ $country->phonecode }})
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <input type="text" value="{{ old('phone', $user->phone ?? '') }}"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone" name="phone" placeholder="9898989898" />
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
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="useremail" class="form-label">Email Address <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" readonly style="cursor: no-drop;background:#f6f6f6"
                                                    value="{{ old('email', $user->email ?? '') }}"
                                                    class="form-control  @error('email') is-invalid @enderror"
                                                    id="useremail" name="email" placeholder="Enter email" />
                                                <span class="form-text fs-xs fst-italic text-muted">
                                                    This is your registered email address and cannot be changed.
                                                </span>
                                                @error('email')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-4">
                                                <label for="profilephoto" class="form-label">Profile Photo</label>
                                                <input class="form-control @error('profile') is-invalid @enderror"
                                                    type="file" id="profilephoto" name="profile" />
                                                @error('profile')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <x-image-preview class="img-fluid rounded border" id="imagePreview"
                                                width="200" imagepath="users" :image="$user->avatar ?? ''" />
                                        </div>
                                    </div>



                                    <!-- Address Info -->
                                    <h5
                                        class="mb-3 text-uppercase bg-light-subtle p-1 border-dashed border rounded border-light d-flex justify-content-center align-items-center gap-1">
                                        <i data-lucide="map-pin" class="fs-lg"></i>
                                        Address Info
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="address-line1" class="form-label">Address</label>
                                                <input type="text" name="address"
                                                    class="form-control  @error('address') is-invalid @enderror"
                                                    id="address-line1"
                                                    value="{{ old('address', $user->address->address ?? '') }}"
                                                    placeholder="Street, Apartment, Unit, etc." />
                                                @error('address')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <select name="country"
                                                    class="form-select @error('country') is-invalid @enderror"
                                                    id="country">
                                                    <option value="">Choose One</option>
                                                    @foreach (getAllCountries() as $country)
                                                        <option value="{{ $country->id }}" @selected(old('country', $user->address->country_id ?? '') == $country->id)>
                                                            {{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="state" class="form-label">State / Province</label>
                                                <select name="state"
                                                    data-selected="{{ old('state', $user->address?->state_id) }}"
                                                    class="form-select @error('state') is-invalid @enderror"
                                                    id="state">
                                                    <option value="">Choose One</option>
                                                </select>
                                                @error('state')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <select name="city"
                                                    data-selected="{{ old('city', $user->address?->city_id) }}"
                                                    class="form-select @error('city') is-invalid @enderror"
                                                    id="city">
                                                    <option value="">Choose One</option>
                                                </select>
                                                @error('city')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="zipcode" class="form-label">Postal / ZIP Code</label>
                                                <input type="text" name="zipcode"
                                                    value="{{ old('zipcode', $user->address->zipcode ?? '') }}"
                                                    class="form-control @error('zipcode') is-invalid @enderror"
                                                    id="zipcode" placeholder="Postal Code" />
                                                @error('zipcode')
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Submit -->
                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-success" data-loading-button
                                            data-loader-text="Updating...">
                                            <span id="loaderBtn" class="spinner-border spinner-border-sm me-1 d-none"
                                                role="status" aria-hidden="true" data-spinner>
                                            </span>

                                            <span id="saveBtn" data-button-text>
                                                Save Changes
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end settings Data-->
                        </div>
                        <!-- end tab content-->
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card-->
            </div>
            <!-- end col-->
        </div>
        <!-- end row-->
    
@endsection
@push('js')
    <script>
        const imageInput = document.getElementById('profilephoto');
        const imagePreview = document.getElementById('imagePreview');
        const stateListsUrl = "{{ route('states') }}";
        const citiesListsUrl = "{{ route('cities') }}";
    </script>
@endpush
