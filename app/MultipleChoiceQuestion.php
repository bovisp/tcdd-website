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
                    return array_key_exists('text_en', $answer) || array_key_exists('text_fr', $answer);
                });

                foreach ($answers as $answer) {
                    MultipleChoiceQuestionAnswer::create([
                        'multiple_choice_question_id' => $question->id,
                        'is_correct' => $answer['is_correct'] ? 1 : 0,
                        'text' => [
                            'en' => array_key_exists('text_en', $answer) && !is_null($answer['text_en']) ? $answer['text_en'] : 'No translation added',
                            'fr' => array_key_exists('text_fr', $answer) && !is_null($answer['text_fr']) ? $answer['text_fr'] : 'No translation added',
                        ]
                    ]);
                }
            }
        });
    }

    protected $fillable = [
        'question_id',
        'multiple_answers',
        'shuffle_answers'
    ];

    public function answers()
    {
        return $this->hasMany(MultipleChoiceQuestionAnswer::class);
    }
}
