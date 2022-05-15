<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray($request)
    {
        $name = '';
        $content = '';
        if($this->attributesoptions) {
            foreach ($this->attributesoptions as $elem) {
                if ($elem->attribute_id == 4) {
                    $name = $elem->value;
                }
                if ($elem->attribute_id == 5) {
                    $content = $elem->value;
                }
            }
        }
        $price = 0;
        $discount = 0;
        return [
            'id'=>$this->id,
            'session_id'=>$this->session_id,
            'product_id'=>$this->session_id,
            'user_id'=>$this->session_id,
            'quantity'=>$this->quantity,
            'name'=>$name ?: 'нет имени',
            'content'=>$content ?: 'нет имени',
            'price'=>$this->productprice ? $this->productprice->price : 0,
            'discount'=>$this->productprice ? $this->productprice->discount : 0,
        ];
    }
}
