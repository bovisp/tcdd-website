<?php

namespace App\Http\Controllers\Questions\Questions\Api;

use App\Part;
use App\Question;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DuplicateQuestionController extends Controller
{
    public function store(Question $question)
    {
        $newQuestion = Question::create([
            'author_id' => auth()->id(),
            'question_type_id' => $question->questionType->id
        ]);

        $newQuestion->editors()->sync($question->editors->pluck('id'));

        $duplicateQuestionTypeClass = 'App\\Classes\\QuestionTypes\\Duplicate' . ucfirst($question->questionType->code) . 'Question';

        $duplicateQuestionData = (new $duplicateQuestionTypeClass($question, $newQuestion))->duplicate();

        $contentBuilderEn = $newQuestion->contentBuilder()->create([
            'language' => 'en'
        ]);

        $contentBuilderFr = $newQuestion->contentBuilder()->create([
            'language' => 'fr'
        ]);

        $question->contentBuilder->each(function ($builder) use ($contentBuilderEn, $contentBuilderFr) {
            $builder->parts->each(function (Part $part) use ($contentBuilderEn, $contentBuilderFr) {
                $partType = $part->contentBuilderType->type;

                $duplicatePartClass = 'App\\Classes\\ContentTypes\\Duplicate' . ucfirst($partType);

                (new $duplicatePartClass(
                    $contentBuilderEn, $contentBuilderFr, $part, $partType === 'tab' ? true : false, null
                ))->duplicate();
            });
        });

        return [
            'contentBuilder' => [
                'en' => $contentBuilderEn->id,
                'fr' => $contentBuilderFr->id
            ],
            'questionId' => $newQuestion->id,
            'questionData' => $duplicateQuestionData
        ];
    }
}
