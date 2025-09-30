<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGuideImage extends Model
{
    public $incrementing = false;
    protected $fillable=[
        'id',
        'filename',
        'study_guide_section_data_id'
    ];
}
