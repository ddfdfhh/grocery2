<table class="table table-bordered">
    <tbody>

        @php
            $l = 0;
            $table_columns1 = array_column($table_columns, 'column');
        @endphp
        @foreach ($table_columns as $t)
            @php ++$l;
            $t=$t['column']; @endphp
            <tr>
             <th>{{ ucwords($table_columns[$l-1]['label']) }}</th>
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$row->{$t}' />
                    </td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($row->{$t}) }}</td>
                @elseif(isFieldPresentInRelation($model_relations, $t) >= 0)
                    @if (
                        $row->{$t} &&
                            (preg_match("/image$/", $t) ||
                                preg_match("/_image$/", $t) ||
                                preg_match("/_doc$/", $t) ||
                                preg_match("/_file$/", $t) ||
                                preg_match("/_pdf$/", $t)))
                        <td>

                            <x-singleFile :fileName="$row->{$t}" :modelName="$module" :folderName="$storage_folder" :fieldName="$t"
                                :rowid="$row->id" />
                        </td>
                    @elseif(preg_match("/images$/", $t) ||
                            preg_match("/_images$/", $t) ||
                            preg_match("/_docs$/", $t) ||
                            preg_match("/_files$/", $t) ||
                            preg_match("/_pdfs$/", $t))
                        <td>
                            <!-- here image list is list of table row in object form *****-->

                            <x-showImages :row=$row :fieldName=$t :storageFolder=$storage_folder :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                        </td>
                    @else
                        <td>{{ getForeignKeyFieldValue($model_relations, $row, $t, ['BelongsTo' => 'name']) }}</td>
                    @endif
                @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                        $row->{$t} &&
                        (preg_match("/image$/", $t) ||
                            preg_match("/_image$/", $t) ||
                            preg_match("/_doc$/", $t) ||
                            preg_match("/_file$/", $t) ||
                            preg_match("/_pdf$/", $t)))
                    <td>

                        <x-singleFile :fileName="$row->{$t}" :modelName="$module" :folderName="$storage_folder" :fieldName="$t"
                            :rowid="$row->id" />
                    </td>
                @elseif(isFieldPresentInRelation($model_relations, $t) < 0 &&
                        (preg_match("/images$/", $t) ||
                            preg_match("/_images$/", $t) ||
                            preg_match("/_docs$/", $t) ||
                            preg_match("/_files$/", $t) ||
                            preg_match("/_pdfs$/", $t)))
                    <td>
                        <!-- here image list is list of table row in object form *****-->

                        <x-showImages :row=$row :fieldName=$t :storageFolder=$storage_folder :tableName="getTableNameFromImageFieldList($image_field_names, $t)" />
                    </td>
                @else
                    <td>
                        @php
                            if (!is_numeric($row->{$t})) {
                                $tr = json_decode($row->{$t}, true);
                            
                                if ($tr !== null) {
                                    echo showArrayInColumn($tr, $l);
                                } else {
                                    echo $row->{$t};
                                }
                            } else {
                                echo $row->{$t};
                            }
                            
                        @endphp
                    </td>
                @endif
            </tr>
        @endforeach
<tr><th class="text-start">Role</th><td>{{implode(',',$row->getRoleNames()->toArray())}}</td></tr>
    </tbody>
</table>
