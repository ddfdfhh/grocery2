{!! Form::open()->route($plural_lowercase . '.store')->id(strtolower($module) . '_form')->multipart() !!}
@if ($has_image && count($image_field_names) > 0)

    <div class="row">
        @if ($show_crud_in_modal)

           

            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <div class="card-text">
                            <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Category <span class="text-danger">*</span></label>
                
                                <select id="category_id" class="form-select" multiple id="choices-category-input" name="categories[]"
                                     onChange="showProductsonMultiCategorySelect()">
                                    {!! $category_options !!}
                                </select>
                
                            </div>
                            {{-- <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Border Color</label>
                
                               <input type="color" name="border_color" class="form-control">
                
                            </div> --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Item Background Color </label>
                
                               <input type="color" name="background_color" class="form-control">
                
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Section Background Color </label>
                
                               <input type="color" name="section_background_color" class="form-control">
                
                            </div>
                        </div>
                            <x-forms :data="$data" column='2' />
                            @if (count($repeating_group_inputs) > 0)
                                @foreach ($repeating_group_inputs as $grp)
                                    <x-repeatable :data="$grp['inputs']" :label="$grp['label']" values="" :index="$loop->index"
                                        :hide="$grp['hide']" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                @endforeach
                            @endif
                           
                            <x-imageform :data="$data" column='2' />


                        </div>
                    </div>
                </div>
            </div>
           
        @else
            <x-forms :data="$data" column='1' />
            <x-imageform :data="$data" column='1' />
            @if (count($repeating_group_inputs) > 0)
                @foreach ($repeating_group_inputs as $grp)
                    <x-repeatable :data="$grp['inputs']" :label="$grp['label']" values="" :index="$loop->index"
                        :hide="$grp['hide']" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                @endforeach
            @endif
        @endif
    </div>
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
