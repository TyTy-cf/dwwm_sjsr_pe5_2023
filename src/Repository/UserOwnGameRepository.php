<?php

namespace App\Repository;

use App\Entity\UserOwnGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserOwnGame>
 *
 * @method UserOwnGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserOwnGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserOwnGame[]    findAll()
 * @method UserOwnGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserOwnGameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserOwnGame::class);
    }

    public function save(UserOwnGame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserOwnGame $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTotalBenefit(): int
    {
        return $this->createQueryBuilder('uog')
            ->select('SUM(g.price)')
            ->join('uog.game', 'g')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBenefitForLastYear(int $limit = 4): array
    {
        return $this->createQueryBuilder('uog')
            ->select('SUM(g.price) as total', 'YEAR(uog.createdAt) as year')
            ->join('uog.game', 'g')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
