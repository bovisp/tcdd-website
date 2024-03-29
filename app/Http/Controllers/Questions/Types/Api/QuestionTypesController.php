<?php

namespace App\Http\Controllers\Questions\Types\Api;

use App\QuestionType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questions\QuestionTypeResource;

class QuestionTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return QuestionTypeResource::collection(
            QuestionType::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3'
        ]);

        QuestionType::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_questions_types_api_questiontypes.store_message')
            ]
        ], 200);
    }

    public function update(QuestionType $type)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3'
        ]);

        $type->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ]
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_questions_types_api_questiontypes.update_message')
            ]
        ], 200);
    }
}
