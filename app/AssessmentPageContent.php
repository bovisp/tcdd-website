<?php

namespace App;

use App\AssessmentPage;
use App\AssessmentPageContentType;
use Illuminate\Database\Eloquent\Model;

class AssessmentPageContent extends Model
{
    protected $fillable = [
        'assessment_page_content_type_id',
        'content_model_id',
        'order',
        'page_id',
        'question_number',
        'question_score'
    ];

    public function assessmentPageContentType()
    {
        return $this->belongsTo(AssessmentPageContentType::class);
    }

    public function assessmentPage()
    {
        return $this->belongsTo(AssessmentPage::class);
    }
}
