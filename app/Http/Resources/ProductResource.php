<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'name'=> $this->resource->name,
            'content'=>$this->resource->content,
            'view_count'=>$this->resource->view_count,
            'main_image'=>$this->resource->main_image,
            'images'=>$this->resource->images,
            'price'=>$this->resource->price,
            'category_id'=>$this->resource->category_id,
            'brand_id'=>$this->resource->brand_id,
                'product_id'=>$this->resource->product_id,
                'rating'=>$this->resource->rating,
                'order_count'=>$this->resource->order_count,
                'name_attributes'=>$this->resource->name_attributes,
                'attribute_info'=>$this->resource->attribute_info,

        ];
    }
}
