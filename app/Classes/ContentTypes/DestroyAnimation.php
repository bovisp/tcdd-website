<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\AnimationPart;
use Illuminate\Support\Facades\File;

class DestroyAnimation
{
    protected $part;

    public function __construct(AnimationPart $part)
    {
        $this->part = $part;
    }

    public function delete()
    {
        foreach (unserialize($this->part->images) as $file) {
            File::delete(public_path($file['file']));
        }

        $this->part->delete();
    }
}