<?php

namespace App\Http\Resources\Assessments;

use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentPageResource extends JsonResource
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
            'assessment_id' => $this->id,
            'number' => $this->number
        ];
    }
}
