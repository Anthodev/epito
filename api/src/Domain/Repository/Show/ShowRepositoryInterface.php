<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Show;

/**
 * @method ?Show  find(string $id)
 * @method Show[] findAll()
 * @method Show[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Show  findOneBy(array<string, mixed> $criteria)
 * @method void   save(Show $show)
 * @method void   update(Show $show)
 * @method void   delete(Show $show)
 * @method void   refresh(Show $show)
 * @method void   rollback()
 */
interface ShowRepositoryInterface
{
}
