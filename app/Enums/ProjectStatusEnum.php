<?php

namespace App\Enums;

enum ProjectStatusEnum: int
{
    case NEW = 1;
    case STARTED = 2;
    case WAITING_REVIEW = 3;
    case REVIEWED = 4;
    case DEPLOYED = 5;
    case FINISHED = 6;
    case CANCELED = 7;
    case ON_HOLD = 8;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
