<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\ContentPart;

class DestroyContent
{
    protected $part;

    public function __construct(ContentPart $part)
    {
        $this->part = $part;
    }

    public function delete()
    {
        $this->part->delete();
    }
}