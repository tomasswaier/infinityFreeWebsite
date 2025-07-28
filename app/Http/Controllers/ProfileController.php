<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

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

        $user= User::find($id);
        return view('profile.update', [
            'user' => $user,
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
