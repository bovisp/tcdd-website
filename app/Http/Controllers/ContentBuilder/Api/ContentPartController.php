<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\ContentPart;
use App\ContentBuilder;
use App\TabPartSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\ContentTypes\DestroyContent;
use App\Http\Resources\ContentBuilder\PartResource;

class ContentPartController extends Controller
{
    public function store(ContentBuilder $contentBuilder)
    {
        request()->validate([
            'content' => 'required|min:8',
            'content_builder_type_id' => 'required|exists:content_builder_types,id'
        ], [
            'content.required' => __('app_http_controllers_contentbuilder_api_contentpart.content_required'),
            'min' => __('app_http_controllers_contentbuilder_api_contentpart.min'),
            'content_builder_type_id.required' => __('app_http_controllers_contentbuilder_api_contentpart.content_builder_type_id_required'),
            'content_builder_type_id.exists' => __('app_http_controllers_contentbuilder_api_contentpart.content_builder_type_id_exists')
        ]);

        if (request('is_tab_section') === false) {
            $part = Part::create([
                'content_builder_id' => $contentBuilder->id,
                'sort_order' => count($contentBuilder->parts) ? $contentBuilder->parts->last()->sort_order + 1 : 1,
                'content_builder_type_id' => request('content_builder_type_id')
            ]);
    
            ContentPart::create([
                'part_id' => $part->id,
                'content' => request('content')
            ]);

            return new PartResource($part);
        } else {
            $content = ContentPart::create([
                'content' => request('content')
            ]);
    
            return [
                'data' => [
                    'content' => $content->content,
                    'id' => $content->id,
                    'type' => 'content'
                ],
                'builderType' => [
                    'type' => 'content'
                ]
            ];
        }
    }

    public function update(ContentPart $contentPart)
    {
        request()->validate([
            'content' => 'required'
        ], [
            'content.required' => __('app_http_controllers_contentbuilder_api_contentpart.content_required')
        ]);

        $contentPart->update([
            'content' => request('content')
        ]);

        return new PartResource(Part::find($contentPart->part_id));
    }

    public function destroy(ContentPart $partType)
    {
        (new DestroyContent($partType))->delete();
    }
}
