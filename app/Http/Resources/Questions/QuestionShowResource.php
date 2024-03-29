<?php

namespace App\Http\Resources\Questions;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionShowResource extends JsonResource
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
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'marking_guide_en' => $this->getTranslation('marking_guide', 'en'),
            'marking_guide_fr' => $this->getTranslation('marking_guide', 'fr'),
            'author' => $this->author,
            'questionCategory' => optional($this->questionCategory),
            'question_category_id' => optional($this->questionCategory)->id,
            'categoryName' => optional($this->questionCategory)->name,
            'section' => optional($this->section),
            'section_id' => optional($this->section)->id,
            'sectionName' => optional($this->section)->name,
            'tags' => $this->tags,
            'type' => $this->questionType->code,
            'editors' => $this->editors,
            'contentBuilder' => [
                'en' => $this->contentBuilder->where('language', '=', 'en')->first()->id,
                'fr' => $this->contentBuilder->where('language', '=', 'fr')->first()->id,
            ],
            'assessments' => $this->assessments(),
            'inAssessment' => $this->inAssessment()
        ];
    }
}
