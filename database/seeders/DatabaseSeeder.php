<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
//            RoleSeeder::class,
//            PermissionSeeder::class,
//            UserSeeder::class,
//            CurrencySeeder::class,

//            AttributeSeeder::class,

            ProductsSeeder::class,
            ImageSeeder::class,
            AttributeOptionSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductCategorySeeder::class,
            ProductPriceSeeder::class,
        ]);

    }
}
