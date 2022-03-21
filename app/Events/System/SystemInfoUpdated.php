<?php

namespace App\Events\System;

use App\System;
use Illuminate\Support\Facades\Cache;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class SystemInfoUpdated
{
    use Dispatchable, SerializesModels;

    public $system;

    /**
     * Create a new job instance.
     *
     * @param  System  $system
     * @return void
     */
    public function __construct(System $system)
    {
        // Clear system_settings from cache
        Cache::forget('system_settings');
        Cache::forget('system_timezone');
        Cache::forget('system_currency');

        $this->system = $system;
    }
}
