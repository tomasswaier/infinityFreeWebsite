@extends('layouts.app')
@section('mainContent')
<div class="p-10">

    <h1>Question Creator . Jajo I will not be doing ur thing bcs it's too hard(/timeconsuming and i no wanna)</h1>
    <br>
    <div class="p-4 bg-project-dark-blue rounded-lg inline-block ">
        <form action="{{route('question.store')}}"  method="post" enctype="multipart/form-data">
            @csrf
            <div id="user-list">

            </div>

        </form ">
    </div>
</div>
<script src="{{ asset('js/questionCreator.js') }}" onload="load_input_field()"></script>
@endsection
