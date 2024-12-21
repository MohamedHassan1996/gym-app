<?php

namespace App\Enums\Task;

enum TaskImportanceStatus: int{


    case GREEN = 0;
    case YELLOW = 1;
    case RED = 2;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
