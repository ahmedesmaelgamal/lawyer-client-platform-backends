<?php

namespace App\Enums;

enum EventStatusEnum: string
{
    case OFFER = 'offer';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case FINISHED = 'finished';
    case TRANSFERRED = 'transferred';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang()
    {
        return match ($this) {
            self::OFFER => trns('offer'),
            self::ACCEPTED => trns('accepted'),
            self::REJECTED => trns('rejected'),
            self::CANCELLED => trns('cancelled'),
            self::FINISHED => trns('finished'),
            self::TRANSFERRED => trns('transferred'),
        };
    }
}
