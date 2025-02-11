<?php

namespace App\Enums;

enum ReservationStatusEnum : string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
