<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AssessmentParticipantsActivationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }
    
    public function update(Assessment $assessment)
    {
        if (optional(AssessmentAttempt::whereAssessmentParticipantId((int) request()->query('id')))->first()) {
            return response()->json([
                'data' => [
                    'message' => 'You cannot deactivate a participant who has started their attempt.'
                ]
            ], 403);
        }

        $participant = DB::table('assessment_participants')
            ->where('id', (int) request()->query('id'))->update([
                'activated' => (int) request()->query('activated') ? 0 : 1,
            ]);

        return DB::table('assessment_participants')
            ->where('assessment_id', $assessment->id)
            ->where('activated', 1)
            ->count();
    }
}
