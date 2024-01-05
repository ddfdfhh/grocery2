{!! Form::open()->route($plural_lowercase . '.store')->id(strtolower($module) . '_form')->multipart() !!}
<div class="row">
    <div class="col-md-12 mb-4">
        <label class="form-label" for="product-title-input">Category <span class="text-danger">*</span></label>

        <select id="category_id" class="form-select" multiple id="choices-category-input" name="category_id[]" required
            onChange="showProductsonMultiCategorySelect()">
            {!! $category_options !!}
        </select>

    </div>
</div>
    <x-forms :data="$data" column='2' />
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
