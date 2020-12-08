<?php

namespace App;

use App\Assessment;
use App\AssessmentPageContent;
use Illuminate\Database\Eloquent\Model;

class AssessmentPage extends Model
{
    protected $fillable = [
        'assessment_id',
        'number'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function assessmentPageContents()
    {
        return $this->hasMany(AssessmentPageContent::class)
            ->orderBy('order', 'asc');
    }
}
