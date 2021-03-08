<?php

namespace App\Http\Resources\Assessments;

use App\AssessmentAttempt;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentIndexResource extends JsonResource
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
            'name' => $this->name,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'assessment_type_id' => $this->assessment_type_id,
            'assessmentType' => $this->assessmentType,
            'section_id' => $this->section_id
        ];
    }
}
