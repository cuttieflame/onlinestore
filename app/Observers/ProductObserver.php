<?php

namespace App\Observers;

use App\Products;
use App\Models\ProductInfo;

class ProductObserver
{
    public function created(Products $product)
    {
        ProductInfo::create([
           'id'=>$product->id,
           'name_attributes'=> '[
	{
        "name": "Specification",
        "id": "1"
      },
      {
          "name": "Warranty info",
         "id": "2"
      },
       {
           "name": "Shipping info",
         "id": "3"
      },
       {
           "name": "Seller profile",
         "id": "4"
      }
    ]',
            'attribute_info'=>'[
    {
        "text": "With supporting text below as a natural lead-in to additional content. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.Some great feature name here Lorem ipsum dolor sit amet consectetur Duis aute irure dolor in reprehenderit Optical heart sensor Easy fast and ver good Some great feature name here Modern style and design",
        "more_info": [

      {
         "name": "Display",
         "text": "13.3-inch LED-backlit display with IPS"
      },
       {
         "name": "Processor capacity",
         "text": "2.3GHz dual-core Intel Core i5"
      },
       {
         "name": "Camera quality",
         "text": "720p FaceTime HD camera"
      },
       {
         "name": "Graphics",
         "text": "Intel Iris Plus Graphics 640"
      }
   ]
    },
    {
        "text": "Tab content or sample information now Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo",
        "more_info": ""
    },
    {
        "text": "Another tab content or sample information now Dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        "more_info": ""
    },
    {
        "text": "Some other tab content or sample information now Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        "more_info": ""
    }
]'
        ]);
        activity()
            ->performedOn($product)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('create')
            ->inLog('create')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'product'=>$product->id])
            ->log('create product');
    }

    public function updated(Products $product)
    {
        activity()
            ->performedOn($product)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('update')
            ->inLog('update')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'product'=>$product->id])
            ->log('update product');
    }
    public function deleted(Products $product)
    {
        activity()
            ->performedOn($product)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('delete')
            ->inLog('delete')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'product'=>$product->id])
            ->log('delete product');
    }
    public function restored(Products $product)
    {
        //
    }

    public function forceDeleted(Products $product)
    {
        //
    }
}
