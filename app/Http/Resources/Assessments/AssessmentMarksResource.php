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
            'question_number' => $this->assessmentPageContentItem->question_score,
            'question_mark' => $this->assessmentPageContentItem->question_mark,
            'question_id' => $this->assessmentPageContentItem->model_id,
            'marking_guide' => Question::find($this->assessmentPageContentItem->model_id)->marking_guide,
            'marked_by' => optional(User::find($this->marker_id))->fullname,
            'marked_on' => $this->updated_at,
            'description' => $this->description
        ];
    }
}
