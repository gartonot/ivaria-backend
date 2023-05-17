<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(MailMessage::class)->subject('Reservation Created')->view('notifications.reservation-created', [
            'id' => $this->reservation->id,
            'name' => $this->reservation->name,
            'phone' => $this->reservation->phone,
            'guests' => $this->reservation->guests,
            'status' => $this->reservation->status,
            'date' => $this->reservation->date,
            'created_at' => $this->reservation->created_at,
        ]);
    }
}