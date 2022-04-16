<?php

namespace Database\Seeders;

use App\Models\Brand;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('brands')->truncate();
        $categories = \DB::table('categories')->get();
        $categories_count = \DB::table('categories')->count();

        $this->faker = Faker::create();
        $sks = [];
        for ($i = 1; $i <= $categories_count; $i++) {
            array_push($sks,$i);
        }

            for ($i = 1; $i <= $categories_count - 1; $i++) {
                $gvn = array_rand($sks,20);
                $newgvn = array_unique($gvn);
                $rndm = implode(",",$newgvn);
                $brand = new Brand();
                $brand->title = $this->faker->lastName();
                $brand->category_id = $categories[$i]->id;
                $brand->categories = "[$rndm]";
                $brand->save();
            }
    }
}
