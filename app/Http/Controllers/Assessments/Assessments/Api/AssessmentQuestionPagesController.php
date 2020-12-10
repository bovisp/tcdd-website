<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentPage;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentPageResource;

class AssessmentQuestionPagesController extends Controller
{
    public function index(Assessment $assessment) {
        return AssessmentPageResource::collection($assessment->pages);
    }

    public function store(Assessment $assessment)
    {
        $assessmentPage = AssessmentPage::create([
            'assessment_id' => $assessment->id,
            'number' => $assessment->pages->count() + 1
        ]);

        return new AssessmentPageResource($assessmentPage);
    }
}
