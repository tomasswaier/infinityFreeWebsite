<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    public function showAll(Request $request){
        return view('welcome',[
            'schools'=>School::all()
        ]);
    }
    public function save(Request $request){
        try {
            $model=new School;
            $model->name=$request->name;
            $model->save();
        } catch (\Throwable $th) {
            Log::error('Error when creating new school:',$th);
        }
        return redirect('/');
    }
}
