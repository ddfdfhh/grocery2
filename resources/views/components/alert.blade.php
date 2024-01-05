@if($errors->any())
    {!! implode('', $errors->all('<div class="alert alert-danger">&#9888;&nbsp;&nbsp;:message</div>')) !!}
@endif
@if(\Session::has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
          <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-desktop align-top me-2"></i>Success!</h6>
          <span>{{\Session::get('success')}}</span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
 @endif
@if(\Session::has('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
          <h6 class="alert-heading mb-1"><i class="bx bx-xs bx-error align-top me-2"></i></h6>
          <span>{{\Session::get('error')}}</span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
 @endif