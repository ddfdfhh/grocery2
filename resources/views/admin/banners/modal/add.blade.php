{!! Form::open()->route($plural_lowercase . '.store')->id(strtolower($module) . '_form')->multipart() !!}

    <x-forms :data="$data" column='1' />
    <div class="col-md-12">
        
        <x-imageform :data="$data" column='1' />
   

</div>
    @if (count($repeating_group_inputs) > 0)
       @foreach ($repeating_group_inputs as $grp)
                      <x-repeatable :data="$grp['inputs']" :label="$grp['label']" values="" :index="$loop->index"
                                            :hide="$grp['hide']" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                @endforeach
    @endif



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
