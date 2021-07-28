<?php

namespace App\Classes\Assessments;

use App\Assessment;
use App\AssessmentPageContentItem;

class RenumberAssessmentQuestions
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function renumber()
    {
        $assessmentQuestions = $this->getAssessmentQuestions();

        $this->renumberQuestions($assessmentQuestions);
    }

    protected function getAssessmentQuestions()
    {
        return $this->assessment->pages->map(function ($page) {
            return $page->assessmentPageContents->map(function ($assessmentPageContent) {
                return $assessmentPageContent->assessmentPageContentItems->where('type', '=', 'Question')->map(function ($item) use ($assessmentPageContent) {
                    return [
                        'id' => $item->id,
                        'question' => $item->question_number,
                        'order' => $assessmentPageContent->order
                    ];
                });
            });
        })
        ->flatten(2)
        ->sortBy('order');
    }

    protected function renumberQuestions($assessmentQuestions)
    {
        for ($i = 0; $i < $assessmentQuestions->count(); $i++) {
            $item = AssessmentPageContentItem::find($assessmentQuestions[$i]['id']);

            $item->update([
                'question_number' => $i +1
            ]);
        }
    }
}