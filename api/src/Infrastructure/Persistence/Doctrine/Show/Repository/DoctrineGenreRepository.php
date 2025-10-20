<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository;

use App\Domain\Model\Show\Genre;
use App\Domain\Repository\Show\GenreRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGenreRepository extends DoctrineBaseEntityRepository implements GenreRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }
}
