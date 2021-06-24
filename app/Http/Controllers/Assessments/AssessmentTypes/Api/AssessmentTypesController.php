<?php

namespace App\Http\Controllers\Assessments\AssessmentTypes\Api;

use App\AssessmentType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Assessments\AssessmentTypeResource;

class AssessmentTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return AssessmentTypeResource::collection(
            AssessmentType::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        AssessmentType::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_assessments_assessmentypes_api_assessmenttypes.store_message')
            ]
        ], 200);
    }

    public function update(AssessmentType $assessmentType)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3'
        ]);

        $assessmentType->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_assessments_assessmentypes_api_assessmenttypes.update_message')
            ]
        ], 200);
    }
}
