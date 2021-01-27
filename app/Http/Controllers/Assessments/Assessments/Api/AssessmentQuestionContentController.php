<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentPage;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;

class AssessmentQuestionContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        });
    }

    public function changeScore(Assessment $assessment, AssessmentPageContentItem $item)
    {
        $item->update([
            'question_score' => request('score')
        ]);

        return $item->question_score;
    }

    public function changePage(Assessment $assessment, AssessmentPageContentItem $item)
    {
        $assessmentPageContent = AssessmentPageContent::find($item->assessment_page_content_id);

        $assessmentPageContent->update([
            'assessment_page_id' => request('page_number_id')
        ]);

        $assessmentQuestions = $assessment->pages->map(function ($page) {
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

        for ($i = 0; $i < $assessmentQuestions->count(); $i++) {
            $assessmentPageContentItem = AssessmentPageContentItem::find($assessmentQuestions[$i]['id']);

            $assessmentPageContentItem->update([
                'question_number' => $i + 1
            ]);
        }

        return AssessmentPage::find($assessmentPageContent->assessment_page_id)->number;
    }
}
