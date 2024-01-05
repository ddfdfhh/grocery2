@foreach ($ar as $variant)
    @if($variant['row'])
        <div class="accordion-item shadow mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse${i}">
                    {{ $variant['name'] }}
                </button>
            </h2>
            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                data-bs-parent="#default-accordion-example">
                <div class="accordion-body">
                    <div class="row">

                        <div class="col-md-3">
                            <input type="hidden" name="variant_id__{{$variant['name'] }}" value="{{ $variant['row']->id }}" />
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">
                                    Price</label>

                                <input type="number" class="form-control" name="variant_price__{{ $variant['name'] }}"
                                    placeholder="Price" value="{{ $variant['row']->price }}">



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Sale
                                    Price</label>

                                <input type="number" class="form-control" name="variant_sale_price__{{$variant['name'] }}"
                                    id="product-price-input" placeholder="Sale Price" value="{{ $variant['row']->sale_price }}">



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Stock Quantity
                                    </label>

                                <input type="number" class="form-control" name="variant_quantity__{{$variant['name'] }}"
                                    id="product-price-input4" placeholder="Sale Price" value="{{ $variant['row']->quantity }}">



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Max Quantity Allowed
                                    </label>

                                <input type="number" class="form-control" name="variant_max_quantity_allowed__{{$variant['name'] }}"
                                    id="product-price-input4" placeholder="Maximum quantity allowed" value="{{ $variant['row']->max_quantity_allowed }}">



                            </div>


                        </div>

                       {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Main
                                    Image
                                </label>

                                <input type="file" class="form-control" name="variant_image__{{ $variant['name'] }}" />



                            </div>
                            @if ($variant['row']->image)
                                <div class="image_place" id="variant_img_div">

                                    <i class="bx bx-trash text-danger del_ic"
                                        onClick="deleteFileSelf('{!! $variant['row']->image !!}', 'ProductVariant', 'products/{!! $variant['product_id'] !!}/variants/',
                                                                    'image','{!! $variant['row']->id !!}')"></i>
                                    <a href="{{ asset('storage/products/' . $variant['product_id'] . '/variants/' . $variant['row']->image) }}"
                                        data-lightbox="image-1">
                                        <img src="{{ asset('storage/products/' .$variant['product_id']. '/variants/' . $variant['row']->image) }}"
                                            style="width:100%;height:100%;object-fit:fit" />

                                    </a>

                                </div>
                            @endif

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Gallery
                                </label>

                                <input type="file" multiple class="form-control"
                                    name="variant_product_images__{{ $variant['name'] }}" />



                            </div>
                            @if (count($variant['row']->images) > 0)
                                <div class="hstack gap-2">
                                    @foreach ($variant['row']->images as $img)
                                        <div class="image_place" id="variant_img_div-{{ $img->id }}">
                                            <i class="bx bx-trash text-danger del_ic"
                                                onclick="deleteFileFromTable('{!! $img->id !!}', 'product_variant_images', 'products/{!! $variant['product_id'] !!}/variants/', '{!! route('deleteTableFile') !!}')"></i>
                                            <a href="{{ asset('storage/products/' . $variant['product_id'] . '/variants/' . $img->name) }}"
                                                data-lightbox="image-1">

                                                <img src="{{ asset('storage/products/' .$variant['product_id']. '/variants/' . $img->name) }}"
                                                    style="width:100%;height:100%;object-fit:fit" />
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>--}}
                    </div>
                </div>
            </div>
        </div><!--acrdian end-->
    @else
        <div class="accordion-item shadow mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $loop->index }}" aria-expanded="true" aria-controls="collapse${i}">
                    {{ $variant['name'] }}
                </button>
            </h2>
            <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="headingOne"
                data-bs-parent="#default-accordion-example">
                <div class="accordion-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">
                                    Price</label>

                                <input type="number" class="form-control" name="variant_price__{{ $variant['name'] }}"
                                    placeholder="Price" >



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Sale
                                    Price</label>

                                <input type="number" class="form-control" name="variant_sale_price__{{$variant['name'] }}"
                                    id="product-price-input" placeholder="Sale Price" >



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Stock Quantity
                                    </label>

                                <input type="number" class="form-control" name="variant_quantity__{{$variant['name'] }}"
                                    id="product-price-input4" placeholder="Stock QUantity">



                            </div>


                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Max Quantity Allowed
                                    </label>

                                <input type="number" class="form-control" name="variant_max_quantity_allowed__{{$variant['name'] }}"
                                    id="product-price-input4" placeholder="Maximum quantity allowed" 
                                    >



                            </div>


                        </div>
                        {{--<div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Main
                                    Image
                                </label>

                                <input type="file" class="form-control" name="variant_image__{{ $variant['name'] }}" />



                            </div>
                        

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" for="product-title-input">Gallery
                                </label>

                                <input type="file" multiple class="form-control"
                                    name="variant_product_images__{{ $variant['name'] }}" />



                            </div>
                        

                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach
