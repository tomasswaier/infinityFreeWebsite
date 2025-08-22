<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    //
    protected $fillable=[
        'school_id',
        'test_name',
        'test_author',
        'number_of_submits'

    ];
    public function questions():HasMany{
        return $this->hasMany(Question::class,'tests_id');
    }
}
