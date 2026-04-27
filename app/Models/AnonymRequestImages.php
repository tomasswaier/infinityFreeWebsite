<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnonymRequestImages extends Model
{
    protected $fillable=[
        'anonym_request_id',
        'image_name',
    ];
}
