@props(['data'])
<!--Data should be array each item['name','label','type='select ,data,input ',options=['key'=>'',value=>''] -->
@php
    $data = $data;
@endphp
@if (!empty($data))
    @php
        $count = 0;
        if (count($_GET) > 0) {
            unset($_GET['page']);
            foreach ($_GET as $k => $v) {
                if (!empty($v)) {
                    $count++;
                }
            }
        }
        
    @endphp

    <div class="" id="filter">
        <button type="button" class="rounded-0 btn btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#filter_modal">
            <i class="bx bx-filter-alt"></i>&nbsp;&nbsp;Filter {!! $count > 0 ? '<span class="badge bg-info text-white" style="padding:3.8px 4.8px">' . $count . '</span>' : '' !!}
        </button>
        <div id="filter_modal" class="modal fade" role="dialog" data-bs-focus="false">
            <div class="modal-dialog">

                <!-- Modal content-->
                <form id="try">
                    <div class="modal-content" style="max-height:600px;overflow-y:auto">
                        <div class="modal-header">

                            <b>Filter List</b><a class="ml-2 btn btn-sm btn-primary" style="margin-left:5px"
                                href="{{ request()->url() }}">Reset </a>
                           
                        </div>
                        <div class="modal-body">

                            <div style="mb-2"">
                           
                                @foreach ($data as $t)
                                    @if ($t['type'] == 'date')
                                        <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                                        <div class="d-flex mb-2 mt-2">
                                            <div class="form-group mr-2" style="margin-right:2px;width: 50%;">
                                                <label style="font-size: 12px;"
                                                    for="start_{{ $t['name'] }}">Start</label>
                                                <input type="date" placeholder="Start Date" class="date-picker form-control"
                                                    name="start_{{ $t['name'] }}">
                                            </div>
                                            <div class="form-group" style="width: 50%;">
                                                <label style="font-size: 12px;"
                                                    for="end-{{ $t['name'] }}">End</label>
                                                <input type="date" placeholder="End Date" class="date-picker form-control"
                                                    name="end_{{ $t['name'] }}">
                                            </div>
                                        </div>
                                    @elseif ($t['type'] == 'number')
                                        <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                                        <div class="d-flex mb-2">
                                            <div class="form-group mr-2" style="margin-right:2px;width: 50%;">
                                                <label style="font-size: 12px;"
                                                    for="min_{{ $t['name'] }}">Min</label>
                                                <input type="number" class="form-control"
                                                    name="min_{{ $t['name'] }}">
                                            </div>
                                            <div class="form-group" style="width: 50%;">
                                                <label style="font-size: 12px;"
                                                    for="max-{{ $t['name'] }}">Max</label>
                                                <input type="number" class="form-control"
                                                    name="max_{{ $t['name'] }}">
                                            </div>
                                        </div>
                                    @elseif($t['type'] == 'select')
                                        <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                                        <div class="form-group mb-2 mt-2">
                                            <select class="form-control" name="{{ $t['name'] }}">
                                                <option value="">Select {{ $t['label'] }}</option>
                                                @foreach ($t['options'] as $p)
                                                    <option value="{{ $p->id }}"
                                                        @if (isset($t['default']) && $p->id == $t['default']) selected @endif>
                                                        {{ $p->name }}</option>
                                                @endforeach

                                            </select>

                                        </div>
                                    @elseif($t['type'] == 'text')
                                        <b style="font-weight: 600;font-size: 13px;">{{ $t['label'] }}</b>
                                        <div class="form-group mb-2">
                                            <input class="form-control" name="{{ $t['name'] }}" type="text" />
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="form-group mt-2">

                            </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary rounded-0">Submit</button>
                <button type="button" class="btn btn-danger rounded-0" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
    </div>
    </div>
    </div>
@endif
