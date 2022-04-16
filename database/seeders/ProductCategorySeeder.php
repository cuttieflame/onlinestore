<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductCategory;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_categories')->truncate();
        $this->faker = Faker::create();
            $categories = Category::all();
            $count_categories = \DB::table('categories')->count();
            for ($b = 1;$b <= 25;$b++) {
                for ($i = 1; $i <= 50; $i++) {
                    $pr_ctg = new ProductCategory();
                    $pr_ctg->product_id = $i;
                    $pr_ctg->category_id = $this->faker->numberBetween(1, $count_categories);
                    $pr_ctg->save();
                }
            }


//            for ($i = 1; $i <= 229; $i++) {
//                $a = $a + 1;
//                $pr_ctg = new ProductCategory();
//
//                $pr_ctg->product_id = $i;
//                $pr_ctg->category_id = $this->faker->numberBetween($min = 6, $max = 342);
//            }

    }
}
