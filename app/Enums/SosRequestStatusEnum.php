<?php

namespace App\Enums;

enum SosRequestStatusEnum : string
{
    case NEW = 'new';
    case ACCEPTED = 'accepted';
    case COMPLETED = 'completed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::NEW => trns('new'),
            self::ACCEPTED => trns('accepted'),
            self::COMPLETED => trns('completed'),
        };
    }
}
