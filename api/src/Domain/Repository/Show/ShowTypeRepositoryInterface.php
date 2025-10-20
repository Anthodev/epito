<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\ShowType;

/**
 * @method ?ShowType  find(string $id)
 * @method ShowType[] findAll()
 * @method ShowType[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?ShowType  findOneBy(array<string, mixed> $criteria)
 * @method void       save(ShowType $type)
 * @method void       update(ShowType $type)
 * @method void       delete(ShowType $type)
 * @method void       refresh(ShowType $type)
 * @method void       rollback()
 */
interface ShowTypeRepositoryInterface
{
}
