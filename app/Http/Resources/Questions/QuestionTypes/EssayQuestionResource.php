<?php

namespace App\Http\Resources\Questions\QuestionTypes;

use App\EssayQuestion;
use App\ContentBuilder;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;

class EssayQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $questionData = EssayQuestion::find($this->question_type_model_id);
        
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
                    'rich_text' => $questionData->rich_text,
                    'parts' => PartResource::collection(ContentBuilder::find($contentBuilderId)->parts)
                ]
            ]
        ];
    }
}
