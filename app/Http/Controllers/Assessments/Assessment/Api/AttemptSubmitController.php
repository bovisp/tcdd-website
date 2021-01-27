<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttemptSubmitController extends Controller
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

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $attempt->update([
            'answers' => request('answers')
        ]);

        DB::table('assessment_participants')
            ->where('participant_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->update(['activated' => 0]);

        return;
    }
}
