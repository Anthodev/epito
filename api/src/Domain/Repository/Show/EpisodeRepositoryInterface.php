<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Episode;

/**
 * @method ?Episode  find(string $id)
 * @method Episode[] findAll()
 * @method Episode[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Episode  findOneBy(array<string, mixed> $criteria)
 * @method void      save(Episode $episode)
 * @method void      update(Episode $episode)
 * @method void      delete(Episode $episode)
 * @method void      refresh(Episode $episode)
 * @method void      rollback()
 */
interface EpisodeRepositoryInterface
{
}
