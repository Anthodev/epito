<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Client;

use App\Shared\Dto\Client\ApiGetSearchShowResponseDto;
use App\Shared\Enum\ShowStatusEnum;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiGetSearchShowResponseDtoNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize(
        mixed $object,
        ?string $format = null,
        array $context = [],
    ): array {
        if (!$object instanceof ApiGetSearchShowResponseDto) {
            throw new \InvalidArgumentException('The object must be an instance of TvmazeGetSearchShowResponseDto');
        }

        return [
            'id' => $object->id,
            'name' => $object->name,
            'type' => $object->type,
            'language' => $object->language,
            'genres' => $object->genres,
            'status' => $object->status->value,
            'summary' => $object->summary,
            'averageRuntime' => $object->averageRuntime,
            'premiered' => $object->premiered,
            'ended' => $object->ended,
            'network' => $object->network,
            'image' => $object->image,
            'theTvDbId' => $object->theTvDbId,
            'imdbId' => $object->imdbId,
        ];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): bool {
        return $data instanceof ApiGetSearchShowResponseDto;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): ApiGetSearchShowResponseDto {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data is not an array');
        }

        $id = isset($data['id']) && is_int($data['id']) ? $data['id'] : 0;
        $name =
            isset($data['name']) && is_string($data['name'])
                ? $data['name']
                : '';
        $type =
            isset($data['type']) && is_string($data['type'])
                ? $data['type']
                : '';
        $language =
            isset($data['language']) && is_string($data['language'])
                ? $data['language']
                : '';
        /** @var string[] $genres */
        $genres =
            isset($data['genres']) && is_array($data['genres'])
                ? $data['genres']
                : [];
        /** @var ShowStatusEnum $status */
        $status =
            isset($data['status']) && is_string($data['status'])
                ? ShowStatusEnum::tryFrom(strtolower($data['status']))
                : ShowStatusEnum::IN_DEVELOPMENT;
        $summary =
            isset($data['summary']) && is_string($data['summary'])
                ? $data['summary']
                : '';
        $averageRuntime =
            isset($data['averageRuntime']) && is_int($data['averageRuntime'])
                ? $data['averageRuntime']
                : null;
        $premiered =
            isset($data['premiered']) && is_string($data['premiered'])
                ? $data['premiered']
                : new \DateTime()->format(\DateTime::ATOM);
        $ended =
            isset($data['ended']) && is_string($data['ended'])
                ? $data['ended']
                : null;
        $network =
            isset($data['network']) && is_string($data['network']['name'])
                ? $data['network']['name']
                : null;
        $image =
            isset($data['image']) && is_string($data['image']['original'])
                ? $data['image']['original']
                : null;
        $theTvDbId =
            isset($data['externals']['thetvdb']) && is_int($data['externals']['thetvdb'])
                ? $data['externals']['thetvdb']
                : null;
        $imdbId =
            isset($data['externals']['imdb']) && is_string($data['externals']['imdb'])
                ? $data['externals']['imdb']
                : null;

        return new ApiGetSearchShowResponseDto(
            id: $id,
            name: $name,
            type: $type,
            language: $language,
            genres: $genres,
            status: $status,
            summary: $summary,
            averageRuntime: $averageRuntime,
            premiered: $premiered,
            ended: $ended,
            network: $network,
            image: $image,
            theTvDbId: $theTvDbId,
            imdbId: $imdbId,
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsDenormalization(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): bool {
        return ApiGetSearchShowResponseDto::class === $type
            || "App\Shared\Dto\Client\ApiGetSearchShowResponseDto" === $type;
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            ApiGetSearchShowResponseDto::class => true,
        ];
    }
}
