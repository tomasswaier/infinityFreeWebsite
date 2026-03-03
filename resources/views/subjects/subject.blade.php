@extends('layouts.app')
@section('mainContent')

<div class="p-10 inline-block float-left lx:w-3/4">
    <div class="ml-20">
        <h1 class="text-2xl">{{$subject['name']}}</h1>
        <span>Average rating:{{$subject['rating']}}/10. (Rateing is average of past 20 votes)</span>
    </div>
    <div class="ml-20">
        <h1 class="text-2xl"></h1>
        @auth
        <form action="{{url('subjects/rating/'.$subject['id'])}}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="subject_id" value="{{$subject['id']}}">
            @csrf
            <span>Rate the subject !!! Your rating:</span>
            <input type="number" name="userRating" class="w-20"
                @isset($subject_user_rating)
                    value="{{$subject_user_rating}}"
                @else
                    value="1"
                @endisset
            >
            <span>/10</span>
        </form>
        @endauth
    </div>
    <div class="md:w-1/2 float-start">
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
@if(Auth::user() && supervisesSchool(Auth::user(),$school_id))
<div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
    <h4>admin panel</h4>
    <a href=""></a>
    <div class="bg-project-blue text-white rounded-sm p-2 mb-2">
        <a href="{{url('admin/subjectCreator/'.$subject['school_id'].'/'.$subject['id'])}}">edit subject</a>
    </div>
</div>
@endif
@endsection
