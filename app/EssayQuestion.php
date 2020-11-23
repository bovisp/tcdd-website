<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EssayQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'rich_text'
    ];
}
