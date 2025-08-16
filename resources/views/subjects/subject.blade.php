@extends('layouts.app')
@section('mainContent')

<div class="p-10 inline-block float-left w-3/4">
    <div class="ml-20">
        <h1 class="text-2xl">{{$subject['name']}}</h1>
        <span>Rating:{{$subject['rating']}}/10</span>
    </div>
    <div class="w-1/3 float-start">
        <div class="p-2">
            <span>Tags:</span>
            @foreach($tags as $tag)
                <span class="bg-project-light-blue p-2 rounded-sm mx-1">{{$tag->name}}</span>
            @endforeach
        </div>
        <span> <span class="font-bold text-lg">TLDR:</span>{{$subject['tldr']}}</span><br>
        <span class="font-bold text-lg">Description:</span><span>{{$subject['description']}}</span>
        <br>
        <br>
    </div>
    <div class=" bg-red-100 w-30 h-30 "></div>
</div>
@if(Auth::user() && Auth::user()->authorization=='admin')
<div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
    <h4>admin panel</h4>
    <a href=""></a>
    <div class="bg-project-blue text-white rounded-sm p-2 mb-2">
        <a href="{{url('admin/subjectCreator/'.$subject['school_id'].'/'.$subject['id'])}}">click me</a>
    </div>
</div>
@endif
@endsection
