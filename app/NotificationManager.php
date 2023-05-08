<?php

namespace App;

use App\Models\Order;
use App\Notifications\OrderCreated;
use Illuminate\Support\Facades\Notification;

class NotificationManager
{
    public function notifyAboutOrderCreated(Order $order)
    {
        Notification::route('mail', config('mail.to.address'))->notify(new OrderCreated($order));
    }
}
