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
        $sks = [];
        for ($i = 6; $i <= 342; $i++) {
            array_push($sks,$i);
        }
        $gvn = array_rand($sks,40);
        $newgvn = array_unique($gvn);
        $rndm = implode(",",$newgvn);
            return [
                'title' => $this->faker->lastName(),
                'category_id'=>$i,
                'categories'=>"[$rndm]",
            ];
    }
}
