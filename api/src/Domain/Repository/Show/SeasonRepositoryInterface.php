<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Season;

/**
 * @method ?Season  find(string $id)
 * @method Season[] findAll()
 * @method Season[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Season  findOneBy(array<string, mixed> $criteria)
 * @method void     save(Season $season)
 * @method void     update(Season $season)
 * @method void     delete(Season $season)
 * @method void     refresh(Season $season)
 * @method void     rollback()
 */
interface SeasonRepositoryInterface
{
}
