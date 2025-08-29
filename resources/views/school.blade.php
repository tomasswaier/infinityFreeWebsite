@extends('layouts.app')
@section('mainContent')
<div class="p-2">
    <section name="placeholder section">

        <span>Here will be displayed study guides and stuff for your uni</span>
        <br>
        <br>
    </section>
    <section class="p-10">
        <h2>placeholder header</h2>
        <table>
            <thead>
                <tr><th class="w-80">name</th><th>subjects</th></tr>
            </thead>
            <tbody>
                @foreach($study_guides as $study_guide)
                    <tr><td>
                    <a href="{{url('admin/studyGuide/'.$study_guide->id)}}">{{$study_guide->name}}</a></td><td>todo:subject</td></tr>
                @endforeach
            </tbody>
        </table>
    </section>

</div>
@if(Auth::user() && supervisesSchool(Auth::user(),$school_id))
    <div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute">
        <h4>admin panel</h4>
        <div class="bg-project-blue text-white rounded-sm p-2 mb-2">
            <a href="{{url('admin/studyGuide/create/'.$school_id)}}">create study guide</a>
        </div>
    </div>
@endif
@endsection

