@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <!-- Statistics Cards -->
            <div class="col-3 col-md-3 col-lg-3 mb-4">
                <a href="{{route('admin.generateTable')}}"><div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-success"><i
                                    class="bx bx-purchase-tag fs-4"></i></span>
                        </div>
                        <span class="d-block text-nowrap">Generate Model/Table</span>

                    </div></a>
                </div>
            </div>
            <div class="col-3 col-md-3 col-lg-3 mb-4">
            <a href="{{route('admin.generateTable')}}">    <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-warning"><i
                                    class="bx bx-purchase-tag fs-4"></i></span>
                        </div>
                        <span class="d-block text-nowrap">Generate Module Crud</span>

                    </div></a>
                </div>
            </div>
            <div class="col-3 col-md-3 col-lg-3 mb-4">
             <a href="{{route('admin.generateTable')}}">   <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-danger"><i
                                    class="bx bx-purchase-tag fs-4"></i></span>
                        </div>
                        <span class="d-block text-nowrap">Add Relationship </span>

                    </div></a>
                </div>
            </div>
        </div>
    </div>
@endsection
