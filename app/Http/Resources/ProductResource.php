<?php

namespace App\Http\Resources;

use ApiHelper\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
//class ProductResource extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->resource,



//            'entity_id' => $this->resource[0]->entity_id,
//            'attribute_set_id' => $this->resource[0]->attribute_set_id,
//            'created_at' => $this->resource[0]->attribute_set_id,
//            'updated_at' => $this->resource[0]->attribute_set_id,
//            'user_id' => $this->resource[0]->user_id,
//            'attributesoptions' => $this->resource[0]->attributesoptions,
//            'productprice' => $this->resource[0]->productprice,

//            'name'=> $this->resource->name,
//            'content'=>$this->resource->content,
//            'view_count'=>$this->resource->view_count,
//            'main_image'=>$this->resource->main_image,
//            'images'=>$this->resource->images,
//            'price'=>$this->resource->price,
//            'category_id'=>$this->resource->category_id,
//            'brand_id'=>$this->resource->brand_id,
//                'product_id'=>$this->resource->product_id,
//                'rating'=>$this->resource->rating,
//                'order_count'=>$this->resource->order_count,
//                'name_attributes'=>$this->resource->name_attributes,
//                'attribute_info'=>$this->resource->attribute_info,
        ];
    }
}
