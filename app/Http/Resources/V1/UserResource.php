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
            'name' => $this->name,
            'sex'=> $this->sex,
            'phone'=> $this->phone,
            'address' => $this->address,
            'district'=> $this->district,
            'province' => $this->province,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'avatar' => $this->avatar,
            'email'=> $this->email,
        ];
    }
}
