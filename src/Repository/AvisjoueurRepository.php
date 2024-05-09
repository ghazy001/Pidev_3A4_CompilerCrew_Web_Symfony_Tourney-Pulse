<?php

namespace App\Repository;

use App\Entity\Avisjoueur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avisjoueur>
 *
 * @method Avisjoueur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avisjoueur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avisjoueur[]    findAll()
 * @method Avisjoueur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisjoueurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avisjoueur::class);
    }

    public function Nbravis(): int
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT count(avis.idAvis) AS total
         FROM App\Entity\Avisjoueur avis'
        );

        $avis = $query->getSingleScalarResult(); // Use getSingleScalarResult() to get the count directly

        return (int) $avis; // Cast the result to integer
    }


    public function countAvisByJoueur()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT u.id, COUNT(a.user) AS totalAvis, u.firstname
            FROM App\Entity\Avisjoueur a
            JOIN App\Entity\User u WITH a.user = u.id
            GROUP BY u.id, u.firstname"
        );

        return $query->getResult();
    }

    public function TopPlayer()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT a
        FROM App\Entity\Avisjoueur a
        ORDER BY a.note DESC"
        );

        return $query->getResult();
    }

    public function findBySearchQuery($query)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.user', 'u')
            ->andWhere('u.firstname LIKE :query OR a.commentaire LIKE :query')
            ->orWhere('a.note LIKE :query')
            ->orWhere('a.dateavis LIKE :query')
            ->orWhere('a.idAvis LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->getQuery()
            ->getResult();
    }


    public function getAverageNoteForUser($userId)
    {
        return $this->createQueryBuilder('a')
            ->select('AVG(a.note) as avgNote')
            ->andWhere('a.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();
    }



//    /**

//     * @return Avisjoueur[] Returns an array of Avisjoueur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Avisjoueur
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
