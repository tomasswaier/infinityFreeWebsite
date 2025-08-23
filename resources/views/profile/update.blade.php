@extends('layouts.app')
@section('mainContent')
<div class="p-10">
    <h1>funny html page</h1>
    <div>id:{{$user->id}}</div>
    <div>name:{{$user->name}}</div>
    <div>description:{{$user->description}}</div>
    <div>authorization:{{$user->authorization}}&emsp;&emsp;
        <form action="{{route('profile.privilege.save')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{$user->id}}">
            <select id="roleSelector" name="selected_authorization" onChange="displaySchoolsIfSupervisor()">
                <option value="user" >user</option>
                <option value="supervisor" {{$user->authorization=="supervisor"? "selected":''}}>supervisor</option>
                <option value="admin" {{$user->authorization=="admin"? "selected":''}}>admin</option>
            </select><br>
            <fieldset id='availableSchools' disabled>
                @foreach($schools as $school)
                    <span>{{$school->name}}</span>
                    <input type="checkbox" name="enable_school_{{$school->id}}" value="{{$school->id}}" >
                    <br>
                @endforeach
            </fieldset>

            <button type="submit" class="rounded-lg p-1 border border-black mt-4">Submit</button>

        </form>
    </div>
</div>
<script>
 function displaySchoolsIfSupervisor(){
    const selectedRights=document.getElementById('roleSelector');
    const schoolsElement=document.getElementById('availableSchools');
    if(selectedRights.value=='supervisor'){
      schoolsElement.disabled=false;
      availableSchools.hidden=false;
    }else{
      schoolsElement.disabled=true;
      availableSchools.hidden=true;
    }
 }
 displaySchoolsIfSupervisor();


</script>

@endsection
