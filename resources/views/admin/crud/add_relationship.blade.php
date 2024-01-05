@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Add Relationship /</span>Add
        </h4>

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Add Relationship</h5> <small
                            class="text-muted float-end">Default label</small>
                    </div>

                    <div class="card-body">
                        <!--modalable content-->
                         {!! Form::open()->route('admin.addTableRelationship')->attrs(['data-module' => 'Crud']) !!}
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                  <div class="form-group"><label class="form-label">Select Model</label>
                                    <select name="model" id="table" class="form-control " tabindex="-1"
                                        aria-hidden="true" >
                                        <option value="" selected>Select model</option>
                                        @foreach ($models as $table)
                                            <option value="{{ $table }}">{{ $table }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                           
                             
                            <div class="col-md-12 mb-3">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Many To Many Configruation</legend>
                                    <div id="many_to_many_repeat" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlus('many_to_many_repeat')">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinus('many_to_many_repeat')">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">

                                            <div class="col-md-6 mb-3">
                                                <select name="many_to_many_models[]" id="table" class="form-control "
                                                    tabindex="-1" aria-hidden="true">
                                                    <option value="" selected>Select model</option>
                                                    @foreach ($models as $table)
                                                        <option value="{{ $table }}">{{ $table }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                              <div class="col-md-6 mb-3">

                                                 <div class="form-group">
                                                    <label class="form-label">Relationship Name</label>
                                                    <input type="text" id="" class="form-control"
                                                        name="many_to_many_relationship_name[]" value="" class="form-control "
                                                        placeholder="Enter relationship  name" aria-invalid="false">
                                                </div><br>

                                            </div> 
                                            {{-- <div class="col-md-6 mb-3">

                                                <div class="form-group">
                                                  
                                                    <input type="text" name="field_name_many[]" value=""
                                                        class="form-control " placeholder="Enter field name"
                                                        aria-invalid="false">
                                                </div>

                                            </div> --}}


                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12 mb-3">
                                <!--end repea-->
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Has Many Configruation</legend>
                                    <div id="has_many_repeat" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusHasMany()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusHasMany()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">

                                            <div class="col-md-4 mb-3">
                                                <select name="has_many_model[]"  class="form-control "
                                                    tabindex="-1" aria-hidden="true">
                                                    <option value="" selected>Select model</option>
                                                    @foreach ($models as $table)
                                                        <option value="{{ $table }}">{{ $table }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <div class="form-group">
                                                    
                                                    <input type="text" id="" class="form-control"
                                                        name="has_many_fk[]" value="" class="form-control "
                                                        placeholder="Enter foreign key(has one)" aria-invalid="false">
                                                </div>

                                            </div>
                                              <div class="col-md-4 mb-3">

                                                 <div class="form-group">
                                                    <label class="form-label">Relationship Name</label>
                                                    <input type="text" id="" class="form-control"
                                                        name="has_many_relationship_name[]" value="" class="form-control "
                                                        placeholder="Enter relationship  name" aria-invalid="false">
                                                </div><br>

                                            </div> 
                                            {{-- <div class="col-md-4 mb-3">

                                                <div class="form-group">
                                                   
                                                    <input type="text" name="field_name_has_many[]" value=""
                                                        class="form-control " placeholder="Enter field name"
                                                        aria-invalid="false">
                                                </div>

                                            </div> --}}


                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>


                            </div>
                             <div class="col-md-12 mb-3">
                                <!--end repea-->
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Has One Configruation</legend>
                                    <div id="has_one_repeat" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlus('has_one_repeat')">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinus('has_one_repeat')">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">

                                            <div class="col-md-4 mb-3">
                                                <select name="has_one_model[]"  class="form-control "
                                                    tabindex="-1" aria-hidden="true">
                                                    <option value="" selected>Select model</option>
                                                    @foreach ($models as $table)
                                                        <option value="{{ $table }}">{{ $table }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">

                                                <div class="form-group">
                                                    
                                                    <input type="text" id="" class="form-control"
                                                        name="has_one_fk[]" value="" class="form-control "
                                                        placeholder="Enter foreign key(has one)" aria-invalid="false">
                                                </div>

                                            </div>
                                              <div class="col-md-4 mb-3">

                                                 <div class="form-group">
                                                    <label class="form-label">Relationship Name</label>
                                                    <input type="text" id="" class="form-control"
                                                        name="has_one_relationship_name[]" value="" class="form-control "
                                                        placeholder="Enter relationship  name" aria-invalid="false">
                                                </div><br>

                                            </div> 
                                            {{-- <div class="col-md-4 mb-3">

                                                <div class="form-group">
                                                   
                                                    <input type="text" name="field_name_has_one[]" value=""
                                                        class="form-control " placeholder="Enter field name"
                                                        aria-invalid="false">
                                                </div>

                                            </div> --}}


                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>


                            </div>
                           

                        </div>
                        <div class="row">
                            <div class="col-sm-10">
                                @php
                                    $r = 'Submit';
                                @endphp
                                {!! Form::submit($r)->id(strtolower($module))->primary() !!}
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <!--modal ends here-->
                    </div><br>
                </div>
            </div>
        </div>
    </div>
@endsection
