 {!! Form::open()->put()->route($plural_lowercase . '.update', [\Str::singular($plural_lowercase) => $model->id])->id(strtolower($module) . '_form') !!}
@if ($has_image && count($image_field_names) > 0)
    <div class="row">
        @if ($show_crud_in_modal)
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        <div class="card-text">
                            <x-forms :data="$data" column='1' />
                            @if (count($repeating_group_inputs) > 0)
                              @foreach ($repeating_group_inputs as $grp)
                                        <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}"
                                   :disableButtons="$grp['disable_buttons']"         :hide="$grp['hide']" :index="$loop->index" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                    @endforeach
                            @endif



                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">

                    <div class="card-body">
                        <div class="card-text">
                            <x-imageform :data="$data" column='1' />
                        </div>
                    </div>
                </div>
            </div>
        @else
            <x-forms :data="$data" column='1' />
            <x-imageform :data="$data" column='1' />
            @if (count($repeating_group_inputs) > 0)
                 @foreach ($repeating_group_inputs as $grp)
                                        <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}"
                                            :disableButtons="$grp['disable_buttons']"      :hide="$grp['hide']"  :index="$loop->index" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                    @endforeach
            @endif
        @endif
    </div>
@else
    <x-forms :data="$data" column='1' />
    @if (count($repeating_group_inputs) > 0)
         @foreach ($repeating_group_inputs as $grp)
                                        <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}"
                                            :disableButtons="$grp['disable_buttons']"        :hide="$grp['hide']"     :index="$loop->index" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                    @endforeach
    @endif



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