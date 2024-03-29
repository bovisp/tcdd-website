<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use Carbon\Carbon;
use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\AssessmentAttemptStarted;
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
                    return $participant->pivot->activated && $participant->pivot->participant_id === auth()->id();
                })
                ->first();

            $attempt = AssessmentAttempt::whereAssessmentParticipantId($participantActive->pivot->id)->first();

            if ($attempt) {
                abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));
            }

            if ($participantActive) {
                return $next($request);
            }

            abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));
        })->only(['store']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if (auth()->user()->hasRole(['administrator'])) {
                return $next($request);
            }

            $participantActive = $assessment->participants
                ->filter(function ($participant) {
                    return $participant->pivot->activated && $participant->pivot->participant_id === auth()->id();
                })
                ->first();

            if (!$participantActive) {
                abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));
            }

            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);

            $attempt = AssessmentAttempt::find((int) $matches[1][0]);

            if (!$attempt) {
                abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));
            }

            if ($attempt->completed) {
                return redirect(env('APP_URL') . "/users/" . auth()->id());
            }

            $isValidAttemptForUser = $participantActive->pivot->id === $attempt->assessment_participant_id;

            if (!$isValidAttemptForUser) {
                abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));;    
            }

            $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

            if ($time_remaining <= 0) {
                $participantActive->pivot->activated = 0;

                $participantActive->pivot->save();

                abort(403, __('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'));
            }

            return $next($request);
        })->only(['show', 'update']);
    }

    public function store(Assessment $assessment)
    {
        $participantActive = $assessment->participants
            ->filter(function ($participant) {
                return $participant->pivot->activated && $participant->pivot->participant_id === auth()->id();
            })
            ->first();
            
        $assessmentAttempt = AssessmentAttempt::create([
            'assessment_participant_id' => $participantActive->pivot->id,
            'time_remaining' => $assessment->completion_time ? $assessment->completion_time : null,
            'assessment_id' => $assessment->id
        ]);

        $assessment->update([
            'locked' => 1
        ]);

        event(new AssessmentAttemptStarted($assessment->id));

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
