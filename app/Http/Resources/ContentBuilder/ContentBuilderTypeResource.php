<?php

namespace App\Http\Resources\ContentBuilder;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentBuilderTypeResource extends JsonResource
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
            'type' => $this->type,
            'id' => $this->id,
            'description' => $this->description,
            'name' => $this->name,
            'visible' => $this->visible
        ];
    }
}
