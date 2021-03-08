<?php

namespace App;

use App\AssessmentMark;
use App\AssessmentPageContent;
use Illuminate\Database\Eloquent\Model;

class AssessmentPageContentItem extends Model
{
    protected $fillable = [
        'itemable_id',
        'model_id',
        'type',
        'question_number',
        'question_score',
        'assessment_page_content_id'
    ];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function assessmentPageContent()
    {
        return $this->belongsTo(AssessmentPageContent::class);
    }

    public function assessmentMarks()
    {
        return $this->hasMany(AssessmentMark::class);
    }
}
