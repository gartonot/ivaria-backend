<?php

namespace App;

use App\Events\ReservationCreated;
use App\Models\Reservation;

class ReservationManager
{
    private $reservation;

    public function __construct(?Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function create(array $params): Reservation
    {
        $this->reservation = app(Reservation::class)->fill($params);
        $this->reservation->save();
        event(new ReservationCreated($this->reservation));

        return $this->reservation;
    }
}
