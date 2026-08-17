@php
    $width = $attributes['width'] ?? $widthName;
    if (\Agent::isPhone() && ($options['mobile_width'] ?? 0) > 0) {
        $width = $options['mobile_width'] ?? 0;
    }

    $pathNameArr = explode('_', $pathName);
    $pathName = implode('/', $pathNameArr);

    $fullPath = public_path('storage/' . $pathName . '/original/' . $imageName);

@endphp

@if (file_exists($fullPath) && !empty($imageName))
    <img src="{{ route('image.resize', [
        'filename' => $imageName,
        'path' => str_replace('/', '_', $pathName),
        'folder' => 'original',
        'width' => $width,
    ]) }}"
        {{ $attributes->merge() }} fetchpriority="{{ $fetchpriority }}" loading="{{ $lazyName }}">
@else
    <img src="{{ asset('assets/img/no-image.jpg') }}" {{ $attributes->merge() }} fetchpriority="{{ $fetchpriority }}"
        loading="{{ $lazyName }}">
@endif