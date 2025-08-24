<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
