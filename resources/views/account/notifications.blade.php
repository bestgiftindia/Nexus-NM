@php
    $meta = [
        'title' => 'Notifications',
        'description' =>
            'View and manage your latest notifications, account updates, numerology report alerts, and other important activity related to your numerology account.',
        'keywords' =>
            'numerology notifications, account notifications, numerology alerts, report updates, account activity, notification management',
    ];
@endphp
@extends('layouts.account')

@section('content')
    <x-account.breadcrumb pageTitle="Notifications" :lists="[
        route('account.profile.index') => 'Profile',
        '' => 'Notifications',
    ]" />


   
        <div class="row">
            <x-account.profile.profile-card />

            <div class="col-xl-8">
                <div class="card">
                    <x-account.profile.profile-tabs activeTab="notifications" />

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
@endsection
