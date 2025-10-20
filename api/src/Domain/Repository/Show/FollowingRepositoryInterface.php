<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Following;

/**
 * @method ?Following  find(string $id)
 * @method Following[] findAll()
 * @method Following[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Following  findOneBy(array<string, mixed> $criteria)
 * @method void        save(Following $following)
 * @method void        update(Following $following)
 * @method void        delete(Following $following)
 * @method void        refresh(Following $following)
 * @method void        rollback()
 */
interface FollowingRepositoryInterface
{
}
