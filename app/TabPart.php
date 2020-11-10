<?php

namespace App;

use App\TabPartSection;
use Illuminate\Database\Eloquent\Model;

class TabPart extends Model
{
    protected $fillable = [
        'part_id',
        'title',
        'caption'
    ];

    public function tabSections()
    {
        return $this->hasMany(TabPartSection::class);
    }
}
