<?php

namespace App;

use Illuminate\Support\Arr;
use App\MultipleChoiceQuestionAnswer;
use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestion extends Model
{
    protected static function booted() {
        static::updated(function ($question) {
            if ($question->isDirty('question_id')) {
                $answers = array_filter(request('question_type_data')['answers'], function ($answer) {
                    return trim($answer['text']) !== '';
                });

                foreach ($answers as $answer) {
                    MultipleChoiceQuestionAnswer::create([
                        'multiple_choice_question_id' => $question->id,
                        'is_correct' => $answer['is_correct'] ? 1 : 0,
                        'text' => $answer['text']
                    ]);
                }
            }
        });
    }

    protected $fillable = [
        'question_id',
        'multiple_answers'
    ];

    public function answers()
    {
        return $this->hasMany(MultipleChoiceQuestionAnswer::class);
    }
}
