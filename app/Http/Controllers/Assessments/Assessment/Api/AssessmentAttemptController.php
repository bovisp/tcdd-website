<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
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

            if ($participantActive) {
                return $next($request);
            }

            abort(403, 'You are not authorized to view this exam');
        })->only(['store']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);

            if ($attempt) {
                return $next($request);
            }

            return redirect("{env('APP_URL')}/assessments/{$assessment->id}");
        })->only(['show']);
    }

    public function store(Assessment $assessment)
    {
        $participantActive = $assessment->participants
            ->filter(function ($participant) {
                return $participant->pivot->activated && $participant->id === auth()->id();
            })
            ->first();
            
        $assessmentAttempt = AssessmentAttempt::create([
            'assessment_participant_id' => $participantActive->pivot->id
        ]);

        return $assessmentAttempt;
    }
}
