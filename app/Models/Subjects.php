<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\Test;



class Subjects extends Model
{
    protected $fillable=[
        'rating',
        'name',
        'description',
        'tldr',

    ];
    public function tags(){
        return $this->belongsToMany(Tag::class,'subject_tags','subject_id','tag_id');
    }
    public function tests(){
        return $this->belongsToMany(Test::class,'test_subjects','subject_id','test_id');
    }
    public function studyGuides()
    {
        return $this->belongsToMany(StudyGuide::class, 'study_guide_subjects', 'study_guide_id', 'subject_id');
    }

}
