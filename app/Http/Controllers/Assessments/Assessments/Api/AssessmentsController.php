<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\ContentBuilder;
use App\ContentBuilderType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentResource;

class AssessmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->only(['index', 'store']);

        $this->middleware(function ($request, $next) {
            if (auth()->user()->hasRole('administrator')) {
                return $next($request);
            }

            preg_match_all("/\/assessments\/([\d]+)/",request()->url(),$matches);

            $assessment = Assessment::find((int) $matches[1][0]);

            if ($assessment->editors->contains('id', auth()->id())) {
                return $next($request);
            }
            
            abort(403);
        })->only(['update', 'destroy']);
    }

    public function index()
    {
        if (auth()->user()->hasRole('administrator')) {
            return AssessmentResource::collection(
                Assessment::all()
            );
        }
        
        return AssessmentResource::collection(
            auth()->user()->assessmentEditor
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'section_id' => 'required|exists:sections,id',
            'completion_time' => 'nullable|integer|min:1'
        ]);

        $assessment = Assessment::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ],
            'assessment_type_id' => request('assessment_type_id'),
            'section_id' => request('section_id'),
            'completion_time' => request('completion_time')
        ]);

        $assessment->editors()->attach(auth()->id());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Assessment successfully created.'
            ]
        ], 200);
    }

    public function update(Assessment $assessment)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3',
            'assessment_type_id' => 'required|exists:assessment_types,id',
            'section_id' => 'required|exists:sections,id',
            'completion_time' => 'nullable|integer|min:1'
        ]);

        $assessment->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ],
            'assessment_type_id' => request('assessment_type_id'),
            'section_id' => request('section_id'),
            'completion_time' => request('completion_time')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Assessment successfully updated.'
            ]
        ], 200);
    }

    public function destroy(Assessment $assessment)
    {
        DB::table('assessment_participants')->where('assessment_id', '=', $assessment->id)->delete();

        DB::table('assessment_editors')->where('assessment_id', '=', $assessment->id)->delete();

        $assessment->attempts->each->delete();

        foreach($assessment->pages as $page) {
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
        }

        $assessment->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Assessment successfully deleted'
            ]
        ], 200);
    }
}
