@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    <div class="m-2 p-2 inline-block border border-black rounded-md">
    <span>Creators</span> <br>
    <span>fix</span><a href="{{url('admin/testCreator')}}"><u>Test Creator</u></a>
    <br>
    <span>fix</span><a href="{{url('admin/subjectCreator')}}"><u>Subject Creator</u></a>
    <br>
    @if(Auth::user()->authorization=='admin')
    <a href="{{url('admin/schoolCreator')}}"><u>School Creator</u></a>
    <br>
    <a href="{{url('admin/users/manage')}}"><u>User Manager</u></a>
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
    <div class="w-1/2 inline-block">
        <table class="border">
            <thead>
            <tr>
            <th class="w-60">Test name</th>
            <th class="w-20">id</th>
            <th class="w-40">edit button?</th>
            <th class="w-40">add question</th>
            <th class="w-40">delte text</th>
            </tr>
            </thead>
        @foreach($tests as $test)
            <tr class="border">
             <td>{{$test->test_name}}</td> <td>{{$test->id}}</td> <td><a  href="{{url('admin/questionDisplay/'.$test->id)}}">|?|</a></td>  <td><a  href="{{url('admin/questionCreator/'.$test->id)}}">|+|</a></td><td><button type="submit" title="heh lmao never gonna implement this">X</button></td>
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
