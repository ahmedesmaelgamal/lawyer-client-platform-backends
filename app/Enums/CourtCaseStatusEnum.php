<?php

namespace App\Enums;

enum CourtCaseStatusEnum: string
{

    case NEW = 'new';
    case OFFERED = 'offered';
    case ACCEPTED = 'accepted';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case PRIVATE = 'private';// new but for a specific lawyer


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang() {
        return match ($this) {
            self::NEW => trns('new'),
            self::OFFERED => trns('offered'),
            self::ACCEPTED => trns('accepted'),
            self::CANCELLED => trns('cancelled'),
            self::COMPLETED => trns('completed'),
            self::PRIVATE => trns('private'),
        };
    }
}

