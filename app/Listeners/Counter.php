<?php

namespace App\Listeners;

use App\Events\PostHasViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Counter
{
    public function handle(PostHasViewed $event)
    {
        $event->product->increment('view_count');
    }
}
