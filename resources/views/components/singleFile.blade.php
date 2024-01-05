@props(['fileName', 'modelName', 'folderName', 'fieldName', 'rowid'])
@php
    $deleteUrl = '';

    $u = route('deleteFileSelf');
    $onclick = "deleteFileSelf('" . $fieldName . "','" . $modelName . "','" . $folderName . "','" . $fieldName . "','" . $rowid . "')";

@endphp
@if (str_contains($fileName, '.jpg') ||
        str_contains($fileName, '.png') ||
        str_contains($fileName, '.gif') ||
        str_contains($fileName, '.jpeg') ||
        str_contains($fileName, '.webp') ||
        str_contains($fileName, '.avif'))
    @php

        $path = storage_path('app/public/' . $folderName . '/' . $fileName);
        if (!\File::exists($path)) {
            $path = null;
        } else {
            $path = asset('storage/' . $folderName . '/' . $fileName);
        }
    @endphp
    @if ($path)
        <div class="image_preview_box" id="img_div">
            <i class="remove bx bx-trash" @if ($path) onclick="{{ $onclick }}" @endif></i>
            <a href="{{ $path }}" data-lightbox="image-1">
                <img style="width:100px;height:100px;margin:10px" src="{{ $path }}" /></a>
        </div>
    @endif
@else
    @php

        $path = storage_path('app/public/' . $folderName . '/' . $fileName);
        if (!\File::exists($path)) {
            $path = null;
        } else {
            $path = asset('storage/' . $folderName . '/' . $fileName);
        }
    @endphp
    @if ($path)
        <br>
        <i class="bx bx-download"></i> <a href="{{ $path }}" download>{{ $fieldName }}</a>
    @endif
@endif
