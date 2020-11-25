<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\ContentPart;
use App\ContentBuilder;

class DuplicateContent
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
    
            $originalContent = ContentPart::wherePartId($this->part->id)->first();
    
            ContentPart::create([
                'content' => $originalContent->content,
                'part_id' => $newPart->id
            ]);

            return;
        }

        $originalContent = ContentPart::find($this->tabSection->content_id);

        $newContent = ContentPart::create([
            'content' => $originalContent->content
        ]);

        return [
            'data' => $newContent,
            'type' => 'content'
        ];
    }
}