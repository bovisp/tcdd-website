<?php

namespace App\Http\Resources\Users;

use Spatie\Permission\Models\Permission;
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
        $permissions = [];

        foreach (Permission::all() as $permission) {
            if (auth()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'fullname' => $this->fullname,
            'role' => ucfirst($this->role),
            'rank' => $this->roles->where('name', '!=', 'administrator')->first()->rank,
            'roles' => $this->roles->toArray(),
            'section' => optional($this->section)->name,
            'permissions' => $permissions
        ];
    }
}
