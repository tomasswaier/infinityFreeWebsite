@extends('layouts.app')
@section('mainContent')
<div class="ml-10 mt-10">
    <h2>Study Guide creator page</h2>
    <span>rules?</span>
    <br>
    <span>Section is NOT a section until a title is added</span>

    <div>
    <br><br>
        <form id='mainForm' action="{{url('admin/studyGuide/edit/'.$studyGuide->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="studyGuideId" value="{{$studyGuide->id}}">
            <div class="">
                <input type="text" name="title" placeholder="Study Guide title" required value="{{$studyGuide->name}}"><br><br>
                <div id='studyGuideContents'>
                  @foreach($studyGuide->section_data as $section)
                    <div class="py-4">
                      <input type="hidden" name="prev_section_{{$section->id}}" value="{{$section->id}}">
                          <input type="text" class="m-1" name="section_title_{{$section->id}}" placeholder="mofokintitler" value="@isset($section['data']['title']){{$section['data']['title']}}@endisset"><br>
                      @isset($section['data']['hasImage'])
                        <div>
                          <input type="hidden" name="prev_image_{{$section['image']['id']}}" value="">
                          <img src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt=""><br>
                        </div>
                      @else
                        <div>
                          <input type="hidden" name="prev_text_{{$section['id']}}" value="{{$section['id']}}">
                          <textarea rows="4" cols="100" name="section_text_{{$section['id']}}" class="border border-project-blue rounded-md">@isset($section['data']['text']){{$section['data']['text']}}@endisset</textarea>
                        </div>

                      @endisset
                    </div>
                  @endforeach

                </div>
            </div>
            <br>
           <button type="sumbit">submit</button>
        </form>

    </div>


</div>

<script src="{{ asset('js/studyGuideCreator.js') }}" onload="displaySectionCreators()"></script>

@endsection

