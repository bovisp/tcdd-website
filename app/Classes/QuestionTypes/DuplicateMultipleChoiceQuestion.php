<?php

namespace App\Classes\QuestionTypes;

use App\Question;
use App\MultipleChoiceQuestion;
use App\MultipleChoiceQuestionAnswer;

class DuplicateMultipleChoiceQuestion
{
    protected $question;

    protected $duplicateQuestion;

    public function __construct(Question $question, Question $duplicateQuestion)
    {
        $this->question = $question;
        $this->duplicateQuestion = $duplicateQuestion;
    }

    public function duplicate()
    {
        $originalQuestionDataModel = MultipleChoiceQuestion::whereQuestionId($this->question->id)->first();

        $dupicateQuestionDataModel = MultipleChoiceQuestion::create([
            'multiple_answers' => $originalQuestionDataModel->multiple_answers,
            'shuffle_answers' => $originalQuestionDataModel->shuffle_answers,
            'question_id' => $this->duplicateQuestion->id
        ]);

        foreach ($originalQuestionDataModel->answers as $answer) {
            MultipleChoiceQuestionAnswer::create([
                'multiple_choice_question_id' => $dupicateQuestionDataModel->id,
                'is_correct' => $answer->is_correct,
                'text' => [
                    'en' => $answer->getTranslation('text', 'en'),
                    'fr' => $answer->getTranslation('text', 'fr')
                ]
            ]);
        }

        $this->duplicateQuestion->update([
            'question_type_model_id' => $dupicateQuestionDataModel->id
        ]);

        $dupicateQuestionDataModel = $dupicateQuestionDataModel->toArray();

        $dupicateQuestionDataModel['answers'] = MultipleChoiceQuestionAnswer::whereMultipleChoiceQuestionId($dupicateQuestionDataModel['id'])
            ->get()
            ->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'text_en' => $answer->getTranslation('text', 'en'),
                    'text_fr' => $answer->getTranslation('text', 'fr'),
                    'is_correct' => $answer->is_correct,
                    'text' => $answer->text
                ];
            })
            ->toArray();

        return $dupicateQuestionDataModel;
    }
}