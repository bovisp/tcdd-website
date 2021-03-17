<?php

namespace App\Http\Resources\Users;

use App\User;
use App\AssessmentMark;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'text' => $question['model']->contentBuilder->where('language', '=', app()->getLocale())->first()->parts,
                'answer' => $answers['question_' . $question['model']->id],
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
