<?php

namespace App\Http\Resources\Assessments;

use App\Question;
use App\ContentBuilder;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'assessment_id' => $this->assessment_id,
            'number' => $this->number,
            // 'data' => $this->assessmentPageContents->count() ? $this->assessmentPageContents->map(function ($content) {
            //     $pageContentItems = $content->assessmentPageContentItems->map(function ($item) {
            //         if ($item->type === 'ContentBuilder') {
            //             return [
            //                 'id' => $item->model_id,
            //                 'lang' => ContentBuilder::find($item->model_id)->language
            //             ];
            //         }

            //         if ($item->type === 'Question') {
            //             return [
            //                 'id' => $item->model_id,
            //                 'question' => Question::with(
            //                     'author',
            //                     'contentBuilder',
            //                     'section',
            //                     'questionCategory',
            //                     'tags',
            //                     'owner',
            //                     'editors',
            //                     'questionType'
            //                 )
            //                     ->whereId($item->model_id)
            //                     ->first()
            //                     ->append('question_data')
            //             ];
            //         }
            //     });

            //     return [
            //         'order' => $content->order,
            //         'model' => $content,
            //         'items' => $pageContentItems,
            //         'type' => $content->assessmentPageContentItems->first()->type
            //     ];
            // }) : []
        ];
    }
}
