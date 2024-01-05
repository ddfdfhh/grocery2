@props(['path','name','id','table','folderName','inline'])
@php
$deleteUrl='';
if(!empty($id)){
    $u=route('deleteTableFile');
$onclick="deleteFileFromTable('".$id."','".$table."','".$folderName."','".$u."')";

}
$inline=!isset($inline)?true:$inline;
@endphp
@if(!empty($id))
            @if(str_contains($name,'.jpg') || str_contains($name,'.png') || str_contains($name,'.gif') || str_contains($name,'.jpeg'))
                <div class="image_preview_box" style="display:{{$inline=='true'?'inline-block':'block'}}" id="img_div-{{$id}}">
                <i class="remove bx bx-trash" @if($id) onclick="{{$onclick}}" @endif></i> 
                 <a href="{{ $path }}" data-lightbox="image-1">
                <img style="width:100px;height:100px;margin:10px" src="{{$path}}" /></a>
                </div>
            @else
            @php 
            $type=null;
            if(str_contains($name,'.pdf'))
            $type='pdf';
            elseif(str_contains($name,'.csv'))
            $type='pdf';
            elseif(str_contains($name,'.docx'))
            $type='pdf';
            elseif(str_contains($name,'.mp3') || str_contains($name,'.ogg') || str_contains($name,'.wma') || str_contains($name,'.wav') || str_contains($name,'.mp4') || str_contains($name,'.aac'))
            $type='audio';
            elseif(str_contains($name,'.mp4') || str_contains($name,'.avi') || str_contains($name,'.flv') || str_contains($name,'.wma') || str_contains($name,'.webm'))
            $type='video';
            elseif(str_contains($name,'.excel'))
            $type='xl';

            $path=asset('file_icons/'.$type.'.png');
            @endphp
                <div class="image_preview_box" id="img_div-{{$id}}" style="display:{{$inline=='true'?'inline-block':'block'}}">
                        <i class="remove bx bx-trash" @if($id) onclick="{{$onclick}}" @endif></i> 
                        <a href="{{$path}}" data-lightbox="image-1">
                        <img style="width:100px;height:100px;margin:10px" src="{{$path}}" />
                        </a>
                        </div>
            @endif
@else
     @if(str_contains($name,'.jpg') || str_contains($name,'.png') || str_contains($name,'.gif')
      || str_contains($name,'.jpeg'))
                <a href="{{$path}}" data-lightbox="image-1">
                <img style="width:100px;height:100px;margin:10px" src="{{$path}}" />
              </a>
            @else
            @php 
            $type=null;
            if(str_contains($name,'.pdf'))
            $type='pdf';
            elseif(str_contains($name,'.csv'))
            $type='pdf';
            elseif(str_contains($name,'.docx'))
            $type='pdf';
            elseif(str_contains($name,'.mp3') || str_contains($name,'.ogg') || str_contains($name,'.wma') || str_contains($name,'.wav') || str_contains($name,'.mp4') || str_contains($name,'.aac'))
            $type='audio';
            elseif(str_contains($name,'.mp4') || str_contains($name,'.avi') || str_contains($name,'.flv') || str_contains($name,'.wma') || str_contains($name,'.webm'))
            $type='video';
            elseif(str_contains($name,'.excel'))
            $type='xl';

            $path=asset('file_icons/'.$type.'.png');
            @endphp
              
                         <a href="{{$path}}" data-lightbox="image-1">
                        <img style="width:100px;height:100px;margin:10px" src="{{$path}}" />
                        </a>
                      
            @endif
@endif