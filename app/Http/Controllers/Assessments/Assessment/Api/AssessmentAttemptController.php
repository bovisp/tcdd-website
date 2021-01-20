<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentAttemptResource;

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

            return response()->json('You are not authorized to view this exam', 422);
        })->only(['show', 'update']);
    }

    public function store(Assessment $assessment)
    {
        $participantActive = $assessment->participants
            ->filter(function ($participant) {
                return $participant->pivot->activated && $participant->id === auth()->id();
            })
            ->first();
            
        $assessmentAttempt = AssessmentAttempt::create([
            'assessment_participant_id' => $participantActive->pivot->id,
            'time_remaining' => $assessment->completion_time ? $assessment->completion_time : null
        ]);

        return new AssessmentAttemptResource($assessmentAttempt);
    }

    public function show(Assessment $assessment, AssessmentAttempt $attempt)
    {
        return new AssessmentAttemptResource($attempt); 
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        DB::table('assessment_participants')
            ->where('id', $attempt->assessment_participant_id)
            ->update([
                'activated' => 0
            ]);
    }
}