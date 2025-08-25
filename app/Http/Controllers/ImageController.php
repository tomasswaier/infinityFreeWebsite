<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\QuestionImage;
use App\Models\StudyGuideImage;

class ImageController extends Controller
{
    public function show($id){
        return QuestionImage::query()->where('questions_id','=',$id)->get();
    }
    public function upload($image,$id,$imagePath='test_images'){
        //todo: make somethingthat supports multiple images . this supports only one image per question so pls someone rewrite this (preferably keep integers as name just different logic)

        $splitFile=explode('.',$image->getClientOriginalName());
        $fileType='.'.end($splitFile);

        if ($imagePath=='test_images') {
            $imageName=($id-1).$fileType;
            if ($image->storeAs($imagePath, $imageName, 'public')) {
                QuestionImage::create([
                    'questions_id'=>$id,
                    'image_name'=>$imageName
                ]);
                return 1;
            }
        }elseif ($imagePath=='studyGuideImages') {
            $uuid=Str::uuid();
            $imageName=$uuid.'.'.$fileType;
            if ($image->storeAs($imagePath, $imageName, 'public')) {
                StudyGuideImage::create([
                    'id'=>$uuid,
                    'study_guide_section_data_id'=>$id,
                    'filename'=>$imageName
                ]);
                return $imageName;
            }
        }
        return 0;
    }
    public function delete($imageName){
        QuestionImage::where('image_name','=',$imageName)->delete();
        if (unlink('storage/test_images/'.$imageName)!=1) {
            Log::info('image deletion failed');
            return 0;
        }
        return 1;

    }
    public function deleteAllQuestionImages($questionId){
        $allImages=QuestionImage::select('image_name')->where('questions_id','=',$questionId)->get();
        QuestionImage::where('questions_id','=',$questionId)->delete();
        Log::info('id');
        Log::info($allImages);
        foreach($allImages as $image){
            $this->delete($image->image_name);
        }
        return 1;

    }
}
