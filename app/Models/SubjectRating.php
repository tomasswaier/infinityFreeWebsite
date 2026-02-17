<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectRating extends Model
{
    protected $fillable=[
        'subject_id',
        'user_id',
        'rating'
    ];
}
