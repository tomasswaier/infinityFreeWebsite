<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Models\StudyGuide;

class StudyGuideController extends Controller
{
    public function create(Request $request,$schoolId){
        if (!(intval($schoolId)>0 && $request['title']) ){
            Log::erro('something went wrong');
            return redirect('school/'.$schoolId);
        }
        $studyGuide=StudyGuide::create([
            'name'=>$request['title'],
            'author'=>$request->user()->name,
            'version'=>1,
            'viewCount'=>0,
            'school_id'=>$schoolId
        ]);
        $this->uploadStudyGuideContents($request->except('_token','title'),$studyGuide);

        return redirect('school/'.$schoolId);
    }
    private function uploadStudyGuideContents($request,$studyGuide){
        foreach($request as $key=>$val){
            if (strlen($key)<14) {//14 is length of longest checked substr
                Log::error($key.'=>'.$val);
                return;
            }

            Log::info($key.' '.$val);
        }

    }
}
