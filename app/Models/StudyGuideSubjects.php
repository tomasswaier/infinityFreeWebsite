<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGuideSubjects extends Model
{
    protected $fillable=[
        'subject_id',
        'study_guide_id'
    ];
}
