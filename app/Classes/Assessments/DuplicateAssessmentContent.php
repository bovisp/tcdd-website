<?php

namespace App\Classes\Assessments;

use App\Part;
use App\Assessment;
use App\AssessmentPage;
use App\ContentBuilder;
use Illuminate\Support\Str;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;

class DuplicateAssessmentContent
{
    protected $newAssessment;

    protected $previousAssessment;

    public function __construct(Assessment $newAssessment, Assessment $previousAssessment)
    {
        $this->previousAssessment = $previousAssessment;

        $this->newAssessment = $newAssessment;
    }

    public function create()
    {
        $this->addPages();
    }

    protected function addPages()
    {
        foreach ($this->previousAssessment->pages as $page) {
            $assessmentPage = AssessmentPage::create([
                'assessment_id' => $this->newAssessment->id,
                'number' => $page->number
            ]);

            $this->addPageContents($assessmentPage, $page);
        }
    }

    protected function addPageContents(AssessmentPage $page, AssessmentPage $previousPage)
    {
        foreach ($previousPage->assessmentPageContents as $content) {
            $assessmentPageContent = AssessmentPageContent::create([
                'assessment_page_id' => $page->id,
                'order' => $content->order
            ]);

            $this->addPageContentItems($assessmentPageContent, $content);
        }
    }

    protected function addPageContentItems(AssessmentPageContent $content, AssessmentPageContent $previousContent)
    {
        $contentBuilderArr = [];

        foreach ($previousContent->assessmentPageContentItems as $item) {
            if ($item->type === 'ContentBuilder') {
                $builder = $this->duplicateContentBuilder($content, $previousContent, $item);

                $contentBuilderArr[$builder['lang']] = $builder['model'];
            } else {
                $this->duplicateQuestion($content, $item);
            }
        }

        if (count($contentBuilderArr)) {
            $this->duplicateContentBuilderItems($previousContent, $contentBuilderArr);
        }
    }

    protected function duplicateContentBuilder
    (
        AssessmentPageContent $content, AssessmentPageContent $previousContent, AssessmentPageContentItem $previousItem
    )
    {
        $previousContentBuilder = ContentBuilder::find($previousItem->model_id);

        $contentBuilderLanguage = $previousContentBuilder->language;

        $contentBuilder = $content->contentBuilder()->create([
            'language' => $contentBuilderLanguage
        ]);

        $item = AssessmentPageContentItem::create([
            'type' => 'ContentBuilder',
            'model_id' => $contentBuilder->id,
            'assessment_page_content_id' => $content->id
        ]);

        return [
            'lang' => $contentBuilderLanguage,
            'model' => $contentBuilder
        ];
    }

    protected function duplicateContentBuilderItems(AssessmentPageContent $previousContent, $contentBuilderArr)
    {
        $contentBuilderEn = $contentBuilderArr['en'];
        $contentBuilderFr = $contentBuilderArr['fr'];
        
        $previousContent->contentBuilder->each(function ($builder) use ($contentBuilderEn, $contentBuilderFr) {
            $builder->parts->each(function (Part $part) use ($contentBuilderEn, $contentBuilderFr) {
                $partType = $part->contentBuilderType->type;

                $duplicatePartClass = 'App\\Classes\\ContentTypes\\Duplicate' . Str::studly($partType);

                (new $duplicatePartClass(
                    $contentBuilderEn, $contentBuilderFr, $part, $partType === 'tab' ? true : false, null
                ))->duplicate();
            });
        });
    }

    protected function duplicateQuestion(AssessmentPageContent $content, $item)
    {
        AssessmentPageContentItem::create([
            'type' => 'Question',
            'model_id' => $item->model_id,
            'assessment_page_content_id' => $content->id,
            'question_score' => $item->question_score,
            'question_number' => $item->question_number
        ]);
    }
}