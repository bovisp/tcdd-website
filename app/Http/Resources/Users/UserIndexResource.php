<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserIndexResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fullname' => $this->fullname,
            'role' => ucfirst($this->role),
            'rank' => $this->roles->where('name', '!=', 'administrator')->first()->rank,
            'roles' => $this->roles->toArray(),
            'section' => optional($this->section)->name
        ];
    }
}
