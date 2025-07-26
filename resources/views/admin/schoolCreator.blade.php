@extends('layouts.app')
@section('mainContent')
<div class="p-10 text-project-dark-blue text-lg w-full h-full flex justify-center align-middle">
    <div class="p-4 bg-white inline-block rounded-lg shadow-neutral-500 shadow-md">
        <div class="grid grid-flow-row row-auto align-middle">
            <form action="{{route('school.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <h2>School Creator Page</h2>
                <span>Name:</span>
                <input type="text" name="name" value="" class="rounded-lg  bg-project-white  p-2 shadow-inner"><br>
                <div class="w-80">Before submitting this answer the following question :Do I want to create a NEW SCHOOL PAGE? if answer is yes then you may proceed to submit</div>
                <button type="submit" class=" rounded-xl  p-2 shadow-neutral-800 shadow-sm">submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

