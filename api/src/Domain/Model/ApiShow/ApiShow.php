<?php

declare(strict_types=1);

namespace App\Domain\Model\ApiShow;

use App\Shared\Enum\ShowStatusEnum;

class ApiShow
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $language,
        public ShowStatusEnum $status,
        public ?int $averageRuntime = null,
        public ?\DateTimeImmutable $premiered = null,
        public ?\DateTimeImmutable $ended = null,
        public ?string $image = null,
        /** @var string[] $genres */
        public array $genres = [],
        public ?int $theTvDbId = null,
        public ?string $imdbId = null,
        /** @var ApiEpisode[] $episodes */
        public array $episodes = [],
    ) {
    }
}
