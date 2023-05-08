<?php

namespace App\Listeners\Notifications;

use App\Channels\SlackChannel;
use App\Events\InvoiceCreated as InvoiceCreatedEvent;
use App\Events\OrderCreated;
use App\NotificationManager;
use App\Notifications\InvoiceCreated;
use Clickadilla\Invoices\PaymentMethodTypes\Wire;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAboutOrderCreated implements ShouldQueue
{
    public $queue = 'default';

    public function handle(OrderCreated $event)
    {
        app(NotificationManager::class)->notifyAboutOrderCreated($event->order);
    }
}
