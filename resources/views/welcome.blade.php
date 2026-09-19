@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <section>
        <h2>WELCOME</h2>
    </section>
    <br>
    <section class="m-30">
        <span>Current schools:</span>
        <div class="flex gap-4">
            @foreach($schools as $school)
            <a href="{{url('school/'.$school->id)}}" class="">
            <div class="inline-block p-2 text-6xl bg-project-blue rounded-md hover:bg-project-light-blue">
                <span>{{$school->name}}</span>
            </div></a>
            @endforeach
        </div>
    </section>
        <br>
    <section class="max-w-5xl">
        <h3>help</h3>
        <span>Recently the UI changed bcs ppl have been complaining. Now instead of my beautiful handcrafted manually written beauty that perfectly fit the style of 2009 first time webdev now you have this ai slop. Fuck u!!</span><br><br
        <span>This is an open source student project and passwords are not safe in my hands!</span>
        <br><br><span>Code is a big mess. Anyone who knows a little laravel who wants to help is welcome</span>
        <br><br>
        <span>This is my webisite for studying and testing your knowledge</span>
        <br>
        <span>main purpose of this website is to share study guides, share information on subjects at your faculty and create tests to test the knowledge of poeple who studied from study guides</span>
        <br>
        <span>Content on this website is regulated only for racial slurs and nsfw content. If there happens to be content which violates copyright or any kind of law please contact me(discord .maryann)</span>
    </section>
</div>
@endsection
