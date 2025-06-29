<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    //
    protected $fillable=[
        'test_name',
        'test_author',
        'number_of_submits'

    ];
}
