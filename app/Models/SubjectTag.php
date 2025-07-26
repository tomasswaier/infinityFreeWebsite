<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectTag extends Model
{
    protected $fillable=[
        'subject_id',
        'tag_id'
    ];
}
