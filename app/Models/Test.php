<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    //
    protected $fillable=[
        'school_id',
        'test_name',
        'test_author',
        'number_of_submits'

    ];
}
