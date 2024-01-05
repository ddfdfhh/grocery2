@props(['i', 'timetable', 'subjectTeacher', 'teachers'])
<style>
.p td{
border:1px solid black;
}
.table-border-bottom-0 tr:last-child td, .table-border-bottom-0 tr:last-child th {
    border-bottom-width: 1px!important;
}
</style>
@php
    $timetable = json_decode($timetable, true);
    $subjectTeacher = json_decode($subjectTeacher, true);
    
@endphp
<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#myModal{{ $i }}">
    Show
</button>

<!-- The Modal -->
<div class="modal p" id="myModal{{ $i }}">
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Class Timetable</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table class="table"  cellspacing="3" align="center">
                    <tr>
                        <td align="center" class="font-bold"></td>
                            @php
                                $keys = array_keys($timetable);

                                $row = $timetable[$keys[0]];
                            @endphp
                            @foreach ($row as $p)
                        <td><b>{{ $p['from'] }}-{{ $p['to'] }}</b></td>
                        @endforeach
                    </tr>
                    <tr>
                        <td align="center"><b>MONDAY</b></td>
                            @php
                                $iter = $timetable['Monday'];
                            @endphp
                            @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
                                            <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                            @endif
                                        
                                   </td>
                           @endforeach

                    </tr>
                    <tr>
                        <td align="center"><b>TUESDAY</b></td>
                            @php
                                $iter = $timetable['Tuesday'];
                            @endphp
                              @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
 <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                             @endif
                                        
                                   </td>
                           @endforeach

                    </tr>
                    <tr>
                        <td align="center"><b>WEDNESDAY</b></td>
                            @php
                                $iter = $timetable['Wednesday'];
                            @endphp
                              @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
                                            <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                            @endif
                                        
                                   </td>
                           @endforeach

                    </tr>
                    <tr>
                        <td align="center"><b>THURSDAY</b></td>
                            @php
                                $iter = $timetable['Thursday'];
                            @endphp
                             @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
                                            <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                            @endif
                                        
                                   </td>
                           @endforeach

                    </tr>
                    <tr>
                        <td align="center"><b>FRIDAY</b></td>
                            @php
                                $iter = $timetable['Friday'];
                            @endphp
                             @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
                                            <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                            @endif
                                        
                                   </td>
                           @endforeach

                    </tr>
                    <tr>
                        <td align="center"><b>SATURDAY</b></td>
                            @php
                                $iter = $timetable['Saturday'];
                            @endphp
                            @foreach ($iter as $p)
                                        <td align="center">
                                            <p>{{ $p['subject'] }}</p>
                                            @if(!in_array($p['subject'],getNonSubjectType()))
                                             <p><b>{{ $teachers[$subjectTeacher[$p['subject']]] }}</b></p>
                                            @endif
                                        
                                   </td>
                           @endforeach

                    </tr>

                </table>


            </div>

            <!-- Modal footer -->
            <div class="modal-footer mt-2">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
