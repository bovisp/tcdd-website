<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\MediaPart;

class BuildMediaData
{
    protected $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function getData()
    {
        $data = MediaPart::wherePartId($this->part->id)->first();

        return [
            'filename' => unserialize($data->filename),
            'title' => $data->title,
            'caption' => $data->caption,
            'type' => 'media'
        ];
    }
}