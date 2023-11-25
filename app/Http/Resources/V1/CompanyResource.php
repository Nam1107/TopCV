<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $is_follow = isset($this->isFollowing) ? 1 : 0;

        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'email'=> $this->email,
            'address'=> $this->address,
            'district'=> $this->district,
            'city'=> $this->city,
            'phone'=> $this->phone,
            'logo'=> $this->logo,
            'detail'=> $this->details,
            'url_page'=> $this->url_page,
            'follow_count'=> $this->follow_count,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'owner'=> new UserResource($this->whenLoaded('ownedBy')),
            'followed'=> $is_follow 
        ];
    }
}
