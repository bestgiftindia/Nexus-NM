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
    <x-account.breadcrumb pageTitle="Social Accounts" :lists="[
        route('account.profile.index') => 'Profile',
        '' => 'Social Accounts',
    ]" />


    
        <div class="row">
            <x-account.profile.profile-card />


            <div class="col-xl-8">
                <div class="card">
                    <x-account.profile.profile-tabs activeTab="social" />

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form method="post" action="{{ route('account.socialMedia.update') }}" id="socialForm">
                                    @csrf
                                    <!-- Social -->
                                    <h5
                                        class="mb-3 text-uppercase bg-light-subtle p-1 border-dashed border rounded border-light d-flex justify-content-center align-items-center gap-1">
                                        <i data-lucide="earth" class="fs-lg"></i>
                                        Social Accounts
                                    </h5>
                                    <div class="row g-3">
                                        @foreach ($lists as $list)
                                            <div class="col-md-6">
                                                <label for="social-{{ $list->id }}"
                                                    class="form-label">{{ ucwords(str_replace("_"," ",$list->social_media_icon)) }}</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">
                                                        <x-dynamic-component :component="'account.svg.' . $list->social_media_icon" />
                                                    </span>
                                                    <input type="text" class="form-control"
                                                        id="social-{{ $list->id }}"
                                                        name="social_media[{{ $list->id }}]"
                                                        value="{{ old('social_media.' . $list->id, $list->link ?? '') }}"
                                                        placeholder="{{ ucwords(str_replace("_"," ",$list->social_media_icon)) }} URL" />
                                                </div>
                                                @error('social_media.' . $list->id)
                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endforeach
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
