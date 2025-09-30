@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    @if(Auth::user() && supervisesSchool(Auth::user(),$school_id))
        <div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
            <h4>admin panel</h4>
            <div class="bg-project-blue text-white rounded-sm p-2 mb-2">
                <a href="{{url('admin/studyGuide/create/'.$school_id)}}">create study guide</a>
            </div>
        </div>
    @endif
    <section name="placeholder section">

        <h3>Welcome to your school page</h3>
        <br>
        <br>
    </section>
    <section class="p-10">
        <h2>STUDY GUIDES!!</h2>
        <br>
        <form id='main_form' action="" method="post" enctype="multipart/form-data">
            @csrf
            <span>Looking for specific subject?:</span>
            <select name="subject" onchange="this.form.submit()">
                <option value="0"></option>
                @foreach($subjects as $subject)
                    <option value="{{$subject->id}}" @if($subject->id==$selected_subject_id)
                                                    selected
                                                    @endif>{{$subject->name}}</option>
                @endforeach
            </select>
            <table>
                <thead>
                    <tr><th class="w-80">name</th></tr>
                </thead>
                <tbody>
                    @foreach($study_guides as $study_guide)
                        <tr><td align="center">
                        <a href="{{url('admin/studyGuide/'.$study_guide->id)}}" class="underline text-project-super-blue">{{$study_guide->name}}</a></td></tr>
                    @endforeach
                </tbody>
            </table>
            {{--idk should i keep this in ? pagination seems like overkill--}}
            <input type="button" name="prev" value="PREV">
            <input type="button" name="next" value="NEXT">
        </form>
    </section>

</div>
@endsection

