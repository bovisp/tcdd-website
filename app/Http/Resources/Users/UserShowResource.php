<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowResource extends JsonResource
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
            'fullname' => $this->firstname . ' ' . $this->lastname,
            'email' => $this->email,
            'role' => $this->roles->where('name', '!=', 'administrator')->first()->name,
            'roleRank' => $this->roles->where('name', '!=', 'administrator')->first()->rank,
            'reportingStructure' => $this->reportingStructure()
        ];
    }
}
