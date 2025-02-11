<?php

namespace App\Models;

use App\Enums\ReservationStatusEnum;
use App\Observers\EventObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(EventObserver::class)]
class Event extends Model
{
    use HasFactory;

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function confirmedReservations(): HasMany
    {
        return $this->reservations()
            ->where('status', ReservationStatusEnum::CONFIRMED);
    }
}
