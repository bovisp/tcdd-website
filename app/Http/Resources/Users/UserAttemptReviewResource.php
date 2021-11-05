<?php

namespace App\Http\Resources\Users;

use App\User;
use App\AssessmentMark;
use App\MultipleChoiceQuestion;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;

class UserAttemptReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $totalScore = $this->assessment->questions()->map(function ($question) {
            return $question['assessment_content']->assessmentPageContentItems[0]->question_score;
        })->reduce(function ($carry, $item) {
            return $carry + $item;
        });

        $participantScore = $this->assessmentMarks->pluck('mark')->reduce(function ($carry, $item) {
            return $carry + $item;
        });

        $answers = json_decode($this->answers, true);
        $attemptId = $this->id;

        $questions = $this->assessment->questions()->map(function ($question) use ($answers, $attemptId) {
            return [
                'score' => $question['assessment_content']->assessmentPageContentItems[0]->question_score,
                'number' => $question['assessment_content']->assessmentPageContentItems[0]->question_number,
                'text' => PartResource::collection($question['model']->contentBuilder->where('language', '=', app()->getLocale())->first()->parts),
                'content_builder_id' => $question['model']->contentBuilder->where('language', '=', app()->getLocale())->first()->id,
                'answer' => $answers['question_' . $question['model']->id],
                'answers' => $question['model']->questionType->code === 'multiple_choice' ? MultipleChoiceQuestion::whereQuestionId($question['model']->id)->first()->answers : null,
                'type' => $question['model']->questionType->code,
                'mark' => AssessmentMark::where(
                        'assessment_page_content_id', '=', $question['assessment_content']->assessmentPageContentItems[0]->id
                    )
                    ->where('assessment_attempt_id', '=', $attemptId)
                    ->get()
                    ->map(function ($mark) {
                        return [
                            'score' => $mark->mark,
                            'comment' => $mark->description,
                            'marker' => $mark->marker
                        ];
                    })
                    ->first()
            ];
        })->sortBy('question_number');

        return [
            'title' => $this->assessment->name,
            'section' => $this->assessment->section->name,
            'type' => $this->assessment->assessmentType->name,
            'marked_on' => $this->publish_date,
            'total_score' => $totalScore,
            'participant_score' => $participantScore,
            'percentage' => round((($participantScore / $totalScore) * 100), 1),
            'questions' => $questions
        ];
    }
}
