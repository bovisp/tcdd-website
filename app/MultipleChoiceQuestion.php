<?php

namespace App;

use App\Question;
use Illuminate\Support\Arr;
use App\MultipleChoiceQuestionAnswer;
use Illuminate\Database\Eloquent\Model;

class MultipleChoiceQuestion extends Model
{
    protected static function booted() {
        static::updated(function ($question) {
            if ($question->isDirty('question_id')) {
                $answers = array_filter(request('question_type_data')['answers'], function ($answer) {
                    if (trim($answer['text_en']) === '' && trim($answer['text_fr']) === '') {
                        return false;
                    }

                    return true;
                });

                foreach ($answers as $answer) {
                    MultipleChoiceQuestionAnswer::create([
                        'multiple_choice_question_id' => $question->id,
                        'is_correct' => $answer['is_correct'] ? 1 : 0,
                        'text' => [
                            'en' => trim($answer['text_en']) ? $answer['text_en'] : '**No translation added**',
                            'fr' => trim($answer['text_fr']) ? $answer['text_fr'] : '**No translation added**'
                        ]
                    ]);
                }

                return;
            }
        });
    }

    protected $fillable = [
        'question_id',
        'multiple_answers',
        'shuffle_answers'
    ];

    protected $with = [
        'answers'
    ];

    // protected $appends =[
    //     'type'
    // ];

    // public function getTypeAttribute()
    // {
    //     return $this->question->questionType->code;
    // }

    public function answers()
    {
        return $this->hasMany(MultipleChoiceQuestionAnswer::class);
    }

    // public function question()
    // {
    //     return $this->belongsTo(Question::class);
    // }
}
