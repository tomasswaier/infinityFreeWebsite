<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable=[
        'questions_id',
        'preceding_text',
        'data',
        'option_type'
    ];
    protected $casts = [
        'data' => 'array'
    ];

    //
}
