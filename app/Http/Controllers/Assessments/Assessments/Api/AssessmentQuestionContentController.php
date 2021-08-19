<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentPage;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\RenumberAssessmentQuestions;

class AssessmentQuestionContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);
            
            if ($assessment->locked) {
                return response()->json([
                    'data' => [
                        'message' => __('app_http_controllers_assessments_assessments_api_assessmentquestioncontent.cannotlocked')
                    ]
                ], 403);
            }

            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        });
    }

    public function changeScore(Assessment $assessment, AssessmentPageContentItem $item)
    {
        request()->validate([
            'score' => 'required|min:0|integer'
        ]);

        $item->update([
            'question_score' => request('score')
        ]);

        return $item->question_score;
    }

    public function changePage(Assessment $assessment, AssessmentPageContentItem $item)
    {
        request()->validate([
            'page_number' => [
                'required',
                'min:0',
                'integer',
                function ($attribute, $value, $fail) use ($item) {
                    $assessmentId = $item->assessmentPageContent->assessmentPage->assessment->id;

                    $assessmentPage = AssessmentPage::whereAssessmentId($assessmentId)
                        ->whereNumber(request('page_number'))
                        ->first();

                    if (!$assessmentPage) {
                        $fail(__('app_http_controllers_assessments_assessments_api_assessmentquestioncontent.pagenotexist'));
                    }
                },
            ]
        ]);

        $assessmentPageContent = AssessmentPageContent::find($item->assessment_page_content_id);

        $assessmentId = $item->assessmentPageContent->assessmentPage->assessment->id;

        $assessmentPage = AssessmentPage::whereAssessmentId($assessmentId)
            ->whereNumber(request('page_number'))
            ->first();

        $assessmentPageContent->update([
            'assessment_page_id' => $assessmentPage->id
        ]);

        (new RenumberAssessmentQuestions($assessment))->renumber();

        return $assessmentPage->number;
    }
}
