<?php

namespace App\Enums;

enum CurrencyEnum: string
{
    case USD = 'usd';
    case SUD = 'sud';
    case EGP = 'egp';
    case EUR = 'eur';
    case GBP = 'gbp';
    case SAR = 'sar';
    case AED = 'aed';
    case KWD = 'kwd';
    case QAR = 'qar';
    case MAD = 'mad';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::EGP => trns('egp'),
            self::EUR => trns('eur'),
            self::GBP => trns('gbp'),
            self::SAR => trns('sar'),
            self::AED => trns('aed'),
            self::KWD => trns('kwd'),
            default => trns('egp'),
        };
    }
}
