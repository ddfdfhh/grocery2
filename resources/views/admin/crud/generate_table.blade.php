@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid5">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Crud /</span>Add table
        </h4>

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Genrate Table</h5> <small class="text-muted float-end">Default label</small>
                    </div>

                    <div class="card-body">
                        <!--modalable content-->
                        {!! Form::open()->route('admin.generateTable')->attrs(['data-module' => 'Crud']) !!}
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Table Name</label>
                                    <input type="text" id="module" name="table" class="form-control"
                                        placeholder="Enter table name" aria-invalid="false">
                                </div>

                            </div>
                            <div class="col-md-6 mb-3">
                                <p>Timestamps?
                                <div class="form-check">
                                    <input type="radio" name="timestamps" value="True" class="form-check-input"
                                        aria-invalid="false" checked="checked">
                                    <label class="form-check-label">True</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="timestamps" value="False" class="form-check-input"
                                        aria-invalid="false">
                                    <label class="form-check-label">False</label>
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


                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>


                            </div>

                            <!--image parr-->

                            <div class="col-md-12" id="f_container">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Add Fields</legend>

                                    <div id="table_creation" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end"
                                                    style="">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusTableCreate()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusTableCreate()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">
                                            <div class="col-md-2 mb-3">

                                                <div class="form-group">
                                                    <label class="form-label">Column name</label>
                                                    <input type="text" name="col_name" value=""
                                                        class="form-control " placeholder="Enter col name"
                                                        aria-invalid="false">
                                                </div>

                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <p>Column Type</p>
                                                    @php
                                                        $type = ['varchar(300)', 'smallText', 'longText', 'Int', 'smallInt', 'mediumInt', 'tinyInt', 'enum', 'json', 'decimal(10,2)', 'date', 'timestamp', 'current_timestamp'];
                                                        $props = ['unsigned', 'nullable', 'unique', 'index'];
                                                        
                                                    @endphp
                                                    <div class="row">
                                                        @foreach ($type as $t)
                                                            <div class="col-md-6">
                                                                <div class="form-check">
                                                                    <input type="radio" name="data_type"
                                                                        value="{{ $t }}"
                                                                        class="form-check-input" aria-invalid="false">
                                                                    <label
                                                                        class="form-check-label">{{ $t }}</label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label">Enum Values</label>
                                                                <input type="text" id=""
                                                                    class="form-control f_cols" name="enums"
                                                                    value="" class="form-control "
                                                                    placeholder="Enter comma spearted enums"
                                                                    aria-invalid="false">
                                                            </div><br>
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div><br>

                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <p>Constraints</p>
                                                @foreach ($props as $t)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="contraints[]"
                                                            value="{{ $t }}" class="form-check-input"
                                                            aria-invalid="false">
                                                        <label class="form-check-label">{{ $t }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group"><label class="form-label">Which
                                                        Relationship?</label>
                                                    <select name="relationship_type" class="form-control " tabindex="-1"
                                                        aria-hidden="true" onChange="">

                                                        <option value="">Select Relationship </option>
                                                       
                                                        <option value="belongsTo">BelongsTo</option>
                                                       
                                                    </select>
                                                </div><br>
                                                <div class="form-group"><label class="form-label">Select Relationship
                                                        Model</label>
                                                    <select name="relationship_model" id="table"
                                                        class="form-control " tabindex="-1" aria-hidden="true"
                                                        onchange="fetchColumns(this.value)">
                                                        <option value="" selected="">Select model</option>
                                                        @foreach ($models as $table)
                                                            <option value="{{ $table }}">
                                                                {{ $table }}</option>
                                                        @endforeach
                                                    </select>
                                                </div><br>
                                                <div class="form-group">
                                                    <label class="form-label">Foreign Key</label>
                                                    <input type="text" id="" class="form-control f_cols"
                                                        name="relationship_foreign_table_key" value=""
                                                        class="form-control " placeholder="Enter fkey"
                                                        aria-invalid="false">
                                                </div><br>
                                                <div class="form-group">
                                                    <label class="form-label">Relationship Name</label>
                                                    <input type="text" id="" class="form-control"
                                                        name="relationship_name" value="" class="form-control "
                                                        placeholder="Enter rel name" aria-invalid="false">
                                                </div><br>
                                                <div class="form-group">
                                                    <label class="form-label">My Key</label>
                                                    <input type="text" id="" class="form-control f_cols"
                                                        name="relatinoship_my_key" value="" class="form-control "
                                                        placeholder="Enter my key" aria-invalid="false">
                                                </div><br>
                                            </div>


                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <!---searchable fields-->

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
