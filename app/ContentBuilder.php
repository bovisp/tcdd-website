<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentBuilder extends Model
{
    protected $connection = 'mysql';

    protected $table = 'content_builders';

    protected $fillable = [
        'language'
    ];
    
    public function contentable()
    {
        return $this->morphTo();
    }
}
