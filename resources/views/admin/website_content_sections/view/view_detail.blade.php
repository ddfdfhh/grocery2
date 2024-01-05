@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">


        <h6 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Leads/</span> View
        </h6>
        <div class="row gy-4">
            <!-- User Sidebar -->
            <div class="col-xl-5 col-lg-65 col-md-6 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">


                        <h5 class="pb-2 mb-4">Model Details</h5>
                        <div class="info-container">
                               <x-displayViewData :module="$module" :row1="$row" :modelRelations="$model_relations" :viewColumns="$view_columns"
                            :imageFieldNames="$image_field_names" :storageFolder="$storage_folder" :repeatingGroupInputs="$repeating_group_inputs"/>
                    
                            <div class="d-flex justify-content-center pt-3">
                               

                                <a href="editUrl" class="rounded-0 btn btn-primary me-3"><i
                                        class="fa fa-edit"></i> Edit</a>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
                <!-- Plan Card -->
                <div class="card">
                    <div class="card-body">

                        <h5 class="pb-2 border-bottom mb-4">Enquired Products Details</h5>
                     {{--
                        {!! showArrayInColumnNoPopup(json_decode($row->enquired_products_detail, true), 'product_id') !!}
                        --}}
                    </div>
                </div>
                <!-- /Plan Card -->
            </div>
            <!--/ User Sidebar -->


            <!-- User Content -->
            <div class="col-xl-7 col-lg-6 col-md-6 order-0 order-md-1">

                <!--/ User Pills -->

                <!-- Change Password -->
                <div class="card">
                    <h5 class="card-header">Remarks</h5>
                    <div class="table-responsive">
                        <table class="table border-top">
                            <thead>
                                <tr>
                                    <th class="text-truncate">Remarks</th>
                                    <th class="text-truncate">Date</th>
                                    <th class="text-truncate">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                               {{-- 
                                   @php
                                    $conversations = $row->conversations ? json_decode($row->conversations, true) : [];
                                @endphp
                             
                                 @if ($row->conversations && count($conversations) > 0)
                                    @foreach ($conversations as $item)
                                        <tr id="row-1">

                                            <td class="text-truncate" style="word-wrap: break-word;max-width:600px;">
                                                {{ $item['message'] }}</td>
                                            <td class="text-truncate">SDSD</td>
                                            <td class="text-truncate">
                                                <button class="btn btn-xs btn-danger"
                                                    onClick="deleteJsonColumnData('rowid,'id','leads','id','column','deleteInJsonColumnDataRoute')">
                                                    <i class="bx bx-trash"></i></button>
                                            </td>

                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" style="text-align:center">No Data Available</td>
                                    </tr>
                                @endif  --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <h5 class="card-header">Add Remark</h5>
                    <div class="card-body">
                        <form data-module="Remark" id="remark_form-1"
                            data-url="we">

                            <div class="row">
                                <div class="mb-3 col-12 col-sm-12 ">
                                    <div id="resp-1"></div>
                                    <input type="hidden" name="lead_id" id="lead_id-1"
                                        value="1" />
                                    <textarea type="text" required name="conversation" id="conversation-1"
                                        class="form-control p-4 mt-3" placeholder="Add Conversation" aria-describedby="subscribe"></textarea>
                                </div>


                                <div>
                                    <button type="button" onclick="addEditRemark('1')"
                                        id="remark_btn-0" class="rounded-0 btn btn-primary me-2"><i
                                            class="fa fa-plus-circle"></i> Submit</button>
                                </div>
                            </div>
                        </form>
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
