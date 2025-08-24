<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyGuide extends Model
{
    //
    protected $fillable=[
        'name',
        'author',
        'version',
        'viewCount',
        'school_id'
    ];

    public function sectionOrder():HasMany{
        return $this->hasMany(StudyGuideSectionOrder::class,'study_guide_section_id');
    }
}
