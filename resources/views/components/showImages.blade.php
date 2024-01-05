@props(['inline','row','fieldName','storageFolder','tableName'])
@php 
/**t is filedname **/
$image_list=$row->{$fieldName};

$storage_folder=$storageFolder;
$inline=!isset($inline)?true:$inline;
@endphp


    @if (count($image_list) > 0)
        @foreach ($image_list as $image)
            @php
                $path = storage_path('app/public/' . $storage_folder . '/' . $image->name);
                if (!\File::exists($path)) {
                    $path = null;
                } else {
                    $path = asset('storage/' . $storage_folder . '/' . $image->name);
                }
            @endphp
            @if ($path)
               
                <x-image :inline="$inline" :name="$image->name" :path="$path" :id="$image->id" :table="$tableName" :folderName="$storageFolder" />
                
            @endif
        @endforeach
    @endif

