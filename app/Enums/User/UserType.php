<?php

namespace App\Enums\User;

enum UserType: int{

    case ADMIN = 1;
    case CLIENT = 0;
    case TRAINER = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
