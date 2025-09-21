<?php

namespace App\Http\Controllers;

use App\Models\School;
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
    public function blank(Request $request,$schoolId){
            return view('admin/studyGuide/create',[
                'school_id'=>$schoolId,
                'subjects'=>School::find($schoolId)->subjects()->get(),
            ]);
    }

    public function edit(Request $request,$studyGuideId){
        $prevVersion=StudyGuide::find($studyGuideId);
        if (!$prevVersion ||$request['studyGuideId']!=$studyGuideId){
            Log::error('Someone is fucking around ...');
            return redirect('/');
        }
        Log::info($request->all());

        $studyGuide=StudyGuide::create([
            'name'=>$request['title'],
            'author'=>$request->user()->name,
            'version'=>$prevVersion->version+1,
            'viewCount'=>0,
            'school_id'=>$prevVersion->school_id
        ]);
        $this->uploadStudyGuideContents($request,$studyGuide);
        return redirect('school/'.StudyGuide::find($studyGuideId)->school_id);
    }
    private function uploadStudyGuideContents($request,$studyGuide){
        //I'm sorry for how this function is about to look like
        $order=0;
        $prevSection=-1;
        foreach($request->all() as $key=>$val){
            if (in_array($key,['_token','title','studyGuideId'])) {
                continue;
            }
            if (substr($key,0,13)=='prev_section_') {
                if (!StudyGuideSectionData::find($val)) {
                  continue;
                }
                $prevSection=$val;
            }
            if (substr($key,0,11)=='prev_image_')  {
                if (!StudyGuideSectionData::find($prevSection) || $prevSection==-1) {
                    continue;
                }
                StudyGuideSectionOrder::create([
                    'order'=>$order,
                    'study_guide_id'=>$studyGuide->id,
                    'study_guide_section_data_id'=>$prevSection,
                ]);
                $prevSection=-1;
                $order++;
            }
            if (substr($key,0,13)=='section_text_') {
                $studyGuideSectionData=null;
                if ($prevSection!=-1 ) {
                    $prevSectionData=StudyGuideSectionData::find($prevSection);
                    if (isset($request['section_title_'.substr($key,13,strlen($key))]) && $prevSectionData['data']['title']==$request['section_title_'.substr($key,13,strlen($key))] && $prevSectionData['data']['text']==$val) {
                        $studyGuideSectionData=$prevSectionData;
                    }
                    $prevSection=-1;

                }
                if(!$studyGuideSectionData){
                    $sectionData=collect();
                    $sectionData['sectionType']='simpleTextSection';
                    $sectionData['text']=($val);
                    if ($request['section_title_'.substr($key,13,strlen($key))]) {
                        $sectionData['title']=$request['section_title_'.substr($key,13,strlen($key))];
                    }
                    $studyGuideSectionData=StudyGuideSectionData::create([
                        'data'=>$sectionData,
                        'name'=>$request->user()->name,
                    ]);
                }
                StudyGuideSectionOrder::create([
                    'order'=>$order,
                    'study_guide_id'=>$studyGuide->id,
                    'study_guide_section_data_id'=>$studyGuideSectionData->id,
                ]);
                $order++;
            }
            else if (substr($key,0,18)=='section_left_text_') {
                $studyGuideSectionData=null;
                if ($prevSection!=-1 ) {
                    $prevSectionData=StudyGuideSectionData::find($prevSection);
                    if (isset($request['section_title_'.substr($key,13,strlen($key))]) && $prevSectionData['data']['title']==$request['section_title_'.substr($key,13,strlen($key))] && $prevSectionData['data']['text_left']==$val && isset($request['section_right_text_'.substr($key,18,strlen($key))]) && $prevSectionData['data']['text_right']== $request['section_right_text_'.substr($key,18,strlen($key))]) {
                        $studyGuideSectionData=$prevSectionData;
                    }
                    $prevSection=-1;

                }
                if(!$studyGuideSectionData){
                    $sectionData=collect();
                    $sectionData['sectionType']='verticalSplitTextSection';
                    $sectionData['text_left']=($val);
                    if ($request['section_title_'.substr($key,13,strlen($key))]) {
                        $sectionData['title']=$request['section_title_'.substr($key,13,strlen($key))];
                    }
                    if (isset($request['section_right_text_'.substr($key,18,strlen($key))])) {
                        $sectionData['text_right']=$request['section_right_text_'.substr($key,18,strlen($key))];
                    }

                    $studyGuideSectionData=StudyGuideSectionData::create([
                        'data'=>$sectionData,
                        'name'=>$request->user()->name,
                    ]);
                }
                StudyGuideSectionOrder::create([
                    'order'=>$order,
                    'study_guide_id'=>$studyGuide->id,
                    'study_guide_section_data_id'=>$studyGuideSectionData->id,
                ]);
                $order++;

            }
            else if (substr($key,0,9)=='img_file_') {
                $sectionData=collect();
                $sectionData['sectionType']='imageSection';
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


