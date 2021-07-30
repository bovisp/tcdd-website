<?php

namespace App\Classes\Assessments;

use App\Assessment;
use App\AssessmentPageContentItem;
use App\Classes\Helpers\QueryResultSorter;

class RenumberAssessmentQuestions
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function renumber()
    {
        $pageContents = $this->getPageContents();

        $this->checkSequentialOrder($pageContents);

        $assessmentQuestions = $this->getFlattenedQuestionCollection();

        $this->renumberQuestions(QueryResultSorter::sortBy($assessmentQuestions->toArray(), [
            'page' => 'asc',
            'order' => 'asc'
        ]));
    }

    protected function getFlattenedQuestionCollection()
    {
        return $this->assessment->pages->map(function ($page) {
            return $page->assessmentPageContents->map(function ($assessmentPageContent) use ($page) {
                return $assessmentPageContent->assessmentPageContentItems->where('type', '=', 'Question')->map(function ($item) use ($assessmentPageContent) {
                    return [
                        'id' => $item->id,
                        'question' => $item->question_number,
                        'order' => $assessmentPageContent->order,
                        'page' => $item->assessmentPageContent->assessmentPage->number
                    ];
                });
            });
        })
        ->flatten(2);
    }

    protected function renumberQuestions($assessmentQuestions)
    {
        for ($i = 0; $i < count($assessmentQuestions); $i++) {
            $item = AssessmentPageContentItem::find($assessmentQuestions[$i]['id']);

            $item->update([
                'question_number' => $i +1
            ]);
        }
    }

    protected function checkSequentialOrder ($pages)
    {
        foreach ($pages as $page) {
            $orderedContent = $page->sortBy('order');

            $orderedArr = $orderedContent->pluck('order')->toArray();

            $comparisonArr = range(1, count($orderedArr));

            if (!array_diff($orderedArr, $comparisonArr)) {
                continue;
            }

            for ($i = 0; $i < count($orderedContent); $i++) {
                $orderedContent[$i]['content']->update([
                    'order' => $comparisonArr[$i]
                ]);
            }
        }
    }

    protected function getPageContents()
    {
        return $this->assessment->pages->map(function ($page) {
            return $page->assessmentPageContents->map(function ($assessmentPageContent) use ($page) {
                return [
                    'page' => $page->id,
                    'content' => $assessmentPageContent,
                    'order' => $assessmentPageContent->order
                ];
            });
        })
        ->flatten(1)
        ->groupBy('page');
    }
}