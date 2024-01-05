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
           $storage_folder=$storage_folder.'/'.$r->id;
              
         @endphp
        <tr id="row-{{ $r->id }}">
            <td>
                {{ $i++ }}
                <input name="ids[]" class="form-check-input" type="checkbox" value="{{ $r->id }}" />



            </td>
            @foreach ($table_columns1 as $t)
                @php   ++$l;
                
                @endphp
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
                        <td>{{ getForeignKeyFieldValue($model_relations, $r, $t) }}</td>
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
                        (preg_match("/images$/", $t) ||
                            preg_match("/_images$/", $t) ||
                            preg_match("/_docs$/", $t) ||
                            preg_match("/_files$/", $t) ||
                            preg_match("/_pdfs$/", $t)))
                    <td>
                        <!-- here image list is list of table row in object form *****-->

                        <x-showImages :row=$r :fieldName=$t :storageFolder=$storage_folder :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                    </td>
                @else
                    <td class="text-start">

                        @php
                            if (!is_numeric($r->{$t})) {
                                $tr = json_decode($r->{$t}, true);
                            
                                $by_json_key = isset($table_columns[$l - 1]['by_json_key']) ? $table_columns[$l - 1]['by_json_key'] : 'id';
                                if ($tr !== null) {
                                    $hide_columns = isset($table_columns[$l - 1]['hide_columns_in_json_view']) ? $table_columns[$l - 1]['hide_columns_in_json_view'] : [];
                            
                                    if (count($hide_columns) > 0) {
                                        $tr = array_map(function ($v) use ($hide_columns) {
                                            foreach ($hide_columns as $col) {
                                                unset($v[$col]);
                                            }
                                            return $v;
                                        }, $tr);
                                    }
                                    if (isset($table_columns[$l - 1]['show_json_button_click'])) {
                                        if ($table_columns[$l - 1]['show_json_button_click']) {
                                            echo showArrayInColumn($tr, $l, $by_json_key);
                                        } else {
                                            echo showArrayInColumnNotButtonForm($tr, $l, $by_json_key);
                                        }
                                    } else {
                                        echo showArrayInColumn($tr, $l, $by_json_key);
                                    }
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
            <td>
                <x-crudButtons :row="$r" :pluralLowercase="$plural_lowercase" :module="$module" :crudTitle="$crud_title"
                    :hasPopup="$has_popup" :showCrudInModal="$show_crud_in_modal" />
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
<div id="view_modal" class="modal fade" role="dialog">
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
