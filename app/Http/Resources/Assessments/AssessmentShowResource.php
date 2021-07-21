<?php

namespace App\Http\Resources\Assessments;

use App\AssessmentAttempt;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Assessments\AssessmentPageResource;

class AssessmentShowResource extends JsonResource
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
            'description' => $this->description,
            'description_en' => $this->getTranslation('description', 'en'),
            'description_fr' => $this->getTranslation('description', 'en'),
            'completion_time' => $this->completion_time,
            'assessment_type_id' => $this->assessment_type_id,
            'assessmentType' => $this->assessmentType,
            'section_id' => $this->section_id,
            'editors' => $this->editors,
            'participants' => $this->participants->map(function ($participant) {
                if (optional(AssessmentAttempt::whereAssessmentParticipantId($participant->pivot->id)->first())->completed) {
                    $participant->completed = true;

                    return $participant;
                }

                return $participant;
            }),
            'locked' => $this->locked ? true : false,
            'marking_completed' => $this->marking_completed ? true : false,
            'marking_completed_on' => $this->marking_completed_on
        ];
    }
}
