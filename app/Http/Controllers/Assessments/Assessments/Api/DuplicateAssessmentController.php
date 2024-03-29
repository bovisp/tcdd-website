<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\DuplicateAssessment;
use App\Http\Resources\Assessments\AssessmentResource;
use App\Classes\Assessments\DuplicateAssessmentContent;
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
            'fr' => $assessment->getTranslation('name', 'fr') . ' (Copy)'
        ];

        $newAssessment->description = [
            'en' => $assessment->getTranslation('description', 'en') . ' (Copy)',
            'fr' => $assessment->getTranslation('description', 'fr') . ' (Copy)'
        ];

        $newAssessment->locked = 0;

        $newAssessment->marking_completed = 0;

        $newAssessment->marking_completed_on = null;

        $newAssessment->save();

        $newAssessment->editors()->attach(auth()->user());

        (new DuplicateAssessmentContent($newAssessment, $assessment))->create();

        return new AssessmentShowResource($newAssessment);
    }
}
