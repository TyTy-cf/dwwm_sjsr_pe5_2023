<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use App\Entity\UserOwnGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function save(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Effectue une recherche de type "LIKE %_%" parmi les noms de catégories
     *
     * @param string $name le nom à rechercher parmi les catégories
     * @return array
     */
    public function findAllByApproxName(string $name): array {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getArrayResult();
    }

    public function getMostSoldCategories(int $limit = 3): array
    {
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult(Category::class, 'c');
        $rsm->addFieldResult('c', 'id', 'id');
        $rsm->addFieldResult('c', 'name', 'name');
        $rsm->addFieldResult('c', 'slug', 'slug');

        return $this->_em
            ->createNativeQuery(
                'SELECT c.*
                FROM category c
                JOIN game_category ON game_category.category_id = c.id
                JOIN user_own_game ON user_own_game.game_id = game_category.game_id
                GROUP BY c.id
                ORDER BY COUNT(*) DESC
                LIMIT ?',
                $rsm
            )
            ->setParameter(1, $limit)
            ->getResult();
    }

//        return $this->createQueryBuilder('c')
//            ->select('c')
//            ->join('c.games', 'games')
//            ->join(
//                UserOwnGame::class,
//                'uog',
//                Join::WITH,
//                'uog.game = games'
//            )
//            ->groupBy('c.id')
//            ->orderBy('COUNT(c)', 'DESC')
//            ->setMaxResults($nb)
//            ->getQuery()
//            ->getResult();
//    }

}
