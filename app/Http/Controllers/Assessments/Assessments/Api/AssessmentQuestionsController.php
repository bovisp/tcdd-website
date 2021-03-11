<?php

namespace App\Http\Controllers\Assessments\Assessments\Api;

use App\Question;
use App\Assessment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questions\QuestionIndexResource;

class AssessmentQuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['assessment-edit']);
    }

    public function index(Assessment $assessment)
    {
        $questionsInAssesment = $assessment->pages->map(function ($page) {
            return $page->assessmentPageContents->map(function ($assessmentPageContent) {
                return $assessmentPageContent->assessmentPageContentItems->where('type', '=', 'Question')->map(function ($item) use ($assessmentPageContent) {
                    return $item->model_id;
                });
            });
        })
        ->flatten(2)
        ->toArray();
        
        if (auth()->user()->hasAnyRole(['administrator', 'director', 'manager'])) {
            return QuestionIndexResource::collection(
                Question::with(
                    'author',
                    'contentBuilder',
                    'section',
                    'questionCategory',
                    'tags',
                    'owner',
                    'editors',
                    'questionType'
                )
                ->where('name', '!=', null)
                ->whereNotIn('id', $questionsInAssesment)
                ->get()
            );
        }

        $editorForQuestions = auth()->user()->questionEditor->pluck('id')->toArray();

        $authorForQuestions = auth()->user()->questions->pluck('id')->toArray();

        return QuestionIndexResource::collection(
            Question::with(
                'author',
                'contentBuilder',
                'section',
                'questionCategory',
                'tags',
                'owner',
                'editors',
                'questionType'
            )   
                ->whereIn('id', array_merge($editorForQuestions, $authorForQuestions))
                ->where('name', '!=', null)
                ->whereNotIn('id', $questionsInAssesment)
                ->get()
                ->each(function ($question) {
                    $question->append('question_data');
                })
        );
    }
}
