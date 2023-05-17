<?php

namespace App\Events;

use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Queue\SerializesModels;

class ReservationCreated
{
    use SerializesModels;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }
}
