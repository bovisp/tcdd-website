<?php

namespace App\Classes\Assessments;

use App\Assessment;

class DestroyAssessment
{
    protected $assessment;

    public function __construct(Assessment $assessment)
    {
        $this->assessment = $assessment;
    }

    public function destroy()
    {
        $this->assessment->delete();

        $this->removeParticipantsAndInstructors();
    }

    protected function removeParticipantsAndInstructors()
    {
        $this->assessment->editors()->detach();

        $this->assessment->participants()->detach();
    }
}