<?php

namespace App\Http\Resources\Questions;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoryName' => optional($this->questionCategory)->name,
            'sectionName' => optional($this->section)->name,
            'contentBuilder' => [
                'en' => $this->contentBuilder->where('language', '=', 'en')->first()->id,
                'fr' => $this->contentBuilder->where('language', '=', 'fr')->first()->id,
            ]
        ];
    }
}
