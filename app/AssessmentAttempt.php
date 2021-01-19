<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $fillable = [
        'participant_id',
        'assessment_id'
    ];
}
