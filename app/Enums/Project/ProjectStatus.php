<?php

namespace App\Enums\Project;

enum ProjectStatus: int{

    case TO_WORK = 0;

    case IN_PROGRESS = 1;

    case DONE = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
