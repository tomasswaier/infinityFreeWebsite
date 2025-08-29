<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudyGuideSectionData extends Model
{
    protected $fillable=[
        'data',
        'name',
        'study_guide_section_order_id'

    ];
    protected $casts = [
        'data' => 'array'
    ];
    public function image():HasOne{
        return $this->hasOne(StudyGuideImage::class,'study_guide_section_data_id');
    }
}
