<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\AnimationPart;
use App\ContentBuilder;
use App\TabPartSection;
use App\Http\Controllers\Controller;
use App\Classes\ContentTypes\DestroyAnimation;
use App\Http\Resources\ContentBuilder\PartResource;

class AnimationPartController extends Controller
{
    public function store(ContentBuilder $contentBuilder)
    {
        request()->validate([
            'files' => 'required',
            'content_builder_type_id' => 'required|exists:content_builder_types,id',
            'title' => 'nullable',
            'caption' => 'nullable'
        ], [
            'files.required' => __('app_http_controllers_contentbuilder_api_animationpart.files_required'),
            'content_builder_type_id.required' => __('app_http_controllers_contentbuilder_api_animationpart.content_builder_type_id_required'),
            'content_builder_type_id.exists' => __('app_http_controllers_contentbuilder_api_animationpart.content_builder_type_id_exists'),
            'title.min' => __('app_http_controllers_contentbuilder_api_animationpart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_animationpart.caption_min')
        ]);

        $files = [];

        foreach (request('files') as $file) {
            $fileArr = explode('.', $file['original']);

            $order = last(explode('_', $fileArr[0]));

            if ((int) $order >= 190000000000) {
                $year = substr($order, 0, 4);
                $month = substr($order, 4, 2);
                $day = substr($order, 6, 2);

                $time = substr($order, 8, 4);

                $timestamp = "{$year}/{$month}/{$day} {$time}Z";
            } elseif ((int) $order >= 19000000) {
                $year = substr($order, 0, 4);
                $month = substr($order, 4, 2);
                $day = substr($order, 6, 2);

                $timestamp = "{$year}/{$month}/{$day}";
            } else {
                $timestamp = null;
            }

            $files[] = [
                'file' => $file['file'],
                'order' => (int) $order,
                'timestamp' => $timestamp
            ];
        }

        if (request('is_tab_section') === false) {
            $part = Part::create([
                'content_builder_id' => $contentBuilder->id,
                'sort_order' => count($contentBuilder->parts) ? $contentBuilder->parts->last()->sort_order + 1 : 1,
                'content_builder_type_id' => request('content_builder_type_id')
            ]);

            AnimationPart::create([
                'part_id' => $part->id,
                'images' => serialize($files),
                'title' => request('title'),
                'caption' => request('caption')
            ]);

            return new PartResource($part);
        } else {
            $animation = AnimationPart::create([
                'images' => serialize($files),
                'title' => request('title'),
                'caption' => request('caption')
            ]);

            return [
                'data' => [
                    'images' => $files,
                    'id' => $animation->id,
                    'title' => $animation->title,
                    'caption' => $animation->caption,
                    'type' => 'animation'
                ],
                'builderType' => [
                    'type' => 'animation'
                ]
            ];
        }
    }

    public function update(AnimationPart $animationPart)
    {
        request()->validate([
            'title' => 'nullable',
            'caption' => 'nullable'
        ]);

        $animationPart->update([
            'title' => request('title'),
            'caption' => request('caption')
        ]);

        return new PartResource(Part::find($animationPart->part_id));
    }

    public function destroy(AnimationPart $partType)
    {
        (new DestroyAnimation($partType))->delete();
    }
}
