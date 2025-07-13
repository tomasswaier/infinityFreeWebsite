<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    protected $fillable=[
        'question_id',
        'image_name',
    ];
}
