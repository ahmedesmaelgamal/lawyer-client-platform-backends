<?php

namespace App\Enums;

enum UserTypeEnum : string
{
    case CLIENT = 'client';
    case LAWYER = 'lawyer';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::CLIENT => trns('client'),
            self::LAWYER => trns('lawyer'),
        };
    }
}
