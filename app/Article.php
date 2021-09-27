<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'author',
        'title'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function contentBuilder()
    {
        return $this->morphMany(ContentBuilder::class, 'contentable');
    }
}
