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
        'assessment_page_content_id',
        'marker_id',
        'description'
    ];

    protected $casts = [
        'marked_on' => 'date'
    ];

    public function assessmentPageContentItem()
    {
        return $this->belongsTo(AssessmentPageContentItem::class, 'assessment_page_content_id');
    }

    public function assessmentAttempt()
    {
        $this->belongsTo(AssessmentAttempt::class);
    }
}
