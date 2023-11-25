<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name' => $this->name,
            'email'=> $this->email,
            'sex'=> $this->sex,
            'phone'=> $this->phone,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'district'=> $this->district,
            'province' => $this->province,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            
            
        ];
    }
}
