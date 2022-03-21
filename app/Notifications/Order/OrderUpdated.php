<?php

namespace App\Notifications\Order;


use App\Order;
use App\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Push\HasNotifications;
use Illuminate\Notifications\Messages\MailMessage;

class OrderUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->order->device_id !== null) {
            HasNotifications::pushNotification(self::toArray($notifiable));
        }

        if ($notifiable instanceof Customer) {
            return ['mail', 'database'];
        }

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->from(get_sender_email($this->order->shop_id), get_sender_name($this->order->shop_id))
        ->subject( trans('notifications.order_updated.subject', ['order' => $this->order->order_number]) )
        ->markdown('admin.mail.order.updated', ['url' => url('/'), 'order' => $this->order]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'order' => $this->order->order_number,
            'device_id' => $this->order->device_id,
            'status' => $this->order->orderStatus(true),
            'subject' => trans('notifications.order_updated.subject', ['order' => $this->order->order_number]),
            'message' => trans('notifications.order_updated.message', ['order' => $this->order->order_number]),
        ];
    }
}
