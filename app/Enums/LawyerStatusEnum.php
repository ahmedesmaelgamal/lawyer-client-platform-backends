<?php

namespace App\Enums;

enum LawyerStatusEnum : string
{
    case INDIVIDUAL = 'individual';
    case OFFICE = 'office';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::INDIVIDUAL => trns('individual'),
            self::OFFICE => trns('office'),
        };
    }
}
