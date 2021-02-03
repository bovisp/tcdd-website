<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\DuplicateAssessment;
use App\Http\Resources\Assessments\AssessmentResource;

class DuplicateAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        })->only(['update', 'destroy']);
    }

    public function store(Assessment $assessment)
    {
        return new AssessmentResource(
            (new DuplicateAssessment($assessment))->create()
        );
    }
}
