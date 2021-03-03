<?php

namespace App;

use App\User;
use App\Assessment;
use App\AssessmentMark;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $fillable = [
        'assessment_participant_id',
        'answers',
        'assessment_id',
        'completed',
        'marked',
        'marked_on'
    ];

    public function participant()
    {
        $assessmentParticipant = $this->getAssessmentParticipant();

        $participant = Assessment::find($assessmentParticipant->assessment_id)
            ->participants
            ->filter(function ($participant) use ($assessmentParticipant) {
                return $participant->pivot->assessment_id === $assessmentParticipant->assessment_id && 
                       $participant->pivot->participant_id === $assessmentParticipant->participant_id;
            })
            ->first();
        
        return $participant;
    }

    protected function getAssessmentParticipant()
    {
        return DB::table('assessment_participants')
            ->where('id', $this->assessment_participant_id)
            ->get()
            ->first();
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function assessmentMarks()
    {
        return $this->hasMany(AssessmentMark::class);
    }
}
