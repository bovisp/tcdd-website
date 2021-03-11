<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentAttempt;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentAnswersResource;

class AssessmentAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function index (Assessment $assessment)
    {
        return AssessmentAnswersResource::collection(
            $assessment->attempts->where('completed', '=', 1)
        );
    }

    public function show (Assessment $assessment, AssessmentAttempt $attempt)
    {
        return new AssessmentAnswersResource($attempt);
    }
}
