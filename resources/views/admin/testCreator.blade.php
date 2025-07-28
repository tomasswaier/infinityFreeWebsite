@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    <h1>Test Creator Page!</h1>
    <span>You read it correctly! We no longer have the need to manually insert tests into database!! Be careful tho. You can NOT delete a test after it's creation so choose your insert options carefully</span>
    <br>
    <br>
    <h3>Create Test</h3>
    <br>
    <form action="{{route('testCreator.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="school_id" value="{{$school_id}}">
        <span>Test Name:</span><input type="text" name="test_name" value=""><span>(this can be anything but try to keep it understandabale like "PSI final 2024/2025" )</span>
        <br>
        <span>Test CREATOR Name:"{{ Auth::user()->name }}"</span><span>(This field is derived from your profile thingy)</span>
        <br>
        <button type="submit" class="border rounded-md p-2">Create TEST!</button>
    </form>
</div>
@endsection
