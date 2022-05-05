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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->account_details->first_name,
            'last_name' => $this->account_details->last_name,
            'organization' => $this->account_details->organization,
            'location' => $this->account_details->location,
            'phone' => $this->account_details->phone,
            'birthday' => $this->account_details->birthday,
            'user_image' => $this->account_details->user_image,
            'role_user'=>$this->role_user,
            'permission_user' => $this->permission_user,
        ];
    }
}
