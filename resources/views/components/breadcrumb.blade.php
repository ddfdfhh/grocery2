
@props(['dashboardUrl','indexUrl','title','currentModule'])
<div class="breadcrumb-item active"><a href="{{$dashboardUrl}}">Dashboard</a></div>
<div class="breadcrumb-item"><a href="{{$indexUrl}}">{{$currentModule}}</a></div>
<div class="breadcrumb-item">{{$title}}</div>