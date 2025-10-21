<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer\Client;

use App\Shared\Dto\Client\ApiGetSearchShowResponseDto;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiGetSearchShowResponseDtoArrayNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    /**
     * @param array<ApiGetSearchShowResponseDto> $data
     * @param array<string, mixed>               $context
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws ExceptionInterface
     */
    public function normalize(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): array {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('The object must be an array');
        }

        $result = [];
        foreach ($data as $item) {
            if ($item instanceof ApiGetSearchShowResponseDto) {
                $result[] = $this->normalizer->normalize(
                    $item,
                    $format,
                    $context,
                );
            }
        }

        /** @var array<int, array<string, mixed>> */
        return $result;
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(
        mixed $data,
        ?string $format = null,
        array $context = [],
    ): bool {
        if (!is_array($data) || empty($data)) {
            return false;
        }

        foreach ($data as $item) {
            if (!$item instanceof ApiGetSearchShowResponseDto) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<int, ApiGetSearchShowResponseDto>
     *
     * @throws ExceptionInterface
     */
    public function denormalize(
        mixed $data,
        string $type,
        ?string $format = null,
        array $context = [],
    ): array {
        if (!is_array($data)) {
            return [];
        }

        $result = [];

        foreach ($data as $item) {
            if (is_array($item)) {
                $result[] = $this->denormalizer->denormalize(
                    $item,
                    ApiGetSearchShowResponseDto::class,
                    $format,
                    $context,
                );
            }
        }

        /** @var array<int, ApiGetSearchShowResponseDto> */
        return $result;
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
        return is_array($data)
            && ('array' === $type
                || 'array<ApiGetSearchShowResponseDto>' === $type);
    }

    /**
     * @return array<string, bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            'array' => true,
        ];
    }
}
