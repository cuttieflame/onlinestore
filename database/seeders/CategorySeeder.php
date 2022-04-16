<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Faker::create();
        \DB::table('categories')->truncate();
        $a = 0;
        for ($i = 1; $i <= 500; $i++) {
            if($i == 1) {
                $b = null;
            }
            $b = rand($i,$i + 5);
            if($b % 5 == 0) {
                $b = null;
            }
            $pr_ctg = new Category();
            $pr_ctg->title = $this->faker->company;
            if(is_null($b)) {
                $pr_ctg->parent_id = $b;
            }
            else {
                $pr_ctg->parent_id = $i + $b;
            }
            $pr_ctg->save();
        }
    }
}
