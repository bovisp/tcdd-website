<?php

namespace App\Classes\Assessments;

use App\Assessment;

class DuplicateAssessment
{
    protected $assessment;

    protected $previousAssessment;

    public function __construct(Assessment $previousAssessment)
    {
        $this->previousAssessment = $previousAssessment;
    }

    public function create()
    {
        $this->assessment = $this->persist();

        $this->assessment->editors()->sync(
            $this->previousAssessment->editors->pluck('id')
        );

        return $this->assessment;
    }

    protected function persist()
    {
        return Assessment::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ],
            'assessment_type_id' => request('assessment_type_id'),
            'section_id' => request('section_id'),
            'completion_time' => request('completion_time')
        ]);
    }
}