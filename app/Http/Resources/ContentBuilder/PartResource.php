<?php

namespace App\Http\Resources\ContentBuilder;

use Illuminate\Http\Resources\Json\JsonResource;

class PartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $className = 'App\Classes\ContentTypes\Build' . ucfirst($this->contentBuilderType->type) . 'Data';

        return [
            'id' => $this->id,
            'editingPart' => false,
            'builderType' => $this->contentBuilderType,
            'sort_order' => $this->sort_order,
            'data' => (new $className($this->resource))->getData()
        ];
    }
}
