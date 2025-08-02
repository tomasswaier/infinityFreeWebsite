@extends('layouts.app')
@section('mainContent')

@if(Auth::user() && Auth::user()->authorization=='admin')
<div class="h-20 bg-project-blue text-white">
    <a href="{{url('admin/subjectCreator/'.$subject['school_id'].'/'.$subject['id'])}}">click me</a>
</div>
@endif

<div class="p-10">
    <div class="ml-20">
        <h1 class="text-2xl">PPI</h1>
        <span>Rating:0/10</span>
    </div>
    <div class="w-1/3 float-start">
        <span> <span class="font-bold text-lg">TLDR:</span>{{$subject['tldr']}}</span><br>
        <span class="font-bold text-lg">Description:</span><span>{{$subject['description']}}</span>
        <br>
        <br>
    </div>
    <div class=" bg-red-100 w-30 h-30 "></div>
</div>
@endsection
