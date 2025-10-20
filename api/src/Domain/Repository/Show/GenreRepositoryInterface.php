<?php

declare(strict_types=1);

namespace App\Domain\Repository\Show;

use App\Domain\Model\Show\Genre;

/**
 * @method ?Genre  find(string $id)
 * @method Genre[] findAll()
 * @method Genre[] findBy(array<string, mixed> $criteria, array<string, mixed> $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method ?Genre  findOneBy(array<string, mixed> $criteria)
 * @method void    save(Genre $genre)
 * @method void    update(Genre $genre)
 * @method void    delete(Genre $genre)
 * @method void    refresh(Genre $genre)
 * @method void    rollback()
 */
interface GenreRepositoryInterface
{
}
