<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Shared\Dto\Client\ApiResponseDtoInterface;

interface ApiInterface
{
    /** @return ApiResponseDtoInterface[] */
    public function performRequest(string $query, string $request): array;
}
