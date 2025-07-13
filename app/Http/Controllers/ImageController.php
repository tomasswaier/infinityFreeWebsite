<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionImage;

class ImageController extends Controller
{
    public function show($id){
        return QuestionImage::query()->where('questions_id','=',$id)->get();
    }
    public function upload(){
    }
}
