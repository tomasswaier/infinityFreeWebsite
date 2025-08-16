<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subjects;

class Tag extends Model
{
    protected $fillable=[
        'name',
    ];
    public function subjects()
    {
        return $this->belongsToMany(Subjects::class, 'subject_tags', 'tag_id', 'subject_id');
    }
}
