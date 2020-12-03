<?php

namespace App\Classes\QuestionTypes;

use Illuminate\Support\Arr;
use App\MultipleChoiceQuestionAnswer;
use Illuminate\Support\Facades\Validator;
use App\MultipleChoiceQuestion as MultipleChoice;

class MultipleChoiceQuestion
{
    public function store($data, $tempQuestionId)
    {
        $validation = $this->validate($data);

        if ($validation->fails()) {
            return [
                'passes' => false,
                'errors' => $validation->errors()->toArray()
            ];
        }

        $data['multiple_answers'] = $data['multiple_answers'] ? 1 : 0;
        
        return [
            'passes' => true,
            'model' => MultipleChoice::create(
                array_merge(['question_id' => $tempQuestionId], $data)
            )
        ];
    }

    public function update($data, $questionId)
    {
        $validation = $this->validate($data);

        if ($validation->fails()) {
            return [
                'passes' => false,
                'errors' => $validation->errors()->toArray()
            ];
        }

        $data['multiple_answers'] = $data['multiple_answers'] ? 1 : 0;

        $multipleChoiceQuestionModel = MultipleChoice::find(request('question_type_data')['id']);

        $answerIds = $multipleChoiceQuestionModel->answers->pluck('id')->toArray();

        $UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

        $answersFromRequest = array_filter(request('question_type_data')['answers'], function ($answer) use ($UUIDv4) {
            return !preg_match($UUIDv4, $answer['id']);
        });

        foreach ($answersFromRequest as $answer) {
            MultipleChoiceQuestionAnswer::find($answer['id'])->update([
                'is_correct' => $answer['is_correct'] ? 1 : 0,
                'text' => [
                    'en' => array_key_exists('text_en', $answer) ? $answer['text_en'] : 'No translation added',
                    'fr' => array_key_exists('text_fr', $answer) ? $answer['text_fr'] : 'No translation added'
                ]
            ]);
        }

        $answerIdsFromRequest = Arr::pluck($answersFromRequest, 'id');

        $answersToDelete = array_diff($answerIds, $answerIdsFromRequest);

        MultipleChoiceQuestionAnswer::find($answersToDelete)->each->delete();

        $newAnswers = array_filter(request('question_type_data')['answers'], function ($answer) use ($UUIDv4) {
            return preg_match($UUIDv4, $answer['id']) && (array_key_exists('text_fr', $answer) || array_key_exists('text_en', $answer));
        });

        foreach ($newAnswers as $answer) {
            MultipleChoiceQuestionAnswer::create([
                'multiple_choice_question_id' =>$multipleChoiceQuestionModel->id,
                'is_correct' => $answer['is_correct'] ? 1 : 0,
                'text' => [
                    'en' => array_key_exists('text_en', $answer) && !is_null($answer['text_en']) ? $answer['text_en'] : 'No translation added',
                    'fr' => array_key_exists('text_fr', $answer) && !is_null($answer['text_en']) ? $answer['text_fr'] : 'No translation added'
                ]
            ]);
        }
        
        return [
            'passes' => true,
            'model' => MultipleChoice::whereQuestionId($questionId)->first()->update(
                array_merge(['question_id' => $questionId], $data)
            )
        ];
    }

    public function destroy($questionId)
    {
        $questionTypeModel = MultipleChoice::whereQuestionId($questionId)->first();

        $questionTypeModel->answers->each->delete();

        $questionTypeModel->delete();

        return;
    }

    protected function validate($data)
    {
        return Validator::make($data, [
            'multiple_answers' => ['required', 'boolean'],
            'answers' => [
                'required',
                'array',
                'min:2',
                function ($attribute, $value, $fail) use ($data) {
                    $correctArr = array_filter($data['answers'], function ($answer) {
                        return $answer['is_correct'];
                    });

                    if (count($correctArr) === 0) {
                        $fail('You must supply at leat one correct answer.');
                    }
                },
                function ($attribute, $value, $fail) use ($data) {
                    $noCorrespondingAnswer = array_filter($data['answers'], function ($answer) {
                        return $answer['is_correct'] && !trim($answer['text_en']) && !trim($answer['text_fr']);
                    });

                    if (count($noCorrespondingAnswer)) {
                        $fail('You have chosen a possible answer as correct, but it has no text.');
                    }
                },
                function ($attribute, $value, $fail) use ($data) {
                    $correctArr = array_filter($data['answers'], function ($answer) {
                        return $answer['is_correct'] && (trim($answer['text_en']) || trim($answer['text_fr']));
                    });

                    if (count($correctArr) === count($data['answers']) ) {
                        $fail('You must have more total possible answers that total possible correct answers.');
                    }
                },
            ],
            'answers.*.is_correct' => [
                'boolean'
            ]
        ],
        [
            'multiple_answers.required' => 'The rich multiple answers field is required',
            'multiple_answers.boolean' => 'The rich multiple answers field is required',
            'answers.required' => 'You must supply at least two possible answers',
            'answers.array' => 'The answers you supply must be in the form of an array',
            'answers.min' => 'You must supply at least two possible answers',
            'answers.*.is_correct.boolean' => 'This is not the correct format for a correct answer'
        ]);
    }
}