<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentAttemptCompletedResource;

class AssessmentAttemptsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments', 'assessment-edit']);
    }

    public function index(Assessment $assessment)
    {
        return AssessmentAttemptCompletedResource::collection(
            $assessment->attempts->filter->completed
        );
    }

    public function show(Assessment $assessment, AssessmentAttempt $attempt)
    {
        return new AssessmentAttemptCompletedResource($attempt);
    }
}
