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

    public function findNextUsers(
        int $offset,
        int $limit,
        string $query = "",
        string $role = "",
        string $sortBy = "id",
        string $sortDir = "ASC"
    ): array
    {
        $allowedSorts = [
            'id' => 'u.id',
            'username' => 'u.username',
            'email' => 'u.email',
            'age' => 'u.age',
        ];
        $sortColumn = $allowedSorts[$sortBy] ?? 'u.id';
        $sortDir = strtoupper($sortDir) === 'DESC' ? 'DESC' : 'ASC';

        $queryBuilder = $this->createQueryBuilder('u')
            ->orderBy($sortColumn, $sortDir)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if ($query !== "")
        {
            $queryBuilder->andWhere('u.username LIKE :query OR u.email LIKE :query')
                ->setParameter('query', '%' . $query . '%');
        }

        if ($role !== "")
        {
            $queryBuilder->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%"' . $role . '"%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function countByQuery(string $query = "", string $role = ""): int
    {
        $queryBuilder = $this->createQueryBuilder('u')->select('COUNT(u.id)');

        if ($query !== "")
        {
            $queryBuilder
                ->andWhere("u.username LIKE :query OR u.email LIKE :query")
                ->setParameter('query', '%' . $query . '%')
            ;
        }

        if ($role !== "") {
            $queryBuilder
                ->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%"' . $role . '"%');
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

}
