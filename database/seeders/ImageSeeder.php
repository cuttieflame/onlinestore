<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 50; $i++) {
            $product = new Image();
            $product->images = '[
	{
        "image": "images/HumKE711BtI.jpg",
        "id": "1"
      },
      {
          "image": "images/HumKE711BtI.jpg",
         "id": "2"
      },
       {
           "image": "images/HumKE711BtI.jpg",
         "id": "3"
      },
       {
           "image": "images/HumKE711BtI.jpg",
         "id": "4"
      },
      {
          "image": "images/HumKE711BtI.jpg",
         "id": "5"
      },
      {
          "image": "images/HumKE711BtI.jpg",
         "id": "6"
      }
]';
            $product->product_id = $i;
            $product->save();
        }
    }
}
