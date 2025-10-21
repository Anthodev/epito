<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

class ApiRequestParseException extends \Exception
{
    public function __construct(string $request, int $code = 0)
    {
        $message = sprintf('Failed to parse request: %s', $request);
        parent::__construct($message, $code);
    }
}
