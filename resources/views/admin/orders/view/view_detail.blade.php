@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">

        <div class="row gy-4">
            <!-- User Sidebar -->
            <div class="col-md-4">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">


                        <h5 class="pb-2 mb-4">Order Details</h5>
                        <div class="info-container">
                            <x-displayViewData :module="$module" :row1="$row" :modelRelations="$model_relations" :viewColumns="$view_columns"
                                :imageFieldNames="$image_field_names" :storageFolder="$storage_folder" 
                                :repeatingGroupInputs="$repeating_group_inputs"/>

                            {{-- <div class="d-flex justify-content-center pt-3">


                                <a href="editUrl" class="rounded-0 btn btn-primary me-3"><i class="fa fa-edit"></i> Edit</a>

                            </div> --}}
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->

                <!-- /Plan Card -->
            </div>
            <!--/ User Sidebar -->


            <!-- User Content -->
            <div class="col-md-8">

                <!--/ User Pills -->

                <!-- Change Password -->
                <div class="card">
                    <h5 class="card-header">Items List</h5>
                    <div class="table-responsive">
                        <table class="table border-top">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Image </th>
                                    <th class="text-truncate">Product Name</th>
                                    <th class="text-truncate">Variant Name</th>
                                    <th class="text-truncate">Qty</th>
                                    <th class="text-truncate">Price</th>
                                    <th class="text-truncate">Sell/Net Amount</th>
                                    <th class="text-truncate">Total Discount</th>


                                </tr>
                            </thead>
                            <tbody>

                                @if (count($row->items) > 0)
                                    @foreach ($row->items as $item)
                                       @php 
                                    
                                       $item=$item->toArray();
                                       @endphp
                                        <tr>
                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                <img src="{{$item['image']}}" style="width:50px;height:50px"/>
                                            </td>
                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                {{ $item['name'] }}</td>
                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                {{ $item['variant_name'] }}</td>
                                            <td class="text-truncate">{{ $item['qty'] }} {{ $item['unit'] }}</td>
                                            <td class="text-truncate">{{ $item['price'] }}</td>
                                            <td class="text-truncate">{{ $item['net_cart_amount']>0?$item['net_cart_amount']:$item['sale_price'] }}</td>
                                            <td class="text-truncate">{{ $item['total_discount'] }} </td>
                                      </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align:center">No Data Available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                  <div class="card">
                    <h5 class="card-header">Applied Coupons </h5>
                    <div class="table-responsive">
                        <table class="table border-top">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Coupon Name </th>
                                    <th class="text-truncate">Coupon Type </th>
                                    <th class="text-truncate">Coupon Code</th>
                                    <th class="text-truncate">Discount Method</th>
                                    


                                </tr>
                            </thead>
                            <tbody>
                               
                                @if ($row->applied_coupons)
                                    @foreach ($row->applied_coupons as $item)
                                    
                                        <tr>
                                           
                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                {{ $item->coupon->name}}</td>
                                           
                                            <td class="text-truncate">{{ $item->coupon_type }}</td>
                                            <td class="text-truncate">{{ $item->coupon_method}}</td>
                                            <td class="text-truncate">{{ $item->coupon->coupon_code }}</td>
                                        
                                      </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align:center">No Data Available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
              
                <!--/ Change Password -->


                <!--/ Two-steps verification -->

                <!-- Recent Devices -->

                <!--/ Recent Devices -->
            </div>
            <!--/ User Content -->
        </div>



    </div>
@endsection
