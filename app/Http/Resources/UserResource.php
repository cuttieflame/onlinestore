<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name'=> $this->resource->name,
            'email'=>$this->resource->email,
            'password'=>$this->resource->password,
            'first_name'=>$this->resource->first_name,
            'last_name'=>$this->resource->last_name,
            'organization'=>$this->resource->organization,
            'location'=>$this->resource->location,
            'phone'=>$this->resource->phone,
            'birthday'=>$this->resource->birthday,
            'role_id'=>$this->resource->role_id,
            'permission_id'=>$this->resource->permission_id,

        ];
    }
}
