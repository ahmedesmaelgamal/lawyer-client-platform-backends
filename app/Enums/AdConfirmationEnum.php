<?php

namespace App\Enums;

enum AdConfirmationEnum: string
{

    case REQUESTED = 'requested';
    case REJECTED = 'rejected';
    case CONFIRMED = 'confirmed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::REQUESTED => trns('requested'),
            self::REJECTED => trns('rejected'),
            self::CONFIRMED => trns('confirmed'),
        };
    }
}

