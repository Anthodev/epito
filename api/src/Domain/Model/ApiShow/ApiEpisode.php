<?php

declare(strict_types=1);

namespace App\Domain\Model\ApiShow;

class ApiEpisode
{
    public function __construct(
        public int $id,
        public string $title,
        public int $seasonNumber,
        public int $episodeNumber,
        public ?string $summary = null,
        public ?\DateTimeImmutable $airstamp = null,
        public ?int $runtime = null,
        public ?string $image = null,
    ) {
    }
}
