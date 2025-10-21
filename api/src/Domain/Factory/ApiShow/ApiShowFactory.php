<?php

declare(strict_types=1);

namespace App\Domain\Factory\ApiShow;

use App\Domain\Model\ApiShow\ApiEpisode;
use App\Domain\Model\ApiShow\ApiShow;
use App\Shared\Enum\ShowStatusEnum;

readonly class ApiShowFactory
{
    /**
     * @param string[]     $genres
     * @param ApiEpisode[] $episodes
     */
    public static function create(
        string $name,
        string $summary,
        string $type,
        string $language,
        ShowStatusEnum $status,
        ?int $averageRuntime = null,
        ?\DateTimeImmutable $premiered = null,
        ?\DateTimeImmutable $ended = null,
        ?string $image = null,
        array $genres = [],
        ?int $theTvDbId = null,
        ?string $imdbId = null,
        array $episodes = [],
    ): ApiShow {
        return new ApiShow(
            name: $name,
            summary: $summary,
            type: $type,
            language: $language,
            status: $status,
            averageRuntime: $averageRuntime,
            premiered: $premiered,
            ended: $ended,
            image: $image,
            genres: $genres,
            theTvDbId: $theTvDbId,
            imdbId: $imdbId,
            episodes: $episodes,
        );
    }
}
