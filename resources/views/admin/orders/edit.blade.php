@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">
        <div class="card mb-2">
            <div class="card-header d-flex align-items-center">
                <button type="button" class="btn btn-icon btn-outline-primary">
                    <span class="tf-icons bx bx-edit"></span>
                </button> &nbsp;&nbsp;<h5 class="rounded-1 mb-0 text-primary">Edit {{ properSingularName($plural_lowercase) }}
                </h5>
            </div>
        </div>
        {!! Form::open()->put()->route($plural_lowercase . '.update', [\Str::singular($plural_lowercase) => $model->id])->id(strtolower($module) . '_form')->attrs(['data-module' => $module]) !!}
       @if ($has_image && count($image_field_names) > 0)
            <div class="row">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-body">
                            <div class="card-text">
                                <x-forms :data="$data" column='2' />
                                @if (count($repeating_group_inputs) > 0)
                                    @foreach ($repeating_group_inputs as $grp)
                                        <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}"
                                            :index="$loop->index" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>



                </div>
                <div class="col-md-4 mt-xs-2">
                    <div class="card">

                        <div class="card-body">

                            <h5 class="px-0 py-1 rounded-1 mb-0 text-primary">Upload Documents</h5>
                            <br>

                            <div class="card-text">
                                <x-imageform :data="$data" column='1' />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">

                <div class="card-body">
                    <div class="card-text">
                        <x-forms :data="$data" column='2' />
                        @if (count($repeating_group_inputs) > 0)
                            @foreach ($repeating_group_inputs as $grp)
                                        <x-repeatable :data="$grp['inputs']" :label="$grp['label']" :values="$model->{$grp['colname']}"
                                            :index="$loop->index" :indexWithModal="$grp['index_with_modal']" :modalInputBoxIdWhoseValueToSetInSelect="$grp['modalInputBoxIdWhoseValueToSetInSelect']" />
                                    @endforeach
                        @endif

                    </div>
                </div>
            </div>
        @endif
        <div class="row mt-2">
            <div class="col-sm-12 pull-right" style="text-align:right">
                
                @php
                    $r = 'Submit';
                @endphp
                {!! Form::submit($r)->id(strtolower($module) . '_btn')->primary() !!}
           
    </div>
    </div>
    {!! Form::close() !!}



    </div>
@endsection
