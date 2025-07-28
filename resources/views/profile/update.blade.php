@extends('layouts.app')
@section('mainContent')
<div class="p-10">
<h1>funny html page</h1>
<div>id:{{$user->id}}</div>
<div>name:{{$user->name}}</div>
<div>description:{{$user->description}}</div>
<div>authorization:{{$user->authorization}}&emsp;&emsp;
<select>
<option value="user" >user</option>
<option value="supervisor" {{$user->authorization=="supervisor"? "selected":''}}>supervisor</option>
<option value="admin" {{$user->authorization=="admin"? "selected":''}}>admin</option>
<div>

</div>
</select>
</div>
</div>
@endsection
