<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestionAnswer extends Model
{
    protected $fillable = [
        'multiple_choice_question_id',
        'text',
        'is_correct'
    ];
}
