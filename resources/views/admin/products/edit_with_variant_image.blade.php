@extends('layouts.admin.app')
@section('content')
    <style>
        .bootstrap-tagsinput {
            width: 100% !important;
        }

        .accordion-button {
            background: white !important;
            border: 1px solid #d5cccc !important;
        }

        #var_c1 {
            display: none;
        }

        .image_place .del_ic {
            position: absolute;
            top: -15px;
            right: -3px;
            z-index: 9999;
            cursor: pointer;
        }

        .image_place {
            position: relative;
            width: 80px;
            height: 80px;
            margin-top: 10px;
        }
    </style>
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Create Product</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                            <li class="breadcrumb-item active">Create Product</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        {!! Form::open()->put()->route($plural_lowercase . '.update', [\Str::singular($plural_lowercase) => $model->id])->id(strtolower($module) . '_form')->attrs(['data-module' => $module]) !!}

        <div class="row">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <input type="hidden" value="{{ $model->id }}" id="model_id" />
                                <label class="form-label" for="product-title-input">Product Title <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="name" value="{{ $model->name }}"
                                    id="product-title-input" value="" placeholder="Enter product title" required>

                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Category <span
                                        class="text-danger">*</span></label>

                                <select class="form-select" id="choices-category-input" name="category_id" required>
                                    {!! $category_options !!}
                                </select>

                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Brand <span
                                            class="text-danger">*</span></label>

                                    <select class="form-control" name="brand_id" required>
                                        <option value="">Select Brands</option>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}"
                                                @if ($model->brand_id == $b->id) selected @endif>{{ $b->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Sell By Unit <span class="text-danger">*</span></label>
                                    <select class="form-control no-select2" name="unit" required>
                                        <option value="">Select Unit</option>
                                        <option value="Pcs" @if ($model->unit == 'Pcs') selected @endif>Pcs
                                        </option>
                                        <option value="kg" @if ($model->unit == 'kg') selected @endif>kg</option>
                                        <option value="g" @if ($model->unit == 'g') selected @endif>g</option>
                                        <option value="mg" @if ($model->unit == 'mg') selected @endif>mg</option>
                                        <option value="l" @if ($model->unit == 'l') selected @endif>l</option>
                                        <option value="ml" @if ($model->unit == 'ml') selected @endif>ml</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Price Per Unit<span
                                            class="text-danger">*</span></label>


                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" id="produect-price-addon">{{ getCurrency() }}</span>
                                        <input required type="number" class="form-control" value="{{ $model->price }}"
                                            name="price" id="product-price-input" placeholder="Enter price"
                                            aria-label="Price" aria-describedby="product-price-addon">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Sale Price <span
                                            class="text-danger">*</span></label>

                                    <div class="input-group has-validation mb-3">
                                        <span class="input-group-text" id="pr3oduct-price-addon">{{ getCurrency() }}</span>
                                        <input type="number" class="form-control" name="sale_price"
                                            id="product-price-input" placeholder="Enter sale price" aria-label="Price"
                                            aria-describedby="product-price-addon" value="{{ $model->sale_price }}">

                                    </div>

                                </div>
                            </div>
                            {{-- <div class="col-md-6
                                            mb-3">
                                <div class="form-group ">
                                    <label class="form-label" for="product-title-input">Discount Type</label>

                                    <select class="form-control no-select2" name="discount_type">
                                        <option value="">Select Discount Type</option>
                                        <option value="Flat" @if ($model->discount_type == 'Flat') selected @endif>
                                            Flat</option>
                                        <option value="Percent" @if ($model->discount_type == 'Percent') selected @endif>
                                            Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Discount</label>

                                    <input type="number" class="form-control" name="discount" id="product-price-input"
                                        placeholder="Enter discount value"
                                        value="{{ $model->discount }} aria-label="Price"
                                        aria-describedby="product-price-addon">

                                </div>
                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Package Weight</label>
                                    <div class="d-flex">
                                        <input type="text" class="form-control"
                                            style="border-right:0!important; border-top-right-radius:0;border-bottom-right-radius:0;"
                                            name="package_weight" id="product-price-input" placeholder="Ex. Kg,g,"
                                            aria-label="Price" aria-describedby="product-price-addon"
                                            value="{{ $model->package_weight }}">
                                        <select class="form-select no-select2" name="package_weight_unit"
                                            style="width:40%;border: 1.5px solid #d0dcdd!important; border-top-left-radius:0;border-bottom-left-radius:0;">

                                            <option value="Pcs" @if ($model->package_weight_unit == 'pcs') selected @endif>Pcs
                                            </option>
                                            <option value="kg" @if ($model->package_weight_unit == 'kg') selected @endif>kg
                                            </option>
                                            <option value="g" @if ($model->package_weight_unit == 'g') selected @endif>g
                                            </option>
                                            <option value="mg" @if ($model->package_weight_unit == 'mg') selected @endif>mg
                                            </option>
                                            <option value="l" @if ($model->package_weight_unit == 'l') selected @endif>l
                                            </option>
                                            <option value="ml" @if ($model->package_weight_unit == 'ml') selected @endif>ml
                                            </option>

                                        </select>
                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Inventory</div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            {{-- <div class="col-md-6 mb-4">
                                <!-- Base Example -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="notify_out_of_stock"
                                        id="formCheck1" value="Yes" @if ($model->notify_out_of_stock == 'Yes') checked @endif>
                                    <label class="form-check-label" for="formCheck1">
                                        Notify When reached minimum limit
                                    </label>
                                </div>


                            </div>
                            <div class="col-md-6 mb-4">
                                <!-- Base Example -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="continue_selling"
                                        id="formCheck2" value="Yes" @if ($model->continue_selling == 'Yes') checked @endif>
                                    <label class="form-check-label text-grey" for="formCheck1">
                                        Continue Selling when out of stock
                                    </label>
                                </div>


                            </div> --}}
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-title-input">Quantity in Stock <span
                                        class="text-danger">*</span></label>

                                <input type="number" class="form-control" name="quantity" required
                                    placeholder="Enter quantity in stock" value="{{ $model->quantity }}">

                            </div>
                            {{-- <div class="col-md-6
                                            mb-4">
                                <label class="form-label" for="product-title-input">Minimum Quantity to
                                    notify</label>

                                <input type="number" class="form-control" value="{{ $model->minimum_qty_alert }}"
                                    name="minimum_qty_alert" placeholder="Enter miniumum quantity">

                            </div> --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="product-title-input">Per Quantity of </label>
                                    <div class="d-flex">
                                        <input type="number" class="form-control" value="{{ $model->per_quantity_of }}"
                                            style="border-right:0!important; border-top-right-radius:0;border-bottom-right-radius:0;"
                                            name="per_quantity_of" id="product-price-input" placeholder="Enter quantity "
                                            aria-label="Price" aria-describedby="product-price-addon">
                                        <select class="form-select no-select2" name="per_unit"
                                            style="width:40%;border: 1.5px solid #d0dcdd!important;
                                             border-top-left-radius:0;border-bottom-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:0;">
                                            <option value="kg" @if ($model->per_unit == 'kg') selected @endif>Kg
                                            </option>
                                            <option value="g" @if ($model->per_unit == 'g') selected @endif>g
                                            </option>
                                            <option value="l" @if ($model->per_unit == 'l') selected @endif>L
                                            </option>
                                            <option value="ml" @if ($model->per_unit == 'ml') selected @endif>ml
                                            </option>
                                        </select>
                                        <input type="number" name="per_price" value="{{ $model->per_price }}"
                                            class="form-control"
                                            style="border-left:0;border-top-left-radius:0;border-bottom-left-radius:0"
                                            name="per_price" value="" placeholder="Enter price ">
                                    </div>


                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label" for="stocks-input">Max Purchase Quantity
                                        Allowed<span class="text-danger">*</span></label>
                                    <input type="number" class="form-control"
                                        value="{{ $model->max_quantity_allowed }}" name="max_quantity_allowed"
                                        placeholder="Enter Max Quantity" required>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-group">Short Description <span class="text-danger">*</span></label>

                            <textarea required
                                class="form-control" " name="short_description" placeholder="Must enter minimum of a 100 characters" rows="3">{{ $model->short_description }}</textarea>

                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="form-group mb-3 w-50">
                                <label class="form-group">Features </label>

                                <textarea required class="form-control" name="features" placeholder="Enter Product feratures or benefits"
                                    rows="3">{{ $model->features }}</textarea>



                            </div>
                            <div class="form-group mb-3 w-50">
                                <label class="form-group">Specifications </label>

                                <textarea required class="form-control" name="specifications" placeholder="Enter Product specification"
                                    rows="3">{{ $model->specifications }}</textarea>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-group">Long Description</label>
                            <textarea class="form-control summernote" name="description" rows="10">{!! $model->short_description !!}</textarea>
                        </div>
                    </div>
                </div>
                <!--variant -->

                <!--variant end-->
            </div>
            <div class="col-md-5">
                <div class="card mb-4">

                    <div class="card-body">


                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label for="choices-publish-status-input" class="form-label">Status</label>

                                <select class="form-select no-select2" name="status" id="choices-publish-status-input"
                                    data-choices data-choices-search-false>
                                    <option value="Active" @if ($model->status == 'Active') selected @endif>
                                        Active</option>

                                    <option value="In-Active" @if ($model->status == 'In-Active') selected @endif>
                                        In-Active</option>
                                </select>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label for="choices-publish-visibility-input" class="form-label">Visibility</label>
                                <select class="form-select no-select2" name="visibility"
                                    id="choices-publish-visibility-input" data-choices data-choices-search-false>
                                    <option value="Public" @if ($model->visibility == 'Public') selected @endif>
                                        Public</option>
                                    <option value="Hidden" @if ($model->visibility == 'Hidden') selected @endif>
                                        Hidden</option>
                                </select>
                            </div>
                            {{--<div class="mb-3 col-md-12 ps-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_combo" role="switch"
                                        value="Yes" id="flexSwitchCheckChecked" " @if ($model->is_combo == 'Yes') checked @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Combo Offer</label>
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-12 ps-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"  name="featured"
                                            role="switch" value="Yes" id="flexSwitchCheckChecked" @if ($model->featured == 'Yes') checked @endif>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Featured</label>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>--}}
                        </div>
                        <div class="card mb-4">

                            <div class="card-body">


                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Main Image <span class="text-danger">*</span></label>
                                            <input name="image" type="file" class="form-control" >
                                        </div>
                                          @if ($model->image)
                                    <div class="image_place" id="img_div">
                                        <i class="bx bx-trash text-danger del_ic"
                                            onClick="deleteFileSelf('{!! $model->image !!}', 'Product', 'products/{!! $model->id !!}',
                                             'image','{!! $model->id !!}')"></i>
                                        <a href="{{ asset('storage/products/' . $model->id . '/' . $model->image) }}"
                                            data-lightbox="image-1">
                                            <img src="{{ asset('storage/products/' . $model->id . '/' . $model->image) }}"
                                                style="width:100%;height:100%;object-fit:fit" />

                                        </a>

                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label class="form-label">Gallery(<small>Multiple Images</small>)</label>
                                        <input name="product_images" type="file" class="form-control"
                                            multiple="multiple">
                                    </div>
                                    @if (count($model->images) > 0)
                                        <div class="hstack gap-2">
                                            @foreach ($model->images as $img)
                                                <div class="image_place" id="img_div-{{ $img->id }}">
                                                    <i class="bx bx-trash text-danger del_ic"
                                                        onclick="deleteFileFromTable('{!! $img->id !!}', 'product_images', 'products/{!! $model->id !!}', '{!! route('deleteTableFile') !!}')"></i>
                                                    <a href="{{ asset('storage/products/' . $model->id . '/' . $img->name) }}"
                                                        data-lightbox="image-1">

                                                        <img src="{{ asset('storage/products/' . $model->id . '/' . $img->name) }}"
                                                            style="width:100%;height:100%;object-fit:fit" />
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif



                                    <!-- end card -->



                                    <!-- end card -->

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tax_panel" role="tab">
                                        Tax
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#addproduct-metadata" role="tab">
                                        SEO
                                    </a>
                                </li>

                            </ul>
                        </div>
                        <!-- end card header -->
                        <div class="card-body">
                            <div class="tab-content">

                                <!-- end tab-pane -->

                                <div class="tab-pane" id="addproduct-metadata" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="meta-title-input">Meta title</label>
                                                <input type="text" name="meta_title" class="form-control"
                                                    placeholder="Enter meta title" id="meta-title-input"
                                                    value="{{ $model->meta_title }}">
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="meta-keywords-input">Meta
                                                    Keywords</label>
                                                <input type="text" name="meta_keywords "class="form-control"
                                                    placeholder="Enter meta keywords" value="{{ $model->meta_keywords }}"
                                                    id="meta-keywords-input">
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->

                                    <div>
                                        <label class="form-label" for="meta-description-input">Meta
                                            Description</label>
                                        <textarea class="form-control" name="meta_description" id="meta-description-input"
                                            placeholder="Enter meta description" rows="3">{{ $model->meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="tab-pane active" id="tax_panel" role="tabpanel">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="meta-title-input">SGST(%)</label>
                                                <input type="number" name="sgst" class="form-control"
                                                    placeholder="Enter sgst" value="{{ $model->sgst }}">
                                            </div>
                                        </div>
                                        <!-- end col -->

                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="meta-keywords-input">CGST(%)</label>
                                                <input type="number" name="cgst" class="form-control"
                                                    placeholder="Enter cgst" value="{{ $model->cgst }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="meta-keywords-input">IGST(%)</label>
                                                <input type="number" name="igst" class="form-control"
                                                    placeholder="Enter igst" value="{{ $model->igst }}">
                                            </div>
                                        </div>
                                        <!-- end col -->
                                    </div>
                                    <!-- end row -->


                                </div>
                                <!-- end tab pane -->
                            </div>
                            <!-- end tab content -->
                        </div>
                        <!-- end card body -->
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">

                        <div class="card-body">


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" name="has_variant"
                                            onchange="toggleDivDisplay(this.value,'var_c','Yes')" value="Yes"
                                            type="checkbox" id="formCheck3"
                                            @if ($model->has_variant == 'Yes') checked @endif>
                                        <label class="form-check-label" for="formCheck1">
                                            Product has variant?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12" id="var_c"
                                    @if ($model->has_variant == 'Yes') style="display:block" @endif>

                                    <div class="d-none" id="copy">


                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Attribute</label>
                                                    <select class="form-control no-select2" name="xattribute"
                                                        onChange="show(this.value,event)">
                                                        <option value="">Select Attributes</option>
                                                        @foreach ($attributes as $b)
                                                            <option value="{{ $b->id }}">{{ $b->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 p">


                                            </div>

                                        </div>

                                    </div>


                                    <div id="variant_container"
                                        style="padding: 18px;
                                margin-top: 25px;
                                border: 1px solid #dbe9ec;">


                                        <div class="d-flex justify-content-between">
                                            <h5 class="card-title mb-0" style="font-size:13px;">Create Variants</h5>
                                            <div class="hstack flex-wrap gap-2 pull-right">
                                                <button class="btn btn-soft-dark btn-border " type="button"
                                                    onclick="dynamicAddRemoveRowSimple('add','repeatable_container')">
                                                    Add More</button>
                                                <button class="btn btn-soft-danger btn-border" type="button"
                                                    onclick="dynamicAddRemoveRowSimple('minus','repeatable_container')">
                                                    Remove Row</button>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-12 mb-4" id="repeatable_container">
                                                @if ($model->has_variant)
                                                    @php
                                                        $attributes_s = json_decode($model->attributes, true);
                                                    @endphp
                                                    @if (!empty($attributes_s))
                                                        @foreach ($attributes_s as $k => $v)
                                                            <div class="row mb-4" id="row-{{ $loop->index }}">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Attribute</label>
                                                                        <select class="form-control no-select2"
                                                                            name="attribute-0"
                                                                            onChange="show(this.value,event)">
                                                                            <option value="">Select Attributes
                                                                            </option>
                                                                            @foreach ($attributes as $b)
                                                                                <option value="{{ $b->id }}"
                                                                                    @if ($v['id'] == $b->id) selected @endif>
                                                                                    {{ $b->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 p">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Value</label>
                                                                        <div>
                                                                            <input class="form-control attribute_values"
                                                                                data-tagvalue="{{ $v['value'] }}"
                                                                                name="value-{{ $v['id'] }}"
                                                                                data-role="tagsinput" />
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="row mb-4" id="row-0">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">Attribute</label>
                                                                    <select class="form-control no-select2"
                                                                        name="attribute-0"
                                                                        onChange="show(this.value,event)">
                                                                        <option value="">Select Attributes
                                                                        </option>
                                                                        @foreach ($attributes as $b)
                                                                            <option value="{{ $b->id }}">
                                                                                {{ $b->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 p">


                                                            </div>

                                                        </div>
                                                    @endif
                                                @endif
                                            </div>


                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-4">
                                                <div class="accordion" id="accord">
                                                    @if (!empty($model->variants))
                                                        @foreach ($model->variants as $variant)
                                                            <div class="accordion-item shadow mb-2">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button" type="button"
                                                                        data-bs-toggle="collapse"
                                                                        data-bs-target="#collapse{{ $loop->index }}"
                                                                        aria-expanded="true" aria-controls="collapse${i}">
                                                                        {{ $variant->name }}
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse{{ $loop->index }}"
                                                                    class="accordion-collapse collapse"
                                                                    aria-labelledby="headingOne"
                                                                    data-bs-parent="#default-accordion-example">
                                                                    <div class="accordion-body">
                                                                        <div class="row">

                                                                            <div class="col-md-2">
                                                                                <input type="hidden"
                                                                                    name="variant_id__{{ $variant->name }}"
                                                                                    value="{{ $variant->id }}" />
                                                                                <div class="form-group">
                                                                                    <label class="form-label"
                                                                                        for="product-title-input">
                                                                                        Price</label>

                                                                                    <input type="number"
                                                                                        class="form-control"
                                                                                        name="variant_price__{{ $variant->name }}"
                                                                                        placeholder="Price"
                                                                                        value="{{ $variant->price }}">



                                                                                </div>


                                                                            </div>
                                                                            <div class="col-md-2">
                                                                                <div class="form-group">
                                                                                    <label class="form-label"
                                                                                        for="product-title-input">Sale
                                                                                        Price</label>

                                                                                    <input type="number"
                                                                                        class="form-control"
                                                                                        name="variant_sale_price__{{ $variant->name }}"
                                                                                        id="product-price-input"
                                                                                        placeholder="Sale Price"
                                                                                        value="{{ $variant->sale_price }}">



                                                                                </div>


                                                                            </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="product-title-input">Stock Quantity
                                                            </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="variant_quantity__{{ $variant->name }}"
                                                            id="product-price-inputo"
                                                            placeholder="Stock Quantity"
                                                            value="{{ $variant->quantity }}">



                                                    </div>


                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="form-label"
                                                            for="product-title-input">Maximum Quantity Allowed
                                                            </label>

                                                        <input type="number"
                                                            class="form-control"
                                                            name="variant_max_quantity_allowed__{{ $variant->name }}"
                                                            id="product-price-input"
                                                            placeholder="Max Quantity Customer can purchase"
                                                            value="{{ $variant->max_quantity_allowed }}">



                                                    </div>


                                                </div>

                                                                            {{--<div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label class="form-label"
                                                                                        for="product-title-input">Main
                                                                                        Image
                                                                                    </label>

                                                                                    <input type="file"
                                                                                        class="form-control"
                                                                                        name="variant_image__{{ $variant->name }}" />



                                                                                </div>
                                                                                @if ($variant->image)
                                                                                    <div class="image_place"
                                                                                        id="variant_img_div">

                                                                                        <i class="bx bx-trash text-danger del_ic"
                                                                                            onClick="deleteFileSelf('{!! $variant->image !!}', 'ProductVariant', 'products/{!! $model->id !!}/variants/',
                                                                'image','{!! $variant->id !!}')"></i>
                                                                                        <a href="{{ asset('storage/products/' . $model->id . '/variants/' . $variant->image) }}"
                                                                                            data-lightbox="image-1">
                                                                                            <img src="{{ asset('storage/products/' . $model->id . '/variants/' . $variant->image) }}"
                                                                                                style="width:100%;height:100%;object-fit:fit" />

                                                                                        </a>

                                                                                    </div>
                                                                                @endif

                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label class="form-label"
                                                                                        for="product-title-input">Gallery
                                                                                    </label>

                                                                                    <input type="file" multiple
                                                                                        class="form-control"
                                                                                        name="variant_product_images__{{ $variant->name }}" />



                                                                                </div>
                                                                                @if (count($variant->images) > 0)
                                                                                    <div class="hstack gap-2">
                                                                                        @foreach ($variant->images as $img)
                                                                                            <div class="image_place"
                                                                                                id="variant_img_div-{{ $img->id }}">
                                                                                                <i class="bx bx-trash text-danger del_ic"
                                                                                                    onclick="deleteFileFromTable('{!! $img->id !!}', 'product_variant_images', 'products/{!! $model->id !!}/variants/', '{!! route('deleteTableFile') !!}')"></i>
                                                                                                <a href="{{ asset('storage/products/' . $model->id . '/variants/' . $img->name) }}"
                                                                                                    data-lightbox="image-1">

                                                                                                    <img src="{{ asset('storage/products/' . $model->id . '/variants/' . $img->name) }}"
                                                                                                        style="width:100%;height:100%;object-fit:fit" />
                                                                                                </a>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                @endif--}}

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div><!--acrdian end-->
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>

                                        </div>





                                    </div>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                    </div>

                </div>
                <div class="text-end mb-3 mt-3">
                    <button type="submit" class="btn btn-success w-sm">Submit</button>
                </div>
            </div>
            <!-- end col -->


            </form>




        </div>
    @endsection
