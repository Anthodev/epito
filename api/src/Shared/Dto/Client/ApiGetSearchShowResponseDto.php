<?php

declare(strict_types=1);

namespace App\Shared\Dto\Client;

use App\Shared\Enum\ShowStatusEnum;

readonly class ApiGetSearchShowResponseDto implements ApiResponseDtoInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $language,
        /** @var string[] $genres */
        public array $genres,
        public ShowStatusEnum $status,
        public string $summary,
        public ?int $averageRuntime = null,
        public ?string $premiered = null,
        public ?string $ended = null,
        public ?string $network = null,
        public ?string $image = null,
        public ?int $theTvDbId = null,
        public ?string $imdbId = null,
    ) {
    }
}
