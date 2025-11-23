<?php

namespace App\Enums;

enum OrderStatusEnum : string
{
    case NEW = 'new';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::NEW => trns('new'),
            self::COMPLETED => trns('completed'),
            self::CANCELED => trns('canceled'),
        };
    }
}
