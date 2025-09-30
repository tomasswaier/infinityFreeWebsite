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
    public function info(Request $request,$school_id){
        Log::info($request->all());
        $selected_subject_id=0;
        $query= School::find($school_id)->studyGuides();
        if (!isset($request->order)) {
            $request->order='lastVersion';
        }
        if (isset($request->order) && $request->order!='noOrder') {
            if($request->order=="viewCount"){
                $query->orderBy('viewCount','desc');
            }else if($request->order=="lastVersion"){
                $query->orderBy('version','desc');
            }
        }
        if (isset($request->subject) && $request->subject!=0) {
            $selected_subject_id=$request->subject;
            $query->whereHas('subjects',
                function ($q) use ($selected_subject_id) {
                    $q->where('subjects.id','=', $selected_subject_id);
                }
            );
        }
        $order= $request->order;
        $studyGuides= $query->get()->unique('origin_study_guide_id');
        //I should be skinned alive
        return view('school',
        [
            'school_id'=>$school_id,
            'study_guides'=>$studyGuides,
            'subjects'=>School::find($school_id)->subjects()->get(),
            'order'=>$order,
            'selected_subject_id'=>$selected_subject_id,
        ]
        );
    }
}
