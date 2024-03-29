<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use Carbon\Carbon;
use App\Assessment;
use App\AssessmentMark;
use App\AssessmentAttempt;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;
use App\Events\AssessmentAttemptsMarked;
use App\Http\Resources\Assessments\AssessmentMarksResource;

class AssessmentMarksController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
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
                        $fail(__('app_http_controllers_assessments_assessments_api_assessmentmarks.maxscore1') . $item->question_score . __('app_http_controllers_assessments_assessments_api_assessmentmarks.maxscore2'));
                    }
                }
            ]
        ]);

        $mark = AssessmentMark::create([
            'assessment_attempt_id' => $attempt->id,
            'mark' => request()->has('mark') ? request('mark') : $mark->mark,
            'description' => request()->has('comment') ? request('comment') : $mark->description,
            'assessment_page_content_id' => request('itemId')
        ]);

        $this->updateMarkerIfScoreExists($mark);

        $this->allAnswersMarked($assessment, $attempt, $mark);

        $this->allAttemptsMarked($assessment);

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
                        $fail(__('app_http_controllers_assessments_assessments_api_assessmentmarks.maxscore1') . $item->question_score . __('app_http_controllers_assessments_assessments_api_assessmentmarks.maxscore2'));
                    }
                }
            ]
        ]);

        $mark->update([
            'assessment_attempt_id' => $attempt->id,
            'mark' => request()->has('mark') ? request('mark') : $mark->mark,
            'description' => request()->has('comment') ? request('comment') : $mark->description,
            'assessment_page_content_id' => request('itemId')
        ]);

        $this->updateMarkerIfScoreExists($mark);

        $this->allAnswersMarked($assessment, $attempt, $mark);

        $this->allAttemptsMarked($assessment);

        return new AssessmentMarksResource($mark);
    }

    public function updateScore(Assessment $assessment, AssessmentAttempt $attempt, AssessmentMark $mark)
    {
        request()->validate([
            'score' => 'numeric|min:0'
        ]);

        $mark->update([
            'mark' => request('score')
        ]);

        return new AssessmentMarksResource($mark);
    }

    protected function updateMarkerIfScoreExists(AssessmentMark $mark)
    {
        if (request()->has('mark')) {
            $mark->update([
                'marker_id' => auth()->id()
            ]);
        }
    }

    protected function allAnswersMarked(Assessment $assessment, AssessmentAttempt $attempt, AssessmentMark $mark)
    {
        $scoredAnswers = $attempt->assessmentMarks->filter(function ($mark) {
            return !is_null($mark->mark);
        });

        if ($assessment->questions()->count() === $scoredAnswers->count() && !$attempt->marked) {
            $attempt->update([
                'marked' => 1,
                'marked_on' => Carbon::now()
            ]);
        }
    }

    protected function allAttemptsMarked(Assessment $assessment)
    {
        $numParticipants = $assessment->participants()->count();

        $totalScoredAnswers = 0;

        foreach ($assessment->attempts->filter->completed as $attempt) {
            $totalScoredAnswers += $attempt->assessmentMarks->filter(function ($mark) {
                return !is_null($mark->mark);
            })->count();
        }

        if (($numParticipants * $assessment->questions()->count()) === $totalScoredAnswers) {
            $assessment->update([
                'marking_completed' => 1,
                'marking_completed_on' => Carbon::now()
            ]);

            event(new AssessmentAttemptsMarked($assessment->id, $assessment->marking_completed_on));
        }
    }
}
