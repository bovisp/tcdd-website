<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;

class AssessmentLockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
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
