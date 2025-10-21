<?php

declare(strict_types=1);

namespace App\Shared\Dto\Client;

readonly class TvmazeGetSearchShowResponseDto implements TvmazeResponseDtoInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $language,
        /** @var string[] $genres */
        public array $genres,
        public string $status,
        public string $summary,
        public ?int $averageRuntime = null,
        public ?string $premiered = null,
        public ?string $ended = null,
        public ?string $network = null,
        public ?string $image = null,
    ) {
    }
}
