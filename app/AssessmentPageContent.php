<?php

namespace App;

use App\AssessmentPage;
use App\AssessmentPageContentItem;
use App\AssessmentPageContentType;
use Illuminate\Database\Eloquent\Model;

class AssessmentPageContent extends Model
{
    protected $fillable = [
        'order',
        'assessment_page_id',
    ];

    public function assessmentPage()
    {
        return $this->belongsTo(AssessmentPage::class);
    }

    public function contentBuilder()
    {
        return $this->morphMany(ContentBuilder::class, 'contentable');
    }

    public function assessmentPageContentItems()
    {
        return $this->hasMany(AssessmentPageContentItem::class);
    }
}
