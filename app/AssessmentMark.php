<?php

namespace App;

use App\AssessmentAttempt;
use App\AssessmentPageContentItem;
use Illuminate\Database\Eloquent\Model;

class AssessmentMark extends Model
{
    protected $fillable = [
        'assessment_attempt_id',
        'mark',
        'assessment_page_content_id'
    ];

    public function assessmentPageContentItem()
    {
        $this->belongsTo(AssessmentPageContentItem::class);
    }

    public function assessmentAttempt()
    {
        $this->belongsTo(AssessmentAttempt::class);
    }
}
