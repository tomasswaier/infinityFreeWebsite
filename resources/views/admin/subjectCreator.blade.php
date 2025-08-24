@extends('layouts.app')
@section('mainContent')
<div class="p-10 text-project-dark-blue text-lg w-full h-full flex justify-center align-middle">
    <div class="p-4 bg-white inline-block rounded-lg shadow-neutral-500 shadow-md">
        <div class="grid grid-flow-row row-auto align-middle">
            <form action="{{url('admin/subjectCreator')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="school_id" value="{{$school_id}}">
                @isset($subject)
                    <input type="hidden" name="subjectId" value="{{$subject['id']}}">
                @endisset
                <h2>Subject Creator Page</h2>
                <span>test Id:</span><br><br>
                <div class="p-4">
                    <span>Name:</span>
                    <input type="text" name="subjectName" value="{{isset($subject)? $subject['name']:''}}" class="rounded-lg  bg-project-white  p-2 shadow-inner"  >
                    <span>Rating:</span>
                    <input type="number" name="subjectRating" value="{{isset($subject)? $subject['rating']:''}}" step="0.1" max='10' class="rounded-lg  bg-project-white shadow-inner p-2 w-20">
                </div>
                <div class="p-4">
                    <span>Description:</span><br>

                    <textarea name="subjectDescription" rows="3" cols="40" placeholder="in description try to mention everything from projects to lectures" class="rounded-lg  bg-project-white shadow-inner p-2">{{isset($subject)? $subject['description']:''}}</textarea>
                </div>
                <div class="p-4">
                    <span>TLDR:</span>
                    <br>
                    <textarea name="subjectTldr" rows="3" cols="40" placeholder="Write in here the most relevant parts of description like complexity of projects and tests (1024 chars long)" class="rounded-lg  bg-project-white shadow-inner p-2">{{isset($subject)?$subject['tldr']:''}}</textarea>
                </div>
                <div>
                    <span>Choose Tags</span>
                    <div>
                        @foreach($allTags as $tag)
                            <span>{{$tag['name']}}:</span><input type="checkbox" name="tag_id_{{$tag['id']}}"  {{isset($selectedTags)&&in_array($tag['id'],$selectedTags)? 'checked="true"' : ''}}value="true">
                            <br>
                        @endforeach

                    </div>
                    <br>
                </div>
                <button type="submit" class=" rounded-xl  p-2 shadow-neutral-800 shadow-sm">submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
