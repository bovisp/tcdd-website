<?php

namespace App\Http\Resources\Questions;

use App\Question;
use App\QuestionType;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionTypeDataResource extends JsonResource
{
    protected $question;

    public function __construct(Question $question)
    {
        $this->question = $question;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $questionType = QuestionType::find($this->question->question_type_id);
        
        $questionTypeClass = 'App\\' . ucfirst($questionType->code) . 'Question';

        $questionTypeData = $questionTypeClass::find($this->question->question_type_model_id);

        return [
            'data' => $questionTypeData,
            'type' => $questionType->code
        ];
    }
}
