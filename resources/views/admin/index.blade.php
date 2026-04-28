@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    <div class="m-2 p-2 inline-block border border-black rounded-md">
    <span>Creators</span> <br>
        @if(Auth::user()->authorization=='admin')
            <a href="{{url('admin/schoolCreator')}}"><u>School Creator</u></a>
            <br>
            <a href="{{url('admin/users/manage')}}"><u>User Manager</u></a>
            <br>
            <x-anonym-request-popup source="adminPage"/>
            <a href="{{url('anonymRequest/index')}}"><u>Anonym Request Testing</u></a>
            <br>
        @endif
    </div>
    <br>
    <div class="inline-block w-1/2 float-left">
        <table class="border rounded-xl overflow-hidden">
            <thead class="bg-project-super-blue px-4 h-16 text-xl text-white ">
                <tr>
                    <th name="styling" class="w-4"></th>
                    <th class="w-30">Source</th>
                    <th class="w-100">Text</th>
                    <th class="w-100">Delete</th>
                    <th name="styling" class="w-4"></th>
                </tr>
            </thead>
        </thead>
        <tbody>
        @foreach($anonymRequests as $anonymRequest)
            <tr>
                <td></td>
                <td>{{$anonymRequest->source}}</td>
                <td>{{$anonymRequest->text}}</td>
                <td align="center"><a  href="{{url('admin/anonymRequestDelete/'.$anonymRequest->id)}}"class="text-4xl font-bold">X</a></td><td></td>
            </tr>

        @endforeach
        </tbody>
    </table>

    </div>
    <div class="w-1/2 inline-block rounded-md ">
        <table class="border rounded-xl overflow-hidden">
            <thead class="bg-project-super-blue px-4 h-16 text-xl text-white ">
                <tr>
                    <th name="styling" class="w-4"></th>
                    <th class="w-60">Test name</th>
                    <th class="w-60">Submit count</th>
                    <th class="w-40">edit</th>
                    <th class="w-40">add question</th>
                    <th class="w-40">delete</th>
                    <th name="styling" class="w-4"></th>
                </tr>
            </thead>
        @foreach($tests as $test)
            <tr class="border">
                <td name="styling"></td>
                <td align="center">
                    <a  href="{{url('admin/questionDisplay/'.$test->id)}}">{{$test->test_name}}</a>
                </td>
                <td align="center">{{$test->number_of_submits}}</td>
                <td align="center"><a  href="{{url('admin/questionDisplay/'.$test->id)}}"><img src="{{asset('storage/assets/edit_icon.png')}}" class="h-10 w-auto" alt="random edit icon"></a></td>
                <td align="center"><a  href="{{url('admin/questionCreator/'.$test->id)}}" class="text-4xl font-bold">+</a></td>
                <td align="center"><button type="submit" title="heh lmao never gonna implement this">X</button></td>
                <td name="styling"></td>
                <td></td>

            </tr>
        @endforeach
        </table>
    </div>
    <div class="">
        <span>Hi!</span>
        <span>Tutorials for parts of the website:</span><br>
        <span class="ml-16">Tests:<a href="{{url('admin/resources/testGuide/')}}" class="underline">admin/resources/testGuide</a></span><br>
        <span class="ml-16">Tests:<a href="{{url('admin/resources/studyGuideGuide/')}}" class="underline">admin/resources/studyGuideGuide</a></span><br>
        <br>
        <span></span>
        <p class="pt-16">This project was created to facilitate multiple schools so that's why it's structured as it is and not only for feet</p>
    </div>

    <section name="yap_section">
        <h2>Section for me uwu</h2>
        <pre>creating tags really doesnt work as well as it should so it would be great to redo it(I have not tested it yet but i think it'll kill everything you wrote into ur subject)</pre>
    </section>
</div>
@endsection
