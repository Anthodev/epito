<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Show\Show;

/**
 * @method ?Show  find(string $id)
 * @method Show[] findAll()
 * @method Show[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Show  findOneBy(array<string, mixed> $criteria)
 * @method void   save(Show $role)
 * @method void   update(Show $role)
 * @method void   delete(Show $role)
 * @method void   refresh(Show $role)
 * @method void   rollback()
 */
interface ShowRepositoryInterface {}
