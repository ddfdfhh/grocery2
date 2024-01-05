@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">
       

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">View {{ properSingularName($plural_lowercase) }}</h5>
                    </div>

                    <div class="card-body">
                          <x-displayViewData :module="$module" :row1="$row" :modelRelations="$model_relations" :viewColumns="$view_columns"
                            :imageFieldNames="$image_field_names" :storageFolder="$storage_folder" />
                    
                    </div><br>
                </div>
            </div>
        </div>
    </div>
@endsection
