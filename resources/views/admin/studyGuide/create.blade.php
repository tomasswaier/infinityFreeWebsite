@extends('layouts.app')
@section('mainContent')
<div>
    <h2>Study Guide creator page</h2>
    <span>rules?</span>
    <br>
    <span>Section is NOT a section until a title is added</span>
    <br>
    <span>Text is displayed in p element so to add something to a new line create a new section(intended design)</span>

    <div >
    <span>Choose which subject does this study guide explain(maybe add tags later for when you have 2 that go over webdev or sum ?)</span>
    <select>
        <option value="0"></option>
        @foreach($subjects as $subject)
        <option value="{{$subject->id}}">{{$subject->name}}</option>
        @endforeach
    </select>
    <br><br>
        <form id='mainForm' action="{{url('admin/studyGuide/create/'.$school_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="">
                <input type="text" name="title" placeholder="Study Guide title" required>
                <div id='studyGuideContents'>

                </div>
            </div>
            <br>
           <button type="sumbit">submit</button>
        </form>

    </div>


</div>

<script src="{{ asset('js/studyGuideCreator.js') }}" onload="displaySectionCreators()"></script>

@endsection
