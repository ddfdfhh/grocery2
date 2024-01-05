@extends('layouts.admin.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y pt-5">
       

        <div class="row">
            <!-- Basic Layout -->
            <div class="col-xxl">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">View {{ properSingularName($plural_lowercase) }}</h5>
                    </div>

                    <div class="card-body">
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

                    
                    </div><br>
                </div>
            </div>
        </div>
    </div>
@endsection
