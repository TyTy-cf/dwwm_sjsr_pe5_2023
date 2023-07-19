<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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

        $this->save($user, true);
    }

    public function getQb(): QueryBuilder {
        return $this->createQueryBuilder('u')
            ->select('u', 'c', 'user_own_games', 'game')
            ->leftJoin('u.userOwnGames', 'user_own_games')
            ->leftJoin('user_own_games.game', 'game')
            ->leftJoin('u.country', 'c');
    }

    public function getQbAll(): QueryBuilder {
        return $this->createQueryBuilder('u')
            ->select('u', 'c', 'user_own_games', 'game', 'r', 'rgame')
            ->leftJoin('u.userOwnGames', 'user_own_games')
            ->leftJoin('user_own_games.game', 'game')
            ->leftJoin('u.reviews', 'r')
            ->leftJoin('r.game', 'rgame')
            ->leftJoin('u.country', 'c');
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneFullBy(string $name): ?User
    {
        return $this->getQbAll()
            ->where('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllFull(): array
    {
        return $this->getQb()
            ->getQuery()
            ->getResult();
    }
}
