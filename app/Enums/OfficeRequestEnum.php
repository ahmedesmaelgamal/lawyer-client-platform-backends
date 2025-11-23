<?php

namespace App\Enums;

enum OfficeRequestEnum: int
{
    case NEW = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::NEW => trns('new'),
            self::ACCEPTED => trns('accepted'),
            self::REJECTED => trns('rejected'),
        };
    }
}
