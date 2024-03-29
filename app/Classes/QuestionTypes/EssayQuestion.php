<?php

namespace App\Classes\QuestionTypes;

use App\EssayQuestion as Essay;
use Illuminate\Support\Facades\Validator;

class EssayQuestion
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

        $data['rich_text'] = $data['rich_text'] ? 1 : 0;
        
        return [
            'passes' => true,
            'model' => Essay::create(
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

        $data['rich_text'] = $data['rich_text'] ? 1 : 0;
        
        return [
            'passes' => true,
            'model' => Essay::whereQuestionId($questionId)->first()->update(
                array_merge(['question_id' => $questionId], $data)
            )
        ];
    }

    public function destroy($questionId)
    {
        $questionTypeModel = Essay::whereQuestionId($questionId)->first()->delete();

        return;
    }

    protected function validate($data)
    {
        return Validator::make($data, [
            'rich_text' => ['required', 'boolean'],
        ],
        [
            'rich_text.required' => 'The rich text box field is required',
            'rich_text.boolean' => 'The rich text box field is required',
        ]);
    }
}