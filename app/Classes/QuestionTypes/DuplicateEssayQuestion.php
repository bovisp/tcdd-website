<?php

namespace App\Classes\QuestionTypes;

use App\Question;
use App\EssayQuestion;

class DuplicateEssayQuestion
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
        $originalQuestionDataModel = EssayQuestion::whereQuestionId($this->question->id)->first();

        $dupicateQuestionDataModel = EssayQuestion::create([
            'rich_text' => $originalQuestionDataModel->rich_text,
            'question_id' => $this->duplicateQuestion->id
        ]);

        $this->duplicateQuestion->update([
            'question_type_model_id' => $dupicateQuestionDataModel->id
        ]);

        return $dupicateQuestionDataModel;
    }
}