<?php

namespace App\Classes\ContentTypes;

use App\Part;
use App\TabPart;
use App\ContentBuilder;
use App\TabPartSection;

class DuplicateTab
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
        $newPart = Part::create([
            'content_builder_id' => $this->contentBuilder->id,
            'content_builder_type_id' => $this->part->contentBuilderType->id,
            'sort_order' => $this->part->sort_order
        ]);

        $originalTab = TabPart::wherePartId($this->part->id)->first();

        foreach ($originalTab->tabSections as $section) {
            $contentTypeClass = 'App\\' . ucfirst($section->type) . 'Part';

            $contentTypeModel = $contentTypeClass::find($section->content_id);

            $duplicateContentTypeClass = 'App\\Classes\\ContentTypes\\Duplicate' . ucfirst($section->type);

            $tabPartSectionData = (new $duplicateContentTypeClass(
                $this->contentBuilderEn, $this->contentBuilderFr, null, true, $section
            ))->duplicate();

            TabPartSection::create([
                'title' => $section->title,
                'tab_part_id' => $newPart->id,
                'content_id' => $tabPartSectionData['data']->id,
                'type' => $tabPartSectionData['type']
            ]);
        }
    }
}