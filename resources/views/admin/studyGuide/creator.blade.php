@extends('layouts.app')
@section('mainContent')
<div class="grid grid-flow-row justify-center pt-10">
    <div class="rounded-xl bg-project-light-blue p-4">
        <input type="text" name="title" class="text-2xl rounded-md p-2" placeholder="Title">
        <div id="studyGuideContent" name="study_guide_content">

        </div>

        <h1>Study Guide Creator!!!!</h1>
        <span>Rules:</span>
        <br><span>Separate the materials into as many sections as possible</span>
        <br><span>Do not complain about this website not flexing properly</span>

    </div>
</div>
<script src="{{ asset('js/studyGuideCreator.js') }}" onload="loadInputField()"></script>
@endsection
