<?php

namespace App\Enums\Task;

enum TaskTestingStatus: int{

    case NOT_TESTED = 0;
    case TESTED = 1;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
