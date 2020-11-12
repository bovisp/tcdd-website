<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\ContentPart;

class BuildContentData
{
    protected $part;

    public function __construct(Part $part)
    {
        $this->part = $part;
    }

    public function getData()
    {
        $data = ContentPart::wherePartId($this->part->id)->first();

        return [
            'content' => $data->content,
            'type' => 'content'
        ];
    }
}