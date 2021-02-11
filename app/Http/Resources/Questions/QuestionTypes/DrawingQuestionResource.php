<?php

namespace App\Http\Resources\Questions\QuestionTypes;

use App\ContentBuilder;
use App\DrawingQuestion;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;

class DrawingQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $questionData = DrawingQuestion::find($this->question_type_model_id);
        
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
                    'drawing_options' => unserialize($questionData->drawing_options),
                    'rich_text' => $questionData->rich_text ? true : false,
                    'text_answer' => $questionData->text_answer ? true : false,
                    'parts' => PartResource::collection(ContentBuilder::find($contentBuilderId)->parts)
                ]
            ]
        ];
    }
}
