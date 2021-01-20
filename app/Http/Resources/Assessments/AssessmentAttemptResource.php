<?php

namespace App\Http\Resources\Assessments;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentAttemptResource extends JsonResource
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
            'assessment_participant_id' => $this->assessment_participant_id,
            'time_remaining' => $this->time_remaining,
            'assessment' => $this->assessment(),
            'participant' => $this->participant()
        ];
    }
}
