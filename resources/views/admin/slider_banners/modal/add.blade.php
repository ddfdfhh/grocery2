{!! Form::open()->route($plural_lowercase . '.store')->id(strtolower($module) . '_form')->multipart() !!}

   <div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="card-text">
                    <x-imageform :data="$data" column='1' />
                </div>
            </div>
        </div>
    </div>
   </div>


<div class="row mt-2">
    <div class="col-sm-12 " style="text-align:right">
        @php
            $r = 'Submit';
        @endphp
        {!! Form::submit($r)->id(strtolower($module) . '_btn')->primary() !!}
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Close</button>
    </div>
</div>
{!! Form::close() !!}
