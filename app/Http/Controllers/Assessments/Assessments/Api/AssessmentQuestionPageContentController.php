<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\AssessmentPage;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;

class AssessmentQuestionPageContentController extends Controller
{
    public function store(AssessmentPage $page)
    {
        // Get any existing page content to determine the order placing of the new content.
        $content = AssessmentPageContent::whereAssessmentPageId($page->id)
            ->get()
            ->sortBy('order');

        // If there is no current page content, the order is one.
        // Else, the order in the value of the highest order number plus one.
        if ($content->count() === 0) {
            $order = 1;
        } else {
            $order = $content->last()->order + 1;
        }

        // Persist the new content.
        $assessmentPageContent = AssessmentPageContent::create([
            'assessment_page_id' => $page->id,
            'order' => $order
        ]);

        // Persist the English content builder.
        $contentBuilderEn = $assessmentPageContent->contentBuilder()->create([
            'language' => 'en'
        ]);

        // Add $contentBuilderEn as a content item
        $contentBuilderItemEn = AssessmentPageContentItem::create([
            'type' => 'ContentBuilder',
            'model_id' => $contentBuilderEn->id,
            'assessment_page_content_id' => $assessmentPageContent->id
        ]);

        // Persist the French content builder.
        $contentBuilderFr = $assessmentPageContent->contentBuilder()->create([
            'language' => 'fr'
        ]);

        // Add $contentBuilderFr as a content item
        $contentBuilderItemFr = AssessmentPageContentItem::create([
            'type' => 'ContentBuilder',
            'model_id' => $contentBuilderFr->id,
            'assessment_page_content_id' => $assessmentPageContent->id
        ]);

        return [
            'assessmentPageContent' => $assessmentPageContent,
            'contentBuilderItemEn' => [
                'model' => $contentBuilderItemEn,
                'content' => $contentBuilderEn
            ],
            'contentBuilderItemFr' => [
                'model' => $contentBuilderItemFr,
                'content' => $contentBuilderFr
            ]
        ];
    }
}
