<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentMark;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentMarksResource;

class AssessmentMarksController extends Controller
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

    public function store(Assessment $assessment, AssessmentAttempt $attempt)
    {
        request()->validate([
            'questionId' => 'required|exists:questions,id',
            'itemId' => 'required|exists:assessment_page_content_items,id',
            'comment' => 'nullable|min:3',
            'mark' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $item = AssessmentPageContentItem::find((int) request('itemId'));
                    if (request()->has('mark') && request('mark') > $item->question_score) {
                        $fail('The most a participant can score on this qiestion is ' . $item->question_score . ' points');
                    }
                }
            ]
        ]);

        $mark = AssessmentMark::create([
            'assessment_attempt_id' => $attempt->id,
            'mark' => request()->has('mark') ?? request('mark'),
            'description' => request()->has('comment') ? request('comment') : null,
            'assessment_page_content_id' => request('itemId')
        ]);

        return new AssessmentMarksResource($mark);
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt, AssessmentMark $mark)
    {
        request()->validate([
            'questionId' => 'required|exists:questions,id',
            'itemId' => 'required|exists:assessment_page_content_items,id',
            'comment' => 'nullable|min:3',
            'mark' => [
                'nullable',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    $item = AssessmentPageContentItem::find((int) request('itemId'));
                    if (request()->has('mark') && request('mark') > $item->question_score) {
                        $fail('The most a participant can score on this qiestion is ' . $item->question_score . ' points');
                    }
                }
            ]
        ]);

        $mark->update([
            'assessment_attempt_id' => $attempt->id,
            'mark' => request()->has('mark') ? request('mark') : null,
            'description' => request()->has('comment') ? request('comment') : null,
            'assessment_page_content_id' => request('itemId')
        ]);

        return new AssessmentMarksResource($mark);
    }
}
