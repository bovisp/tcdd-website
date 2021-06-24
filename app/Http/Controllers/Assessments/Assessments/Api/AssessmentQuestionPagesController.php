<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\AssessmentPage;
use App\ContentBuilder;
use App\ContentBuilderType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentPageResource;

class AssessmentQuestionPagesController extends Controller
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
        })->except(['index']);
    }

    public function index(Assessment $assessment) {
        return AssessmentPageResource::collection($assessment->pages);
    }

    public function store(Assessment $assessment)
    {
        $assessmentPage = AssessmentPage::create([
            'assessment_id' => $assessment->id,
            'number' => $assessment->pages->count() + 1
        ]);

        return new AssessmentPageResource($assessmentPage);
    }

    public function update(Assessment $assessment)
    {
        $moved = AssessmentPage::whereAssessmentId($assessment->id)
            ->whereNumber((int) request('oldPageNumber'))
            ->first();

        $replaced = AssessmentPage::whereAssessmentId($assessment->id)
            ->whereNumber((int) request('newPageNumber'))
            ->first();

        $moved->update([
            'number' => (int) request('newPageNumber')
        ]);

        $replaced->update([
            'number' => (int) request('oldPageNumber')
        ]);
    }

    public function destroy(Assessment $assessment, AssessmentPage $page)
    {
        $assessmentPageContents = $page->assessmentPageContents;

        $assessmentPageContents->each(function ($contentItem) {
            $contentItem->assessmentPageContentItems->each(function ($item) {
                if ($item->type === 'ContentBuilder') {
                    $contentBuilder = ContentBuilder::find($item->model_id);
    
                    $contentBuilder->parts->each(function ($part) {
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
        });

        $assessmentPageContents->each(function ($content) {
            $content->assessmentPageContentItems->each->delete();
        });

        $assessmentPageContents->each->delete();

        $page->delete();

        $assessmentPages = AssessmentPage::whereAssessmentId($page->assessment_id)->orderBy('number')->get();

        $number = 1;

        foreach ($assessmentPages as $page) {
            $page->update([
                'number' => $number
            ]);

            $number += 1;
        }

        return;
    }
}
