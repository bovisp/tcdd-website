<?php

namespace App\Http\Resources\Assessments;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentAttemptCompletedResource extends JsonResource
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
            'answers' => json_decode($this->answers, true)
        ];
    }
}
