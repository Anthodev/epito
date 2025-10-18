<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository;

use App\Domain\Model\Show\Season;
use App\Domain\Repository\Show\EpisodeRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineSeasonRepository extends DoctrineBaseEntityRepository implements EpisodeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }
}
