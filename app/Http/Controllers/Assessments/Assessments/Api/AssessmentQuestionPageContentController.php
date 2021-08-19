<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Part;
use App\Assessment;
use App\AssessmentPage;
use App\ContentBuilder;
use App\ContentBuilderType;
use App\AssessmentPageContent;
use App\AssessmentPageContentItem;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\RenumberAssessmentQuestions;
use App\Http\Resources\Assessments\AssessmentPageResource;

class AssessmentQuestionPageContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);

        $this->middleware(function ($request, $next) {
            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->locked) {
                return response()->json([
                    'data' => [
                        'message' => __('app_http_controllers_assessments_assessments_api_assessmentquestioncontent.cannotlocked')
                    ]
                ], 403);
            }

            return $next($request);
        })->only(['addQuestion']);
    }

    public function addQuestion(Assessment $assessment, AssessmentPage $page)
    {
        // Get any existing page content to determine the order placing of the new content.
        $content = AssessmentPageContent::whereAssessmentPageId($page->id)
            ->get()
            ->sortBy('order');

        // If there is no current page content, the order is one.
        // Else, the order is the value of the highest order number plus one.
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

        $assessmentPageContentItem = AssessmentPageContentItem::create([
            'type' => 'Question',
            'model_id' => request('questionId'),
            'assessment_page_content_id' => $assessmentPageContent->id,
            'question_number' => 999,
            'question_score' => (int) request('score')
        ]);

        (new RenumberAssessmentQuestions($assessment))->renumber();
    }

    public function addContent(Assessment $assessment, AssessmentPage $page)
    {
        // Get any existing page content to determine the order placing of the new content.
        $content = AssessmentPageContent::whereAssessmentPageId($page->id)
            ->get()
            ->sortBy('order');

        // If there is no current page content, the order is one.
        // Else, the order is the value of the highest order number plus one.
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
    
    public function reorder(Assessment $assessment, AssessmentPage $page)
    {   
        $moved = AssessmentPageContent::find(request('moved'));

        if ($assessment->locked && $moved->assessmentPageContentItems->first()->type === 'Question') {
            return response()->json([
                'data' => [
                    'message' => __('app_http_controllers_assessments_assessments_api_assessmentquestioncontent.cannotlocked')
                ]
            ], 403);
        }

        $replacement = AssessmentPageContent::where('assessment_page_id', '=', $page->id)
            ->where('order', '=', request('newOrderNumber'))
            ->first();

        $moved->update([
            'order' => request('newOrderNumber')
        ]);

        $replacement->update([
            'order' => request('oldOrderNumber')
        ]);
        
        (new RenumberAssessmentQuestions($assessment))->renumber();
    }

    public function destroyTempItem (Assessment $assessment, AssessmentPage $page)
    {
        if (request('type') === 'content') {
            ContentBuilder::find([
                request('data')['contentBuilderItemEn']['content']['id'],
                request('data')['contentBuilderItemFr']['content']['id']
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
                request('data')['contentBuilderItemEn']['model']['id'],
                request('data')['contentBuilderItemFr']['model']['id']
            ])->each->delete();
        }
        
        AssessmentPageContent::find(request('data')['assessmentPageContent']['id'])->delete();
    }

    public function destroy(Assessment $assessment, AssessmentPageContent $content)
    {
        if ($assessment->locked && $content->assessmentPageContentItems->first()->type === 'Question') {
            return response()->json([
                'data' => [
                    'message' => __('app_http_controllers_assessments_assessments_api_assessmentquestioncontent.cannotlocked')
                ]
            ], 403);
        }

        $contentItems = $content->assessmentPageContentItems->each(function($item) {
            if ($item->type === 'ContentBuilder') {
                $contentBuilder = ContentBuilder::find($item->model_id);

                $contentBuilder->parts->each(function (Part $part) {
                    $type = ContentBuilderType::find($part->content_builder_type_id)->type;

                    $typeClassName = 'App\\' . ucfirst($type) . 'Part';

                    $partType = $typeClassName::wherePartId($part->id)->first();

                    $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($type);

                    (new $destroyClassName($partType))->delete();

                    $part->delete();
                });

                $contentBuilder->delete();
            }
        });

        $contentItems->each->delete();

        $content->delete();

        (new RenumberAssessmentQuestions($assessment))->renumber();
    }
}
