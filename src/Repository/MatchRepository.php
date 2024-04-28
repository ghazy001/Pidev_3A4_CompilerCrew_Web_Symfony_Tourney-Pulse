<?php

namespace App\Repository;

use App\Entity\MatchEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MatchEntity>
 *
 * @method MatchEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MatchEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MatchEntity[]    findAll()
 * @method MatchEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchEntity::class);
    }

    /**
     * Find match by search term.
     *
     * @param string $searchTerm The search term
     * @return array The matching tournois
     */
    public function findBySearchTerm(string $searchTerm): array
    {
        // Implement your search logic here, for example:
        return $this->createQueryBuilder('m')
            ->andWhere('m.nomMatch LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    public function Nbrmatchs(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(m.idMatch) AS total FROM App\Entity\MatchEntity m'
        );

        $total = $query->getSingleScalarResult(); // Utilisez getSingleScalarResult() pour obtenir directement le résultat du compte

        return (int) $total; // Convertissez le résultat en entier
    }

    public function countMatchByDate()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT SUBSTRING(m.dateMatch,1,4) AS matchDate, COUNT(m.idMatch) AS matchCount 
         FROM App\Entity\MatchEntity m 
         GROUP BY matchDate 
         ORDER BY matchDate'
        );

        return $query->getResult();
    }

//    /**
//     * @return MatchEntity[] Returns an array of MatchEntity objects
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

//    public function findOneBySomeField($value): ?MatchEntity
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
