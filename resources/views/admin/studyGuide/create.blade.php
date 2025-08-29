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
    <br><br>
        <form id='mainForm' action="{{url('admin/studyGuide/create/'.$school_id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <div>
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
