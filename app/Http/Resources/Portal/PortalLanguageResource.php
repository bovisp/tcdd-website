<?php

namespace App\Http\Resources\Portal;

use Illuminate\Http\Resources\Json\JsonResource;

class PortalLanguageResource extends JsonResource
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
            'language' => $this->language,
            'language_en' => $this->getTranslation('language', 'en'),
            'language_fr' => $this->getTranslation('language', 'fr'),
        ];
    }
}
