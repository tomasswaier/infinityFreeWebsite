@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <section>
        <h1>here you may manage all users</h1>
        <span>making it public was such a bad idea</span>
    </section>
    <section>
        <table>
        <tr><th>id</th><th>name</th><th>authorization</th></tr>
        <tbody>
        @foreach($users as $user)
            <tr><td>{{$user->id}}</td><td><a href="{{url('profile/'.$user->id)}}">{{$user->name}}</a></td><td>{{$user->authorization}}</td></tr>
        @endforeach
        </tbody>
        </table>
    </section>
</div>
@endsection
