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
        'school_id',
        'parent_study_guide_id',
        'origin_study_guide_id'
    ];

    public function sectionOrder():HasMany{
        return $this->hasMany(StudyGuideSectionOrder::class,'study_guide_section_id');
    }
    public function subjects(){
        return $this->belongsToMany(Subjects::class, 'study_guide_subjects','study_guide_id','subject_id');
    }
    // In StudyGuide model
    public function sectionData() {
        return $this->belongsToMany(
            StudyGuideSectionData::class,
            'study_guide_section_order',
            'study_guide_id',
            'study_guide_section_data_id'
        )->orderByPivot('order')->with('image'); // Include the order if needed
    }
}
