<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\MediaPart;
use App\ContentBuilder;
use App\TabPartSection;
use App\Http\Controllers\Controller;
use App\Classes\ContentTypes\DestroyMedia;
use App\Http\Resources\ContentBuilder\PartResource;

class MediaPartController extends Controller
{
    public function store(ContentBuilder $contentBuilder)
    {
        request()->validate([
            'filename' => 'required',
            'content_builder_type_id' => 'required|exists:content_builder_types,id',
            'title' => 'nullable|min:3',
            'caption' => 'nullable|min:3'
        ], [
            'files.required' => __('app_http_controllers_contentbuilder_api_mediapart.files_required'),
            'content_builder_type_id.required' => __('app_http_controllers_contentbuilder_api_mediapart.content_builder_type_id_required'),
            'content_builder_type_id.exists' => __('app_http_controllers_contentbuilder_api_mediapart.content_builder_type_id_exists'),
            'title.min' => __('app_http_controllers_contentbuilder_api_mediapart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_mediapart.caption_min')
        ]);
        
        if (request('is_tab_section') === false) {
            $part = Part::create([
                'content_builder_id' => $contentBuilder->id,
                'sort_order' => count($contentBuilder->parts) ? $contentBuilder->parts->last()->sort_order + 1 : 1,
                'content_builder_type_id' => request('content_builder_type_id')
            ]);

            MediaPart::create([
                'part_id' => $part->id,
                'filename' => serialize(request('filename')),
                'title' => request('title'),
                'caption' => request('caption')
            ]);

            return new PartResource($part);
        } else {
            $media = MediaPart::create([
                'filename' => serialize(request('filename')),
                'title' => request('title'),
                'caption' => request('caption')
            ]);

            if (request()->has('tab_part_section_title')) {
                
                $tabSection = TabPartSection::create([
                    'title' => request('tab_part_section_title'),
                    'tab_part_id' => request('part_id'),
                    'content_id' => $media->id,
                    'type' => 'media'
                ]);
    
                return [
                    'data' => [
                        'filename' => unserialize($media->filename),
                        'id' => $media->id,
                        'title' => $media->title,
                        'caption' => $media->caption,
                        'type' => 'media'
                    ],
                    'id' => $tabSection->id,
                    'title' => $tabSection->title
                ];
            }

            return [
                'filename' => unserialize($media->filename),
                'title' => $media->title,
                'caption' => $media->caption,
                'id' => $media->id
            ];
        }
    }

    public function update(Part $part)
    {
        request()->validate([
            'title' => 'nullable|min:3',
            'caption' => 'nullable|min:3'
        ], [
            'title.min' => __('app_http_controllers_contentbuilder_api_mediapart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_mediapart.caption_min')
        ]);

        $mediaPart = MediaPart::wherePartId($part->id)->first();

        $mediaPart->update([
            'title' => request('title'),
            'caption' => request('caption')
        ]);

        return new PartResource($part);
    }

    public function destroy(MediaPart $partType)
    {
        (new DestroyMedia($partType))->delete();
    }
}
