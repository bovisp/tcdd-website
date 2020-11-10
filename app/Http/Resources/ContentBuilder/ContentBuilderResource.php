<?php

namespace App\Http\Resources\ContentBuilder;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentBuilderResource extends JsonResource
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
            'parts' => 'The parts'
        ];
    }
}
