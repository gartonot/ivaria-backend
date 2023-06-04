<?php

namespace App\Listeners\Notifications;

use App\Events\ReservationCreated;
use App\NotificationManager;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAboutReservationCreated implements ShouldQueue
{
    public $queue = 'default';

    public function handle(ReservationCreated $event)
    {
        app(NotificationManager::class)->notifyAboutReservationCreated($event->reservation);
    }
}
