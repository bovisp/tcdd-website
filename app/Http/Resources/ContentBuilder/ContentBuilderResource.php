<?php

namespace App\Http\Resources\ContentBuilder;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentBuilder\PartResource;

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
            'editing' => false,
            'lang' => $this->language,
            'parts' => PartResource::collection($this->parts)
        ];
    }
}
