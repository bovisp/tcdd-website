<?php

namespace App\Http\Resources\Issues;

use Illuminate\Http\Resources\Json\JsonResource;

class IssueResource extends JsonResource
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
            'issuer_id' => $this->issuer_id,
            'issuer' => $this->issuer->fullname,
            'title' => $this->title,
            'body' => $this->body,
            'closed' => $this->closed ? 'Yes' : 'No',
            'closed_at' => $this->closed_at ? $this->closed_at->format('m/d/y') : 'No',
            'created_at' => $this->created_at->format('m/d/y'),
            'updated_at' => $this->updated_at->notEqualTo($this->created_at) ? $this->updated_at->format('m/d/y') : null,
            'status' => $this->status,
            'code' => $this->code
        ];
    }
}
