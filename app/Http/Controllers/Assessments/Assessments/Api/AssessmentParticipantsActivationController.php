<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\AddAssessmentToProfilePage;

class AssessmentParticipantsActivationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }
    
    public function update(Assessment $assessment, User $user)
    {
        if (optional(AssessmentAttempt::whereAssessmentParticipantId((int) request()->query('id')))->first()) {
            return response()->json([
                'data' => [
                    'message' => __('app_http_controllers_assessments_assessments_api_assessmentparticipantsactivation.cannotdeactivate')
                ]
            ], 403);
        }

        $participant = DB::table('assessment_participants')
            ->where('participant_id', $user->id)
            ->get()
            ->first();

        DB::table('assessment_participants')
            ->where('participant_id', $user->id)
            ->update([
                'activated' => $participant->activated ? 0 : 1,
            ]);

        event(new AddAssessmentToProfilePage($participant->participant_id));
    }
}
