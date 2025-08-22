<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudyGuideController extends Controller
{
    public function create(Request $request,$schoolId){
        return view('admin/studyGuide/creator');
    }
}
