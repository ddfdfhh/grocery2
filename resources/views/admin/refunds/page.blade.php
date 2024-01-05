@php

    $table_columns1 = array_column($table_columns, 'column');
  
@endphp
@if ($list->total() > 0)
    @php
        $i = $list->perPage() * ($list->currentPage() - 1) + 1;
        $l = 0;
    @endphp
    @foreach ($list as $r)
    @php

   
    $deleteurl = route($plural_lowercase . '.destroy', [\Str::singular($plural_lowercase) => $r->id]);
    $viewurl = route($plural_lowercase . '.show', [\Str::singular($plural_lowercase) => $r->id]);

@endphp
        <tr id="row-{{ $r->id }}">
            <td>
                {{ $i++ }}
                <input name="ids[]" class="form-check-input" type="checkbox" value="{{ $r->id }}" />


            </td>
            @foreach ($table_columns1 as $t)
                @php   ++$l;@endphp
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$r->{$t}' />
                    </td>
                    @elseif($t=='order_id')
                    <td class="text-start">
                        <a target="" href="{{route('orders.show',['order'=>$r->{$t}])}}">View Detail</a></td>
                   
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($r->{$t}) }}</td>
                    @elseif(isFieldPresentInRelation($model_relations, $t) >= 0)
                    <td>{{ getForeignKeyFieldValue($model_relations, $r, $t) }}</td>
                @else
                    <td class="text-start">

                        @php

                            echo $r->{$t};

                        @endphp
                    </td>
                @endif
            @endforeach
            <td>
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('view_' . $plural_lowercase))
                    @if ($show_view_in_popup)
                        <a class="btn btn-outline-primary btn-icon" title="View"
                            href="javascript:load_form_offcanvas('{!! strtolower($module) !!}','{!! $viewurl !!}','{!! $crud_title !!}','View')">
                            <i class="bx bx-slideshow"></i>
                        </a>
                    @else
                    <a class="btn btn-outline-primary btn-icon" title="View" href='{!! $viewurl !!}'>
                        <i class="bx bx-slideshow"></i>
                    </a>
                    @endif
                @endif
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('delete_' . $plural_lowercase))
                    <a class="btn  btn-outline-danger btn-icon" title="Delete"
                        href="javascript:deleteRecord('{!! $r->id !!}','{!! $deleteurl !!}');">
                        <i class="bx bx-trash"></i></a>
                @endif
            </td>


        </tr>
    @endforeach
    <td colspan='7'>{!! $list->links() !!}</td>
    </tr>
@else
    <tr>
        <td colspan="{{ count($table_columns) + 1 }}" align="center">No records</td>
    </tr>
@endif
<div id="{{ strtolower($module) }}_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">View {{ $module }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="spinner-border text-muted"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
