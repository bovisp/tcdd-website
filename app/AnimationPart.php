<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnimationPart extends Model
{
    protected $fillable = [
        'part_id',
        'images',
        'title',
        'caption'
    ];
}
