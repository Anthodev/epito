<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Enum\ShowStatusEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class ShowStatusEnumType extends Type
{
    public const string NAME = "ShowStatusEnum";

    public function getSQLDeclaration(
        array $column,
        AbstractPlatform $platform,
    ): string {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToPHPValue(
        mixed $value,
        AbstractPlatform $platform,
    ): ?ShowStatusEnum {
        if (!is_string($value)) {
            return null;
        }

        return ShowStatusEnum::from((string) $value);
    }

    public function convertToDatabaseValue(
        mixed $value,
        AbstractPlatform $platform,
    ): mixed {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof ShowStatusEnum) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Expected instance of %s, got %s",
                    ShowStatusEnum::class,
                    gettype($value),
                ),
            );
        }

        return $value->value;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
