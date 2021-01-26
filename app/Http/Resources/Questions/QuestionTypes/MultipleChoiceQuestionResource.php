<?php

namespace App\Http\Resources\Questions\QuestionTypes;

use App\ContentBuilder;
use App\MultipleChoiceQuestion;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;

class MultipleChoiceQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $questionData = MultipleChoiceQuestion::find($this->question_type_model_id);
        
        $contentBuilderId = $this->contentBuilder
            ->filter(function ($content) {
                return $content->language === app()->getLocale();
            })
            ->first()
            ->id;

        return [
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'type' => $this->questionType->code,
                'data' => [
                    'multiple_answers' => $this->multiple_answers,
                    'shuffle_answers' => $this->shuffle_answers,
                    'answers' => $questionData->answers->map(function ($answer) {
                        return [
                            'id' => $answer->id,
                            'text' => $answer->text
                        ];
                    }),
                    'parts' => PartResource::collection(ContentBuilder::find($contentBuilderId)->parts)
                ]
            ]
        ];
    }
}
