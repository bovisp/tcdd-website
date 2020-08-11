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
            'description' => $this->description,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'description_en' => $this->getTranslation('description', 'en'),
            'description_fr' => $this->getTranslation('description', 'fr'),
            'score' => $this->score,
            'author' => $this->author,
            'questionCategory' => $this->questionCategory,
            'question_category_id' => $this->questionCategory->id,
            'categoryName' => $this->questionCategory->name,
            'section' => $this->section,
            'section_id' => $this->section->id,
            'sectionName' => $this->section->name,
        ];
    }
}
