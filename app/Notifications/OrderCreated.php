<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(MailMessage::class)->subject('Order Created')->view('notifications.order-created', [
            'phone' => $this->order->phone,
            'user' => $this->order->username,
            'orderId' => $this->order->id,
            'dishes' => $this->order->dishes,
            'totalPrice' => $this->order->totalPrice(),
        ]);
    }
}
