<table class="table table-bordered">
    <tbody>

        @php
            $l = 0;
            $table_columns1 = array_column($view_columns, 'column');
        @endphp
        @foreach ($view_columns as $t)
            @php ++$l;
            $t=$t['column']; @endphp
            <tr>
             <th>{{ ucwords($view_columns[$l-1]['label']) }}</th>
                @if (str_contains($t, 'status'))
                    <td>
                        <x-status :status='$row->{$t}' />
                    </td>
                @elseif(str_contains($t, '_at') || str_contains($t, 'date'))
                    <td>{{ formateDate($row->{$t}) }}</td>
                @elseif($t=='permissions')
                  
                        <td>
                        @foreach($row->permissions as $y)

                        {{$y->label}},<br>
                        @endforeach
                         </td>
                   
                 @else
                  <td>{!!$row->{$t} !!}</td>
                @endif
            </tr>
        @endforeach

    </tbody>
</table>
