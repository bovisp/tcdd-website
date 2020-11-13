<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\TabPart;

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
                    'content' => $content->content
                ];
            } elseif ($section->type === 'animation') {
                $builtContent = [
                    'images' => unserialize($content->images),
                    'title' => $content->title,
                    'caption' => $content->caption
                ];
            } elseif ($section->type === 'media') {
                $builtContent = [
                    'filename' => unserialize($content->filename),
                    'title' => $content->title,
                    'caption' => $content->caption
                ];
            }

            $sections[] = [
                'id' => $section->id,
                'tab_part_id' => $section->tab_part_id,
                'content_id' => $section->content_id,
                'type' => $section->type,
                'title' => $section->title,
                'content' => [
                    'data' => $builtContent
                ]
            ];
        }

        return [
            'id' => $data->id,
            'title' => $data->title,
            'caption' => $data->caption,
            'tabSections' => $sections
        ];
    }
}