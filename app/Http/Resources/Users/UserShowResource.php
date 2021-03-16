<?php

namespace App\Http\Resources\Users;

use App\AssessmentAttempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $assessmentParticipantIds = DB::table('assessment_participants')
            ->where('participant_id', '=', auth()->id())
            ->get()
            ->pluck('id')
            ->toArray();

        $markedAssessments = AssessmentAttempt::whereAssessmentParticipantId($assessmentParticipantIds)
            ->wherePublished(1)
            ->get();

        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fullname' => $this->firstname . ' ' . $this->lastname,
            'email' => $this->email,
            'role' => $this->roles->where('name', '!=', 'administrator')->first()->name,
            'roleRank' => $this->roles->where('name', '!=', 'administrator')->first()->rank,
            'reportingStructure' => $this->reportingStructure(),
            'assessments' => $this->assessmentParticipant,
            'markedAssessments' => $markedAssessments->map(function (AssessmentAttempt $attempt) {
                $totalScore = $attempt->assessment->questions()->map(function ($question) {
                    return $question['assessment_content']->assessmentPageContentItems[0]->question_score;
                })->reduce(function ($carry, $item) {
                    return $carry + $item;
                });

                $participantScore = $attempt->assessmentMarks->pluck('mark')->reduce(function ($carry, $item) {
                    return $carry + $item;
                });
                
                return [
                    'total_score' => $totalScore,
                    'participant_score' => $participantScore,
                    'percentage' => round((($participantScore / $totalScore) * 100), 1),
                    'section' => $attempt->assessment->section->name,
                    'type' => $attempt->assessment->assessmentType->name,
                    'name' => $attempt->assessment->name,
                    'marked' => $attempt->publish_date,
                    'id' => $attempt->id
                ];
            })
        ];
    }
}
