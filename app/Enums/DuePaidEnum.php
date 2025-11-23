<?php

namespace App\Enums;

enum DuePaidEnum: int
{
    case UNPAID = 0;
    case PAID = 1;
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::UNPAID => trns('unpaid'),
            self::PAID => trns('paid'),
        };
    }
}
