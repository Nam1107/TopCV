<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' =>$this->id,
            'title' => $this->title, 
            'salary'=>$this->salary, 
            'sex_required'=>$this->salary, 
            'desc' =>$this->desc , 
            'exp_required'=>$this->exp_required,
            'quantity' =>$this->quantity,
            'level_required' =>$this->level_required,
            'field_of_job' =>$this->field_of_job,
            'expire_at' =>$this->expire_at,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'company' => new CompanyResource($this->whenLoaded('company')),
        ];
    }
}
