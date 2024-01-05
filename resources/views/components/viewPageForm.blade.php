@props(['data', 'column', 'radio'])
<style>
    .jFiler-theme-default .jFiler-input {
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        width: 100% !important;
    }
.group-span-filestyle >label{
height:100%;border-radius:0;
padding-top:11px;
}
    input[type=text] {
        background: white !important;
    }

    .jFiler-input-caption span {
        color: #bbbdc2 !important;
        font-size: 15px !important;
    }
    .jFiler-input,
{
border-radius: 0!important;
}
    .jFiler-input-button {
    border-radius: 0!important;
        color: white !important;
        color: #fff;
        background: #69809a !important;
        border-color: #69809a;
        box-shadow: 0 0.125rem 0.25rem rgb(147 158 170 / 40%);
        display: inline-block;
        font-weight: 400;
        line-height: 1.4;
        padding-top: 6px !important;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.469rem 1.375rem;
        font-size: 0.9375rem;
        border-radius: 0.25rem;
        transition: all .2s ease-in-out;

    }
</style>
@foreach ($data as $item)
    @php
        
        $has_legend = false;
        $inputs = $item['inputs'];
        if (strlen($item['label']) > 0) {
            $has_legend = true;
        }
        
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
    @if ($has_legend)
        <fieldset class="form-group border p-3 fieldset">
            <legend class="legend">{{ $item['label'] }}</legend>
    @endif
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
                $r['label']=str_replace(' Id', '', $r['label']);
                if(isset($r['placeholder']))
                $r['placeholder']=str_replace('_', ' ', $r['placeholder']);
            @endphp

            <div class="col-md-{{ $col }} mb-3">
                @if ($r['tag'] == 'input')
                    @if ($r['type'] == 'text' || $r['type'] == 'password'  || $r['type'] == 'number' || $r['type'] == 'email'  || $r['type'] == 'date'  || $r['type']=='datetime-local')
                        {!! Form::text($r['name'], $r['label'])->value($r['default'])->type($r['type'])->placeholder($r['placeholder'])->attrs($attrs) !!}
                    @elseif($r['type'] == 'file')
                        {!! Form::text($r['name'], $r['label'])->value($r['default'])->type($r['type'])->placeholder($r['placeholder'])->attrs(array_merge($attrs,['class' => 'form-control'])) !!}
                        @if (!empty($r['default']))
                            <div class="d-flex">
                                <x-showImageInEdit :default="$r['default']" />
                            </div>
                        @endif
                    @elseif ($r['type'] == 'radio' || $r['type'] == 'checkbox')
                        <p style="font-weight:450;font-size: 14px;color: #277fc6">{{ $r['label'] }}</p>
                        <div class="align-items-center">
                            @if ($r['multiple'])
                                @foreach ($r['value'] as $t)
                                    @php
                                        ++$y;
                                        $checked = count($r['default']) > 0 ? (in_array($t->value, $r['default']) ? true : false) : ($y == 1 ? true : false);
                                   $inline=count($r['value'])>6?false:true;
                                    @endphp

                                    
                                        @if ($r['inline'])
                                            {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($r['inline']) !!}
                                        @else
                                            {!! Form::checkbox($r['name'] . '[]', $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($inline) !!}
                                        @endif
                                  

                                    
                                @endforeach
                            @else
                                @foreach ($r['value'] as $t)
                                    @php
                                        ++$y;
                                        $checked = $r['default'] ? ($t->value == $r['default'] ? true : false) : ($y == 1 ? true : false);
                                         $inline=count($r['value'])>6?false:true;
                                    @endphp
                                    @if ($r['type'] == 'checkbox')
                                        @if ($r['inline'])
                                            {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($r['inline']) !!}
                                        @else
                                            {!! Form::checkbox($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($inline) !!}
                                        @endif
                                    @endif
                                    @if ($r['type'] == 'radio')
                                        @if ($r['inline'])
                                            {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($r['inline']) !!}
                                        @else
                                            {!! Form::radio($r['name'], $t->label, $t->value)->attrs($r['attr'])->checked($checked)->inline($inline) !!}
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
                    data-rowid="{{ !empty($r['has_toggle_div']['rowid']) ? $r['has_toggle_div']['rowid'] : '' }}"
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
@if ($has_legend)
    </fieldset>
@endif
@endforeach
