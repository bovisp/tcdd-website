<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Support\Facades\DB;
use App\Events\AssessmentCompleted;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\Assessment\PrepareAssessment;

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
                if ($attempt->completed) {
                    return redirect(env('APP_URL') . "/users/" . auth()->id());
                }
                
                return $next($request);
            }
    
            return response()->json(__('app_http_controllers_assessments_assessment_api_assessmentattempt.notauthorizedexam'), 422);
        });
    }

    public function update(Assessment $assessment, AssessmentAttempt $attempt)
    {
        $allAnswers = (new PrepareAssessment($assessment, $attempt))->prepare();

        $attempt->update([
            'answers' => $allAnswers,
            'completed' => 1
        ]);

        DB::table('assessment_participants')
            ->where('participant_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->update(['activated' => 0]);

        event(new AssessmentCompleted($assessment->id, $attempt->id));

        return auth()->id();
    }
}
