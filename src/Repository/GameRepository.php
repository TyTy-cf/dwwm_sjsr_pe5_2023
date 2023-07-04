<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\UserOwnGame;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTendances(
        ?int $limit = null,
        bool $includeDate = false,
        DateTime $date = null
    ): array {
//        SELECT g.* FROM game g
        $qb = $this->createQueryBuilder('g')
//        JOIN user_own_game ON user_own_game.game_id = g.id
            ->join(
                UserOwnGame::class,
                'uog',
                Join::WITH,
                'uog.game = g'
            );

        if ($includeDate && $date !== null) {
//        WHERE user_own_game.created_at <= DATE_ADD(CURRENT_TIMESTAMP(), INTERVAL 1 MONTH)
            $qb->where('uog.createdAt >= :date')
                ->setParameter('date', $date);
        }
//        GROUP BY g.id
        return $qb->groupBy('g.id')
//        ORDER BY COUNT(*) DESC;
            ->orderBy('COUNT(g)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByRelatedCategory(Game $game, ?int $limit = null): array {
        return $this->createQueryBuilder('g')
            ->join('g.categories', 'c')
            ->where('c IN (:categories)')
            ->setParameter('categories', $game->getCategories())
            ->andWhere('g != :currentGame')
            ->setParameter('currentGame', $game)
            ->setMaxResults($limit)
            ->distinct(true) // Permet de bien prendre en compte la limite et d'ignorer le nombre instances
            ->getQuery()
            ->getResult();
    }

//        SELECT *
//        FROM game
//        JOIN game_category ON game_category.game_id = game.id
//        JOIN category ON game_category.category_id = category.id
//        WHERE category = $category;
    public function findByCategory(Category $category): array {
        return $this->createQueryBuilder('g')
            ->join('g.categories', 'categ')
            ->where('categ = :categ')
            ->setParameter('categ', $category)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findFullOneBy(string $slug): ?Game {
        return $this->createQueryBuilder('g')
            ->select('g', 'categ', 'r', 'c', 'u')
            ->leftJoin('g.categories', 'categ')
            ->leftJoin('g.reviews', 'r')
            ->leftJoin('r.user', 'u')
            ->leftJoin('g.countries', 'c')
            ->where('g.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
