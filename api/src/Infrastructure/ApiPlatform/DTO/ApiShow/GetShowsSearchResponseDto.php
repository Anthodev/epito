<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiPlatform\DTO\ApiShow;

use App\Shared\Enum\ShowStatusEnum;

final class GetShowsSearchResponseDto
{
    public function __construct(
        public string $name,
        public string $summary,
        public string $type,
        public string $language,
        public ShowStatusEnum $status,
        public ?int $id = null,
        public ?int $averageRuntime = null,
        public ?\DateTimeImmutable $premiered = null,
        public ?\DateTimeImmutable $ended = null,
        public ?string $image = null,
        /** @var string[] $genres */
        public array $genres = [],
        public ?int $theTvDbId = null,
        public ?string $imdbId = null,
    ) {
    }
}
