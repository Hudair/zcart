<?php

namespace App\Listeners\Mail;

use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSent;

class LogSentMessage
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
     * @param  InventoryLow  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        Log::info('........................... Message Sent Successfully .................................');
    }
}