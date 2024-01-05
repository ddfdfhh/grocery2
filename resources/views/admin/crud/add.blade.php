@extends('layouts.admin.app')
@section('content')
    <div class="container-fluid">
        <h4 class="py-3 breadcrumb-wrapper mb-4">
            <span class="text-muted fw-light">Crud /</span>Create Module
        </h4>

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Generate Module</h5> <small class="text-muted float-end">Default label</small>
                    </div>

                    <div class="card-body">
                        <!--modalable content-->
                        {!! Form::open()->route('admin.generateModule')->attrs(['data-module' => 'Crud']) !!}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"><label class="form-label">Select Table</label>
                                    <select name="table" id="table" class="form-control " tabindex="-1"
                                        aria-hidden="true" onChange="setVal(this.value)">
                                        <option value="" selected>Select table</option>
                                        @foreach ($tables as $table)
                                            <option value="{{ $table }}">{{ $table }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="module" name="module[]" id="module[]" value=""
                                        class="form-control valid is-valid"
                                        placeholder="Enter module name captialise no space" aria-invalid="false">
                                </div>

                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Plural LowerCase (_ Join)</label>
                                    <input type="text" name="plural[]" id="plural" value=""
                                        class="form-control valid is-valid" placeholder="Enter pluralcase"
                                        aria-invalid="false">
                                </div>

                            </div>
                            <hr/>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="name" class="form-label">Singular Nice Uppercase Name</label>
                                    <input type="text" name="singular_name" id="sing" value=""
                                        class="form-control valid is-valid" placeholder="Enter sigular nice name like Driver Set"
                                        aria-invalid="false">
                                </div>

                            </div> 
                            <div class="col-md-4">
                                <p>Has Detailed View?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_detail_view" value="Yes" checked="checked"
                                        class="form-check-input valid is-valid" aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_detail_view" value="No" class="form-check-input"
                                        aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <p>Is Crud In Popup?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="modal[]" value="Yes" checked="checked"
                                        class="form-check-input valid is-valid" aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="modal[]" value="No" class="form-check-input"
                                        aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <hr/>
                            <div class="col-md-4">
                                <p>Is Popup Modal type?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="show_crud_in_modal" value="Yes" checked="checked"
                                        class="form-check-input valid is-valid" aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="show_crud_in_modal" value="No" class="form-check-input"
                                        aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <p>Exportable?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="export[]" value="Yes"
                                        onChange="showExportableFields(this.value,'export_fields','export_fields')"
                                        class="form-check-input" aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="export[]" value="No" checked="checked"
                                        onChange="showExportableFields(this.value,'export_fields','export_fields')"
                                        class="form-check-input " aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <div class="col-md-4" id="exportable_div">
                                <p>Exportable Fields</p>
                                <div id="export_fields">

                                </div>


                            </div>
                            <hr/>
                            <div class="col-md-4  mt-2 mb-3">
                                <p>View Page Columns</p>
                                <div id="view_page_columns">

                                </div>


                            </div>
                            <div class="col-md-6 mb-3">
                                <p>Crud Page Title Name</p>
                                <div >
                                    <input type="text" name="crud_title" class="form-control " aria-invalid="false">
                                </div>


                            </div>
                            <hr/>
                            <div class="col-md-12">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Validation Genration</legend>

                                    <div id="repeatable_validation" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusValidation('repeatable1')">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusValidation('repeatable1')">-</button>

                                                </div>
                                            </div>

                                            <div class="row copy_row border-1">
                                                <div class="col-md-6 mb-3" id="validation_fields">



                                                </div>
                                                <div class="col-md-6 mb-3 rules">

                                                </div>
                                            </div>
                                            <hr>

                                        </div>
                                    </div>

                                </fieldset>


                            </div>
                            <div class="col-md-6">
                                <p>has Reptable?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_repeating_group[]" value="Yes"
                                        onChange="showRepeatingDiv(this.value,'repeatable_create_div')"
                                        class="form-check-input" aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_repeating_group[]" value="No" checked="checked"
                                        onChange="showRepeatingDiv(this.value,'repeatable_create_div')"
                                        class="form-check-input " aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <div class="col-md-12" id="repeatable_create_div">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Repeatable Group</legend>

                                    <div id="repeatable_outer" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusRepeatableOuter()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusRepeatableOuter()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">
                                            <div class="col-md-6 mb-3" id="repeatable_cols_div"></div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group repeatable_label">

                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3  inputs"> </div>

                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Create Input Group</legend>

                                    <div id="repeatable_create" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusRepeatableCreate()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusRepeatableCreate()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">
                                            <div class="col-md-12 mb-3">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Fieldset label</label>
                                                    <input type="text" name="fieldset_label[]" value=""
                                                        class="form-control " placeholder="Enter label"
                                                        aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">

                                                <fieldset class="form-group border p-3 fieldset">
                                                    <legend class="w-auto px-2 legend">Add Inputs </legend>

                                                    <div id="nested_create" class="repeatable" style="margin-bottom:5px">
                                                        <div class="row">

                                                            <div class="col-md-12">
                                                                <div class="d-flex justify-content-end">

                                                                    <button type="button"
                                                                        class="btn btn-success btn-xs mr-5"
                                                                        onclick="addPlusRepeatableCreateNested()">+</button>


                                                                    <button type="button" class="btn btn-danger btn-xs"
                                                                        onclick="removeMinusRepeatableCreateNested()">-</button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row copy_row2 border-1">
                                                            <div class="col-md-6 mb-3" id="create_cols_div"></div>
                                                            <div class="col-md-6 mb-3">

                                                                <div class="form-group create_label">

                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mb-3 create_inputs"></div>
                                                        </div>
                                                    </div>

                                                </fieldset>
                                            </div>
                                        </div>

                                    </div>

                                </fieldset>


                            </div>
                           
                            

                            <div class="col-md-12" id="toggle_grop_container">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Toggable Group</legend>

                                    <div id="toggable_group" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusToggableOuter()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusToggableOuter()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row3 border-1">
                                            <div class="col-md-6 mb-3" id="toggable_cols_div"></div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-group toggable_value">

                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3  toggable_inputs"> </div>

                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!--image parr-->
                            <div class="col-md-6">
                                <p>Has Upload?</p>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_upload" value="Yes"
                                        onChange="showHideImageGroup(this.value)" class="form-check-input"
                                        aria-invalid="false">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" name="has_upload" value="No" checked="checked"
                                        onChange="showHideImageGroup(this.value)" class="form-check-input "
                                        aria-invalid="false">
                                    <label class="form-check-label">No</label>
                                </div>

                            </div>
                            <div class="col-md-12" id="file_grop_container" style="display:none">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Image/File Inputs</legend>

                                    <div id="file_group" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusFile()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusFile()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">

                                            <div class="col-md-2 mb-3">
                                                <div class="form-group">

                                                    <div class="form-check">
                                                        <input type="checkbox" name="image_type[]" value="Single"
                                                            checked="checked" onChange="showHideFields(this.value)"
                                                            class="form-check-input" aria-invalid="false">
                                                        <label class="form-check-label">Single</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="checkbox" name="image_type[]"
                                                            onChange="showHideFields(this.value)" value="Multiple"
                                                            class="form-check-input " aria-invalid="false">
                                                        <label class="form-check-label">Multiple</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div id="file_cols_div" class="file_cols_div"></div>
                                                <div id="file_field_input" class="file_field_input" style="display:none">
                                                    <div class="form-group">
                                                        <label class="form-label">Form Field name</label>
                                                        <input type="text" name="image_col_name[]" value=""
                                                            class="form-control " placeholder="Enter Id"
                                                            aria-invalid="false">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <div class="form-group"><label class="form-label">Select Table</label>
                                                    <select name="image_table[]" id="table" class="form-control "
                                                        tabindex="-1" aria-hidden="true"
                                                        onChange="if((this.value).indexOf('_images')==-1 || (this.value).indexOf('_files')==-1) alert('Select Image table')">
                                                        <option value="">Select table</option>
                                                        @foreach ($tables as $table)
                                                            <option value="{{ $table }}">
                                                                {{ $table }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">Parent Table Id</label>
                                                    <input type="text" name="parent_table_id[]" value=""
                                                        class="form-control " placeholder="Enter Id"
                                                        aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">File Model Name</label>
                                                    <input type="text" name="model_name[]" value=""
                                                        class="form-control" placeholder="Enter Id" aria-invalid="false">
                                                </div>
                                            </div>

                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!--index page-->
                            <div class="col-md-12" id="file_grop_container2">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Select Columns For Index Page</legend>

                                    <div id="index_page_column_group" class="repeatable" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-end">

                                                    <button type="button" class="btn btn-success btn-xs mr-5"
                                                        onclick="addPlusIndexPage()">+</button>


                                                    <button type="button" class="btn btn-danger btn-xs"
                                                        onclick="removeMinusIndexPage()">-</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row copy_row border-1">

                                            <div class="col-md-4 mb-3" id="index_page_cols_div">

                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label class="form-label">Label Name</label>
                                                    <input type="text" name="index_label[]" value=""
                                                        class="form-control index_label " placeholder="Enter label"
                                                        aria-invalid="false">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <p>Sortable?</p>
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" name="sortable[]" value="Yes"
                                                        checked="checked" class="form-check-input" aria-invalid="false">
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="checkbox" name="sortable[]" value="No"
                                                        class="form-check-input " aria-invalid="false">
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            </div>

                                            <hr>


                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!--filterable fields-->
                            <div class="col-md-12">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Filterable Columns</legend>

                                    <div id="index_page_column_group" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12" id="filterable_cols_div">

                                            </div>
                                        </div>

                                    </div>
                                </fieldset>
                            </div>
                            <!---searchable fields-->
                            <div class="col-md-12">
                                <fieldset class="form-group border p-3 fieldset">
                                    <legend class="w-auto px-2 legend">Searchable Columns</legend>

                                    <div id="index_page_column_group" style="margin-bottom:5px">
                                        <div class="row">

                                            <div class="col-md-12" id="searchable_cols_div">

                                            </div>
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
