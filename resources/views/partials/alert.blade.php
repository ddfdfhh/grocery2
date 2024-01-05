@if(\Session::has('success'))
<div class="alert alert-success alert-has-icon">
    <div class="alert-icon"><i class="far fa-check"></i></div>
    <div class="alert-body">
    <div class="alert-title">Success</div>
   {{\Session::get('success')}}
    </div>
</div>
@endif
\@if(\Session::has('error'))
<div class="alert alert-danger alert-has-icon">
    <div class="alert-icon"><i class="far fa-exclamation-triangle"></i></div>
    <div class="alert-body">
    <div class="alert-title">Oops</div>
   {{\Session::get('error')}}
    </div>
</div>
@endif
@if ($errors->any())
<div class="alert alert-danger alert-has-icon">
    <div class="alert-icon"><i class="far fa-exclamation-triangle"></i></div>
    <div class="alert-body">
    <div class="alert-title">Form Errors</div>
     @foreach ($errors->all() as $error)
        <div>{{$loop->index+1}}.  {{$error}}</div>
     @endforeach
     </div>
</div>
 @endif

