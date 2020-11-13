<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TabPartSection extends Model
{
    protected $fillable = [
        'tab_part_id',
        'title',
        'content_id',
        'type'
    ];
}
