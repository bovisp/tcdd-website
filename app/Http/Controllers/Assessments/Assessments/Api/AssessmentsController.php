<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\ContentBuilder;
use App\ContentBuilderType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Classes\Assessments\DestroyAssessment;
use App\Http\Resources\Assessments\AssessmentShowResource;
use App\Http\Resources\Assessments\AssessmentIndexResource;

class AssessmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->only(['index', 'store']);

        $this->middleware(['assessment-edit'])->only(['update', 'destroy', 'show']);
    }

    public function index()
    {
        if (auth()->user()->hasRole('administrator')) {
            return AssessmentIndexResource::collection(
                Assessment::all()
            );
        }
        
        return AssessmentIndexResource::collection(
            auth()->user()->assessmentEditor
        );
    }

    public function show(Assessment $assessment)
    {
        return new AssessmentShowResource($assessment);
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
                'message' => __('app_http_controllers_assessments_assessments_api_assessments.store_message')
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
                'message' => __('app_http_controllers_assessments_assessments_api_assessments.update_message'),
                'assessment' => new AssessmentShowResource($assessment)
            ]
        ], 200);
    }

    public function destroy(Assessment $assessment)
    {
        (new DestroyAssessment($assessment))->destroy();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_assessments_assessments_api_assessments.destroy_message')
            ]
        ], 200);
    }
}
