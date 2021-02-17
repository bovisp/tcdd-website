<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentLockController extends Controller
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
        });
    }

    public function update(Assessment $assessment)
    {
        if ($assessment->locked === 1 && $assessment->attempts->count()) {
            return response()->json([
                'data' => [
                    'message' => 'You cannot unlock an assessment in which participants have attempted or are attempting.'
                ]
            ], 403);
        }

        $assessment->update([
            'locked' => $assessment->locked === 1 ? 0 : 1
        ]);

        return $assessment->locked ? true : false;
    }
}
