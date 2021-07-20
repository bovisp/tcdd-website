<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\DuplicateAssessment;
use App\Http\Resources\Assessments\AssessmentResource;
use App\Http\Resources\Assessments\AssessmentShowResource;

class DuplicateAssessmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function store(Assessment $assessment)
    {
        $newAssessment = $assessment->replicate();

        $newAssessment->name = [
            'en' => $assessment->getTranslation('name', 'en') . ' (Copy)',
            'fr' => $assessment->getTranslation('name', 'en') . ' (Copy)'
        ];

        $newAssessment->save();

        $newAssessment->editors()->attach(auth()->user());

        return new AssessmentShowResource($newAssessment);
        // return new AssessmentResource(
        //     (new DuplicateAssessment($assessment))->create()
        // );
    }
}
