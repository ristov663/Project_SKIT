<?php

namespace App\Observers;

use App\Enums\ReservationStatusEnum;
use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "creating" event.
     */
    public function creating(Reservation $reservation): void
    {
        $reservation->status = ReservationStatusEnum::PENDING;
    }
}
