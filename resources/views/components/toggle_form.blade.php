@props(['inputs', 'column','row'])

@php
    
    $inputs = array_map(function ($v) {
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
    }, $inputs);
    
    $col = $column == 3 ? 4 : ($column == 2 ? 6 : 12);
    
@endphp

<div class="row">
    @php
        $x = 0;
        $y = 0;
    @endphp
    @foreach ($inputs as $r)
        @php
            ++$x;
            $has_toggle_div = isset($r['has_toggle_div']['toggle_div_id']) ? true : false;
            $attrs = isset($r['attr']) ? $r['attr'] : [];
            if (isset($r['event'])) {
                $attrs[$r['event']['name']] = $r['event']['function'];
            }
             $spl = explode("__json__", $r['name']);
            $col_name = $spl[0];
            $key_name = rtrim($spl[1],'[]');

            $r['default']=!empty($row)?$row->{$key_name}:'';
            $r['label']=str_replace(' Id', '', $r['label']);
        @endphp

        <div class="col-md-{{ $col }} mb-3">

            @if ($r['tag'] == 'input')
                @if ($r['type'] == 'text' || $r['type'] == 'number' || $r['type'] == 'email' || $r['type'] == 'file'|| $r['type'] == 'date'|| $r['type'] == 'datetime-local')
                    {!! Form::text($r['name'], $r['label'])->value($r['default'])->type($r['type'])->placeholder($r['placeholder'])->attrs($attrs) !!}
                    @if ($r['type'] == 'file' && !empty($r['default']))
                        <div class="d-flex">
                            <x-showImageInEdit :default="$r['default']" />
                        </div>
                    @endif
                @elseif ($r['type'] == 'radio' || $r['type'] == 'checkbox')
                    <p style=" font-weight:600;font-size: 14px;margin:0">{{ $r['label'] }}</p>
                    <div class="align-items-center">
                        @if ($r['multiple'])
                            @foreach ($r['value'] as $t)
                                @php
                                    ++$y;
                                    $checked = count($r['default']) > 0 ? (in_array($t->value, $r['default']) ? true : false) : ($y == 1 ? true : false);
                                @endphp

                                @if ($r['type'] == 'checkbox')
                                    @if ($r['inline'])
                                        {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                    @else
                                        {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                    @endif
                                @endif

                                @if ($r['type'] == 'radio')
                                    @if ($r['inline'])
                                        {!! Form::radio($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                    @else
                                        {!! Form::radio($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                    @endif
                                @endif
                            @endforeach
                        @else
                            @foreach ($r['value'] as $t)
                                @php
                                    ++$y;
                                    $checked = $r['default'] ? ($t->value == $r['default'] ? true : false) : ($y == 1 ? true : false);
                                @endphp
                                @if ($r['type'] == 'checkbox')
                                    @if ($r['inline'])
                                        {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                    @else
                                        {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                    @endif
                                @endif
                                @if ($r['type'] == 'radio')
                                    @if ($r['inline'])
                                        {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline() !!}
                                    @else
                                        {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline(false) !!}
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                @else
                @endif
            @elseif($r['tag'] == 'textarea')
                @php
                    
                    $attrs['id'] = 'summernote-' . $r['name'];
                    
                @endphp
                {!! Form::textarea($r['name'], $r['label'], $r['default'])->value($r['default'])->attrs($attrs)->placeholder($r['placeholder']) !!}
            @elseif($r['tag'] == 'select' && !$r['multiple'])
                @php
                    $attr['class'] = 'select2';
                @endphp
                {!! Form::select($r['name'], $r['label'])->options($r['options'], $r['custom_key_for_option'], $r['custom_id_for_option'], 'id')->value($r['default'])->attrs($attrs) !!}
            @elseif($r['tag'] == 'select' && $r['multiple'])
                {!! Form::select($r['name'] . ($r['multiple'] ? '[]' : ''), $r['label'])->options($r['options'], $r['custom_key_for_option'], $r['custom_id_for_option'], 'id')->value($r['default'])->attrs($attrs)->multiple() !!}
            @else
            @endif
        </div>
        @if ($has_toggle_div)
            <div class="col-md-{{ $col }} mb-3 toggable_div" id="{{ $r['has_toggle_div']['toggle_div_id'] }}"
                data-module="{{ $r['has_toggle_div']['plural_lowercase'] }}"
                data-colname="{{ $r['has_toggle_div']['colname'] }}"
                data-inputidforvalue="{{ $r['has_toggle_div']['inputidforvalue'] }}">

            </div>
        @endif
    @endforeach
    @if ($x % 2 == 0)
</div>
@else
</div>
<!---first row end here if x is not mutliple of 2 -->
@endif
