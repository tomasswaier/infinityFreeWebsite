@extends('layouts.app')
@section('mainContent')
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
          <p>{{$section['data']['text']}}</p>
          <br>
      @endisset
    </section>
  @endforeach
</div>
@endsection
