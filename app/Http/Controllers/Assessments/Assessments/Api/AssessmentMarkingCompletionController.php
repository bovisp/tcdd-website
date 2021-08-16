<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use Carbon\Carbon;
use App\Assessment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentShowResource;

class AssessmentMarkingCompletionController extends Controller
{
    public function update(Assessment $assessment)
    {
        $assessment->update([
            'marking_completed' => request('status') ? 1 : 0,
            'marking_completed_on' => request('status') ? Carbon::now() : null
        ]);

        return new AssessmentShowResource($assessment);
    }
}
