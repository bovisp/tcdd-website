<?php

namespace App\Classes\QuestionTypes;

use App\Question;
use App\DrawingQuestion;
use Illuminate\Support\Facades\File;

class DuplicateDrawingQuestion
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
        $originalQuestionDataModel = DrawingQuestion::whereQuestionId($this->question->id)->first();

        $unserializedDrawingOptions = unserialize($originalQuestionDataModel->drawing_options);

        $originalFile = last(explode('/', $unserializedDrawingOptions['background_image'][0]['file']));

        $fileExtension = last(explode('.', $originalFile));

        $newFile = '/storage/entries/' . auth()->id() . '/' .  md5(uniqid(rand(), true)) . '.' . $fileExtension;

        File::copy(public_path($unserializedDrawingOptions['background_image'][0]['file']), public_path($newFile));

        $dupicateQuestionDataModel = DrawingQuestion::create([
            'drawing_options' => serialize($unserializedDrawingOptions),
            'rich_text' => $originalQuestionDataModel->rich_text,
            'text_answer' => $originalQuestionDataModel->text_answer,
            'question_id' => $this->duplicateQuestion->id
        ]);

        $this->duplicateQuestion->update([
            'question_type_model_id' => $dupicateQuestionDataModel->id
        ]);

        $dupicateQuestionDataModel = $dupicateQuestionDataModel->toArray();

        $dupicateQuestionDataModel['drawing_options'] = unserialize($dupicateQuestionDataModel['drawing_options']);

        return $dupicateQuestionDataModel;
    }
}