@php
    
    $table_columns = array_column($table_columns, 'column');
@endphp
@if ($list->total() > 0)
    @php
        $i = $list->perPage() * ($list->currentPage() - 1) + 1;
        $l = 0;
    @endphp
    @foreach ($list as $r)
        @php
            
            $deleteurl = route($plural_lowercase . '.destroy', [\Str::singular($plural_lowercase) => $r->id]);
            $editurl = route($plural_lowercase . '.edit', [\Str::singular($plural_lowercase) => $r->id]);
            $viewurl =  route($plural_lowercase . '.show', [\Str::singular($plural_lowercase) => $r->id]);
            
        @endphp
        <tr id="row-{{ $r->id }}">
            <td>
                {{ $i++ }}


            </td>
            @foreach ($table_columns as $t)
                @php   ++$l;@endphp
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$r->{$t}' />
                    </td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($r->{$t}) }}</td>
                  @elseif(isFieldPresentInRelation($model_relations, $t) >= 0)
                    @if (
                        $r->{$t} &&
                            (preg_match("/image$/", $t) ||
                                preg_match("/_image$/", $t) ||
                                preg_match("/_doc$/", $t) ||
                                preg_match("/_file$/", $t) ||
                                preg_match("/_pdf$/", $t)))
                        <td>

                            <x-singleFile :fileName="$r->{$t}" :modelName="$module" :folderName="$storage_folder" :fieldName="$t"
                                :rowid="$r->id" />
                        </td>
                    @elseif(preg_match("/images$/", $t) ||
                            preg_match("/_images$/", $t) ||
                            preg_match("/_docs$/", $t) ||
                            preg_match("/_files$/", $t) ||
                            preg_match("/_pdfs$/", $t))
                        <td>
                            <!-- here image list is list of table row in object form *****-->

                            <x-showImages :row=$r :fieldName=$t :storageFolder=$storage_folder :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                        </td>
                    @else
                        <td>{{ getForeignKeyFieldValue($model_relations, $r, $t, ['BelongsTo' => 'name']) }}</td>
                    @endif
                @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                        $r->{$t} &&
                        (preg_match("/image$/", $t) ||
                            preg_match("/_image$/", $t) ||
                            preg_match("/_doc$/", $t) ||
                            preg_match("/_file$/", $t) ||
                            preg_match("/_pdf$/", $t)))
                    <td>

                        <x-singleFile :fileName="$r->{$t}" :modelName="$module" :folderName="$storage_folder" :fieldName="$t"
                            :rowid="$r->id" />
                    </td>
                @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                        (preg_match("/images$/", $t) || preg_match("/_images$/", $t) ||
                        preg_match("/_docs$/", $t) ||
                        preg_match("/_files$/", $t) ||
                        preg_match("/_pdfs$/", $t)))
                    <td>
                        <!-- here image list is list of table row in object form *****-->

                        <x-showImages :row=$r :fieldName=$t :storageFolder=$storage_folder :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                    </td>
                @else
                    <td>
                        @php
                            if (!is_numeric($r->{$t})) {
                                $tr = json_decode($r->{$t}, true);
                            
                                if ($tr !== null) {
                                     /*   $tr = array_map(function ($v) {
                                       
                                        $v['date']=formateDate($v['date'],true);
                                        return $v;
                                    }, $tr);
                                  
                                   $delete_data_info=['row_id_val'=>$r->id,'table'=>'leads','json_column_name'=>'conversations','delete_url'=>route('deleteInJsonColumnData')];
                                    echo showArrayInColumn($tr, $l,'by_user_id','lg','Remarks',true,$delete_data_info);*/
                                    echo showArrayInColumn($tr, $l);
                                } else {
                                    echo $r->{$t};
                                }
                            } else {
                                echo $r->{$t};
                            }
                            
                        @endphp
                    </td>
                @endif
            @endforeach
            <td>{{implode(',',$r->getRoleNames()->toArray())}}</td>
            <td>
               @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('view_' . $plural_lowercase))
                <a class="btn btn-success btn-icon" title="View"
                    href="javascript:viewRecord('{!! $r->id !!}','{!! $viewurl !!}','{!!strtolower($module)!!}')">
                    <i class="bx bx-dice-4"></i>
                </a>
                @endif
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('edit_' . $plural_lowercase))
                    <a class="btn  btn-info btn-icon" title="Edit"
                        href="{{ $editurl }}">
                        <i class="bx bx-edit"></i> </a>
                @endif
                @if (auth()->user()->hasRole(['Admin']) ||
                        auth()->user()->can('delete_' . $plural_lowercase))
                    <a class="btn  btn-danger btn-icon" title="Delete"
                        href="javascript:deleteRecord('{!! $r->id !!}','{!! $deleteurl !!}');">
                        <i class="bx bx-trash"></i></a>
                @endif
                {{-- <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                            class="bx bx-dots-vertical-rounded"></i></button>
                    <div class="dropdown-menu">
                             <a class="dropdown-item" href="javascript:viewRecord('{!! $r->id !!}','{!! $viewurl !!}','{!!strtolower($module)!!}');"><i class="bx bx-trophy me-2"></i> View</a>
                @if (auth()->user()->hasRole(['Admin']) ||
    auth()->user()->can('edit_' . $plural_lowercase))
                        <a class="dropdown-item" href="{{ route($plural_lowercase . '.edit', [strtolower($module) => $r->id]) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                     @endif 
                       @if (auth()->user()->hasRole(['Admin']) ||
    auth()->user()->can('delete_' . $plural_lowercase))
                          <a class="dropdown-item" href="javascript:deleteRecord('{!! $r->id !!}','{!! $deleteurl !!}');"><i class="bx bx-trash me-2"></i> Delete</a>
                    @endif 
                     </div>
                </div> --}}
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
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title">View {{ $module }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="spinner-border text-muted"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
