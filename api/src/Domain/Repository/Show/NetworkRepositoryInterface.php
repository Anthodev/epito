<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Network;

/**
 * @method ?Network  find(string $id)
 * @method Network[] findAll()
 * @method Network[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Network  findOneBy(array<string, mixed> $criteria)
 * @method void      save(Network $network)
 * @method void      update(Network $network)
 * @method void      delete(Network $network)
 * @method void      refresh(Network $network)
 * @method void      rollback()
 */
interface NetworkRepositoryInterface
{
}
