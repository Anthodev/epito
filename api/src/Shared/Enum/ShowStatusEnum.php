<?php

namespace App\Shared\Enum;

enum ShowStatusEnum: string
{
    case TO_BE_DETERMINED = 'to_be_determined';
    case IN_DEVELOPMENT = 'in_development';
    case RUNNING = 'running';
    case ENDED = 'ended';
    case CANCELED = 'canceled';
}
