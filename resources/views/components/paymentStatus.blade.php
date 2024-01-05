@props(['status'])
@if($status=='Paid' )
<div class="badge bg-label-success me-1 ">{{$status}}</div>
@elseif($status=='Cancelled' || $status=='Partial')
<div class="badge bg-label-warning me-1">{{$status}}</div>
@elseif($status=='Pending' || $status=='Unpaid')
<div class="badge bg-label-danger me-1">{{$status}}</div>
@endif