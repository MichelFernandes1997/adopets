<?php

namespace App\Listeners;

use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserActionsInProducts;

class LogActionsForProducts
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserActionsInProducts  $event
     * @return void
     */
    public function handle(UserActionsInProducts $event)
    {
        Log::channel('info')->info($event->actionMessage);
    }
}
