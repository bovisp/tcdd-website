<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class QuestionCategory extends Model
{
    use HasTranslations;

    protected $translatable = [
        'name',
        'description'
    ];

    protected $fillable = [
        'name',
        'description'
    ];
}
