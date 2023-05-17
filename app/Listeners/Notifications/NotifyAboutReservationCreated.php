<?php

namespace App\Listeners\Notifications;

use App\Channels\SlackChannel;
use App\Events\InvoiceCreated as InvoiceCreatedEvent;
use App\Events\OrderCreated;
use App\Events\ReservationCreated;
use App\NotificationManager;
use App\Notifications\InvoiceCreated;
use Clickadilla\Invoices\PaymentMethodTypes\Wire;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAboutReservationCreated implements ShouldQueue
{
    public $queue = 'default';

    public function handle(ReservationCreated $event)
    {
        app(NotificationManager::class)->notifyAboutReservationCreated($event->reservation);
    }
}
