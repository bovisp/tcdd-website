<?php

namespace App\Http\Controllers\Assessments\Assessment;

use App\Assessment;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;

class AssessmentAttemptController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                abort(403, 'You are not authorized to view this exam');
            }

            $attempt = AssessmentAttempt::whereAssessmentParticipantId(
                $participantActive->pivot->id
            )->first();
            
            if ($attempt) {
                return redirect(env('APP_URL') . "/assessment/{$assessment->id}/attempt/{$attempt->id}");
            }

            return $next($request);
        })->only(['index']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                abort(403, 'You are not authorized to view this exam');
            }

            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);

            if ($attempt) {
                return $next($request);
            }

            return redirect("{env('APP_URL')}/assessment/{$assessment->id}");
        })->only(['show']);
    }

    public function index(Assessment $assessment)
    {
        return view('assessments.assessment.index', compact('assessment'));
    }

    public function show(Assessment $assessment, AssessmentAttempt $attempt)
    {
        return view('assessments.assessment.show', compact('attempt'));
    }
}
