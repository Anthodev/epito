<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository;

use App\Domain\Model\Show\Network;
use App\Domain\Repository\Show\NetworkRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineNetworkRepository extends DoctrineBaseEntityRepository implements NetworkRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Network::class);
    }
}
