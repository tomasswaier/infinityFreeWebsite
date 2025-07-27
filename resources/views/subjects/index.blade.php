@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <section>
        <div class="inline-block float-left w-3/4">
            <h2>This is a page dedicated to sharing information about subjects. So you know what to expect</h2>
            <span>so far it's only a concept</span>
            <table>
            <thead>
            <tr><th class="pr-5">Subject</th><th>Rating</th></tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr><td><a href="{{url('subjects/info/'.$subject['id'])}}"><u>{{$subject['name']}}</u></a></td><td>{{$subject['rating']}}/10</td></tr>
                @endforeach
            </tbody>
            </table>
        </div>
        @if(Auth::user() && Auth::user()->authorization=='admin')
        <div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
            <h4>admin panel</h4>
            <a href=""></a>
            <div class="bg-project-blue text-white rounded-sm p-2">
                <a href="{{url('admin/subjectCreator/'.$school_id)}}">add subject</a>
            </div>
        </div>
        @endif

    </section>
    <div>
    </div>
</div>
@endsection
