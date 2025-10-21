<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

class ShowsNotFoundException extends \InvalidArgumentException
{
    public function __construct(
        string $message = 'Shows not found',
        int $code = 404,
    ) {
        parent::__construct($message, $code);
    }
}
