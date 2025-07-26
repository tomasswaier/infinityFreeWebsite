@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <section>
        <h2>This is a page dedicated to sharing information about subjects. So you know what to expect</h2>
        <span>so far it's only a concept</span>
        <table>
        <thead>
        <tr><th class="pr-5">Subject</th><th>Rating</th></tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
                <tr><td><a href="{{url('subjects/'.$subject['id'])}}"><u>{{$subject['name']}}</u></a></td><td>{{$subject['rating']}}/10</td></tr>
            @endforeach
        </tbody>
        </table>

    </section>
    <div>
        <span>This website will not have user accounts for everyone so rating system is based on opinions of few who have attended the subjects. (also I know some people would call shit like PIS a good subject just because it's free)
        </span>
    </div>
</div>
@endsection
