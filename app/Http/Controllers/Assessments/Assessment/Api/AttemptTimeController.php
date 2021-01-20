<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use Carbon\Carbon;
use App\Assessment;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;

class AttemptTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessment\/([\d]+)/",request()->url(),$matches);
    
            $assessment = Assessment::find((int) $matches[1][0]);
    
            preg_match_all("/\/attempt\/([\d]+)/",request()->url(),$matches);
    
            $attempt = AssessmentAttempt::find((int) $matches[1][0]);
    
            if ($attempt) {
                return $next($request);
            }
    
            return response()->json('You are not authorized to view this exam', 422);
        });
    }

    public function index (Assessment $assessment, AssessmentAttempt $attempt)
    {
        $time_remaining = $assessment->completion_time - Carbon::now()->diffInMinutes($attempt->created_at);

        if ($time_remaining >= 0) {
            $attempt->update([
                'time_remaining' => $time_remaining
            ]);
        }

        return $time_remaining;
    }
}
