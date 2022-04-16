<?php

namespace Database\Factories;

use App\Models\Products;
use Eav\AttributeOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    protected $model = AttributeOption::class;
    public function definition()
    {
        return [
            'attribute_id'=>3,
            'label'=>' ',
            'value'=>$this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 10000),
            'product_id'=>$this->faker->numberBetween($min = 1, $max = 200),
        ];
    }
}
