<?php

namespace App\Classes\Assessments\Assessment;

use App\Question;
use App\AssessmentMark;
use App\AssessmentPage;
use App\AssessmentAttempt;
use App\AssessmentPageContentItem;

abstract class MarkQuestion
{
    protected $question;

    protected $participantAnswers;

    protected $attempt;

    public function __construct(Question $question, $participantAnswers, AssessmentAttempt $attempt)
    {
        $this->question = $question;
        $this->participantAnswers = $participantAnswers;
        $this->attempt = $attempt;
    }

    protected function findContentItem()
    {
        return AssessmentPageContentItem::whereType('Question')
            ->whereModelId($this->question->id)
            ->whereIn('assessment_page_content_id', $this->getAssessmentPageContentIds())
            ->first();
    }

    protected function getAssessmentPageContentIds()
    {
        return $this->attempt
            ->assessment
            ->pages
            ->map(function (AssessmentPage $page) {
                return [
                    'contentIds' => $page->assessmentPageContents->pluck('id')
                ];
            })
            ->flatten(3)
            ->toArray();
    }

    protected function persistScore($score, $markerId, $comments)
    {
        AssessmentMark::create([
            'assessment_attempt_id' => $this->attempt->id,
            'assessment_page_content_id' => $this->findContentItem()->id,
            'mark' => $score,
            'marker_id' => $markerId,
            'comments' => $comments
        ]);
    }
}