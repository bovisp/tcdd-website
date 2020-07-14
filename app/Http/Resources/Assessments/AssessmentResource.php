<?php

namespace App\Http\Resources\Assessments;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
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
            'sectionName' => $this->section->name,
            'typeName' => $this->assessmentType->name,
            'visibility' => $this->visible ? 'Yes' : 'No',
            'name' => $this->name,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'visible' => $this->visible,
            'description' => $this->description,
            'description_en' => $this->getTranslation('description', 'en'),
            'description_fr' => $this->getTranslation('description', 'en'),
            'assessment_type_id' => $this->assessment_type_id,
            'assessmentType' => $this->assessmentType,
            'section_id' => $this->section_id,
            'total_score' => $this->total_score
        ];
    }
}
