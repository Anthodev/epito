<?php

namespace App\Shared\Enum;

enum ShowStatusEnum: string
{
    case IN_DEVELOPMENT = "in_development";
    case RUNNING = "running";
    case ENDED = "ended";
    case CANCELED = "canceled";
}
