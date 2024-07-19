<?php

namespace App\Repository;

use App\Entity\Medicament;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medicament>
 */
class MedicamentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medicament::class);
    }

    public function getWithSearch(string $search)
    {
        return $this->createQueryBuilder("m")
                ->where('m.nom LIKE :search')
                ->setParameter("search", '%'.$search.'%')
                ->getQuery()
                ->getResult();
    }

    public function getAll (int $offset, int $limit, string $search, int $category)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m', 'c')
            ->leftJoin('m.category', 'c')
            ->where('m.nom LIKE :search')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameter('search', '%'.$search.'%');
        
        if ($category != 1000) {
            $qb->andWhere('c.id = :category')
               ->setParameter('category', $category);
        }
        return new Paginator(
                $qb->getQuery()->setHint(Paginator::HINT_ENABLE_DISTINCT, true)
        );
    }

    //    /**
    //     * @return Medicament[] Returns an array of Medicament objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Medicament
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
