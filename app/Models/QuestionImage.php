<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionImage extends Model
{
    protected $fillable=[
        'questions_id',
        'image_name',
    ];
}
