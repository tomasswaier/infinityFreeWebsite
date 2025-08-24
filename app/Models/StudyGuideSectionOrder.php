<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGuideSectionOrder extends Model
{
    protected $fillable=[
        'study_guide_section_id',
        'order'
    ];
    public function sectionData():HasMany{
        return $this->hasMany(StudyGuideSectionData::class,'study_guide_section_order_id');
    }
}
