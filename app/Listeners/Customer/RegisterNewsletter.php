<?php

namespace App\Listeners\Customer;

use Newsletter;
use App\SystemConfig;
use App\Events\Customer\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterNewsletter implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->customer->accepts_marketing && SystemConfig::isNewsletterConfigured()) {
            Newsletter::subscribeOrUpdate($event->customer->email);
        }
    }
}
