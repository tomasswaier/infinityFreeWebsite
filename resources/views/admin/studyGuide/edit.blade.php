@extends('layouts.app')
@section('specialHeaderContent')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endsection
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
                    <div class="py-4 border m-1">
                      <input type="hidden" name="prev_section_{{$section->id}}" value="{{$section->id}}">
                      @isset($section['data']['hasImage'])
                        <div>
                          <span class="m-1" name="section_title_{{$section->id}}">@isset($section['data']['title']){{$section['data']['title']}}@endisset</span<br>
                          <input type="hidden" name="prev_image_{{$section['image']['id']}}" value="{{$section['image']['id']}}">
                          <img src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt=""><br>
                        </div>
                      @else
                        <div>
                          <input type="text" class="m-1" name="section_title_{{$section->id}}" placeholder="mofokintitler" value="@isset($section['data']['title']){{$section['data']['title']}}@endisset"><br>
                          <textarea rows="4" cols="100" name="section_text_{{$section['id']}}" class="border border-project-blue rounded-md">@isset($section['data']['text']){{$section['data']['text']}}@endisset</textarea>
                        </div>

                      @endisset
                      <button type="button" onclick="removeParent(this)" class="border rounded-md" >delete section</button>
                    </div>
                  @endforeach

                </div>
            </div>
            <br>
           <button type="sumbit">submit</button>
        </form>

    </div>


</div>

<script src="{{ asset('js/studyGuideCreator.js') }}" onload="displaySectionCreators();initScrollable('studyGuideContents')"></script>

@endsection

