<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\MediaPart;
use App\ContentBuilder;
use Illuminate\Support\Facades\File;

class DuplicateMedia
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

            $originalMedia = MediaPart::wherePartId($this->part->id)->first();
        } else {
            $originalMedia = MediaPart::find($this->tabSection->content_id);
        }
        
        $originalFile = last(explode('/', unserialize($originalMedia->filename)[0]['file']));

        $fileExtension = last(explode('.', $originalFile));

        $newFile = '/storage/entries/' . auth()->id() . '/' .  md5(uniqid(rand(), true)) . '.' . $fileExtension;

        File::copy(public_path(unserialize($originalMedia->filename)[0]['file']), public_path($newFile));

        if ($this->isTabPart === false) {
            MediaPart::create([
                'part_id' => $newPart->id,
                'filename' => serialize([
                    'file' => $newFile,
                    'original' => unserialize($originalMedia->filename)[0]['original']
                ]),
                'title' => $originalMedia->title,
                'caption' => $originalMedia->caption
            ]);

            return;
        }

        $newMedia = MediaPart::create([
            'filename' => serialize([
                'file' => $newFile,
                'original' => unserialize($originalMedia->filename)[0]['original']
            ]),
            'title' => $originalMedia->title,
            'caption' => $originalMedia->caption
        ]);

        return [
            'data' => $newMedia,
            'type' => 'media'
        ];
    }
}