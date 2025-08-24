@extends('layouts.app')
@section('mainContent')
<div class="flex justify-center align-middle">
<div class="bg-project-blue lg:mt-48 text-white p-10 rounded-xl">
    <h3  class=" text-4xl">Profile Information</h3><br>
    <div class="inline-block float-left mr-4">
        <span>Name:<span class="font-bold">{{$user->name}}</span></span><br>
        <span>Authorization:<span class="font-bold">{{$user->authorization}}</span></span><br>
        <span>Description:<span class="font-bold">{{$user->description}}</span></span>
    </div>
    <div class="inline-block">
        <div class="p-2 bg-project-super-blue rounded-xl font-bold">
            <span>profile picture</span>
            @if(strtolower($user->name[0])=='f' ||strtolower($user->name[0])=='v')
                <img class="w-32 md:w-96 h-auto" src="{{asset('storage/assets/user_icon_fv.webp')}}" alt="">
            @else
                <img class="w-32 md:w-96 h-auto" src="{{asset('storage/assets/user_icon.webp')}}" alt="">
            @endif
        </div>
    </div>
</div>
<div class="right-0 bg-project-super-blue text-project-white p-3 rounded-md absolute mr-4 mt-4">
    <h4>admin panel</h4>
    <div class="bg-project-blue text-white rounded-sm p-2">
        <a href="{{url('profile/update/'.$user->id)}}">alter priviledges</a>
    </div>
</div>
</div>
@endsection
