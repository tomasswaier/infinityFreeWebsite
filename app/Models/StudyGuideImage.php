<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGuideImage extends Model
{
    protected $fillable=[
        'id',
        'filename',
        'study_guide_section_data_id'
    ];
}
