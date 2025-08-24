<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    //
    protected $fillable=[
        'name',
    ];
    public function tests():HasMany{
        return $this->hasMany(Test::class);
    }
    public function studyGuides():HasMany{
        return $this->hasMany(StudyGuide::class);
    }
}
