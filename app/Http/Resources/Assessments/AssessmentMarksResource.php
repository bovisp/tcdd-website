<?php

namespace App\Http\Resources\Assessments;

use App\User;
use App\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentMarksResource extends JsonResource
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
            'assessment_attempt_id' => $this->assessment_attempt_id,
            'mark' => $this->mark,
            'question_id' => $this->assessmentPageContentItem->model_id,
            'marked_by' => optional(User::find($this->marker_id))->fullname,
            'description' => $this->description
        ];
    }
}
