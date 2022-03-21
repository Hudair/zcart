<?php

namespace App\Listeners\Shop;

use App\User;
use App\Shop;
use App\Events\Shop\ShopDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Shop\ShopDeleted as ShopDeletedNotification;

class NotifyMerchantShopDeleted implements ShouldQueue
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
     * @param  ShopDeleted  $event
     * @return void
     */
    public function handle(ShopDeleted $event)
    {
        $shop = Shop::withTrashed()->find($event->shop_id);
        $merchant = User::withTrashed()->find($shop->owner_id);

        $merchant->notify(new ShopDeletedNotification());
    }
}
