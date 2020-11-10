<?php

namespace App;

use App\Part;
use Illuminate\Database\Eloquent\Model;

class ContentPart extends Model
{
    protected $fillable = [
        'content',
        'part_id'
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
