<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 *
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function save(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Review $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findReviewsBy(array $orderBy, int $limit = null): array {
        $qb = $this->createQueryBuilder('r')
            ->select('r', 'g', 'u')
            ->join('r.game', 'g')
            ->join('r.user', 'u');

        if (sizeof($orderBy) > 0) {
            foreach ($orderBy as $key => $order) {
                $qb->orderBy('r.' . $key, $order);
            }
        }

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param Game $game
     * @return QueryBuilder
     */
    public function getQbByGame(Game $game): QueryBuilder
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'u')
            ->join('r.game', 'g')
            ->join('r.user', 'u')
            ->where('g = :game')
            ->setParameter('game', $game)
            ->orderBy('r.createdAt', 'DESC');
    }

}
