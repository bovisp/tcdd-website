<?php

namespace App;

use App\Part;
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

    public function parts()
    {
        return $this->hasMany(Part::class)
            ->orderBy('sort_order', 'asc');
    }
}
