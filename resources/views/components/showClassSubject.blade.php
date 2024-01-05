@props(['i', 'subjects'])
@php
    $subjects = json_decode($subjects);
@endphp
<button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#subjectModalal{{ $i }}">
    Show
</button>

<!-- The Modal -->
<div class="modal" id="subjectModalal{{ $i }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Class Subjects</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="d-flex flex-wrap">

                    @foreach ($subjects as $sub)
                        <div class="card m-2">
                            <div class="card-body">
                                <div class="card-text">{{ $sub }}</div>
                                <div class="card-text">Teacher</div>


                            </div>




                        </div>
                    @endforeach
                </div>


            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
