<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestionAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'answer',
        'is_correct'
    ];
}
