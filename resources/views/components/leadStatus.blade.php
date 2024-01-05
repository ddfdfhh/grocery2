@props(['status'])
@if($status=='Converted' )
<div class="badge bg-label-success me-1 ">{{$status}}</div>
@elseif($status=='Working' || $status=='Partial')
<div class="badge bg-label-warning me-1">{{$status}}</div>
@elseif($status=='Failed' || $status=='Partial')
<div class="badge bg-label-danger me-1">{{$status}}</div>
@elseif($status=='New')
<div class="badge bg-label-info me-1">{{$status}}</div>
@elseif($status=='Contacted')
<div class="badge bg-label-primary me-1">{{$status}}</div>
@endif