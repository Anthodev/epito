<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository\DoctrineFollowingRepository;

use App\Domain\Model\Show\Following;
use App\Domain\Repository\Show\FollowingRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineFollowingRepository extends DoctrineBaseEntityRepository implements FollowingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Following::class);
    }
}
