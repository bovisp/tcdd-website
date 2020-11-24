<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\AnimationPart;
use App\ContentBuilder;
use Illuminate\Support\Facades\File;

class DuplicateAnimation
{
    protected $contentBuilderEn;

    protected $contentBuilderFr;

    protected $part;

    protected $isTabPart;

    protected $tabSection;

    protected $contentBuilder;

    public function __construct(
        ContentBuilder $contentBuilderEn, ContentBuilder $contentBuilderFr, $part, $isTabPart, $tabSection
    )
    {
        $this->contentBuilderEn = $contentBuilderEn;
        $this->contentBuilderFr = $contentBuilderFr;
        $this->part = $part;
        $this->isTabPart = $isTabPart;
        $this->tabSection = $tabSection;

        if ($part && $part->contentBuilder->language === 'en') {
            $this->contentBuilder = $this->contentBuilderEn;
        } else if ($part && $part->contentBuilder->language === 'fr') {
            $this->contentBuilder = $this->contentBuilderFr;
        }
    }

    public function duplicate()
    {
        if ($this->isTabPart === false) {
            $newPart = Part::create([
                'content_builder_id' => $this->contentBuilder->id,
                'content_builder_type_id' => $this->part->contentBuilderType->id,
                'sort_order' => $this->part->sort_order
            ]);

            $originalAnimation = AnimationPart::wherePartId($this->part->id)->first();
        } else {
            $originalAnimation = AnimationPart::find($this->tabSection->content_id);
        }

        $files = [];

        foreach (unserialize($originalAnimation->images) as $image) {
            $originalFile = last(explode('/', $image['file']));

            $fileExtension = last(explode('.', $originalFile));

            $newFile = '/storage/entries/' . auth()->id() . '/' .  md5(uniqid(rand(), true)) . '.' . $fileExtension;

            File::copy(public_path($image['file']), public_path($newFile));

            $files[] = [
                'file' => $newFile,
                'order' => $image['order'],
                'timestamp' => $image['timestamp']
            ];
        }

        if ($this->isTabPart === false) {
            AnimationPart::create([
                'part_id' => $newPart->id,
                'images' => serialize($files),
                'title' => $originalAnimation->title,
                'caption' => $originalAnimation->caption
            ]);

            return;
        }

        $newAnimation = AnimationPart::create([
            'images' => serialize($files),
            'title' => $originalAnimation->title,
            'caption' => $originalAnimation->caption
        ]);

        return [
            'data' => $newAnimation,
            'type' => 'animation'
        ];
    }
}