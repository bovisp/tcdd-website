<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\ContentPart;
use App\ContentBuilder;
use App\TabPartSection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContentBuilder\PartResource;

class ContentPartController extends Controller
{
    public function store(ContentBuilder $contentBuilder)
    {
        request()->validate([
            'content' => 'required|min:8',
            'content_builder_type_id' => 'required|exists:content_builder_types,id'
        ], [
            'content.required' => 'You need to add content to this block.',
            'min' => 'You need to add content to this block.',
            'content_builder_type_id.required' => 'You need to add the content builder type to this block.',
            'content_builder_type_id.exists' => 'This content builder type does not exist.'
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

            if (request()->has('tab_part_section_title')) {
                $tabSection = TabPartSection::create([
                    'title' => request('tab_part_section_title'),
                    'tab_part_id' => request('part_id'),
                    'content_id' => $content->id,
                    'type' => 'content'
                ]);
    
                return [
                    'data' => [
                        'content' => $content->content,
                        'id' => $content->id,
                        'type' => 'content'
                    ],
                    'id' => $tabSection->id,
                    'title' => $tabSection->title
                ];
            }

            return [
                'content' => $content->content,
                'id' => $content->id
            ];
        }
    }

    public function update(Part $part)
    {
        request()->validate([
            'content' => 'required'
        ], [
            'content.required' => 'You need to add content to this block.'
        ]);

        $contentPart = ContentPart::wherePartId($part->id)->first();

        $contentPart->update([
            'content' => request('content')
        ]);

        return new PartResource($part);
    }
}
