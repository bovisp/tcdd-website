<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\AnimationPart;

class BuildAnimationData
{
    protected $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function getData()
    {
        $data = AnimationPart::wherePartId($this->part->id)->first();

        return [
            'images' => unserialize($data->images),
            'title' => $data->title,
            'caption' => $data->caption,
            'type' => 'animation',
            'id' => $data->id,
        ];
    }
}