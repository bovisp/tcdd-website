<?php

namespace App\Http\Resources\Portal;

use Illuminate\Http\Resources\Json\JsonResource;

class PortalCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'portal_category_id' => $this->portal_category_id,
            'moodle_course_id' => $this->moodle_course_id,
            'portal_language_id' => $this->portal_language_id,
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
            'language' => $this->portalLanguage,
            'languageName' => $this->portalLanguage->language,
            'category' => $this->portalCategory,
            'categoryName' => $this->portalCategory->name
        ];
    }
}
