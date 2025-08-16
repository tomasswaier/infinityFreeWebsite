@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <section>
        <div class="inline-block float-left w-3/4">
            <h2>This is a page dedicated to sharing information about subjects. So you know what to expect</h2>
            <form>
                <div>
                    <h3 class="font-bold text-2xl">Tags</h3>
                    @foreach($tags as $tag)
                        <span>{{$tag['name']}}</span>
                        <input type="checkbox" name="checkbox_{{$tag['id']}}" value="1">
                    @endforeach
                </div>
                <button type="submit" class="border-black border p-2 rounded-md mb-4 mt-2">Filter</button>
            </form>
            <table class="rounded-xl border-separate border-black border p-2">
                <thead>
                    <tr><th class="pr-5">Subject</th><th>Rating</th><th>tags</th></tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                        <tr><td><a href="{{url('subjects/info/'.$subject['id'])}}"><u>{{$subject['name']}}</u></a></td><td>{{$subject['rating']}}/10</td>
                        <td>
                            @foreach($subject['tags'] as $tag)
                                <span>{{$tag->name}}|</span>
                            @endforeach
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(Auth::user() && Auth::user()->authorization=='admin')
        <div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
            <h4>admin panel</h4>
            <a href=""></a>
            <div class="bg-project-blue text-white rounded-sm p-2 mb-2">
                <a href="{{url('admin/subjectCreator/'.$school_id)}}">add subject</a>
            </div>
            <div class="bg-project-blue text-white rounded-sm p-2">
                <a href="{{ url('admin/tagCreator/'.$school_id)}}">Create tag!</a>
            </div>
        </div>
        @endif

    </section>
    <div>
    </div>
</div>
@endsection
