<?php

namespace App\Http\Resources\Articles;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleShowResource extends JsonResource
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
            'contentBuilder' => [
                'en' => $this->contentBuilder->where('language', '=', 'en')->first()->id,
                'fr' => $this->contentBuilder->where('language', '=', 'fr')->first()->id
            ]
        ];
    }
}
