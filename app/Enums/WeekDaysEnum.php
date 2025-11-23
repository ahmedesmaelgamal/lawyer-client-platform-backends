<?php

namespace App\Enums;

enum WeekDaysEnum : string
{
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::SATURDAY => trns('saturday'),
            self::SUNDAY => trns('sunday'),
            self::MONDAY => trns('monday'),
            self::TUESDAY => trns('tuesday'),
            self::WEDNESDAY => trns('wednesday'),
            self::THURSDAY => trns('thursday'),
            self::FRIDAY => trns('friday'),
            default => trns('saturday'),
        };
    }
}
