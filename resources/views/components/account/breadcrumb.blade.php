@props(['pageTitle', 'lists'])
<div class="page-title-head d-flex align-items-center">
    <div class="flex-grow-1">
        <h4 class="page-main-title m-0">{{ $pageTitle ?? '' }}</h4>
    </div>

    <div class="text-end">
        <ol class="breadcrumb m-0 py-0">
            <li class="breadcrumb-item"><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
            @foreach ($lists as $route => $listName)
                @if (!empty($route) && $route != '#')
                    <li class="breadcrumb-item"><a href="{{ $route }}">{{ $listName }}</a></li>
                @else
                    <li class="breadcrumb-item active">{{ $listName }}</li>
                @endif
            @endforeach
        </ol>
    </div>
</div>
