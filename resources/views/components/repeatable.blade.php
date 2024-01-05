@props([
    'data',
    'label',
    'values',
    'index',
    'indexWithModal',
    'modalInputBoxIdWhoseValueToSetInSelect',
    'hide',
    'disableButtons',
])
@php
    $data = $data;
$disableButtons=!isset($disableButtons)?false:$disableButtons;
    $values = json_decode($values, true);
    //  dd($values);
    $data = array_map(function ($v) {
        if ($v['tag'] == 'select') {
            $ar = $v['options'];
            $key = $v['custom_key_for_option'];
            $name = str_replace('_', ' ', $v['name']); /***this is for select first item like select event or select package */
            $p = explode(' ', $name);
            $new_ar = [(object) ['id' => '', $key => 'Select ' . ucfirst($p[0])]];
            foreach ($ar as $k) {
                array_push($new_ar, (object) $k);
            }
            $v['options'] = $new_ar;
        }
        return $v;
    }, $data);
    $ar_val = [];
    if ($values) {
        $ar_val = $values;
    }
    // dd($ar_val);
@endphp
<div id="{{ strtolower(str_replace(' ', '_', $label)) }}"  style="display:{{ isset($hide) ? ($hide == 'true' || $hide==true ? 'none' : 'block') : 'block' }}">
    <label class="form-label">{{ $label }}</label>
    <fieldset class="form-group border p-3 fieldset mb-2"
       >

        <div id="repeatable{{ $index }}" class="repeatable" style="margin-bottom:5px">

            @if (!$disableButtons)
                <div class="row">

                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">


                            <button type="button" class="btn btn-icon btn-label-primary"
                                style="border-right:1px solid #b6cfd1" onclick="addMoreRow()">
                                <span class="tf-icons bx bx-plus"></span>
                            </button>
                            <button type="button" class="btn btn-icon btn-label-secondary" onclick="removeRow()">
                                <span class="tf-icons bx bx-minus"></span>
                            </button>


                        </div>
                    </div>
                </div>
            @endif
            @if ($values && is_array($values))
                @foreach ($values as $t)
                    <div class="row copy_row">
                        @php
                            $n = floor(12 / count($data));
                        @endphp
                        @foreach ($data as $input)
                            @php
                                $spl = explode('__json__', $input['name']);
                                $key = rtrim($spl[1], '[]');
                                $value = isset($t[$key]) ? $t[$key] : '';
                            @endphp
                            <div class="col-md-{{ $n }}">
                                @if (!isset($input['has_modal']))
                                    <x-input_placing :inputRow="$input" :value="$value" />
                                @else
                                    <div class="form-group">
                                        <label for="usr">{{ ucwords($input['label']) }}</label>
                                        <div class="d-flex mb-3">
                                            <select class="form-select" name="{{ $input['name'] }}"
                                                placeholder="Enter {{ $key }}">
                                                <option value="{{ $t[$key] }}" selected>{{ $t[$input['name']] }}
                                                </option>
                                            </select>
                                            <div class="input-group-append">
                                                <a href="#" type="button"
                                                    class="mt-2 btn btn-icon btn-label-secondary"
                                                    style="margin-top:1px!important;height: 100%!important;"
                                                    onClick="openJsonModal()">
                                                    <i class="bx bx-search"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="row copy_row" id="copy-row-1">
                    @php
                        $n = floor(12 / count($data));
                    @endphp
                    @foreach ($data as $input)
                        @php
                            $spl = explode('__json__', $input['name']);
                            $key = rtrim($spl[1], '[]');
                        @endphp
                        <div class="col-md-{{ $n }}">

                            @if (!isset($input['has_modal']))
                                <x-input_placing :inputRow="$input" value="" />
                            @else
                                <div class="form-group">
                                    <label for="usr">{{ ucwords($input['label']) }}</label>
                                    <div class="d-flex mb-3">
                                        <select class="form-select rounded-0" name="{{ $input['name'] }}"
                                            placeholder="Enter {{ $key }}">
                                            <option></option>
                                        </select>
                                        <div class="input-group-append">
                                            <a href="#" type="button"
                                                class="mt-2 btn btn-icon btn-label-secondary"
                                                style="margin-top:1px!important;height: 100%!important;"
                                                onClick="openJsonModal()">
                                                <i class="bx bx-search"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </fieldset>
</div>

<div class="modal" id="json_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Search Record</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                @if (isset($data[$indexWithModal]['modal_inputs']) && count($data[$indexWithModal]['modal_inputs']) > 0)
                    @foreach ($data[$indexWithModal]['modal_inputs'] as $input)
                        <x-input_placing :inputRow="$input" value="" />
                        <div class="mb-2"></div>
                    @endforeach
                @endif
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button"
                    onClick="setInputIdInRepeatable('{{ $modalInputBoxIdWhoseValueToSetInSelect }}')"
                    class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
