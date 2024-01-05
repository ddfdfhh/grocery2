@props(['data', 'column', 'radio'])
<style>
    .jFiler-theme-default .jFiler-input {
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        width: 100% !important;
    }

    .group-span-filestyle>label {
        height: 100%;
        border-radius: 0;
        padding-top: 11px;
    }



    .jFiler-input-caption span {
        color: #bbbdc2 !important;
        font-size: 15px !important;
    }

    .jFiler-input,
    {
    border-radius: 0 !important;
    max-width:200px!important;
    }

    .jFiler-input-button {
        background: white !important;
        border-radius: 0 !important;
      
        color: black;

        
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
        font-size: 14px;

        transition: all .2s ease-in-out;

    }
   
</style>


            @foreach ($data as $item)
                @php
                    
                    $inputs = $item['inputs'];
                    
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
                            $r['label'] = str_replace(' Id', '', $r['label']);
                            if (isset($r['placeholder'])) {
                                $r['placeholder'] = str_replace('_', ' ', $r['placeholder']);
                            }
                            $my_col = $col;
                            if (isset($r['col'])) {
                                $my_col = $r['col'];
                            }
                        @endphp
                        @if ($r['tag'] == 'input')
                            @if ($r['type'] == 'file')
                                <div class="col-md-{{ $my_col }} mb-3">

                                    {!! Form::text($r['name'], $r['label'])->value($r['default'])->primary()->type($r['type'])->placeholder($r['placeholder'])->attrs(array_merge($attrs, ['class' => 'form-control'])) !!}
                                   
                                   
                                    @if (!empty($r['default']))
                                        <div class="d-flex">
                                            <x-showImageInEdit :default="$r['default']" />
                                        </div>
                                        @else
                                     
                                    @endif

                                </div>
                            @endif
                        @endif
                    @endforeach

                </div>
            @endforeach
     
