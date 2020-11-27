<?php

namespace App\Classes\QuestionTypes;

use App\DrawingQuestion as Drawing;
use Illuminate\Support\Facades\Validator;

class DrawingQuestion
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
        $data['text_answer'] = $data['text_answer'] ? 1 : 0;
        $data['drawing_options']['eraser'] = $data['drawing_options']['eraser'] ? 1 : 0;
        $data['drawing_options']['clear'] = $data['drawing_options']['clear'] ? 1 : 0;

        $data['drawing_options'] = serialize($data['drawing_options']);
        
        return [
            'passes' => true,
            'model' => Drawing::create(
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
        $data['text_answer'] = $data['text_answer'] ? 1 : 0;
        $data['drawing_options']['eraser'] = $data['drawing_options']['eraser'] ? 1 : 0;
        $data['drawing_options']['clear'] = $data['drawing_options']['clear'] ? 1 : 0;

        $data['drawing_options'] = serialize($data['drawing_options']);
        
        return [
            'passes' => true,
            'model' => Drawing::whereQuestionId($questionId)->first()->update(
                array_merge(['question_id' => $questionId], $data)
            )
        ];
    }

    public function destroy($questionId)
    {
        $questionTypeModel = Drawing::whereQuestionId($questionId)->first()->delete();

        return;
    }

    protected function validate($data)
    {
        return Validator::make($data, [
            'rich_text' => 'required|boolean',
            'text_answer' => 'required|boolean',
            'drawing_options' => 'required|array',
            'drawing_options.eraser' => 'required|boolean',
            'drawing_options.clear' => 'required|boolean',
            'drawing_options.pen_colors' => 'required|array',
            'drawing_options.background_image' => 'required'
        ],
        [
            'rich_text.required' => 'The rich text box field is required',
            'rich_text.boolean' => 'The rich text box field is required',
            'text_answer.required' => 'The answer text box field is required',
            'text_answer.boolean' => 'The answer text box field is required',
            'drawing_options.required' => 'The drawing options are required',
            'drawing_options.array' => 'The drawing options must be an array',
            'drawing_options.eraser.required' => 'The eraser field is required',
            'drawing_options.eraser.boolean' => 'The eraser field is required',
            'drawing_options.clear.required' => 'The clear field is required',
            'drawing_options.clear.boolean' => 'The clear field is required',
            'drawing_options.pen_colors.required' => 'You must specify at least one pen color',
            'drawing_options.pen_colors.array' => 'The drawing colors must be represented by an array',
            'drawing_options.background_image.required' => 'You must supply a background image'
        ]);
    }
}