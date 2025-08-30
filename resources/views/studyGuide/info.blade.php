@extends('layouts.app')
@section('mainContent')
    @if(Auth::user() && supervisesSchool(Auth::user(),$studyGuide->school_id))
    <div class="absolute right-0 bg-project-super-blue text-project-white p-3 rounded-md">
        <span>Admin Panel</span>
        <br><br>
        <span class=" bg-project-blue text-white rounded-sm p-2"><a href="{{url('admin/studyGuide/edit/'.$studyGuide->id)}}">Edit Guide</a></span>
    </div>
    @endif
<div class="ml-10">
  <h2 class="text-4xl font-bold">{{$studyGuide->name}}</h2>
  <br>
  @foreach($studyGuide['section_data'] as $_key => $section)
    <section>

      @isset($section['image'])
        <img src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt="myimage">
        @isset($section['data']['title'])
          <span class="text-gray-500">{{$section['data']['title']}}</span>
        @endisset
        <br>
      @else
        @isset($section['data']['title'])
          <h3 class="text-2xl bold">{{$section['data']['title']}}</h3>
          <br>
        @endisset
          <pre>{{$section['data']['text']}}</pre>
          <br>
      @endisset
    </section>
  @endforeach
</div>
@endsection
