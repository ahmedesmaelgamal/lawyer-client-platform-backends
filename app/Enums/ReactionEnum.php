<?php

namespace App\Enums;

enum ReactionEnum : string
{
    case LIKE = 'like';
    case DISLIKE = 'dislike';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::LIKE => trns('like'),
            self::DISLIKE => trns('dislike'),
        };
    }
}
