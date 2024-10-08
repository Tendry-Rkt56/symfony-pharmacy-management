<?php

namespace App\Repository;

use App\Entity\Vente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vente>
 */
class VenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vente::class);
    }

    public function getSommes ()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT SUM(total) FROM (SELECT total FROM vente ORDER BY id DESC LIMIT 5)";
        $result = $conn->prepare($sql);
        return $result->executeQuery();
    }

    public function getSomme()
    {
        return $this->createQueryBuilder("v")
                    ->select("v.total")
                    ->orderBy("v.id", "DESC")
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult();
    }

    public function getLastVente()
    {
        return $this->createQueryBuilder("v")
                    ->select("v", "u")
                    ->innerJoin("v.user", 'u')
                    ->orderBy("v.id", "DESC")
                    ->setMaxResults(5)
                    ->getQuery()
                    ->getResult();

    }

    //    /**
    //     * @return Vente[] Returns an array of Vente objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Vente
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
