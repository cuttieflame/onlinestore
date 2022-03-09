<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $brands_array = ['asus','toshiba','dell','viewsonic','sony','acer','genius','canon','epson','a4tech','HP','sven','philips','panasonic','defender',
            'benq','palit','lenovo','nikon','amd','nvidia','kingston'];
        foreach($brands_array as $brand) {
            return [
                'brand'=>$this->faker->firstNameFemale,
            ];
        }

    }
}
