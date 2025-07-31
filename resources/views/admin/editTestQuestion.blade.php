
@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <h1>Question Creator. </h1>
    <br>
    <div class="p-4 bg-project-dark-blue rounded-lg inline-block ">
        <form action="{{route('question.edit')}}"  method="post" enctype="multipart/form-data">
            @csrf
            <input name="question_id" type="hidden" value="{{$question['id']}}"/>
            <div>

                <input id="user_image" name="user_image" type="file" onChange="display_input_image()" /><br>
                @if(isset($images[0]['image_name']))
                    <img id="display_image" src="{{ asset('storage/test_images/' . $images[0]['image_name']) }}"alt="Uploaded Image" />
                    <input type="hidden" name="prev_image" id='prev_image' value="{{$images[0]['image_name']}}">
                @else
                    <img id="display_image" src="" alt="Uploaded Image" />
                @endif

                <input type="button" class="p-2 border-black border rounded-md"value="remove image" onclick="remove_input_image()">
            </div>
            <br><br>

            <div id="user-list">
            </div>
        </form ">
    </div>
</div>
<script>
    let question_data=@json($question);
    question_data['options']=@json($options);
</script>
<script src="{{ asset('js/questionCreator.js') }}" onload="load_input_field(question_data)"></script>
@endsection
