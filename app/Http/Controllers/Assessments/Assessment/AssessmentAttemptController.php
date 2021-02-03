<?php

namespace App\Http\Controllers\Assessments\Assessment;

use Carbon\Carbon;
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
                return redirect(env('APP_URL') . "/users/" . auth()->id());
            }

            $attempt = AssessmentAttempt::whereAssessmentParticipantId(
                $participantActive->pivot->id
            )->first();
            
            if ($attempt) {
                $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

                if ($time_remaining <= 0) {
                    $participantActive->pivot->activated = 0;

                    $participantActive->pivot->save();
                    
                    return redirect(env('APP_URL') . "/users/{$participantActive->id}");
                }

                return redirect(env('APP_URL') . "/assessment/{$assessment->id}/attempt/{$attempt->id}");
            }

            return $next($request);
        })->only(['index']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->pivot->participant_id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                return redirect(env('APP_URL') . "/users/" . auth()->id());
            }

            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);

            if (!$attempt) {
                return redirect(env('APP_URL') . "/assessment/{$assessment->id}");    
            }

            $isValidAttemptForUser = $participantActive->pivot->id === $attempt->assessment_participant_id;

            if (!$isValidAttemptForUser) {
                return redirect(env('APP_URL') . "/assessment/{$assessment->id}");    
            }

            $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

            if ($time_remaining <= 0) {
                $participantActive->pivot->activated = 0;

                $participantActive->pivot->save();

                return redirect(env('APP_URL') . "/users/{$participantActive->id}");
            }

            return $next($request);
        })->only(['show']);
    }

    public function index(Assessment $assessment)
    {
        return view('assessments.assessment.index', compact('assessment'));
    }

    public function show(Assessment $assessment, AssessmentAttempt $attempt)
    {
        return view('assessments.assessment.show', compact('attempt', 'assessment'));
    }
}
