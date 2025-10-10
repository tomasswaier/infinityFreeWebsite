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
    <section class="w-full">
      @isset($section['data']['sectionType'])
        @isset($section['data']['title'])
          <span class="text-2xl font-bold">{{!! nl2br(e($section['data']['title']))}}</span>
          <br>
        @endisset

        @if($section['data']['sectionType']=="imageSection")
          <img class=" h-auto w-full lg:w-1/2 lg:h-auto" src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt="myimage">
          <br>
        @elseif($section['data']['sectionType']=="simpleTextSection")
            <pre class="whitespace-pre-line">{{!! nl2br(e($section['data']['text']))}}</pre>
            <br>
        @elseif($section['data']['sectionType']=="verticalSplitTextSection")
          <div class="grid  grid-cols-1 md:grid-cols-2">
            <pre class='p-4 whitespace-pre-line'>{{$section['data']['text_left']}}</pre>
            <pre class='p-4 whitespace-pre-line'>{{$section['data']['text_right']}}</pre>
          </div>
        @else
          <span>ERROR UNKNOWN SECTION TYPE</span>
        @endif
      @endisset
    </section>
  @endforeach
</div>
<div class="w-full">
@isset($prevStudyGuide)
    <a href="{{url('studyGuide/'.$prevStudyGuide->id)}}">old Version</a>
@endisset
<span class="p-10">version</span>
@isset($nextStudyGuide)
    <a href="{{url('studyGuide/'.$nextStudyGuide->id)}}">new Version</a>
@endisset


</div>
@endsection
