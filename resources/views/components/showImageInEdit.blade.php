@props(['default'])

@if (is_null(json_decode($default)))
    @if (str_contains($default, '.jpg') ||
            str_contains($default, '.png') ||
            str_contains($default, '.gif') ||
            str_contains($default, '.jpeg') ||
            str_contains($default, '.webp') ||
            str_contains($default, '.avif'))
        @php

            $path = storage_path('app/public/' . $default);
            if (!\File::exists($path)) {
                $path = null;
            } else {
                $path = asset('storage/' . $default);
            }
        @endphp
        @if ($path)
            <a href="{{ $path }}" data-lightbox="image-1">
                <img style="width:100px;height:100px;margin:10px" src="{{ $path }}" /></a>
        @endif
    @else
        @if (str_contains($default, '.pdf') ||
                str_contains($default, '.docx') ||
                str_contains($default, '.docs') )
                

            @php

                $path = storage_path('app/public/' . $default);
                if (!\File::exists($path)) {
                    $path = null;
                } else {
                    $path = asset('storage/' . $default);
                }
            @endphp
            @if ($path && $default)
                <br>
                <i class="bx bx-download"></i> <a href="{{ $path }}" download>{{ $default }}</a>
            @endif
        @endif
    @endif
@else
    @php
        $default = json_decode($default, true);
        //default=['id'=>'1','name'=>'image2.jpeg','folder'=>'users','table'=>'ss']
    @endphp
    @if (count($default) > 0)

        @foreach ($default as $item)
            @php
                $path = storage_path('app/public/' . $item['folder'] . '/' . $item['name']);
                //  dd($path);
                if (!\File::exists($path)) {
                    $path = null;
                } else {
                    $path = asset('storage/' . $item['folder'] . '/' . $item['name']);
                }
            @endphp
            @if ($path)
                <x-image :name="$item['name']" :path="$path" :id="$item['id']" :folderName="$item['folder']" :table="$item['table']" />
            @endif
        @endforeach
    @endif
@endif
