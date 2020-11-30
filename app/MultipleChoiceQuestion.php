<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'multiple_answers'
    ];
}
