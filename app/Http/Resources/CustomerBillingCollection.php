<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBillingCollection extends ResourceCollection
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
            'data' => CustomerBillingResource::collection($this->collection),
            'meta' => ['song_count' => $this->collection->count()],
        ];
    }
}
