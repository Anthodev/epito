<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Show\Repository;

use App\Domain\Model\Show\Episode;
use App\Domain\Repository\Show\EpisodeRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineEpisodeRepository extends DoctrineBaseEntityRepository implements EpisodeRepositoryInterface
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Episode::class);
    }
}
