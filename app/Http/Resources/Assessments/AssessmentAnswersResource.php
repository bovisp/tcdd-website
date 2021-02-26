<?php

namespace App\Http\Resources\Assessments;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Assessments\AssessmentMarksResource;

class AssessmentAnswersResource extends JsonResource
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
            'participant' => $this->participant(),
            'assessment_participant_id' => $this->assessment_participant_id,
            'answers' => json_decode($this->answers),
            'marks' => AssessmentMarksResource::collection($this->assessmentMarks)
        ];
    }
}
