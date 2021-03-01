<?php

namespace App\Http\Resources\Assessments;

use App\MultipleChoiceQuestion;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;
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
        $participant = $this->participant();

        return [
            'id' => $this->id,
            'participant' => $this->participant(),
            'assessment_participant_id' => $this->assessment_participant_id,
            'participant_firstname' => $participant->firstname,
            'participant_lastname' => $participant->lastname,
            'participant_fullname' => $participant->fullname,
            'answers' => json_decode($this->answers),
            'marks' => AssessmentMarksResource::collection($this->assessmentMarks),
            'questions' => $this->assessment->questions()->map(function ($question) {
                $type = $question['model']->questionType->code;

                return [
                    'id' => $question['model']->id,
                    'type' => $type,
                    'question_number' => $question['assessment_item']->question_number,
                    'question_score' => $question['assessment_item']->question_score,
                    'question_item' => $question['assessment_item']->id,
                    'marking_guide' => $question['model']->marking_guide,
                    'parts' => PartResource::collection(
                        $question['model']->contentBuilder->where('language', '=', app()->getLocale())->first()->parts
                    ),
                    'answers' => $type === 'multiple_choice' ? MultipleChoiceQuestion::whereQuestionId($question['model']->id)->first()->answers : null
                ];
            })
        ];
    }
}
