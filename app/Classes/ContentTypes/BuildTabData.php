<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\TabPart;
use Illuminate\Support\Arr;

class BuildTabData
{
    protected $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function getData()
    {
        $data = TabPart::wherePartId($this->part->id)->first();

        $sections = [];

        foreach ($data->tabSections->sortBy('created_at') as $section) {
            $contentClass = 'App\\' . ucfirst($section->type) . 'Part';

            $content = $contentClass::find($section->content_id);

            if ($section->type === 'content') {
                $builtContent = [
                    'content' => $content->content,
                    'date' => $content->created_at->timestamp
                ];
            } elseif ($section->type === 'animation') {
                $builtContent = [
                    'images' => unserialize($content->images),
                    'title' => $content->title,
                    'caption' => $content->caption,
                    'date' => $content->created_at->timestamp
                ];
            } elseif ($section->type === 'media') {
                $builtContent = [
                    'filename' => unserialize($content->filename),
                    'title' => $content->title,
                    'caption' => $content->caption,
                    'date' => $content->created_at->timestamp
                ];
            }

            $sections[] = [
                'id' => $section->id,
                'tab_part_id' => $section->tab_part_id,
                'content_id' => $section->content_id,
                'type' => $section->type,
                'label' => $section->title,
                'order' => $section->order,
                'hasContent' => true,
                'content' => [
                    'data' => $builtContent,
                    'builderType' => [
                        'type' => $section->type
                    ]
                ]
            ];
        }

        return [
            'id' => $data->id,
            'title' => $data->title,
            'caption' => $data->caption,
            'tabs' => array_values(Arr::sort($sections, function ($value) {
                return $value['content']['data']['date'];
            }))
        ];
    }
}