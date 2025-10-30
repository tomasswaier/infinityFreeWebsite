@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    <div class="m-2 p-2 inline-block border border-black rounded-md">
    <span>Creators</span> <br>
    <!--<span>fix</span><a href="{{url('admin/test/testCreator')}}"><u>Test Creator</u></a>-->
    <br>
    <span>fix</span><a href="{{url('admin/subjectCreator')}}"><u>Subject Creator</u></a>
    <br>
    @if(Auth::user()->authorization=='admin')
    <a href="{{url('admin/schoolCreator')}}"><u>School Creator</u></a>
    <br>
    <a href="{{url('admin/users/manage')}}"><u>User Manager</u></a>
    <br>
    <a href="{{url('admin/studyGuide/create')}}"><u>studyGuide creator</u></a>
    <br>
    @endif
    </div>
    <br>
    <div class="inline-block w-1/2 float-left">
    <span>Study guides</span>
    <table>
       <thead>
        <tr><th class="p-2">id</th><th>name</th></tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>filer data</td></tr>
            <tr><td>3</td><td>som study guide</td></tr>
            <tr><td>4</td><td>usb wii nintendo</td></tr>
            <tr><td>5</td><td>whhi I hate ppi</td></tr>
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
                <td align="center">{{$test->test_name}}</td><td align="center">{{$test->number_of_submits}}</td><td align="center"><a  href="{{url('admin/questionDisplay/'.$test->id)}}"><img src="{{asset('storage/assets/edit_icon.png')}}" class="h-10 w-auto" alt="random edit icon"></a></td><td align="center"><a  href="{{url('admin/questionCreator/'.$test->id)}}" class="text-4xl font-bold">+</a></td><td align="center"><button type="submit" title="heh lmao never gonna implement this">X</button></td>
                <td name="styling"></td>
                <td></td>

            </tr>
        @endforeach
        </table>
    </div>

    <section name="yap_section">
        <h2>Section for me uwu</h2>
        <span>creating tags really doesnt work as well as it should so it would be great to redo it(I have not tested it yet but i think it'll kill everything you wrote into ur subject)</span>
    </section>
</div>
@endsection
