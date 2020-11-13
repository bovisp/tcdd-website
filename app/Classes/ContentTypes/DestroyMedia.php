<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\MediaPart;
use Illuminate\Support\Facades\File;

class DestroyMedia
{
    protected $part;

    public function __construct(MediaPart $part)
    {
        $this->part = $part;
    }

    public function delete()
    {
        foreach (unserialize($this->part->filename) as $file) {
            File::delete(public_path($file['file']));
        }

        $this->part->delete();
    }
}