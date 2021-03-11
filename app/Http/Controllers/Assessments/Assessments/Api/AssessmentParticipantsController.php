<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\User;
use Carbon\Carbon;
use App\Assessment;
use App\AssessmentAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\AssessmentAttemptsMarked;

class AssessmentParticipantsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function update(Assessment $assessment)
    {
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id',
        ]);

        $assessmentParticipantIds = $assessment->participants->pluck('pivot.participant_id');

        $participantsNotInRequest = array_diff($assessmentParticipantIds->toArray(), request('users'));

        foreach ($participantsNotInRequest as $user) {
            $assessmentParticipantId = $assessment->participants->map(function ($participant) use ($user) {
                if ($participant->pivot->participant_id === $user) {
                    return $participant->pivot->id;
                }
            })->filter()->first();

            if (AssessmentAttempt::whereAssessmentParticipantId($assessmentParticipantId)->count()) {
                return response()->json([
                    'data' => [
                        'message' => 'You cannot remove participants who have already attempted this exam.'
                    ]
                ], 403);
            }
        }

        $assessment->participants()->sync(request('users'));

        $numParticipants = $assessment->participants()->count();

        $totalScoredAnswers = 0;

        foreach ($assessment->attempts->filter->completed as $attempt) {
            $totalScoredAnswers += $attempt->assessmentMarks->filter(function ($mark) {
                return !is_null($mark->mark);
            })->count();
        }

        if (($numParticipants * $assessment->questions()->count()) === $totalScoredAnswers) {
            $assessment->update([
                'marking_completed' => 1,
                'marking_completed_on' => Carbon::now()
            ]);

            event(new AssessmentAttemptsMarked($assessment->id, $assessment->marking_completed_on));
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Participants updated'
            ]
        ], 200);
    }

    public function create(Assessment $assessment)
    {
        $users = User::all()
            ->filter(function ($user) use ($assessment) {
                return !$assessment->participants->contains('id', $user->id) && 
                    !$assessment->editors->contains('id', $user->id) && 
                    !$user->hasRole('administrator');
            })
            ->toArray();

        return response()->json(array_values($users), 200);
    }

    public function store(Assessment $assessment)
    {
        request()->validate([
            'users' => 'present|nullable|array',
            'users.*' => 'integer|exists:users,id'
        ]);

        $assessment->participants()->attach(request('users'));

        $assessment->update([
            'marking_completed' => 0,
            'marking_completed_on' => null
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Participants successfully added.'
            ]
        ], 200);
    }
}
