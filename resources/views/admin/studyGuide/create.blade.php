@extends('layouts.app')
@section('mainContent')
<div>
    <h2>Study Guide creator page</h2>
    <span>rules?</span>
    <br>
    <br>
    <span>Text is displayed in p element so to add something to a new line create a new section(intended design)</span><br>
    <span>Hosting forces like 10 MB limit on post request. Please compress images before sending</span><br>

    <div >
    <span>Choose which subject does this field relate to(maybe add tags later for when you have 2 that go over webdev , asm or PSZ sum ?)</span>
        <form id='mainForm' action="{{url('admin/studyGuide/create/'.$school_id)}}" method="post" enctype="multipart/form-data">
            {{--this select could be made into a component with lookup and shi--}}
            <select name="studyGuideSubjects">
                <option value="0"></option>
                @foreach($subjects as $subject)
                    <option value="{{$subject->id}}">{{$subject->name}}</option>
                @endforeach
            </select>
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
