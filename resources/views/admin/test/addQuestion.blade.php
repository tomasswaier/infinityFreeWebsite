@extends('layouts.app')
@section('mainContent')
<div class="p-10">

    <h1>Question Creator. Someone change this. I do not have time to make a good looking ui. Preferable change everything from how colors are used to the shapes no need to keep "design" of the website in mind(if i designed the website yet)</h1>
    <br>
    <div class="p-4 bg-project-blue rounded-lg inline-block ">
        <form action="{{route('question.store')}}"  method="post" enctype="multipart/form-data">
            @csrf
            <div id="user-list">

            </div>

        </form ">
    </div>
</div>
<script src="{{ asset('js/questionCreator.js') }}" onload="load_input_field()"></script>
@endsection
