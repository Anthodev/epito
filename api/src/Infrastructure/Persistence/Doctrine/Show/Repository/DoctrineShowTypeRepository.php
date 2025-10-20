<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository;

use App\Domain\Model\Show\ShowType;
use App\Domain\Repository\Show\ShowTypeRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineShowTypeRepository extends DoctrineBaseEntityRepository implements ShowTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShowType::class);
    }
}
