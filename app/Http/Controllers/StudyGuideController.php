<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


use App\Models\StudyGuide;
use App\Models\StudyGuideSectionData;
use App\Models\StudyGuideSectionOrder;

class StudyGuideController extends Controller
{
    public function create(Request $request,$schoolId){
        if (!(intval($schoolId)>0 && $request['title']) ){
            Log::error('something went wrong');
            return redirect('school/'.$schoolId);
        }
        $studyGuide=StudyGuide::create([
            'name'=>$request['title'],
            'author'=>$request->user()->name,
            'version'=>1,
            'viewCount'=>0,
            'school_id'=>$schoolId
        ]);
        $this->uploadStudyGuideContents($request,$studyGuide);

        return redirect('school/'.$schoolId);
    }
    public function edit(Request $request,$studyGuideId){
        if (!StudyGuide::find($studyGuideId)){
            Log::error('study guide Id not found');
            return redirect('/');
        }
        Log::info($request->all());
        /*
        $studyGuide=StudyGuide::create([
            'name'=>$request['title'],
            'author'=>$request->user()->name,
            'version'=>1,
            'viewCount'=>0,
            'school_id'=>$schoolId
        ]);
        $this->uploadStudyGuideContents($request,$studyGuide);
         */

        return redirect('school/'.StudyGuide::find($studyGuideId)->school_id);
    }
    private function uploadStudyGuideContents($request,$studyGuide){
        $order=0;
        foreach($request->all() as $key=>$val){
            if (strlen($key)<14) {//14 is length of longest checked substr
                continue;
            }
            if (substr($key,0,13)=='section_text_') {
                $sectionData=collect();
                $sectionData['text']=($val);
                if ($request['section_title_'.substr($key,13,strlen($key))]) {
                    $sectionData['title']=$request['section_title_'.substr($key,13,strlen($key))];
                }
                $studyGuideSectionData=StudyGuideSectionData::create([
                    'data'=>$sectionData,
                    'name'=>$request->user()->name,
                ]);
                StudyGuideSectionOrder::create([
                    'order'=>$order,
                    'study_guide_id'=>$studyGuide->id,
                    'study_guide_section_data_id'=>$studyGuideSectionData->id,
                ]);
                $order++;
            }
            else if (substr($key,0,9)=='img_file_') {
                $sectionData=collect();
                $sectionData['hasImage']=true;
                $id=substr($key,9,strlen($key));
                if ($request['img_title_'.substr($key,9,strlen($key))]) {
                    $sectionData['title']=$request['img_title_'.substr($key,9,strlen($key))];
                }
                $studyGuideSectionData=StudyGuideSectionData::create([
                    'data'=>$sectionData,
                    'name'=>$request->user()->name,
                ]);
                StudyGuideSectionOrder::create([
                    'order'=>$order,
                    'study_guide_id'=>$studyGuide->id,
                    'study_guide_section_data_id'=>$studyGuideSectionData->id,
                ]);
                (new ImageController)->upload($val,$studyGuideSectionData->id,'studyGuideImages');
                $order++;

            }
        }
    }
    private function getStudyGuide($studyGuideId){
        $studyGuide=StudyGuide::with('sectionData.image')->find($studyGuideId);
        if(!$studyGuide){
            return null;
        }
        $studyGuide['section_data']=$studyGuide->sectionData;
        foreach($studyGuide['section_data'] as &$section){
            $section['image']=$section->image;
        }
        return $studyGuide;
    }
    public function show(Request $request,$guideId){
        $studyGuide=$this->getStudyGuide($guideId);
        if (!$studyGuide) {
            return redirect('/');
        }
        return view('studyGuide/info',[
            'studyGuide'=>$studyGuide,
            'school_id'=>$studyGuide->school_id
        ]);
    }
    public function displayEditor(Request $request,$guideId){
        $studyGuide=$this->getStudyGuide($guideId);
        if (!$studyGuide ||!supervisesSchool($request->user() ,$studyGuide->school_id)) {
            return redirect('/');
        }
        return view('admin/studyGuide/edit',[
            'studyGuide'=>$studyGuide,
            'school_id'=>$studyGuide->school_id
        ]);
    }

}


