<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\QuestionImage;

class ImageController extends Controller
{
    public function show($id){
        return QuestionImage::query()->where('questions_id','=',$id)->get();
    }
    public function upload($image,$id){
        //todo: make somethingthat supports multiple images . this supports only one image per question so pls someone rewrite this (preferably keep integers as name just different logic)

        $splitFile=explode('.',$image->getClientOriginalName());
        $fileType='.'.end($splitFile);

        $imageName=$id.$fileType;
        $insertedImage=QuestionImage::create([
            'questions_id'=>$id,
            'image_name'=>$imageName
        ]);
        if ($image->storeAs('test_images', $imageName, 'public')) {
            return 1;
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
