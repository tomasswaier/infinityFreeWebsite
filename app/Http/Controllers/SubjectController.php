<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Subjects;
use App\Models\SubjectTag;
use App\Models\Tag;
use PDO;

class SubjectController extends Controller
{

    public function showAllSubjects($school_id,Request $request){
        return view('subjects/index',[
            'subjects'=>Subjects::query()->where('school_id','=',$school_id)->get(),
            'school_id'=>$school_id,
        ]);
    }

    public function showSubject(Request $request,$id=null){
        if ($id==null) {
            return redirect('subjects');
        }
        $subject=Subjects::find($id);
        if ($subject==null) {
            return redirect('subjects');
        }

        return view('subjects/subject',[
            'subject'=>$subject
        ]);
    }


    public function editSubject(Request $request,$schoolId,$subjectId=null){
        $info=$request->all();
        $subject=null;
        $selectedTags=null;
        if (isset($subjectId)) {
            $subject=Subjects::find($subjectId);
            $temp=SubjectTag::select('tag_id')->where('subject_id','=',$subjectId)->get();
            $selectedTags=[];
            foreach($temp as $tag){
                array_push($selectedTags,$tag['tag_id']);
            }
        }
        $tags=Tag::query()->where('school_id','=',$schoolId)->get();
        return view('admin/subjectCreator',[
        'allTags'=>$tags,
        'selectedTags'=>$selectedTags,
        'subject'=>$subject,
        'school_id'=>$schoolId
        ]);
    }
    //
    public function saveSubject(Request $request){
        $info=$request->all();
        if (isset($info['subjectId'])) {
            $subject=Subjects::find($info['subjectId']);
        }else{
            $subject=new Subjects;
        }
        $subject->name=$info['subjectName'];
        $subject->school_id=$info['school_id'];
        $subject->description=$info['subjectDescription'];
        $subject->tldr=$info['subjectTldr'];
        $subject->rating=$info['subjectRating'];
        $subject->save();
        SubjectTag::query()->where('subject_id','=',$subject->id)->delete();
        $info=$request->except('_token','subjectName','subjectRating','subjectDescription','subjectTldr');
        //Log::info($info);
        foreach($info as $key=>$val){
            //Log::info(substr($key,0,7));
            if (strlen($key)>7 && substr($key,0,7)=='tag_id_') {
                $id=intval(substr($key,7,strlen($key)-7));
                //Log::info($id);
                SubjectTag::create([
                    'subject_id'=>$subject->id,
                    'tag_id'=>$id,
                ]);

            }
        }
        return redirect('admin/subjectCreator/'.$info['school_id']);
    }

    public function saveTag(Request $request){
        //should I check the users rights here ? if im not mistaken then the csrf token should take care of someone just calling this but i'm not sure
        try {
            $info=$request->all();
            $tag= new Tag;
            $tag->name=$info['tagName'];
            $tag->school_id=$info['school_id'];
            $tag->save();
        } catch (\Throwable $th) {
            Log::error('Error while creating Tag:'.$th);
        }
        return redirect('admin/subjectCreator/'.$info['school_id']);
    }
}
