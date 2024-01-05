@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <style>
            .nav-tabs a.active {
                color: white !important;
            }

            .nav-tabs a.inactive {
                color: black !important;
                border: 1px solid #e6dfdf;
            }
        </style>
        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between flex-wrap">
                    <h4 class="card-title">All {{ properPluralName($plural_lowercase) }}</h4>
                    <div class="d-flex">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @if (auth()->user()->hasRole(['Admin']) ||
                                    auth()->user()->can('create_' . $plural_lowercase))
                                <button type="button" class="rounded-0 btn btn-primary text-white">
                                    <a href="{{ route($plural_lowercase . '.create') }}"
                                        class="text-decoration-none text-white">
                                        <i class="bx bx-plus-circle" style="margin-top:-3px"></i> Add New
                                    </a>
                                </button>
                            @endif

                            @if ($has_export)
                                <button type="button"
                                    class="rounded-0 dt-button buttons-collection btn btn-label-primary dropdown-toggle me-2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span><i class="bx bx-export me-sm-2"></i> <span
                                            class="d-none d-sm-inline-block">Export</span></span>
                                </button>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ route(strtolower($module) . '.export', ['type' => 'excel']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-printer me-2"></i>XLS</span></a>
                                        <a class="dropdown-item"
                                            href="{{ route(strtolower($module) . '.export', ['type' => 'csv']) }}?{{ http_build_query($_GET) }}"><span><i
                                                    class="bx bx-file me-2"></i>CSV</span></a>


                                    </li>

                                </ul>
                            @endif

                        </div>

                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-between flex-wrap">

                    <x-groupButtonIndexPage :filterableFields="$filterable_fields" :pluralLowercase="$plural_lowercase" :bulkUpdate="$bulk_update" :moduleTableName="$module_table_name" />

                    <x-search :searchableFields="$searchable_fields" />
                </div>



            </div>

            <div class="card-body">

                <div class="card-header border-bottom">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        @foreach ($tabs as $tab)
                            @php
                                $colname = $tab['column'];
                                $active = 'active bg-primary';
                                if (!isset($_GET[$colname])) {
                                    $active = $loop->first ? 'active bg-primary' : 'inactive';
                                } else {
                                    $active = isset($_GET[$colname]) && !empty($_GET[$colname]) && $_GET[$colname] == $tab['value'] ? 'active bg-primary' : 'inactive';
                                }
                            @endphp
                            <li class="nav-item">
                                <a href="{{ url()->current() . '?' . $tab['column'] . '=' . $tab['value'] }}"
                                    class="nav-link {{ $active }}" role="tab"
                                    aria-controls="navs-within-card-active" aria-selected="true">{{ $tab['label'] }}
                                    @if (isset($tab['count']))
                                        <span
                                            class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-primary ms-1">{{ $tab['count'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="navs-within-card" role="tabpanel">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#
                                            <input class="form-check-input" type="checkbox" id="check_all" />

                                        </th>
                                        @foreach ($table_columns as $t)
                                            @if ($t['sortable'] == 'Yes')
                                                <x-row column="{{ $t['column'] }}"
                                                    label="{{ str_replace(' Id', '', $t['label']) }}" />
                                            @else
                                                <th>{{ str_replace(' Id', '', $t['label']) }}</th>
                                            @endif
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0" id="tbody">
                                    @include('admin.' . $plural_lowercase . '.page')
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="hidden_page" id="hidden_page"
                    value="{{ !empty($_GET['page']) ? $_GET['page'] : '1' }}" />
                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="" />
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                <input type="hidden" name="search_by" id="search_by" value="" />

            </div>
        </div>
    </div>
@endsection
