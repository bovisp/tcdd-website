<?php

namespace App\Http\Controllers\Questions\Questions\Api;

use App\Tag;
use App\Part;
use App\User;
use App\Question;
use App\QuestionType;
use App\ContentBuilder;
use App\ContentBuilderType;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\Questions\QuestionIndexResource;
use App\Http\Resources\Questions\QuestionTypeDataResource;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:manage assessments'])->except(['destroy']);
    }

    public function index()
    {
        if (auth()->user()->hasAnyRole(['administrator', 'director', 'manager'])) {
            return QuestionIndexResource::collection(
                Question::where('name', '!=', null)->get()
            );
        }

        $editorForQuestions = auth()->user()->questionEditor->pluck('id')->toArray();

        $authorForQuestions = auth()->user()->questions->pluck('id')->toArray();

        return QuestionIndexResource::collection(
            Question::whereIn('id', array_merge($editorForQuestions, $authorForQuestions))
                ->where('name', '!=', null)
                ->get()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3|max:60',
            'name_fr' => 'required|min:3|max:60',
            'score' => 'required|integer|min:1',
            'section_id' => 'required|integer|exists:sections,id',
            'question_category_id' => 'required|integer|exists:question_categories,id',
            'tags' => 'array|present',
            'tags.*' => 'integer|exists:tags,id',
            'question_type_id' => 'required|integer|exists:question_types,id'
        ]);

        $questionTypeClass = 'App\\Classes\\QuestionTypes\\' . Str::studly(QuestionType::find(request('question_type_id'))->code) . 'Question';

        $questionDataValidator = (new $questionTypeClass)->store(
            request('question_type_data'), request('id')
        );

        if ($questionDataValidator['passes'] === false) {
            return response()->json([
                'errors' => $questionDataValidator['errors']
            ], 422);
        }

        $question = Question::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'marking_guide' => [
                'en' => request('marking_guide_en'),
                'fr' => request('marking_guide_fr')
            ],
            'author_id' => auth()->id(),
            'score' => request('score'),
            'section_id' => request('section_id'),
            'question_category_id' => request('question_category_id'),
            'question_type_id' => request('question_type_id'),
            'question_type_model_id' => $questionDataValidator['model']->id
        ]);

        $questionDataValidator['model']->update(['question_id' => $question->id]);

        $tempQuestion = Question::find(request('id'));

        $tempQuestion->contentBuilder->each->update([
            'contentable_id' => $question->id
        ]);

        $tempQuestion->delete();

        $question->tags()->attach(Tag::whereIn('id', request('tags'))->get());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully added.'
            ]
        ], 200);
    }

    public function id() 
    {
        $question = Question::create();

        $contentBuilderEn = $question->contentBuilder()->create([
            'language' => 'en'
        ]);

        $contentBuilderFr = $question->contentBuilder()->create([
            'language' => 'fr'
        ]);

        return [
            'questionId' => $question->id,
            'contentBuilderId' => [
                'en' => $contentBuilderEn->id,
                'fr' => $contentBuilderFr->id
            ]
        ];
    }

    public function questionTypeData(Question $question)
    {
        return new QuestionTypeDataResource($question);
    }

    public function update(Question $question)
    {
        request()->validate([
            'name_en' => 'required|min:3|max:60',
            'name_fr' => 'required|min:3|max:60',
            'score' => 'required|integer|min:1',
            'section_id' => 'required|integer|exists:sections,id',
            'question_category_id' => 'required|integer|exists:question_categories,id',
            'tags' => 'array|present',
            'tags.*' => 'integer|exists:tags,id',
            'editors' => 'array|present',
            'editors.*' => 'integer|exists:users,id'
        ]);

        $questionTypeClass = 'App\\Classes\\QuestionTypes\\' . Str::studly($question->questionType->code) . 'Question';

        $questionDataValidator = (new $questionTypeClass)->update(
            request('question_type_data'), $question->id
        );

        if ($questionDataValidator['passes'] === false) {
            return response()->json([
                'errors' => $questionDataValidator['errors']
            ], 422);
        }

        $question->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'marking_guide' => [
                'en' => request('marking_guide_en'),
                'fr' => request('marking_guide_fr')
            ],
            'score' => request('score'),
            'section_id' => request('section_id'),
            'question_category_id' => request('question_category_id')
        ]);

        $question->tags()->sync(Tag::whereIn('id', request('tags'))->get());

        $question->editors()->sync(User::whereIn('id', request('editors'))->get());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully updated'
            ]
        ], 200);
    }

    public function destroy(Question $question)
    {
        $question->contentBuilder
            ->each(function (ContentBuilder $contentBuilder) {
                $contentBuilder->parts->each(function (Part $part) {
                    $type = ContentBuilderType::find($part->content_builder_type_id)->type;

                    $typeClassName = 'App\\' . ucfirst($type) . 'Part';

                    $partType = $typeClassName::wherePartId($part->id)->first();

                    $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($type);

                    (new $destroyClassName($partType))->delete();

                    $part->delete();
                });
            });

        $question->tags()->detach();

        $question->editors()->detach();

        $question->contentBuilder->each->delete();

        if ($question->questionType !== null) {
            $questionTypeClass = 'App\\Classes\\QuestionTypes\\' . Str::studly($question->questionType->code) . 'Question';

            (new $questionTypeClass)->destroy($question->id);
        }

        $question->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Question successfully deleted'
            ]
        ], 200);
    }
}
