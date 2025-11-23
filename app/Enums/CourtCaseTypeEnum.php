<?php

namespace App\Enums;

enum CourtCaseTypeEnum: string
{

    case LEGAL_ADVICE = 'legal_advice';
    case ADVOCACY = 'advocacy';
    case DEFAULT = 'default';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang() {
        return match ($this) {
            self::LEGAL_ADVICE => trns('LEGAL_ADVICE'),
            self::ADVOCACY => trns('ADVOCACY'),
            self::DEFAULT => trns('DEFAULT'),
        };
    }
}

