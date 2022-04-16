<?php

namespace Database\Seeders;

use App\Models\SubscriptionItem;
use App\Products;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param $nb
     * @return void
     */
    public function run()
    {
//        \App\Products::factory()->count(200)->create();
//        \DB::table('products')->truncate();
        $this->faker = Faker::create();
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        for ($i = 1; $i <= 50; $i++) {
            $a = $stripe->products->create([
                'name' => $this->faker->company,
                ['metadata' => ['product_id'=>$i,'price' => $this->faker->numberBetween($min = 2, $max = 1000),'currency'=>'usd']],
            ]);
            $sub_items = new SubscriptionItem();
            $sub_items->stripe_id = $a->id;
            $sub_items->stripe_product = $a->name;
            $sub_items->stripe_price = 'price';
            $sub_items->quantity = 1;
            $sub_items->save();
//
//            $product = new Products();
//            $product->entity_id = 1;
//            $product->attribute_set_id = 1;
//            $product->save();
        }

//        $sks = [];
//        for ($i = 6; $i <= 342; $i++) {
//            array_push($sks,$i);
//        }
//
//            for ($i = 6; $i <= 342; $i++) {
//                $gvn = array_rand($sks,40);
//                $newgvn = array_unique($gvn);
//                $rndm = implode(",",$newgvn);
//                \DB::table('brands')->insert([
//                    'title' => $this->faker->lastName(),
//                    'category_id'=>$i,
//                    'categories'=>"[$rndm]",
//                ]);
//            }

//            for ($i = 1;$i <=221;$i++) {
//                \DB::table('product_prices')->insert([
//                   'id'=>$i,
//                    'price'=>$this->faker->randomFloat($nbMaxDecimals = 3, $min = 100, $max = 100000),
//                    'discount'=>$this->faker->numberBetween($min = 1, $max = 99),
//                    'old_price'=>$this->faker->randomFloat($nbMaxDecimals = 3, $min = 100, $max = 100000),
//                ]);
//            }
//        for ($b = 890; $b <= 1500; $b++) {
//            for ($i = 1; $i <= 221; $i++) {
//                \DB::table('product_categories')->insert([
//                    'id' => $b + $i,
//                    'product_id' => $i,
//                    'category_id' => $this->faker->numberBetween($min = 6, $max = 342),
//                ]);
//            }
//        }




//                for ($i = 1; $i <= 200; $i++) {
//                    \DB::table('products_info')->insert([
//                        'id'=>$i,
//                        'rating'=>$this->faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 100),
//                        'order_count' => 0,
//                        'name_attributes' => '[
//                        {
//                            "name": "Specification",
//                            "id": "1"
//                          },
//                          {
//                              "name": "Warranty info",
//                             "id": "2"
//                          },
//                           {
//                               "name": "Shipping info",
//                             "id": "3"
//                          },
//                           {
//                               "name": "Seller profile",
//                             "id": "4"
//                          }
//                    ]',
//                        'attribute_info' => '[
//    {
//        "text": "With supporting text below as a natural lead-in to additional content. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.Some great feature name here Lorem ipsum dolor sit amet consectetur Duis aute irure dolor in reprehenderit Optical heart sensor Easy fast and ver good Some great feature name here Modern style and design",
//        "more_info": [
//
//      {
//         "name": "Display",
//         "text": "13.3-inch LED-backlit display with IPS"
//      },
//       {
//         "name": "Processor capacity",
//         "text": "2.3GHz dual-core Intel Core i5"
//      },
//       {
//         "name": "Camera quality",
//         "text": "720p FaceTime HD camera"
//      },
//       {
//         "name": "Graphics",
//         "text": "Intel Iris Plus Graphics 640"
//      }
//   ]
//    },
//    {
//        "text": "Tab content or sample information now Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo",
//        "more_info": ""
//    },
//    {
//        "text": "Another tab content or sample information now Dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
//        "more_info": ""
//    },
//    {
//        "text": "Some other tab content or sample information now Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
//        "more_info": ""
//    }
//]',
//                    ]);
//                }

    }
}
