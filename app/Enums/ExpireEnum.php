<?php

namespace App\Enums;

enum ExpireEnum: string
{
    case EXPIRED = 'expired';
    case ONGOING = 'ongoing';

    /**
     * Returns an array of the values of all cases of the enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Translates the current enum case to its corresponding language string.
     *
     * @return \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
     *         The translated string for the current enum case.
     */

/******  7e38438c-f7b9-4a42-93ae-c2ef42e8b763  *******/
    public function lang(): \Illuminate\Foundation\Application|array|string|\Illuminate\Contracts\Translation\Translator|\Illuminate\Contracts\Foundation\Application|null
    {
        return match ($this) {
            self::EXPIRED => trns('expired'),
            self::ONGOING => trns('ongoing'),
        };
    }

}

