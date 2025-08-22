<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable=[
        'tests_id',
        'question_text',
        'explanation_text'

    ];
    public function options():HasMany{
        return $this->hasMany(Option::class,'questions_id');
    }
}
