<?php

namespace App\Http\Resources\Sections;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'name_en' => $this->getTranslation('name', 'en'),
            'name_fr' => $this->getTranslation('name', 'fr'),
        ];
    }
}
