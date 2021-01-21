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
            'assessment' => $this->assessment()->only([
                'completion_time', 'id', 'name'
            ]),
            'participant' => $this->participant()->only([
                'fullname', 'id', 'pivot'
            ]),
            'pages' => $this->assessment()->pages->map(function ($page) {
                return [
                    'id' => $page->id,
                    'assessment_id' => $page->assessment_id,
                    'number' => $page->number,
                    'content' => $page->assessmentPageContents->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'assessment_page_id' => $content->assessment_page_id,
                            'order' => $content->order,
                            'items' => $content->assessmentPageContentItems->map(function ($item) {
                                if ($item->type === 'Question') {
                                    return [
                                        'type' => $item->type,
                                        'id' => $item->id,
                                        'model_id' => $item->model_id,
                                        'assessment_page_content_id' => $item->assessment_page_content_id,
                                        'question_number' => $item->question_number,
                                        'question_score' => $item->question_score
                                    ];
                                }

                                return [
                                    'type' => $item->type,
                                    'id' => $item->id,
                                    'model_id' => $item->model_id,
                                    'assessment_page_content_id' => $item->assessment_page_content_id
                                ];
                            })
                        ];
                    })
                ];
            })
        ];
    }
}
