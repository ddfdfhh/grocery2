@props(['q'])
<div style="display:inline-block">{!! $q['question'] !!}</div>
@if ($q['question_type'] == 'MCQ')
    <div class="d-flex justify-content-between ps-2">
        <div>
            <b>(A)</b>
            <div style="display:inline-block">{!! $q['option_a'] !!}</div>
        </div>
        <div>
            <b>(B)</b>
            <div style="display:inline-block">{!! $q['option_b'] !!}</div>
        </div>
    </div>
    <div class="d-flex justify-content-between ps-2">
        <div>
            <b>(C)</b>
            <div style="display:inline-block">{!! $q['option_c'] !!}</div>
        </div>
        <div>
            <b>(D)</b>
            <div style="display:inline-block">{!! $q['option_d'] !!}</div>
        </div>
    </div>
    @if ($q['option_e'])
        <div class="ps-2">
            <b>(E)</b>
            <div style="display:inline-block">{!! $q['option_e'] !!}</div>
        </div>
    @endif
    <p><b>Answer</b>:
    <div style="display:inline-block" class="ps-3">
        {!! $q['answer'] !!}
    </div>
    </p>
    @if (!empty($q['answer_explaination']))
        <p> <b>Explaination</b>:
        <div style="display:inline-block" class="ps-3"  >
            {!! $q['answer_explaination'] !!}
        </div>
        </p>
    @endif
@endif
