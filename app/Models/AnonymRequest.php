<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnonymRequest extends Model
{
    protected $fillable=[
        'text',
        'source'

    ];
    public function options():HasMany{
        return $this->hasMany(AnonymRequestImages::class,'anonym_request_id');
    }
}

