@extends('layouts.app')
@section('specialHeaderContent')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endsection
@section('mainContent')
<div class="ml-10 mt-10">
    <h2>Study Guide creator page</h2>
    <span>rules?</span>
    <br>

    <div>
    <br><br>
        <form id='mainForm' action="{{url('admin/studyGuide/edit/'.$studyGuide->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="studyGuideId" value="{{$studyGuide->id}}">
            <div class="">
                <select name="studyGuideSubjects">
                    <option value="0"></option>
                    @foreach($subjects as $subject)
                        <option value="{{$subject->id}}" @if($subject->id==$selected_subject_id)
                                                        selected
                                                        @endif>{{$subject->name}}</option>
                    @endforeach
                </select>
                <input type="text" name="title" placeholder="Study Guide title" required value="{{$studyGuide->name}}"><br><br>
                <div id='studyGuideContents'>
                  @foreach($studyGuide->section_data as $section)
                    <div class="py-4 border m-1">
                      <input type="hidden" name="prev_section_{{$section->id}}" value="{{$section->id}}">
                      @isset($section['data']['sectionType'])
                        @if($section['data']['sectionType']=="imageSection")
                          <div>
                            <span class="m-1" name="section_title_{{$section->id}}">@isset($section['data']['title']){{$section['data']['title']}}@endisset</span<br>
                            <input type="hidden" name="prev_image_{{$section['image']['id']}}" value="{{$section['image']['id']}}">
                            <img src="{{asset('storage/studyGuideImages/'.$section['image']['filename'])}}" alt=""><br>
                          </div>
                        @elseif($section['data']['sectionType']=="simpleTextSection")
                          <div>
                            <input type="text" class="m-1" name="section_title_{{$section->id}}" placeholder="mofokintitler" value="@isset($section['data']['title']){{$section['data']['title']}}@endisset"><br>
                            <textarea rows="4" cols="100" name="section_text_{{$section['id']}}" class="border border-project-blue rounded-md">@isset($section['data']['text']){{$section['data']['text']}}@endisset</textarea>
                          </div>
                        @elseif($section['data']['sectionType']=="verticalSplitTextSection")
                          <div>
                            <input type="text" class="m-1" name="section_title_{{$section->id}}" placeholder="mofokintitler" value="@isset($section['data']['title']){{$section['data']['title']}}@endisset"><br>
                            <div class="grid grid-cols-2 w-4/5">

                                <textarea rows="4" cols="100" name="section_left_text_{{$section['id']}}" class="border border-project-blue rounded-md">@isset($section['data']['text_left']){{$section['data']['text_left']}}@endisset</textarea>
                                <textarea rows="4" cols="100" name="section_right_text_{{$section['id']}}" class="border border-project-blue rounded-md">@isset($section['data']['text_right']){{$section['data']['text_right']}}@endisset</textarea>
                            </div>
                          </div>
                        @else
                          <span>ERROR UNKNOWN SECTION TYPE</span>

                        @endif
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

