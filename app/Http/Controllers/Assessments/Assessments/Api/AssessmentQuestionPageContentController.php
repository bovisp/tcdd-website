<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Part;
use App\AssessmentPage;
use App\ContentBuilder;
use App\ContentBuilderType;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentPageResource;

class AssessmentQuestionPageContentController extends Controller
{
    public function index(AssessmentPage $page)
    {      
        return new AssessmentPageResource($page);
    }

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

        if (request('type') === 'question') {
            $maxQuestionNumber = $page->assessmentPageContents->map(function ($assessmentPageContent) {
                return $assessmentPageContent->assessmentPageContentItems->where('type', '=', 'Question')->map->question_number;
            })->flatten()->max();

            if (!$maxQuestionNumber) {
                $questionNumber = 1;
            } else {
                $questionNumber = $maxQuestionNumber + 1;
            }

            $assessmentPageContentItem = AssessmentPageContentItem::create([
                'type' => 'Question',
                'model_id' => request('question_id'),
                'assessment_page_content_id' => $assessmentPageContent->id,
                'question_number' => $questionNumber,
                'question_score' => request('question_score')
            ]);

            return [
                'assessmentPageContent' => $assessmentPageContent,
                'assessmentPageContentItem' => $assessmentPageContentItem
            ]; 
        }

        if (request('type') === 'content') {
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

    public function reorder(AssessmentPage $page)
    {      
        foreach(request('data') as $part) {
            AssessmentPageContent::find($part['id'])->update([
                'order' => $part['order']
            ]);
        }

        return new AssessmentPageResource($page);
    }

    public function destroy (AssessmentPage $page)
    {
        if (request('type') === 'content') {
            ContentBuilder::find([
                request('data')['data']['contentBuilderItemEn']['content']['id'],
                request('data')['data']['contentBuilderItemFr']['content']['id']
            ])
            ->each(function (ContentBuilder $contentBuilder) {
                $contentBuilder->parts->each(function (Part $part) {
                    $type = ContentBuilderType::find($part->content_builder_type_id)->type;

                    $typeClassName = 'App\\' . ucfirst($type) . 'Part';

                    $partType = $typeClassName::wherePartId($part->id)->first();

                    $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($type);

                    (new $destroyClassName($partType))->delete();

                    $part->delete();
                });

                $contentBuilder->delete();
            });

            AssessmentPageContentItem::find([
                request('data')['data']['contentBuilderItemEn']['model']['id'],
                request('data')['data']['contentBuilderItemFr']['model']['id']
            ])->each->delete();
        }
        
        AssessmentPageContent::find(request('data')['data']['assessmentPageContent']['id'])->delete();
    }
}
