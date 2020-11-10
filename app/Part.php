<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'series_id',
        'sort_order',
        'content_builder_type_id'
    ];
}
