<?php

namespace App\Http\Resources;

use ApiHelper\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Brick\Money\Money;
use Brick\Money\Context\CustomContext;


class ProductResource extends JsonResource
//class ProductResource extends ResourceCollection
{
    public function toArray($request)
    {
        $minus = $this->productprice->price * (1 - $this->productprice->discount / 100);
        return [
            'id'=>$this->id,
            'entity_id'=>$this->entity_id,
            'attribute_set_id'=>$this->attribute_set_id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'user_id'=>$this->user_id,
            'attributesoptions'=>$this->attributesoptions,

            'productprice'=>[
              'id'=>$this->productprice->id,
              'price'=>$this->productprice->price,
             'discount'=>$this->productprice->discount,
              'old_price'=>$this->productprice->old_price,
              'created_at'=>$this->productprice->created_at,
              'updated_at'=>$this->productprice->updated_at,
            ],
        ];
    }
}
