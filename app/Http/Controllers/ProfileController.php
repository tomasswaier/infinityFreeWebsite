<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Log;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\SchoolSupervisor;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function info(Request $request,$id=null): View
    {
        $user=null;
        if ($id!=null) {
            $user=User::find($id);
        }else{
            $user=$request->user();
        }
        $canAlterAuthorization=false;
        return view('profile.info', [
            'user' => $user,
            'alter'=>$canAlterAuthorization,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request,$id)
    {
        $school=School::all();
        $user= User::find($id);
        return view('profile.update', [
            'user' => $user,
            'schools'=>$school,
        ]);

    }
    public function saveUpdate(Request $request)
    {
        $userId=$request['user_id'];
        $canAlterAuthorization=false;
        //todo:add permission checking ?
        $user=User::find(intval($userId));
        if ($user) {
            $user->authorization=$request['selected_authorization'];
            $user->save();
            SchoolSupervisor::where('user_id','=',$user->id)->delete();
            if ($user->authorization=='supervisor') {
                $schools=$request->except('_token','user_id','selected_authorization');
                foreach($schools as $key => $val){
                    $schoolId=$val;
                    SchoolSupervisor::create([
                        'user_id'=>$userId,
                        'school_id'=>intval($schoolId),
                        'created_at'=>NOW(),
                        'updated_at'=>NOW()
                    ]);
                }

            }
        }
        return view('profile.info', [
            'user' => $user,
            'alter'=>$canAlterAuthorization,
        ]);
    }
    /*
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('name')) {
            $request->user()->email_verified_at = null;

        $request->user()->save();

        return Redirect::route('profile.info')->with('status', 'profile-updated');
    }
     */

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function showAll(Request $request){

        return view('admin.users.manage',[
            'users'=>User::all(),
        ]);
    }
}
