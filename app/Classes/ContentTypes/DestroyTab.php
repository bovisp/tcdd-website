<?php

namespace App\Classes\ContentTypes;

use App\TabPart;
use App\TabPartSection;
use Illuminate\Support\Facades\File;

class DestroyTab
{
    protected $part;

    public function __construct(TabPart $part)
    {
        $this->part = $part;
    }

    public function delete()
    {
        $tab = TabPart::find($this->part->id);

        foreach ($tab->tabSections as $section) {
            $typeClassName = 'App\\' . ucfirst($section->type) . 'Part';

            $partType = $typeClassName::find($section->content_id);

            $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($section->type);

            (new $destroyClassName($partType))->delete();

            $section->delete();
        }

        $tab->delete();
    }
}