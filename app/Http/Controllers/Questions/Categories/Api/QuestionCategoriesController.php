<?php

namespace App\Http\Controllers\Questions\Categories\Api;

use App\QuestionCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questions\QuestionCategoryResource;

class QuestionCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->only(['index', 'store', 'update']);

        $this->middleware(['role:administrator'])->only(['destroy']);
    }

    public function index()
    {
        return QuestionCategoryResource::collection(
            QuestionCategory::all()
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

        QuestionCategory::create([
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
                'message' => __('app_http_controllers_questions_categories_api_questioncategories.store_message')
            ]
        ], 200);
    }

    public function update(QuestionCategory $category)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3'
        ]);

        $category->update([
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
                'message' => __('app_http_controllers_questions_categories_api_questioncategories.update_message')
            ]
        ], 200);
    }

    public function destroy(QuestionCategory $category)
    {
        $category->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_questions_categories_api_questioncategories.destroy_message')
            ]
        ], 200);
    }
}
