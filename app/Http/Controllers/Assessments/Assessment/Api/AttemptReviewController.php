<?php

namespace App\Http\Controllers\Assessments\Assessment\Api;

use App\Assessment;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;

class AttemptReviewController extends Controller
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

    public function index(Assessment $assessment, AssessmentAttempt $attempt)
    {
        
    }
}
