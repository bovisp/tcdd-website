<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MediaPart extends Model
{
    protected $fillable = [
        'part_id',
        'filename',
        'title',
        'caption'
    ];
}
