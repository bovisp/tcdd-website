<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Assessment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentResource;

class AssessmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments']);
    }

    public function index()
    {
        return AssessmentResource::collection(
            Assessment::all()
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
            'visible' => 'required|boolean',
            'total_score' => 'required|integer|min:0'
        ]);

        Assessment::create([
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
            'visible' => request('visible'),
            'total_score' => request('total_score')
        ]);

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
            'visible' => 'required|boolean',
            'total_score' => 'required|integer|min:0'
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
            'visible' => request('visible'),
            'total_score' => request('total_score')
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
        $assessment->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Assessment successfully deleted'
            ]
        ], 200);
    }
}
