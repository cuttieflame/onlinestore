<?php
declare(strict_types=1);
namespace App\Jobs;

use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 *
 */
class CartQuantity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected Cart $cart;
    /**
     * @var
     */
    protected string $quantity;
    /**
     * @var
     */
    protected int  $value;

    /**
     * @param $cart
     * @param $quantity
     * @param $value
     */
    public function __construct($cart, $quantity, $value)
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
