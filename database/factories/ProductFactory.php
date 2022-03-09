<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
//'images/HumKE711BtI.jpg'
    public function definition()
    {
        return [
            'name'=>$this->faker->realText(rand(30, 50)),
            'content'=>$this->faker->realText(rand(210, 250)),
            'price'=>rand(1000, 100000),
            'category_id'=>rand(1,20),
            'brand_id'=>rand(1,20),
            'main_image'=>'images/HumKE711BtI.jpg',
            'images'=>'[
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 1
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 2
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 3
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 4
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 5
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 6
    },
    {
        "image": "images/HumKE711BtI.jpg",
        "id": 7
    }
]
',
        ];
    }
}
