<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentPageContentItem extends Model
{
    protected $fillable = [
        'itemable_id',
        'assessment_page_content_id'
    ];

    public function itemable()
    {
        return $this->morphTo();
    }
}
