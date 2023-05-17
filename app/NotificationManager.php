<?php

namespace App;

use App\Models\Order;
use App\Models\Reservation;
use App\Notifications\OrderCreated;
use App\Notifications\ReservationCreated;
use Illuminate\Support\Facades\Notification;

class NotificationManager
{
    public function notifyAboutOrderCreated(Order $order)
    {
        Notification::route('mail', config('mail.to.address'))->notify(new OrderCreated($order));
    }

    public function notifyAboutReservationCreated(Reservation $reservation)
    {
        Notification::route('mail', config('mail.to.address'))->notify(new ReservationCreated($reservation));
    }
}
