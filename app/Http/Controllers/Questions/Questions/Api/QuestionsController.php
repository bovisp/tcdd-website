<?php

namespace App\Http\Controllers\Questions\Questions\Api;

use App\Question;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questions\QuestionIndexResource;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->except(['destroy']);
    }

    public function index()
    {
        return QuestionIndexResource::collection(
            Question::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3',
            'score' => 'required|integer|min:1',
            'section_id' => 'required|integer|exists:sections,id',
            'question_category_id' => 'required|integer|exists:question_categories,id',
        ]);

        Question::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ],
            'author_id' => auth()->id(),
            'score' => request('score'),
            'section_id' => request('section_id'),
            'question_category_id' => request('question_category_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully added.'
            ]
        ], 200);
    }

    public function update(Question $question)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'description_en' => 'required|min:3',
            'description_fr' => 'required|min:3',
            'score' => 'required|integer|min:1',
            'section_id' => 'required|integer|exists:sections,id',
            'question_category_id' => 'required|integer|exists:question_categories,id',
        ]);

        $question->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'description' => [
                'en' => request('description_en'),
                'fr' => request('description_fr')
            ],
            'score' => request('score'),
            'section_id' => request('section_id'),
            'question_category_id' => request('question_category_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully updated'
            ]
        ], 200);
    }

    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully deleted'
            ]
        ], 200);
    }
}
