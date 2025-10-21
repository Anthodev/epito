<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

abstract class AbstractApiClient
{
    /** @return array<string, mixed> */
    abstract protected function makeRequest(string $httpMethod, string $endpoint): array;

    /** @return array<string, mixed> */
    abstract protected function makeRequestWithRetry(string $httpMethod, string $endpoint, int $maxRetries = 3): array;
}
