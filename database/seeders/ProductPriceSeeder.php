<?php

namespace Database\Seeders;

use App\Models\ProductPrice;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('product_prices')->truncate();
        $this->faker = Faker::create();
    for ($i = 1; $i <= 50; $i++) {
        $pr_ctg = new ProductPrice();
        $pr_ctg->id = $i;
        $pr_ctg->price = $this->faker->randomFloat($nbMaxDecimals = 3, $min = 100, $max = 100000);
        $pr_ctg->discount = $this->faker->numberBetween($min = 1, $max = 99);
        $pr_ctg->old_price = $this->faker->randomFloat($nbMaxDecimals = 3, $min = 100, $max = 100000);
        $pr_ctg->save();
    }

    }
}
