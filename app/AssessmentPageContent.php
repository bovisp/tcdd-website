<?php

namespace App;

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
}
