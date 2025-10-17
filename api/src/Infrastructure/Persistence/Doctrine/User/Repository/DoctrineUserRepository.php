<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\User\Repository;

use App\Domain\Model\User\User;
use App\Domain\Repository\User\UserRepositoryInterface;
use App\Infrastructure\Persistence\Doctrine\Common\DoctrineBaseEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineUserRepository extends DoctrineBaseEntityRepository implements
    UserLoaderInterface,
    UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[\Override]
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        $query = $this->createQueryBuilder("u")
            ->where("u.email = :email")
            ->andWhere("u.enabled = true")
            ->setParameter("email", $identifier)
            ->getQuery();

        /** @var UserInterface|null */
        return $query->getOneOrNullResult();
    }
}
