<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use function Symfony\Component\String\u;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findNextUsers(int $afterId, int $limit, string $query = ""): array
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.id > :afterId')
            ->setParameter('afterId', $afterId)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults($limit)
        ;

        if ($query !== "")
        {
            $queryBuilder
                ->andWhere("u.username LIKE :query OR u.email LIKE :query")
                ->setParameter('query', '%' . $query . '%')
            ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function countByQuery(string $query = ""): int
    {
        $queryBuilder = $this->createQueryBuilder('u')->select('COUNT(u.id)');

        if ($query !== "")
        {
            $queryBuilder
                ->andWhere("u.username LIKE :query OR u.email LIKE :query")
                ->setParameter('query', '%' . $query . '%')
            ;
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

}
