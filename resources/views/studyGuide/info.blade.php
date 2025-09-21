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
      @isset($section['data']['sectionType'])
        <span>meow</span>
        @if($section['data']['sectionType']=="imageSection")
          <img src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt="myimage">
          @isset($section['data']['title'])
            <span class="text-gray-500">{{$section['data']['title']}}</span>
          @endisset
          <br>
        @elseif($section['data']['sectionType']=="simpleTextSection")
          @isset($section['data']['title'])
            <h3 class="text-2xl bold">{{$section['data']['title']}}</h3>
            <br>
          @endisset
            <pre>{{$section['data']['text']}}</pre>
            <br>
        @elseif($section['data']['sectionType']=="verticalSplitTextSection")
          @isset($section['data']['title'])
            <h3 class="text-2xl bold">{{$section['data']['title']}}</h3>
            <br>
          @endisset
          <div class="grid grid-cols-2">
            <pre class='w-1/2'>{{$section['data']['text_left']}}</pre>
            <pre class='w-1/2'>{{$section['data']['text_right']}}</pre>
          </div>
        @else
          <span>ERROR UNKNOWN SECTION TYPE</span>
        @endif
      @endisset
    </section>
  @endforeach
</div>
@endsection
