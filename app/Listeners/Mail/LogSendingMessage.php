<?php

namespace App\Listeners\Mail;

use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Events\MessageSending;

class LogSendingMessage
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
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        Log::info('............................. Message Sending .....................................');
        Log::info(['subject' => isset($event->data['subject']) ? $event->data['subject'] : '']);
    }

}