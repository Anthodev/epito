<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiPlatform\UseCase\Show;

use App\Domain\Factory\ApiShow\ApiShowFactory;
use App\Domain\Model\ApiShow\ApiShow;
use App\Infrastructure\Client\ApiInterface;
use App\Infrastructure\Enum\TvmazeRoutesEnum;
use App\Infrastructure\Exception\ShowsNotFoundException;
use App\Shared\Dto\Client\ApiGetSearchShowResponseDto;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GetShowByNameUseCase
{
    public function __construct(
        private ApiInterface $api,
        private SerializerInterface&DenormalizerInterface $serializer,
    ) {
    }

    /**
     * @return ApiShow[]
     */
    public function execute(string $name): array
    {
        try {
            /** @var array<int, array<string, mixed>> $responseData */
            $responseData = $this->api->performRequest(
                $name,
                TvmazeRoutesEnum::GET_SEARCH_SHOWS->value,
            );
        } catch (\Exception) {
            throw new ShowsNotFoundException();
        }

        $denormalizedData = $this->formatGetSearchShowsResponse($responseData);

        /** @var ApiShow[] */
        $result = [];

        /** @var ApiGetSearchShowResponseDto $item */
        foreach ($denormalizedData as $item) {
            $result[] = ApiShowFactory::create(
                name: $item->name,
                type: $item->type,
                language: $item->language,
                status: $item->status,
                summary: $item->summary,
                averageRuntime: $item->averageRuntime,
                premiered: $item->premiered
                    ? new \DateTimeImmutable($item->premiered)
                    : null,
                ended: $item->ended
                    ? new \DateTimeImmutable($item->ended)
                    : null,
                image: $item->image,
                genres: $item->genres,
                theTvDbId: $item->theTvDbId,
                imdbId: $item->imdbId,
                episodes: [],
            );
        }

        return $result;
    }

    /**
     * @param array<int, array<string, mixed>> $responseData
     *
     * @return ApiGetSearchShowResponseDto[]
     */
    private function formatGetSearchShowsResponse(array $responseData): array
    {
        $responseShowData = [];

        foreach ($responseData as $data) {
            if (!isset($data['show'])) {
                continue;
            }

            $responseShowData[] = $data['show'];
        }

        /** @var ApiGetSearchShowResponseDto[] */
        return $this->serializer->denormalize(
            $responseShowData,
            ApiGetSearchShowResponseDto::class.'[]',
        );
    }
}
