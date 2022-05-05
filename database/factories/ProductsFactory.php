<?php

namespace Database\Factories;

use App\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    protected $model = Products::class;
    public function definition()
    {
        return [
            'entity_id'=>1,
            'attribute_id'=>1,
            'user_id'=>null
        ];
    }

}
