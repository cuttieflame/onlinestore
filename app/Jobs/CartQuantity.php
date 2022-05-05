<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CartQuantity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $cart;
    protected  $quantity;
    protected  $value;
    public function __construct($cart,$quantity,$value)
    {
        $this->cart = $cart;
        $this->quantity = $quantity;
        $this->value = $value;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->quantity == 'minus') {
            $this->cart->decrement('quantity', 1);
        }
        if($this->quantity == 'plus') {
            $this->cart->increment('quantity', 1);
        }
        if($this->quantity == 'change') {
            $this->cart->quantity = $this->value;
        }
        $this->cart->save();
    }
}
