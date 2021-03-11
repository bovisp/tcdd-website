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
        $this->middleware(['assessment-edit'])->only(['update', 'destroy']);
    }

    public function store(Assessment $assessment)
    {
        return new AssessmentResource(
            (new DuplicateAssessment($assessment))->create()
        );
    }
}
